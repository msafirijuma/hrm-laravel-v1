@extends('layouts.app')

@section('title', 'Add Department')

@section('content')
    <h2>Add New Department</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Name of Department</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Code (MF: HR, FIN, IT)</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Department</button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection