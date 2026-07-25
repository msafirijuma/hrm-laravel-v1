@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
    <h2 class="mb-4">Karibu, {{ Auth::user()->name }}</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Namba ya Mfanyakazi:</strong> {{ Auth::user()->employee->employee_number ?? 'N/A' }}</p>
            <p><strong>Idara:</strong> {{ Auth::user()->employee->department->name ?? 'N/A' }}</p>
            {{-- <a href="{{ route('apply-leave') }}" class="btn btn-primary">Omba Likizo</a> --}}
            <a href="{{ route('apply-leave') }}" class="btn btn-primary">Omba Likizo</a>
        </div>
    </div>
@endsection