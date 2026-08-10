@extends('layouts.app')

@section('title', 'Generate Payroll')

@section('content')
    <div class="container">
        <h2 class="mb-4">Generate Payroll for {{ $currentMonth }}</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('payrolls.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Select Employee</label>
                        <select name="employee_id" class="form-control form-select" required>
                            <option value="" disabled selected>-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->employee_number }} - {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Mwezi</label>
                        <input type="text" name="month" class="form-control" value="{{ $currentMonth }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Allowances (TZS)</label>
                        <input type="number" name="allowances" class="form-control" value="0" step="1000">
                    </div>

                    <div class="mb-3">
                        <label>Other Deductions (loan, fine)</label>
                        <input type="number" name="other_deductions" class="form-control" value="0" step="1000" min="0">
                    </div>

                    <input type="hidden" name="month" value="{{ $currentMonth }}">

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-calculator"></i> Generate Payroll
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary btn-lg">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection