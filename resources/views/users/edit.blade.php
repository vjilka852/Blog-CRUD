@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>Edit User</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label">Password (leave blank if unchanged)</label>
                    <input type="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Mobile --}}
                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" 
                           name="mobile" 
                           value="{{ old('mobile', $user->mobile) }}" 
                           class="form-control @error('mobile') is-invalid @enderror">
                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- DOB --}}
                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" 
                           name="dob" 
                           value="{{ old('dob', $user->dob) }}" 
                           class="form-control @error('dob') is-invalid @enderror">
                    @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Profile Image --}}
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/'.$user->profile_image) }}" alt="Profile" width="80" class="mt-2 rounded">
                    @endif
                    @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
