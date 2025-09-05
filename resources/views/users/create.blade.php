@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h4>Register User</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    <small class="form-text text-muted">Min 8 characters, must contain letters and numbers.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mobile --}}
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                    @error('mobile') <p class="text-danger">{{ $message }}</p> @enderror
                </div>

                {{-- DOB --}}
                <div class="mb-3">
                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date"
                           name="dob"
                           value="{{ old('dob') }}"
                           class="form-control @error('dob') is-invalid @enderror">
                    @error('dob')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">-- Select Gender --</option>
                        <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender')=='other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Profile Image --}}
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input type="file"
                           name="profile_image"
                           class="form-control @error('profile_image') is-invalid @enderror">
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- inline script placed inside content so it's always output -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Find all inputs/selects inside the form area
    const fields = document.querySelectorAll(".form-control, .form-select");

    function hideFeedback(el) {
        el.classList.remove("is-invalid");
        // Prefer immediate sibling invalid-feedback, otherwise search parent container
        let fb = el.nextElementSibling;
        if (!fb || !fb.classList || !fb.classList.contains('invalid-feedback')) {
            // try to find inside the closest .mb-3 wrapper
            const wrapper = el.closest('.mb-3');
            if (wrapper) {
                fb = wrapper.querySelector('.invalid-feedback');
            } else {
                fb = null;
            }
        }
        if (fb) {
            fb.style.display = 'none';
        }
    }

    fields.forEach(function (el) {
        // For text inputs and textareas: input event
        el.addEventListener('input', function () {
            if (this.classList.contains('is-invalid') && (this.value || this.value === '0')) {
                hideFeedback(this);
            }
        });

        // For selects and also as a fallback when user leaves the field
        el.addEventListener('change', function () {
            if (this.classList.contains('is-invalid') && (this.value || this.value === '0')) {
                hideFeedback(this);
            }
        });

        // If user tabs away, hide if non-empty
        el.addEventListener('blur', function () {
            const val = (this.value || '').toString().trim();
            if (this.classList.contains('is-invalid') && val !== '') {
                hideFeedback(this);
            }
        });
    });
});
</script>

@endsection
