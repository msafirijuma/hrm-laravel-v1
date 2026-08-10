@extends('layouts.app')

@section('title', 'Department')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">All Departments</h2>
        <a href="{{ route('departments.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Add New Department
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover" id="departmentTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name of Department</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $dept->name }}</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ $dept->code }}</span>
                        </td>
                        <td>{{ $dept->description ?? '—' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $dept->employees_count ?? $dept->employees()->count() }}
                            </span>
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button onclick="triggerEdit('{{ route('departments.edit', $dept) }}')" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <!-- Delete Form -->
                            <form id="delete-form-{{ $dept->id }}" action="{{ route('departments.destroy', $dept) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="triggerDelete({{ $dept->id }}, '{{ $dept->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">No any department registered yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // SweetAlert during edit action
    function triggerEdit(url) {
        showPageLoader('We are preparing edit form...');
        window.location.href = url;
    }

    // SweetAlert confirmation during deletion
    function triggerDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You will remove department: "${name}" from the system!`,
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
                    text: 'Please wait while department is being removed from system.',
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
