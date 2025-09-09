<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} | Blog Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
        }
        .blog-header {
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(120deg, #6c63ff, #00c9ff);
            color: white;
            border-radius: 0 0 20px 20px;
        }
        .blog-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .blog-meta {
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .blog-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-top: -30px;
        }
        .blog-tags span {
            margin: 3px;
        }
        .blog-images img {
            border-radius: 12px;
            transition: 0.3s;
            cursor: pointer;
        }
        .blog-images img:hover {
            transform: scale(1.05);
        }
        .author-box {
            display: flex;
            align-items: center;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 12px;
            margin-top: 20px;
        }
        .author-box img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        .links-list a {
            display: block;
            margin-bottom: 8px;
            text-decoration: none;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="blog-header">
        <h1>{{ $blog->title }}</h1>
        <p class="blog-meta">
            By <strong>{{ $blog->user->name ?? 'Unknown' }}</strong> | 
            {{ $blog->created_at->format('F d, Y') }}
        </p>
    </div>

    <!-- Blog Content -->
    <div class="container">
        <div class="blog-content">

            <!-- Back Button -->
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">← Back</a>

            <!-- Description -->
            <p class="lead">{{ $blog->description }}</p>

            <!-- Images -->
            @if($blog->images && is_array($blog->images))
            <div class="row blog-images mb-4">
                @foreach($blog->images as $image)
                    <div class="col-md-4 mb-3">
                        <a href="{{ asset('storage/'.$image) }}" data-lightbox="blog-gallery">
                            <img src="{{ asset('storage/'.$image) }}" class="img-fluid">
                        </a>
                    </div>
                @endforeach
            </div>
            @endif

            <!-- Tags -->
            @if($blog->tags && count($blog->tags))
                <div class="blog-tags mb-3">
                    <h6>Tags:</h6>
                    @foreach($blog->tags as $tag)
                        <span class="badge bg-primary">{{ is_array($tag) ? $tag['name'] ?? '' : $tag }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Links -->
            @if($blog->links && is_array($blog->links))
            <div class="links-list mt-3">
                <h6>Related Links:</h6>
                @foreach($blog->links as $link)
                    <a href="{{ $link['url'] ?? '#' }}" target="_blank">
                        🔗 {{ $link['title'] ?? 'Visit Link' }}
                    </a>
                @endforeach
            </div>
            @endif

            <!-- Author -->
            <img src="{{ optional($blog->user)->profile_image 
            ? asset('storage/' . $blog->user->profile_image) 
            : 'https://via.placeholder.com/60' }}" 
     alt="Author"
     width="60"
     height="60"
     class="rounded-circle">

                    <strong>{{ $blog->user->name ?? 'Unknown Author' }}</strong>
                    <p>Author: {{ $blog->user->name }}</p>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
</body>
</html>
