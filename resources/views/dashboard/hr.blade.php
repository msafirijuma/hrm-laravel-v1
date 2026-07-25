@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
    <h2 class="mb-4">HR Dashboard</h2>

    <div class="row">
        <!-- Stat Cards -->
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Employees</h5>
                    <h2>{{ $totalEmployees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h2>{{ $totalDepartments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Pending Leaves</h5>
                    <h2>{{ $pendingLeaves }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Payroll This Month</h5>
                    <h2>TZS {{ number_format($thisMonthPayroll, 0) }}</h2>
                    <small>{{ $totalPayrollThisMonth }} Employees</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payrolls -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Payrolls</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Month</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td>{{ $payroll->month }}</td>
                        <td>TZS {{ number_format($payroll->net_salary, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection