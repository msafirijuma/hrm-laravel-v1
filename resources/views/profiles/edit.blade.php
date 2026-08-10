@extends('layouts.app')

@section('title', 'Edit My Profile')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit My Profile</h2>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Photo -->
                            <div class="mb-4 text-center">
                                @if($employee && $employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" 
                                         class="rounded-circle mb-3" width="140" height="140" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:140px;height:140px">
                                        <i class="fas fa-user fa-4x text-white"></i>
                                    </div>
                                @endif
                                
                                <label class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-camera"></i> Change Image
                                    <input type="file" name="photo" class="d-none" accept="image/*">
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" 
                                               value="{{ old('phone', $employee->phone) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control" 
                                               value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Department</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $employee->department->name ?? '—' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Position</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $employee->position->name ?? '—' }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="{{ route('my-profile') }}" class="btn btn-secondary btn-lg">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection