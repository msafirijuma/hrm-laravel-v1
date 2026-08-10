@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Super Admin Dashboard</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6>Total Employees</h6>
                    <h2>{{ $totalEmployees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6>Departments</h6>
                    <h2>{{ $totalDepartments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6>Pending Leaves</h6>
                    <h2>{{ $pendingLeaves }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6>Payroll This Month</h6>
                    <h2>TZS {{ number_format($thisMonthPayroll, 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <h6>Active Employees</h6>
                    <h2>{{ $activeEmployees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <h6>Inactive Employees</h6>
                    <h2>{{ $inactiveEmployees }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Recent Logs</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($log->action) }}</span></td>
                        <td>{{ $log->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No activity logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection