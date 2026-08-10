@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
    <div class="container">
        <h2 class="mb-4">My Payslips</h2>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover" id="payrollTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Month</th>
                            <th>Basic Salary</th>
                            <th>Gross Salary</th>
                            <th>Net Salary</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td><strong>{{ $payroll->month }}</strong></td>
                            <td>TZS {{ number_format($payroll->basic_salary, 0) }}</td>
                            <td>TZS {{ number_format($payroll->gross_salary, 0) }}</td>
                            <td><strong>TZS {{ number_format($payroll->net_salary, 0) }}</strong></td>
                            <td>
                                <a href="{{ route('my-payslip.show', $payroll) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-invoice"></i> Check Payslip
                                </a>
                            </td>
                        </tr>
                        @empty
                        {{-- <tr>
                            <td colspan="5" class="text-center py-4">You haven't received any payroll yet.</td>
                        </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection