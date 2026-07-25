@extends('layouts.app')

@section('title', 'Hariri Mfanyakazi')

@section('content')
    <div class="container">
        <h2 class="mb-4">Hariri Taarifa za Mfanyakazi</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jina la Kwanza <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" 
                                       value="{{ old('first_name', $employee->first_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jina la Mwisho <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" 
                                       value="{{ old('last_name', $employee->last_name) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', $employee->email) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Simu <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" 
                                       value="{{ old('phone', $employee->phone) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Kuzaliwa <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="date_of_birth" class="form-control" 
                                       value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Kuajiriwa <span class="text-danger">*</span></label>
                                <input type="date" name="date_hired" class="form-control" 
                                       value="{{ old('date_hired', $employee->date_hired?->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Idara <span class="text-danger">*</span></label>
                                <select name="department_id" class="form-control" required>
                                    <option value="">-- Chagua Idara --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $dept->id == $employee->department_id ? 'selected' : '' }}>
                                            {{ $dept->name }} ({{ $dept->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Cheo <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control" required>
                                    <option value="">-- Chagua Cheo --</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}" {{ $pos->id == $employee->position_id ? 'selected' : '' }}>
                                            {{ $pos->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jinsia <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Mshahara wa Msingi (TZS)</label>
                                <input type="number" name="basic_salary" class="form-control" step="0.01"
                                       value="{{ old('basic_salary', $employee->basic_salary) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Role / Mamlaka <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ $employee->user && $employee->user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label>Picha Mpya (Optional)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        
                        @if($employee->photo)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $employee->photo) }}" 
                                     class="img-thumbnail" width="150" alt="Current Photo">
                                <p class="text-muted small mt-1">Picha ya sasa</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Hifadhi Mabadiliko</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg">Rudi</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection