<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
// use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Blog::with('user');

    

            // If not admin (id=1), only show user’s own blogs
            if (Auth::id() != 1) {
                $query->where('user_id', Auth::id());
            }

            // Filter by user
            if ($request->user_id) {
                $query->where   ('user_id', $request->user_id);
            }

            // Filter by tag
            if ($request->tag_id) {
                $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag_id));
            }

            return DataTables::eloquent($query)
                ->addColumn('title', fn($blog) => $blog->title)
                ->addColumn('user', fn($blog) => optional($blog->user)->name ?? 'N/A')
                ->addColumn('tags', function ($blog) {
                    return !empty($blog->tags) ? implode(', ', $blog->tags) : 'No Tags';
                })
                
                ->addColumn('images', function ($blog) {
                    if (empty($blog->images) || !is_array($blog->images)) {
                        return 'No Images';
                    }
                    $imgs = '';
                    foreach ($blog->images as $img) {
                        $url = asset('storage/' . $img);
                        $imgs .= "<a href='$url' target='_blank'>
                                    <img src='$url' width='50' class='img-thumbnail' />
                                  </a> ";
                    }
                    return $imgs;
                })
                ->editColumn('created_at', fn($blog) => $blog->created_at ? $blog->created_at->format('Y-m-d H:i') : '')
                ->addColumn('action', function ($blog) {
                    $editUrl   = route('blogs.edit', $blog->id);
                    $showUrl   = route('blogs.show', $blog->id);
                    $deleteUrl = route('blogs.destroy', $blog->id);

                    return '
                        <a href="'.$showUrl.'" class="btn btn-sm btn-info">View</a>
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['images', 'action'])
                ->make(true);
        }

        $users = User::all();
        // $tags  = Tag::all();
        return view('blogs.index', compact('users'));
    }

    public function create()
    {
        // $tags  = Tag::all();
        $users = User::all();
        return view('blogs.create', compact( 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|unique:blogs,title|max:255|string',
            'description' => ['required', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 10) {
                    $fail('The '.$attribute.' must be at least 10 words.');
                }
            }],
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'string',

            'user_id'     => 'required|exists:users,id',
            'links.0.title' => 'required|string|max:255',
            'links.0.url' => 'required|url',
            'links.*.title' => 'nullable|string|max:255',
           'links.*.url' => 'nullable|url',
        ]);
        // $blog->tags()->sync($request->tags ?? []);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('blogs', 'public');
            }
        }

        $blog = Blog::create([
            'title'       => $request->title,
            'description' => $request->description,
            'images'      => $imagePaths,
            'tags'        => $request->tags ?? [],
            'user_id'     => $request->user_id,
            'links'       => $request->links,   
        ]);

        // $blog->tags()->sync($request->tags ?? []);

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }

    public function show(string $id)
    {
        $blog = Blog::with(['user'])->findOrFail($id);
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        // $tags  = Tag::all();
        $users = User::all();
        return view('blogs.edit', compact('blog', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $blog = Blog::findOrFail($id);
    
        $request->validate([
            'title'       => 'required|unique:blogs,title,' . $id,
            'description' => ['required', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 10) {
                    $fail('The '.$attribute.' must be at least 10 words.');
                }
            }],
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',
            'user_id'     => 'required|exists:users,id',
            'links'       => 'nullable|array',
        ]);
    
        $imagePaths = $blog->images ?? [];
    
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($blog->images ?? [] as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
    
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('blogs', 'public');
            }
        }
    
        $blog->update([
            'title'       => $request->title,
            'description' => $request->description,
            'images'      => $imagePaths,
            'tags'        => $request->tags ? array_values($request->tags) : [],
            'user_id'     => $request->user_id,
            'links'       => $request->links ? array_values($request->links) : [],
        ]);
    
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }
    
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
    
        foreach ($blog->images ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }
    
        $blog->delete();
    
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }
    
}
