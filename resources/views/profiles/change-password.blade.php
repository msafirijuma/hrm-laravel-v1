@extends('layouts.app')

@section('title', 'Badilisha Nenosiri')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Badilisha Nenosiri</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Nenosiri la Sasa <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Nenosiri Jipya <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label>Rudia Nenosiri Jipya <span class="text-danger">*</span></label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-key"></i> Badilisha Nenosiri
                            </button>
                            <a href="{{ route('my-profile') }}" class="btn btn-secondary btn-lg">Rudi</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection