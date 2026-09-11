@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Super Admin Dashboard</h2>

    <div class="row g-3 mb-4">
        <h5 class="small mb-0 fs-5">Quick Info</h5>
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6>Total Employees</h6>
                    <h2>{{ $totalEmployees }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Employees
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6>Departments</h6>
                    <h2>{{ $totalDepartments }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('departments.index') }}" class="btn btn-light btn-sm text-success font-weight-bold">
                            <i class="fas fa-eye"></i> View Departments
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6>Pending Leaves</h6>
                    <h2>{{ $pendingLeaves }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('leave-requests.pending') }}" class="btn btn-light btn-sm text-warning font-weight-bold">
                            <i class="fas fa-eye"></i> View Pending Leaves
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6>Payroll This Month</h6>
                    <h2>TZS {{ number_format($thisMonthPayroll, 0) }}</h2>
                </div>
                <div class="card-footer">
                    <a href="{{ route('payrolls.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-eye"></i> View Payrolls
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h6>Staff Turnover (This Month)</h6>
                    <h2>{{ $turnoverRate }}%</h2>
                    <small>{{ $terminatedThisMonth }} terminated</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4 mt-5">
        <div class="col-12">
            <!-- Latest Announcements -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-bullhorn text-primary me-2"></i>
                        Latest Announcements
                    </h5>
                    <a href="{{ route('announcements.board') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="fas fa-eye me-2"></i> View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($latestAnnouncements as $item)
                    <div class="p-3 border-bottom position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="pe-3">
                                <h6 class="fw-semibold mb-1">{{ $item->title }}</h6>
                                <p class="text-muted small mb-2">
                                    {{ Str::limit($item->body, 90) }}
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $item->published_at?->diffForHumans() }}
                                </small>
                            </div>
                            @if($item->priority == 'urgent')
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <i class="fas fa-exclamation-circle me-1"></i> Urgent
                                </span>
                            @elseif($item->priority == 'important')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    <i class="fas fa-star me-1"></i> Important
                                </span>
                            @else
                            <span class="badge bg-primary text-light rounded-pill px-3 py-2">Normal</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                        No any announcement at the moment.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Active vs Inactive vs Terminated -->
    <div class="row g-3 mb-4">
        <h5 class="small mb-0 fs-5">Employees Info</h5>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Active</h6>
                    <h3 class="text-success fw-bold">{{ $activeEmployees }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Inactive</h6>
                    <h3 class="text-warning fw-bold">{{ $inactiveEmployees }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Terminated</h6>
                    <h3 class="text-danger fw-bold">{{ $terminatedEmployees }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="row g-3 mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i> Recent Logs
                    </h5>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-light rounded-pill text-info">
                        <i class="fas fa-eye me-2"></i> View All
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th style="width: 120px; min-width: 120px">Date</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th style="width: 140px; min-width: 140px">Description</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActivities as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <strong class="text-dark">{{ $log->user->name ?? 'System/Guest' }}</strong>
                                        <small class="text-muted d-block" style="font-size: 11px;">
                                            {{ $log->user ? ($log->user->getRoleNames()->first() ?? 'User') : '—' }}
                                        </small>
                                    </td>
                                    <td>
                                        <!-- badge color based on activity type -->
                                        <span class="badge px-3 py-1.5 text-uppercase bg-{{ 
                                            Str::contains(strtolower($log->action), ['create', 'add']) ? 'success' : 
                                            (Str::contains(strtolower($log->action), ['update', 'edit', 'change']) ? 'warning text-dark' : 
                                            (Str::contains(strtolower($log->action), ['delete', 'remove', 'cancel']) ? 'danger' : 'info')) 
                                        }}">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td class="text-secondary font-monospace small">{{ $log->ip_address ?? '—' }}</td>
                                </tr>
                                @empty
                                {{-- <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No activity logs yet.</td>
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
    
    <!-- Quick Actions -->
    <div class="row g-3 mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-dark-subtle text-dark">
                    <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('payrolls.reports') }}" class="btn btn-success btn-lg w-100 py-4">
                                <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                                Reports
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-primary btn-lg w-100 py-4">
                                <i class="fas fa-history fa-2x d-block mb-2"></i>
                                Activity Logs
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('settings.index') }}" class="btn btn-info btn-lg w-100 py-4">
                                <i class="fas fa-cog fa-2x d-block mb-2"></i>
                                 Settings
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="public-holidays" class="btn btn-warning btn-lg w-100 py-4">
                                <i class="fas fa-calendar-day fa-2x d-block mb-2"></i>
                                Holidays
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection