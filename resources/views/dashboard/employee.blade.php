@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Employee Dashboard</h2>

    <div class="row g-3 mb-5">
        <h5 class="small mb-0 fs-5">Quick Info</h5>
        <!-- Leave status -->
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6>Leave Status</h6>
                    <h4 class="mb-0">
                        @if($currentLeave)
                            <span class="badge bg-success">On Leave</span>
                        @else
                            <span class="badge bg-danger">No Leave</span>
                        @endif
                    </h4>
                </div>
                <div class="card-footer">
                    <a href="{{ route('my-leaves') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Leaves
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Leave Requests -->
        <div class="col-md-4">
            <div class="card border-0 text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <h6 class="text-white-50 mb-2">Pending Leave Requests</h6>
                    <h3 class="fw-bold mb-0">{{ $pendingLeaveCount }}</h3>
                    <small class="opacity-75">
                        {{ $pendingLeaveCount == 1 ? 'pending request' : 'pending requests' }}
                    </small>
                </div>
                <div class="card-footer">
                    <a href="{{ route('my-leaves') }}" class="btn btn-light btn-sm font-weight-bold" style="color: #764ba2;">
                            <i class="fas fa-eye"></i> View Leaves
                    </a>
                </div>
            </div>
        </div>

        <!-- My Payslip -->
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6>Latest Payslip</h6>
                    <h4 class="mb-0">
                        @if($latestPayslip)
                            TZS {{ number_format($latestPayslip->net_salary, 0) }}
                        @else
                            —
                        @endif
                    </h4>
                </div>
                <div class="card-footer">
                    <a href="{{ route('my-payslips') }}" class="btn btn-light btn-sm text-success font-weight-bold">
                            <i class="fas fa-eye"></i> View Payslips
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balances -->
    @include('layouts.leave-balances')

    <!-- My Attendance Record -->
    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-info text-light bc5fddm">
            <h5 class="mb-0">My Attendance Record (Last 7 Days)</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 120px; min-width: 120px">Date</th>
                            <th>Status</th>
                            <th style="width: 120px; min-width: 120px">Check In</th>
                            <th style="width: 120px; min-width: 120px">Check Out</th>
                            <th style="width: 120px; min-width: 120px">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myAttendance as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d M Y') }}</td>
                            <td>
                                @if($attendance->status == 'present')
                                    <span class="badge bg-success">Present</span>
                                @elseif($attendance->status == 'late')
                                    <span class="badge bg-warning">Late</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="badge bg-danger">Absent</span>
                                @elseif($attendance->status == 'half_day')
                                    <span class="badge bg-info">Half Day</span>
                                @else
                                    <span class="badge bg-secondary">On Leave</span>
                                @endif
                            </td>
                            <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</td>
                            <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</td>
                            <td>{{ $attendance->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No attendance records for the last 7 days.
                            </td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- My Leave Requests -->
    <div class="row g-3 mb-5 mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between bg-primary text-light">
                    <h5 class="mb-0">My Leave Requests</h5>
                    <a href="{{ route('my-leaves') }}" class="btn btn-sm btn-light text-primary">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th style="width: 180px; min-width: 180px">Leave Type</th>
                                    <th style="width: 180px; min-width: 180px">Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myLeaves as $leave)
                                <tr>
                                    <td>{{ $loop ->iteration }}</td>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</td>
                                    <td>{{ $leave->days_requested }}</td>
                                    <td>
                                        @if($leave->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($leave->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">You did not apply for any leave.</td>
                                </tr> --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Public holidays -->
    @include('layouts.public-holidays')

    <!-- Quick Links -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Quick Links</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 text-center">
                <div class="col-12 col-md-3">
                    <a href="{{ route('apply-leave') }}" class="btn btn-success btn-lg w-100 py-4">
                        <i class="fas fa-paper-plane me-3 fa-2x d-block mb-2"></i>
                        Apply Leave
                    </a>
                </div>
                <div class="col-12 col-md-3">
                    <a href="{{ route('my-profile') }}" class="btn btn-primary btn-lg w-100 py-4">
                        <i class="fas fa-user fa-2x d-block mb-2"></i>
                        View Profile
                    </a>
                </div>
                <div class="col-12 col-md-3">
                    <a href="{{ route('password.change') }}" class="btn btn-info btn-lg w-100 py-4">
                        <i class="fas fa-file-invoice fa-2x d-block mb-2"></i>
                            Change Password
                    </a>
                </div>
                <div class="col-12 col-md-3">
                    <a href="{{ route('my-payslips') }}" class="btn btn-warning btn-lg w-100 py-4">
                        <i class="fas fa-key fa-2x d-block mb-2"></i>
                        My Payslips
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection