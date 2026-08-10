@extends('layouts.app')

@section('title', 'Payroll Report - {{ $month }}')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Payroll Report - <strong>{{ $month }}</strong></h2>
            <div>
                <a href="{{ route('payrolls.reports') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Reports
                </a>
                <a href="{{ route('payrolls.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View all payrolls
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-5">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h6>Employee</h6>
                        <h2>{{ $summary['employee_count'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h6>Gross Salary</h6>
                        <h2>TZS {{ number_format($summary['total_gross'], 0) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h6>Total Deductions</h6>
                        <h2>TZS {{ number_format($summary['total_deductions'], 0) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h6>Net Payroll</h6>
                        <h2>TZS {{ number_format($summary['total_net'], 0) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detailed Payroll - {{ $month }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0" id="payrollTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Gross Salary</th>
                                <th>NSSF</th>
                                <th>NHIF</th>
                                <th>PAYE</th>
                                <th>Other Ded.</th>
                                <th>Net Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</strong><br>
                                    <small class="text-muted">{{ $payroll->employee->employee_number }}</small>
                                </td>
                                <td>{{ $payroll->employee->department->name ?? '-' }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->basic_salary, 0) }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->allowances, 0) }}</td>
                                <td class="text-end fw-bold">TZS {{ number_format($payroll->gross_salary, 0) }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->nssf_employee, 0) }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->nhif, 0) }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->paye, 0) }}</td>
                                <td class="text-end">TZS {{ number_format($payroll->other_deductions, 0) }}</td>
                                <td class="text-end fw-bold text-success">
                                    TZS {{ number_format($payroll->net_salary, 0) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection