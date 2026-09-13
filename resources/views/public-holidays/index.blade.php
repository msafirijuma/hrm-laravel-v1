@extends('layouts.app')

@section('title', 'Public Holidays')

@section('content')
<div class="d-md-flex justify-content-between align-items-center mb-4">
    <h2>Public Holidays</h2>
    <div>
        <a href="{{ route('public-holidays.calendar') }}" class="btn btn-info me-2">
            <i class="fas fa-calendar"></i> Calendar View
        </a>
        <a href="{{ route('public-holidays.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Holiday
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped mb-0" id="holidayTable">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th style="width: 120px; min-width: 120px">Date</th>
                        <th>Recurring</th>
                        <th style="width: 120px; min-width: 120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                    <tr>
                        <td><strong>{{ $holiday->name }}</strong></td>
                        <td>{{ $holiday->date->format('d M Y') }}</td>
                        <td>
                            @if($holiday->is_recurring)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button holiday="button" onclick="triggerEdit('{{ route('public-holidays.edit', $holiday) }}')" class="btn btn-sm btn-warning" title="Edit Information">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <!-- Delete Form -->
                            <form id="delete-form-{{ $holiday }}" action="{{ route('public-holidays.destroy', $holiday) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button holiday="button" class="btn btn-sm btn-danger" 
                                        onclick="triggerDelete({{ $holiday }}, '{{ $holiday->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No holidays yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $holidays->links() }}
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
            text: `You will remove public holiday: "${name}" from the system!`,
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
                    text: 'Please wait while public holiday is being removed from system.',
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