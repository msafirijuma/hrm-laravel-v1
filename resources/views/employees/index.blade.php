@extends('layouts.app')

@section('title', 'Wafanyakazi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Employees</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-primary" onclick="showPageLoader('Tunapakia fomu ya kuongeza mfanyakazi...')">
            <i class="fas fa-plus"></i> Add New Employee
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
            <table id="employeeTable" class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee Number</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Phone</th>
                        <th>Hire Date</th>
                        <th class="text-center" style="min-width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $employee->employee_number }}</strong></td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>{{ $employee->department->name ?? '-' }}</td>
                        <td>{{ $employee->position->name ?? '-' }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->date_hired ? $employee->date_hired->format('d M Y') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center align-items-center">
                                <!-- View Button -->
                                <button type="button" onclick="triggerView('{{ route('employees.show', $employee) }}')" class="btn btn-sm btn-info text-white" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Edit Button -->
                                <button type="button" onclick="triggerEdit('{{ route('employees.edit', $employee) }}')" class="btn btn-sm btn-warning" title="Edit Information">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete Form -->
                                <form id="delete-employee-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:none !important;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <!-- Delete Button -->
                                <button type="button" onclick="triggerDelete({{ $employee->id }}, '{{ $employee->first_name }} {{ $employee->last_name }}')" class="btn btn-sm btn-danger" title="Delete Employee">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Hakuna data ya wafanyakazi kwa sasa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://jsdelivr.net"></script>
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

    // SweetAlert during view action
    function triggerView(url) {
        showPageLoader('Tunafungua wasifu wa mfanyakazi...');
        window.location.href = url;
    }

    // SweetAlert during edit action
    function triggerEdit(url) {
        showPageLoader('Tunatayarisha fomu ya marekebisho...');
        window.location.href = url;
    }

    // SweetAlert confirmation during deletion of employee
    function triggerDelete(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You will delete the employee "${name}" from the system!`,
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
                    text: 'Please wait while employee data is being deleted.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('delete-employee-form-' + id).submit();
            }
        });
    }
</script>
@endsection
