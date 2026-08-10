@extends('layouts.app')

@section('title', 'My Leave Requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Leave Requests</h2>
        <a href="{{ route('apply-leave') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Apply New Leave
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="leaveTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Leave ype</th>
                        <th>Leave Period</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td>
                        <td><strong>{{ $leave->days_requested }}</strong></td>
                        <td>{{ Str::limit($leave->reason, 60) }}</td>
                        <td>
                            @if($leave->status == 'rejected')
                                <span class="badge bg-danger" role="button" style="cursor: pointer;" 
                                      data-bs-toggle="modal" data-bs-target="#rejectionModal{{ $leave->id }}">
                                    Rejected <i class="fas fa-info-circle"></i>
                                </span>
                            @elseif($leave->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($leave->status == 'pending')
                                <form id="delete-form-{{ $leave->id }}" action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <!-- Edit Button -->
                                <button type="button" onclick="confirmEdit({{ $leave->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                
                                <!-- Cancel Button -->
                                <button type="button" onclick="cancelLeave({{ $leave->id }})" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Cancel
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Rejection Reason Modal -->
                    @if($leave->status == 'rejected')
                    <div class="modal fade" id="rejectionModal{{ $leave->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Reasons of Rejection</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Leave Type:</strong> {{ $leave->leaveType->name }}</p>
                                    <p><strong>Date:</strong> {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p>
                                    <hr>
                                    <strong>Reason:</strong>
                                    <p class="mt-2">{{ $leave->rejection_reason ?? 'No reason stated.' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    {{-- <tr>
                        <td colspan="7" class="text-center py-4">You did not apply for any leave yet.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
// Confirm edit
function confirmEdit(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are editing leave request information",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Edit!',
        cancelButtonText: 'Cancel',
        allowOutsideClick: false 
    }).then((result) => {
        if (result.isConfirmed) {
            
            Swal.fire({
                title: 'Please wait...',
                text: 'We are opening edit form page',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            window.location.href = `/leave-requests/${id}/edit`;
        }
    });
}

// Cancel Leave
function cancelLeave(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will cancel this leave request!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Cancel!',
        cancelButtonText: 'Not now',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while a request is being cancelled.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endsection
