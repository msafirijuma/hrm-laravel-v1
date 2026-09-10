@extends('layouts.app')

@section('title', 'Holiday Calendar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Holiday Calendar {{ date('Y') }}</h2>
    <a href="{{ route('public-holidays.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    @foreach($holidays->groupBy(fn($h) => $h->date->format('F')) as $month => $monthHolidays)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong>{{ $month }}</strong>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($monthHolidays as $holiday)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $holiday->name }}</span>
                    <span class="badge bg-secondary">{{ $holiday->date->format('d') }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection