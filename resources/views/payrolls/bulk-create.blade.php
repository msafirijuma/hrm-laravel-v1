@extends('layouts.app')

@section('title', 'Bulk Payroll Generation')

@section('content')
    <div class="container">
        <h2 class="mb-4">Generate Bulk Payroll</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('payrolls.bulk.preview') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Month</label>
                            <input type="text" name="month" class="form-control" 
                                   value="{{ $currentMonth }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department (Optional)</label>
                            <select name="department_id" class="form-select">
                                <option value="">-- All Employees --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Preview Payroll
                    </button>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary btn-lg">Back to Payrolls</a>
                </form>
            </div>
        </div>
    </div>
@endsection