@extends('layouts.app')

@section('title', 'Bulk Payroll Preview - {{ $month }}')

@section('content')
    <h2 class="mb-4">Bulk Payroll Preview - {{ $month }}</h2>

    <form action="{{ route('payrolls.bulk.store') }}" method="POST">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover" id="payrollTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Basic Salary</th>
                            <th>Allowances (Posho)</th>
                            <th>Other Deductions</th>
                            <th>Est. Net Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                                <br><small class="text-muted">{{ $employee->employee_number }}</small>
                            </td>
                            <td>{{ $employee->department->name ?? '-' }}</td>
                            <td>TZS {{ number_format($employee->basic_salary ?? 0, 0) }}</td>
                            <td>
                                <input type="number" 
                                       name="employees[{{ $employee->id }}][allowances]" 
                                       class="form-control" 
                                       value="0" 
                                       step="1000" min="0">
                            </td>
                            <td>
                                <input type="number" 
                                       name="employees[{{ $employee->id }}][other_deductions]" 
                                       class="form-control" 
                                       value="0" 
                                       step="1000" min="0">
                            </td>
                            <td class="text-end">
                                <strong>TZS {{ number_format(($employee->basic_salary ?? 0) * 0.9, 0) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-check"></i> Generate All Payrolls
            </button>
            <a href="{{ route('payrolls.bulk.create') }}" class="btn btn-secondary btn-lg">Back</a>
        </div>
    </form>
@endsection