@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">HR Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Employees</h6>
                    <h2 class="mb-0">{{ $totalEmployees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h6 class="card-title">Departments</h6>
                    <h2 class="mb-0">{{ $totalDepartments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h6 class="card-title">Pending Leaves</h6>
                    <h2 class="mb-0">{{ $pendingLeaves }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h6 class="card-title">Payroll This Month</h6>
                    <h2 class="mb-0">TZS {{ number_format($thisMonthPayroll, 0) }}</h2>
                    <small>{{ $totalPayrollCount }} employees</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Leaves -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pending Leave Requests</h5>
                    <a href="{{ route('leave-requests.pending') }}" class="btn btn-sm btn-outline-primary">Angalia Zote</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingLeaveList as $leave)
                            <tr>
                                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                <td>{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</td>
                                <td>{{ $leave->days_requested }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No any pending leave requests yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Hires -->
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Recent Hires</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentHires as $emp)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $emp->first_name }} {{ $emp->last_name }}</span>
                            <small class="text-muted">{{ $emp->created_at->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">No any data now.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection