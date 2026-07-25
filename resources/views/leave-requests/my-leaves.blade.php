@extends('layouts.app')

@section('title', 'My Leave Requests')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Leave Requests</h2>
        <a href="{{ route('apply-leave') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Omba Likizo Mpya
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Aina ya Likizo</th>
                        <th>Tarehe za Likizo</th>
                        <th>Siku</th>
                        <th>Sababu</th>
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
                                <!-- FORM YA SIRI YA KUGHAIRI OMBI -->
                                <form id="delete-form-{{ $leave->id }}" action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <!-- Edit Button with SweetAlert -->
                                <button type="button" onclick="confirmEdit({{ $leave->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Hariri
                                </button>
                                
                                <!-- Cancel Button with SweetAlert -->
                                <button type="button" onclick="cancelLeave({{ $leave->id }})" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Ghairi
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
                                    <h5 class="modal-title">Sababu ya Kukataliwa</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Aina ya Likizo:</strong> {{ $leave->leaveType->name }}</p>
                                    <p><strong>Tarehe:</strong> {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</p>
                                    <hr>
                                    <strong>Sababu:</strong>
                                    <p class="mt-2">{{ $leave->rejection_reason ?? 'Hakuna sababu iliyotolewa.' }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Funga</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Haujapata kuomba likizo yoyote bado.</td>
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
// 1. Uthibitisho wa Hariri (Pamoja na Loader)
function confirmEdit(id) {
    Swal.fire({
        title: 'Una uhakika?',
        text: "Unataka kuhariri taarifa za ombi hili la likizo?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ndiyo, Hariri!',
        cancelButtonText: 'Ghairi',
        allowOutsideClick: false // Inazuia kubonyeza pembeni wakati inaprocess
    }).then((result) => {
        if (result.isConfirmed) {
            // Huu hapa ndio loader wetu wakati ukurasa unajibadili
            Swal.fire({
                title: 'Tafadhali subiri...',
                text: 'Tunafungua ukurasa wa marekebisho',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            window.location.href = `/leave-requests/${id}/edit`;
        }
    });
}

// 2. Uthibitisho wa Kughairi (Pamoja na Loader wakati form inasubmit)
function cancelLeave(id) {
    Swal.fire({
        title: 'Una uhakika?',
        text: "Utakuwa unaghairi ombi la likizo hili kabisa!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ndiyo, Ghairi!',
        cancelButtonText: 'Sio sasa',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Huu hapa ndio loader wakati data inafutwa kwenye database
            Swal.fire({
                title: 'Tunashughulikia...',
                text: 'Tafadhali subiri kidogo wakati ombi linaghairiwa',
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
