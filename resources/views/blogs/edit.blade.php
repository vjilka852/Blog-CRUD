<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.image-preview { max-width: 200px; margin: 5px; }</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Blog</h2>

    <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Blog Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $blog->title }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4" required>{{ $blog->description }}</textarea>
        </div>

        <!-- Current Images -->
        <div class="mb-3">
            <label class="form-label">Current Images</label><br>
            @foreach($blog->images ?? [] as $img)
                <img src="{{ asset('storage/'.$img) }}" class="image-preview img-thumbnail">
            @endforeach
        </div>

        <!-- Upload New -->
        <div class="mb-3">
            <label for="images" class="form-label">Upload New Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
        </div>

        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select name="tags[]" id="tags" class="form-select" multiple>
                @php
                    $availableTags = ['Technology', 'Lifestyle', 'Education', 'Business'];
                @endphp
        
                @foreach($availableTags as $tag)
                    <option value="{{ $tag }}"
                        {{ in_array($tag, $blog->tags ?? []) ? 'selected' : '' }}>
                        {{ $tag }}
                    </option>
                @endforeach
            </select>
        </div>
        

        <!-- User -->
        {{-- <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-select" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $blog->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->id }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div> --}}
        <div class="mb-3">
            <label class="form-label">User</label>
            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        </div>  

     <!-- Links -->
<div class="mb-3">
    <label class="form-label">Links</label>
    <div id="links-wrapper">
        @foreach($blog->links ?? [] as $i => $link)
        <div class="link-item mb-2 d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="text" name="links[{{ $i }}][title]" value="{{ $link['title'] }}" class="form-control mb-2">
                <input type="url" name="links[{{ $i }}][url]" value="{{ $link['url'] }}" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-1" onclick="removeLink(this)">
                &times;
            </button>
        </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-secondary mt-2" onclick="addLink()">+ Add More</button>
</div>
<button type="submit" class="btn btn-success">Update Blog</button>
<a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div>

<script>

let linkIndex = {{ count($blog->links ?? []) }};

function addLink() {
    const wrapper = document.getElementById('links-wrapper');
    const html = `
        <div class="link-item mb-2 d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="text" name="links[${linkIndex}][title]" placeholder="Link Title" class="form-control mb-2">
                <input type="url" name="links[${linkIndex}][url]" placeholder="Link URL" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-1" onclick="removeLink(this)">
                &times;
            </button>
        </div>`;
    wrapper.insertAdjacentHTML('beforeend', html);
    linkIndex++;
}

function removeLink(button) {
    button.closest('.link-item').remove();
}
</script>
</body>
</html>
