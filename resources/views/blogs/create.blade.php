<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <style>
        .image-preview {
            max-width: 200px;
            margin: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Create Blog</h2>

    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Blog Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
            <small class="text-muted">Minimum 10 words required</small>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Blog Images -->
        <div class="mb-3">
            <label for="images" class="form-label">Blog Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewImages(event)">
            <div id="imagePreview" class="d-flex flex-wrap mt-2"></div>
        </div>

       <!-- Tags -->
<div class="mb-3">
    <label for="tags" class="form-label">Tags / Categories</label>
    <select name="tags[]" id="tags"
        class="selectpicker form-control"
        multiple
        data-live-search="true"
        data-selected-text-format="count"
        title="Choose tags...">
        <option value="Technology" {{ in_array('Technology', old('tags', [])) ? 'selected' : '' }}>Technology</option>
        <option value="Lifestyle" {{ in_array('Lifestyle', old('tags', [])) ? 'selected' : '' }}>Lifestyle</option>
        <option value="Education" {{ in_array('Education', old('tags', [])) ? 'selected' : '' }}>Education</option>
        <option value="Business" {{ in_array('Business', old('tags', [])) ? 'selected' : '' }}>Business</option>
    </select>
</div>


        <!-- User ID -->
        <div class="mb-3">
            <label class="form-label">User</label>
            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        </div>

        <!-- Links -->
        <div class="mb-3">
            <label class="form-label">Links <span class="text-danger">*</span></label>
            <div id="links-wrapper">
                <div class="link-item mb-2">
                    <input type="text" name="links[0][title]" placeholder="Link Title"
                           class="form-control mb-2 @error('links.0.title') is-invalid @enderror" required>
                    @error('links.0.title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <input type="url" name="links[0][url]" placeholder="Link URL"
                           class="form-control @error('links.0.url') is-invalid @enderror" required>
                    @error('links.0.url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" onclick="addLink()">+ Add More</button>
        </div>

        <!-- Submit -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Save Blog</button>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function () {
    $('.selectpicker').selectpicker();

    // Auto-close dropdown after selecting an option
    $('#tags').on('changed.bs.select', function () {
        $(this).selectpicker('toggle'); // closes dropdown
    });
});

    $(document).ready(function () {
        $('.selectpicker').selectpicker();
    });

    let linkIndex = 1;

    function addLink() {
        const wrapper = document.getElementById('links-wrapper');
        const html = `
            <div class="link-item mb-2 d-flex align-items-start">
                <div class="flex-grow-1">
                    <input type="text" name="links[${linkIndex}][title]" placeholder="Link Title" class="form-control mb-2">
                    <input type="url" name="links[${linkIndex}][url]" placeholder="Link URL" class="form-control">
                </div>
                <button type="button" class="btn btn-danger btn-sm ms-2 mt-1" onclick="removeLink(this)">✖</button>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
        linkIndex++;
    }

    function removeLink(button) {
        button.closest('.link-item').remove();
    }

    function previewImages(event) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("image-preview");
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
</script>
</body>
</html>
