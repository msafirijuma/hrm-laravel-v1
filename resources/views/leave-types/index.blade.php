@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Types</h2>
        <a href="{{ route('leave-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Type
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name of Leave</th>
                        <th>Maximum Days Per Year</th>
                        <th>Paid?</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $type->name }}</strong></td>
                        <td>{{ $type->max_days_per_year }}</td>
                        <td>
                            @if($type->is_paid)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ $type->description ?? '-' }}</td>
                        <td>
                            <a href="{{ route('leave-types.edit', $type) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('leave-types.destroy', $type) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this leave?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No leave type yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection