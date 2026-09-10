@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Manager Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-3 mb-5">
        <h5 class="small mb-0 fs-5">Quick Info</h5>
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">Team Members</h6>
                    <h2 class="mb-0">{{ $teamMembers->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h6 class="card-title">Team Pending Leaves</h6>
                    <h2 class="mb-0">{{ $pendingTeamLeaves }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <h6 class="card-title">Team On-Leave Now</h6>
                    <h2 class="mb-0">{{ $onLeaveNow }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Balances -->
    {{-- @include('layouts.leave-balances') --}}

    <div class="row mb-5">
        <!-- My Team Members -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-light">
                    <h5 class="mb-0">Team Members</h5>
                    <a href="{{ route('team.members') }}" class="btn btn-light btn-sm text-primary">
                        <i class="fas fa-eye me-1"></i> View All
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="employeeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Hire date</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                <tr>
                                    <td>
                                        <strong>{{ $member->first_name }} {{ $member->last_name }}</strong><br>
                                        <small class="text-muted">{{ $member->employee_number }}</small>
                                    </td>
                                    <td>{{ $member->position->name ?? '-' }}</td>
                                    <td>{{ $member->date_hired }}</td>
                                    <td>{{ $member->phone ?? '-' }}</td>
                                    <td>{{ $member->email ?? '-' }}</td>
                                    <td>
                                        @if($member->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No team members found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Team Leaves -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-info">
            <h5 class="mb-0">Upcoming Team Leaves</h5>
            <a href="{{ route('team.leaves') }}" class="btn btn-light btn-sm text-info">
                <i class="fas fa-eye me-1"></i> View All
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
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
                        @forelse($upcomingTeamLeaves as $leave)
                        <tr>
                            <td>
                                <strong>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</strong>
                            </td>
                            <td>{{ $leave->leaveType->name ?? '-' }}</td>
                            <td>{{ $leave->start_date->format('d M Y') }}</td>
                            <td>{{ $leave->end_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $leave->days_requested }}</span>
                            </td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No upcoming leaves for your team.
                            </td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Public holidays -->
    @include('layouts.public-holidays')
    
    <!-- Quick Links -->
    <div class="col-lg-12">
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-12 col-md-3">
                        <a href="{{ route('my-leaves') }}" class="btn btn-success btn-lg w-100 py-4">
                            <i class="fas fa-calendar-check me-3 fa-2x d-block mb-2"></i>
                            My Leaves
                        </a>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="{{ route('apply-leave') }}" class="btn btn-primary btn-lg w-100 py-4">
                            <i class="fas fa-paper-plane me-3 fa-2x d-block mb-2"></i>
                            Apply Leave
                        </a>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="{{ route('my-profile') }}" class="btn btn-info btn-lg w-100 py-4">
                            <i class="fas fa-user fa-2x d-block mb-2"></i>
                            My Profile
                        </a>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="{{ route('my-payslips') }}" class="btn btn-warning btn-lg w-100 py-4">
                            <i class="fas fa-file-invoice fa-2x d-block mb-2"></i>
                            My Payslips
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection