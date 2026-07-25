@extends('layouts.app')

@section('title', 'Maombi Yanayosubiri')

@section('content')
    <h2 class="mb-4">Maombi ya Likizo Yanayosubiri Approval</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
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
                    <!-- TUMEBADILISHA FOREACH KUWA FORELSE ILI KUWEKA ELSE STATEMENT -->
                    @forelse($pendingLeaves as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->employee->department->name ?? '-' }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td>
                        <td><strong>{{ abs($leave->days_requested) }}</strong></td>
                        <td>{{ Str::limit($leave->reason, 60) }}</td>
                        <td>
                            <!-- 1. FORM YA APPROVE (Imejificha) -->
                            <form id="approve-form-{{ $leave->id }}" action="{{ route('leave-requests.approve', $leave) }}" method="POST" style="display:none;">
                                @csrf
                            </form>

                            <!-- 2. FORM YA REJECT (Imejificha - inaruhusu kubeba sababu ya kukataa) -->
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
                    <!-- HII NDIO ELSE STATEMENT KAMA HAKUNA PENDING REQUESTS -->
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                            Hakuna maombi ya likizo yanayosubiri kwa sasa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- SweetAlert2 Script --}}
<script src="https://jsdelivr.net"></script>
<script>
    function confirmApprove(id) {
        console.log('Approving leave request with ID:', id);
        Swal.fire({
            title: 'Una uhakika?',
            text: "Utakuwa unakubali ombi la likizo",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ndiyo, Kubali!',
            cancelButtonText: 'Ghairi',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Tunashughulikia...',
                    text: 'Tafadhali subiri kidogo wakati ombi linakubaliwa',
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
            title: 'Kataa Ombi la Likizo',
            input: 'textarea',
            inputLabel: 'Sababu ya Kukataa',
            inputPlaceholder: 'Andika sababu hapa...',
            showCancelButton: true,
            confirmButtonText: 'Kataa Ombi',
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Ghairi',
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'Lazima uandike sababu ya kukataa!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    title: 'Tunashughulikia...',
                    text: 'Tafadhali subiri kidogo wakati ombi linakataliwa',
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
