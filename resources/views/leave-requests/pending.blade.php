@extends('layouts.app')

@section('title', 'Pending Leave Requests')

@section('content')
    <h2 class="mb-4">Leave Requests Waiting Approval</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="leaveTable">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>Dates</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                
                    @forelse($pendingLeaves as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->employee->department->name ?? '-' }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td>
                        <td><strong>{{ abs($leave->days_requested) }}</strong></td>
                        <td>{{ Str::limit($leave->reason, 60) }}</td>
                        <td>
                            <!-- Approve Form -->
                            <form id="approve-form-{{ $leave->id }}" action="{{ route('leave-requests.approve', $leave) }}" method="POST" style="display:none;">
                                @csrf
                            </form>

                            <!-- Reject Form -->
                            <form id="reject-form-{{ $leave->id }}" action="{{ url('leave-requests/'.$leave->id.'/reject') }}" method="POST" style="display:none;">
                                @csrf
                                <input type="hidden" name="rejection_reason" id="rejection-reason-input-{{ $leave->id }}">
                            </form>

                            <!-- Approve & Reject Buttons -->
                            <button type="button" title="Approve this request" onclick="confirmApprove({{ $leave->id }})" class="btn btn-sm btn-success px-3 py-1">
                                <i class="fas fa-check"></i> 
                            </button>
                            <button type="button" title="Reject this request" onclick="rejectLeave({{ $leave->id }})" class="btn btn-sm btn-danger px-3 py-1">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                            No any pending request yet.
                        </td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- SweetAlert2 Script --}}
<script>
    function confirmApprove(id) {
        console.log('Approving leave request with ID:', id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be accepting this leave request.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Accept!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while a request is being accepted.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('approve-form-' + id).submit();
            }
        });
    }

    function rejectLeave(id) {
        console.log('Rejecting leave request with ID:', id);
        Swal.fire({
            title: 'Reject leave request',
            input: 'textarea',
            inputLabel: 'Reason for rejection',
            inputPlaceholder: 'Write a reason here...',
            showCancelButton: true,
            confirmButtonText: 'Reject Leave Request',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'You must atleast state the reason for rejection!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while a request is being cancelled.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('rejection-reason-input-' + id).value = result.value;
                document.getElementById('reject-form-' + id).submit();
            }
        });
    }
</script>
@endsection
