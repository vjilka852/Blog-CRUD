@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs List</title>

    <!-- Bootstrap & DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Blogs List</h2>
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">Add Blog</a>
        </div>
    
       <!-- Success Message -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

    
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="userFilter" class="form-label">Filter by User</label>
                <select id="userFilter" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
    
            {{-- <div class="col-md-4">
                <label for="tagFilter" class="form-label">Filter by Tag</label>
                <select id="tagFilter" class="form-select">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}
    
        <!-- Table -->
        <table id="blogsTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Title</th>
                <th>User</th>
                <th>Tags</th>
                <th>Images</th>
                <th>Created At</th>
                <th width="180">Action</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function () {
        let table = $('#blogsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('blogs.index') }}",
                data: function (d) {
                    d.user_id = $('#userFilter').val();
                    d.tag_id = $('#tagFilter').val();
                }
            },
            columns: [
                {data: 'title', name: 'title'},
                {data: 'user', name: 'user'},
                {data: 'tags', name: 'tags', orderable: false, searchable: false},
                {data: 'images', name: 'images', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[4, 'desc']]
        });

        // Redraw table when filters change
        $('#userFilter, #tagFilter').on('change', function () {
            table.draw();
        });
    });
</script>
</body>
</html>
@endsection
