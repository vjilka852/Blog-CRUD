<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }
    /**
     * Display a listing of users.
     */
    public function index()
    {
        if (Auth::id() === 1) {
            //  Admin sees all users
            $users = User::latest()->paginate(10);
        } else {
            //  Normal user sees only their own record
            $users = User::where('id', Auth::id())->paginate(1);
        }
    
        return view('users.index', compact('users'));
    }
    

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:20',
            'email'         => 'required|email|unique:users,email',
            'password'      => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
            ],
            'mobile'        => 'required|digits:10',
            'dob'           => 'required|date',
            'gender'        => ['required', Rule::in(['male', 'female', 'other'])],
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'        => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // handle profile image
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        // save user in DB
        User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'mobile'        => $validated['mobile'],
            'dob'           => $validated['dob'],
            'gender'        => $validated['gender'],
            'profile_image' => $imagePath,
            'status'        => $validated['status'],
        ]);

        return redirect()->route('users.index')->with('success', 'User registered successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => [
                'nullable',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
            ],
            'mobile'        => 'required|string|max:15',
            'dob'           => 'required|date',
            'gender'        => ['required', Rule::in(['male', 'female', 'other'])],
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'        => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $data = $request->only(['name', 'email', 'mobile', 'dob', 'gender', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Custom login check (only active users can log in).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email does not exist. Please register first.'])->withInput();
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['email' => 'Your account is inactive. Contact admin.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
        }

        Auth::login($user);

        return redirect()->route('users.index')->with('success', 'Login successful!');
    }

    /**
     * Register new user (basic register form).
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
                'confirmed',
            ],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'mobile'   => '0000000000',
            'dob'      => now()->toDateString(),
            'gender'   => 'other',
            'profile_image' => null,
            'status'   => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please login.');
    }
}
