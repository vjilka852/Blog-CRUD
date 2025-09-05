<a href="{{ route('blogs.show', $row->id) }}" class="btn btn-sm btn-primary">View</a>
<a href="{{ route('blogs.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('blogs.destroy', $row->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Delete this blog?')" class="btn btn-sm btn-danger">Delete</button>
</form>
