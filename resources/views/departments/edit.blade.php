@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Department</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('departments.update', $department) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Name of Department</label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name', $department->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control text-uppercase" 
                                       value="{{ old('code', $department->code) }}" maxlength="10" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $department->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-lg">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection