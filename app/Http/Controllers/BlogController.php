<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
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
    
            // If admin (id = 1), allow filter by user_id
            if (auth()->id() === 1) {
                if ($request->filled('user_id')) {
                    $query->where('user_id', $request->user_id);
                }
            } else {
                // If not admin, show only logged-in user’s blogs
                $query->where('user_id', auth()->id());
            }
    
            return DataTables::eloquent($query)
                ->addColumn('title', fn($blog) => $blog->title)
                ->addColumn('user', fn($blog) => $blog->user?->name ?? 'N/A')
                ->addColumn('tags', function ($blog) {
                    return !empty($blog->tags)
                        ? implode(', ', $blog->tags)
                        : '—';
                })
                ->addColumn('images', function ($blog) {
                    if (!empty($blog->images)) {
                        $html = '';
                        foreach ($blog->images as $img) {
                            $html .= '<img src="' . asset('storage/' . $img) . '" 
                                        width="50" height="50" 
                                        class="rounded me-1">';
                        }
                        return $html;
                    }
                    return '—';
                })
                ->addColumn('action', function ($blog) {
                    return '
                        <div class="d-flex gap-2">
                            <a href="' . route('blogs.show', $blog->id) . '" class="btn btn-sm btn-info text-white">View</a>
                            <a href="' . route('blogs.edit', $blog->id) . '" class="btn btn-sm btn-warning text-white">Edit</a>
                            <form action="' . route('blogs.destroy', $blog->id) . '" method="POST" onsubmit="return confirm(\'Are you sure?\')" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['images', 'action']) 
                ->make(true);
        }
    
        // Only fetch users if admin
        $users = auth()->id() === 1 ? User::all() : collect();
        return view('blogs.index', compact('users'));
    }
    

    public function create()
    {
        // dd(Auth::user());

        $users = User::all();
        return view('blogs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $users= Auth::user(); // use default web guard

        if (!$users){
            return redirect()->back()->with('error', 'You must be logged in.');
        }

        $request->validate([
            'title'       => 'required|unique:blogs,title|max:255|string',
            'description' => ['required', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 10) {
                    $fail('The ' . $attribute . ' must be at least 10 words.');
                }
            }],
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',
            'user_id'     => 'required|exists:users,id',
        ]);

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
            'user_id'     => $users->id,   
            'links'       => $request->links,
        ]);
        

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully!');
    }

    public function show(Blog $blog)
    {
        $blog->load('user');
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $users = User::all();
    
        // Allow admin (id=1) OR blog owner
        if (auth()->id() !== 1 && auth()->id() !== $blog->user_id) {
            abort(403, 'Unauthorized action.');
        }
    
        return view('blogs.edit', compact('blog', 'users'));
    }
    
    


    public function update(Request $request, Blog $blog)
    {
        $users = User::all();
    
        // Allow admin (id=1) OR blog owner
        if (auth()->id() !== 1 && auth()->id() !== $blog->user_id) {
            abort(403, 'Unauthorized action.');
        }
    
        $request->validate([
            'title'       => 'required|unique:blogs,title,' . $blog->id,
            'description' => ['required', function ($attribute, $value, $fail) {
                if (str_word_count($value) < 10) {
                    $fail('The ' . $attribute . ' must be at least 10 words.');
                }
            }],
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',
            'links'       => 'nullable|array',
        ]);
    
        $imagePaths = $blog->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($blog->images ?? [] as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('blogs', 'public');
            }
        }
    
        $updateData = [
            'title'       => $request->title,
            'description' => $request->description,
            'images'      => $imagePaths,
            'tags'        => $request->tags ? array_values($request->tags) : [],
            'links'       => $request->links ? array_values($request->links) : [],
        ];
    
        // Only set user_id if a normal user is editing
        if (auth()->id() !== 1) {
            $updateData['user_id'] = auth()->id();
        }
    
        $blog->update($updateData);
    
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }
    

    public function destroy(Blog $blog)
    {
        foreach ($blog->images ?? [] as $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }
}
