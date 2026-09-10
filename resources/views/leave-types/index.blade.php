@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Leave Types</h2>
        <a href="{{ route('leave-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Leave Type
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="leaveTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="min-width: 120px;">Maximum Days Per Year</th>
                            <th>Paid?</th>
                            <th style="min-width: 120px;">Description</th>
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
                                <!-- Edit Button -->
                                <button type="button" onclick="triggerEdit('{{ route('leave-types.edit', $type) }}')" class="btn btn-sm btn-warning" title="Edit Information">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Delete Form -->
                                <form id="delete-form-{{ $type->id }}" action="{{ route('leave-types.destroy', $type) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="triggerDelete({{ $type->id }}, '{{ $type->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="6" class="text-center py-4">No leave type yet.</td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

    // Loader function during page navigation
    function showPageLoader(message) {
        Swal.fire({
            title: 'Please wait...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // SweetAlert during edit action
    function triggerEdit(url) {
        showPageLoader('We are preparing edit form...');
        window.location.href = url;
    }

    // SweetAlert confirmation during deletion
    function triggerDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You will remove leave type: "${name}" from the system!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while leave type is being removed from system.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Submit form
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection