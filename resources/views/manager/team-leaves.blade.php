@extends('layouts.app')

@section('title', 'Team Leaves')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Team Leaves</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
           <i class="fas fa-arrow-left me-2"></i> Dashboard
        </a>
    </div>

    <!-- Currently on Leave -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Team On Leave ({{ $onLeaveNow->count() }})</h5>
        </div>
        <div class="card-body p-4">
            <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($onLeaveNow as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->leaveType->name ?? '-' }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td><span class="badge bg-info">{{ $leave->days_requested }}</span></td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center py-3 text-muted">No employees currently on leave.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Leaves -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Pending Requests ({{ $pendingLeaves->count() }})</h5>
        </div>
        <div class="card-body p-4">
            <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLeaves as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->leaveType->name ?? '-' }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td><span class="badge bg-warning">{{ $leave->days_requested }}</span></td>
                        <td>{{ Str::limit($leave->reason, 40) }}</td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="6" class="text-center py-3 text-muted">No pending leave requests.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Leaves -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Upcoming Team Leaves ({{ $upcomingLeaves->count() }})</h5>
        </div>
        <div class="card-body p-4">
            <table class="table table-hover table-bordered table-striped mb-0" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingLeaves as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                        <td>{{ $leave->leaveType->name ?? '-' }}</td>
                        <td>{{ $leave->start_date->format('d M Y') }}</td>
                        <td>{{ $leave->end_date->format('d M Y') }}</td>
                        <td><span class="badge bg-success">{{ $leave->days_requested }}</span></td>
                    </tr>
                    @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center py-3 text-muted">No upcoming leave requests.</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection