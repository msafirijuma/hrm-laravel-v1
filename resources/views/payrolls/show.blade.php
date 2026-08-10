@extends('layouts.app')

@section('title', 'Payslip - {{ $payroll->month }}')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>PAYSLIP - {{ $payroll->month }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}<br>
                                <strong>Emp. Number:</strong> {{ $payroll->employee->employee_number }}
                            </div>
                            <div class="col-md-6 text-end">
                                <strong>Department:</strong> {{ $payroll->employee->department->name ?? '-' }}<br>
                                <strong>Position:</strong> {{ $payroll->employee->position->name ?? '-' }}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <th>Basic Salary</th>
                                <td class="text-end">TZS {{ number_format($payroll->basic_salary, 0) }}</td>
                            </tr>
                            <tr>
                                <th>Allowances</th>
                                <td class="text-end">TZS {{ number_format($payroll->allowances, 0) }}</td>
                            </tr>
                            <tr class="table-success">
                                <th>Gross Salary</th>
                                <td class="text-end"><strong>TZS {{ number_format($payroll->gross_salary, 0) }}</strong></td>
                            </tr>
                            <tr>
                                <th>NSSF (10%)</th>
                                <td class="text-end">TZS {{ number_format($payroll->nssf_employee, 0) }}</td>
                            </tr>
                            <tr>
                                <th>NHIF</th>
                                <td class="text-end">TZS {{ number_format($payroll->nhif, 0) }}</td>
                            </tr>
                            <tr>
                                <th>PAYE</th>
                                <td class="text-end">TZS {{ number_format($payroll->paye, 0) }}</td>
                            </tr>
                            <tr>
                                <th>Other Deductions</th>
                                <td class="text-end">TZS {{ number_format($payroll->other_deductions, 0) }}</td>
                            </tr>
                            <tr class="table-danger">
                                <th>Total Deductions</th>
                                <td class="text-end">TZS {{ number_format($payroll->nssf_employee + $payroll->nhif + $payroll->paye + $payroll->other_deductions, 0) }}</td>
                            </tr>
                            <tr class="table-info">
                                <th><strong>NET SALARY</strong></th>
                                <td class="text-end"><strong>TZS {{ number_format($payroll->net_salary, 0) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <!-- Back to payrolls -->
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Payrolls
                    </a>
                    <!-- Print Button -->
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print"></i> Print
                    </button>

                    <!-- Download PDF Button -->
                    <a href="{{ route('payrolls.download', $payroll) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>

                    <!-- Edit -->
                    <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <!-- Mark as Paid -->
                    @if($payroll->status !== 'paid')
                        <form action="{{ route('payrolls.mark-paid', $payroll) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Una uhakika umelipa mshahara huu?')">
                                <i class="fas fa-check-circle"></i> Mark as Paid
                            </button>
                        </form>
                    @endif
                </div> 
            </div>
        </div>
    </div>
@endsection