@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Manager Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">My Team Members</h6>
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

    <div class="row">
        <!-- My Team Members -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Team Members</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
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
                                <td colspan="3" class="text-center py-4 text-muted">
                                    No any team members yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="col-lg-5">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('my-leaves') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar-alt"></i> My Leaves
                        </a>
                        <a href="{{ route('apply-leave') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Apply Leave
                        </a>
                        <a href="{{ route('my-profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <a href="{{ route('my-payslips') }}" class="btn btn-outline-info">
                            <i class="fas fa-file-invoice"></i> My Payslips
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Summary</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Total Team Members:</strong> {{ $teamMembers->count() }}
                    </p>
                    <p class="mb-2">
                        <strong>Pending Leaves Request:</strong> 
                        <span class="badge bg-warning">{{ $pendingTeamLeaves }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>On-Leave Now:</strong> 
                        <span class="badge bg-info">{{ $onLeaveNow }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection