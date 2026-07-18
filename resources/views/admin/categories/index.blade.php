@extends('admin.layout', ['pageTitle' => 'Manage Categories'])

@section('content')
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <h2>Add New Category</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="form-inline">
            @csrf
            <input type="text" name="name" placeholder="Category name" required>
            <button type="submit" class="btn-primary">Add Category</button>
        </form>
    </div>

    <div class="card">
        <h2>All Categories</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Plants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.update', ['id' => $category->category_id]) }}" class="inline-form">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required>
                                <button type="submit" class="btn-outline">Save</button>
                            </form>
                        </td>
                        <td>{{ $category->plants_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.destroy', ['id' => $category->category_id]) }}" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection