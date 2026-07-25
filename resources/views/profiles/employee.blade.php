@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="container">
        <h2 class="mb-4">Taarifa Zangu</h2>

        <div class="row">
            <!-- Left Side - Photo & Basic Info -->
            <div class="col-lg-4">
                <div class="card shadow-sm text-center mb-4">
                    <div class="card-body">
                        @if($employee && $employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" 
                                 class="rounded-circle mb-3" width="170" height="170" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-info d-flex align-items-center justify-content-center mx-auto mb-3" style="width:170px;height:170px">
                                <i class="fas fa-user fa-5x text-white"></i>
                            </div>
                        @endif
                        
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-1">{{ $employee->employee_number ?? 'N/A' }}</p>
                        <span class="badge bg-success">{{ ucfirst($employee->status) }}</span>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit"></i> Hariri Profile
                </a>
                <a href="{{ route('password.change') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-key"></i> Badilisha Nenosiri
                </a>
            </div>

            <!-- Right Side - Details -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Maelezo Yangu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <strong>Email:</strong><br> {{ Auth::user()->email }}
                            </div>
                            <div class="col-md-6">
                                <strong>Simu:</strong><br> {{ $employee->phone ?? '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tarehe ya Kuzaliwa:</strong><br> 
                                {{ $employee->date_of_birth ? $employee->date_of_birth->format('d M Y') : 'Haijajazwa' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tarehe ya Kuajiriwa:</strong><br> 
                                {{ $employee->date_hired ? $employee->date_hired->format('d M Y') : '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Idara:</strong><br> {{ $employee->department->name ?? '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Cheo:</strong><br> {{ $employee->position->name ?? '—' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Mshahara Msingi:</strong><br> 
                                <strong>TZS {{ number_format($employee->basic_salary ?? 0, 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection