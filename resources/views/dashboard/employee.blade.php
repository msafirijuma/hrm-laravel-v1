@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 fw-bold">Welcome, {{ auth()->user()->name }}</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6>Leave Balance</h6>
                    <h2 class="mb-0">{{ $leaveBalance }} siku</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6>Leave Status</h6>
                    <h4 class="mb-0">
                        @if($currentLeave)
                            <span class="badge bg-success">On Leave</span>
                        @else
                            <span class="badge bg-secondary">No Leave</span>
                        @endif
                    </h4>
                </div>
            </div>
        </div>
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
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">My Pending Leave Request</h5>
                    <a href="{{ route('my-leaves') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Leave Type</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myLeaves as $leave)
                            <tr>
                                <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                <td>{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M') }}</td>
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
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">You did not apply for any leave.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('apply-leave') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Apply Leave
                        </a>
                        <a href="{{ route('my-profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user"></i> View Profile
                        </a>
                        <a href="{{ route('password.change') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                        <a href="{{ route('my-payslips') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-invoice"></i> My Payslips
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection