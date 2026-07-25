@extends('layouts.app')

@section('title', 'Payroll Reports')

@section('content')
    <h2 class="mb-4">Payroll Reports</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Total Payroll Yote</h5>
                    <h3>TZS {{ number_format($totalPayroll, 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Employees Paid</h5>
                    <h3>{{ $totalEmployeesPaid }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Choose Month</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($months as $m)
                <div class="col-md-3 mb-2">
                    <a href="{{ route('payrolls.monthly.report', $m) }}" 
                       class="btn btn-outline-primary w-100">
                        {{ $m }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection