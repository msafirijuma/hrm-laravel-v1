@extends('layouts.app')


@section('content')
    <div class="container-fluid">
        <h2 class="mb-3">@yield('title', 'Dashboard')</h2>
        
        <div class="row">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
                <div>
                    {{-- <h1 class="h3 mb-1 text-gray-800">Dashboard</h1> --}}
                    <p class="mb-0 mt-3 text-muted small">
                        Welcome, <strong class="me-2"><?= htmlspecialchars($_SESSION['name'] ?? $_SESSION['username'] ?? 'User')?></strong> • <?= date('l, d F Y') ?>
                    </p>
                </div>
                <div class="text-sm-end">
                    <small class="text-muted">Last Login: {{ date('Y/m/d H:i' , strtotime($_SESSION['last_login'] ?? 'now')) }} </small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Employees</h5>
                        <h3>0</h3>
                        <p class="card-text">Total Employees</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Departments</h5>
                        <h3>0</h3>
                        <p class="card-text">Departments Registered</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Requests</h5>
                        <h3>0</h3>
                        <p class="card-text">Pending Requests</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Current Year</h5>
                        <h3>{{ date('Y') }}</h3>
                        <p class="card-text">Financial Year</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection