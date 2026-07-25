@extends('layouts.app')

@section('title', 'Ongeza Mfanyakazi Mpya')

@section('content')
    <div class="container">
        <h2 class="mb-4">Ongeza Mfanyakazi Mpya</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jina la Kwanza <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Jina la Mwisho <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Simu <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Kuzaliwa <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Kuajiriwa <span class="text-danger">*</span></label>
                                <input type="date" name="date_hired" class="form-control" required>
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
                                        <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
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
                                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
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
                                    <option value="">-- Chagua --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Mshahara wa Msingi (TZS)</label>
                                <input type="number" name="basic_salary" class="form-control" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Role / Mamlaka <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    <option value="">-- Chagua Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label>Picha ya Mfanyakazi <span class="text-muted">(Optional)</span></label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, JPEG, PNG (Max 2MB)</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Hifadhi Mfanyakazi
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg">Rudi</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection