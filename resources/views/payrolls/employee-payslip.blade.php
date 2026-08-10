@extends('layouts.app')

@section('title', 'PAYSLIP - ' . $payroll->month . (date($payroll->year) ? ' ' . $payroll->year : ''))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm payslip-card">
                    
                    <!-- Company Header -->
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <!-- Logo -->
                            <div>
                                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" 
                                     style="max-height: 70px; width: auto;">
                            </div>
                            
                            <!-- Company Address -->
                            <div class="text-end">
                                <strong>{{ config('app.name', 'Your Company Ltd') }}</strong><br>
                                <small>
                                    Dar es Salaam, Tanzania<br>
                                    Phone: +255 123 456 789<br>
                                    Email: info@yourcompany.co.tz<br>
                                    TIN: 123-456-789
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Payslip Title -->
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">PAYSLIP - {{ $payroll->month }}</h4>
                    </div>

                    <div class="card-body p-4">
                        <!-- Employee Info -->
                        <div class="row mb-4 border-bottom pb-3">
                            <div class="col-md-6">
                                <strong>Employee:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}<br>
                                <strong>Emp. Number:</strong> {{ $payroll->employee->employee_number }}
                            </div>
                            <div class="col-md-6 text-md-end">
                                <strong>Department:</strong> {{ $payroll->employee->department->name ?? '-' }}<br>
                                <strong>Position:</strong> {{ $payroll->employee->position->name ?? '-' }}
                            </div>
                        </div>

                        <!-- Salary Breakdown -->
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Notes</th>
                                    <th class="text-end">Amount (TZS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-end">{{ number_format($payroll->basic_salary, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>Allowances</td>
                                    <td class="text-end">{{ number_format($payroll->allowances, 0) }}</td>
                                </tr>
                                <tr class="table-success fw-bold">
                                    <td>Gross Salary</td>
                                    <td class="text-end">{{ number_format($payroll->gross_salary, 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="fw-bold">DEDUCTIONS</td>
                                </tr>
                                <tr>
                                    <td>NSSF (10%)</td>
                                    <td class="text-end">{{ number_format($payroll->nssf_employee, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>NHIF</td>
                                    <td class="text-end">{{ number_format($payroll->nhif, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>PAYE</td>
                                    <td class="text-end">{{ number_format($payroll->paye, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>Other Deductions</td>
                                    <td class="text-end">{{ number_format($payroll->other_deductions, 0) }}</td>
                                </tr>
                                <tr class="table-danger fw-bold">
                                    <td>Total Deductions</td>
                                    <td class="text-end">
                                        {{ number_format($payroll->nssf_employee + $payroll->nhif + $payroll->paye + $payroll->other_deductions, 0) }}
                                    </td>
                                </tr>
                                <tr class="table-info fs-5 fw-bold">
                                    <td>NET SALARY</td>
                                    <td class="text-end">TZS {{ number_format($payroll->net_salary, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-center mt-5 text-muted small">
                            <p>Thank you for your hard work. You will be paid according to company policy.</p>
                            <p><strong>Printed on:</strong> {{ now()->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('my-payslips') }}" class="btn btn-secondary me-2">← Back</a>
                    
                    <a href="{{ route('payrolls.download', $payroll) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')

<style>

    @media screen {
        .payslip-card {
            margin-top: 20px;
            border-radius: 8px;
        }
    }

    @media print {
        body * {
            visibility: hidden !important;
        }
        @page {
            size: auto;   
            margin: 10mm 15mm 10mm 15mm; 
            }

        body {
            margin: 0px; 
        }
    
        .payslip-card, .payslip-card * {
            visibility: visible !important;
        }
        
        .payslip-card {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            border: 1px solid #000 !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 10px !important;
        }

        @page {
            size: A4;
            margin: 15mm !important;
        }
        
        .table {
            width: 100% !important;
        }
    }
</style>
@endsection