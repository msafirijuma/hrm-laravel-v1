@extends('layouts.app')

@section('title', 'Edit Payroll - {{ $payroll->month }}')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Payroll - {{ $payroll->month }}</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('payrolls.update', $payroll) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Employee</label>
                            <input type="text" class="form-control" 
                                   value="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Month</label>
                            <input type="text" class="form-control" value="{{ $payroll->month }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Basic Salary</label>
                            <input type="text" class="form-control" 
                                   value="TZS {{ number_format($payroll->basic_salary, 0) }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Allowances</label>
                            <input type="number" name="allowances" class="form-control" 
                                   value="{{ $payroll->allowances }}" step="1000" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Other Deductions</label>
                            <input type="number" name="other_deductions" class="form-control" 
                                   value="{{ $payroll->other_deductions }}" step="1000" min="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $payroll->notes }}</textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Save Changes</button>
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection