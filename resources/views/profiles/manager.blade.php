@extends('layouts.app')

@section('title', 'Manager Profile')

@section('content')
    <div class="container">
        <h2 class="mb-4">Manager Profile</h2>

        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        @if($employee && $employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" class="rounded-circle mb-3" width="160" height="160">
                        @else
                            <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width:160px;height:160px">
                                <i class="fas fa-user-tie fa-4x text-white"></i>
                            </div>
                        @endif
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted">Department Manager</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">My Department Info</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Department:</strong> {{ $employee->department->name ?? '—' }}</p>
                        <p><strong>Total Employees:</strong> {{ $employee->department->employees_count ?? '0' }}</p>
                        <hr>
                        <a href="{{ route('my-team') }}" class="btn btn-success">View My Team</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection