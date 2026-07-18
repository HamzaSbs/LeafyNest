@extends('admin.layout', ['pageTitle' => 'Manage Suppliers'])

@section('content')
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <h2>Add New Supplier</h2>
        <form method="POST" action="{{ route('admin.suppliers.store') }}" class="form-inline">
            @csrf
            <input type="text" name="name" placeholder="Supplier name" required>
            <input type="text" name="contact" placeholder="Contact (phone / email)">
            <button type="submit" class="btn-primary">Add Supplier</button>
        </form>
    </div>

    <div class="card">
        <h2>All Suppliers</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Plants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.suppliers.update', ['id' => $supplier->supplier_id]) }}" class="inline-form">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $supplier->name }}" required>
                        </td>
                        <td>
                                <input type="text" name="contact" value="{{ $supplier->contact }}" placeholder="Contact">
                        </td>
                        <td>{{ $supplier->plants_count }}</td>
                        <td>
                                <button type="submit" class="btn-outline">Save</button>
                            </form>
                            <form method="POST" action="{{ route('admin.suppliers.destroy', ['id' => $supplier->supplier_id]) }}" onsubmit="return confirm('Delete this supplier?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No suppliers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection