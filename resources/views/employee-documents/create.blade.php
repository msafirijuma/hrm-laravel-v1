@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<h2 class="mb-4">Upload Document – {{ $employee->first_name }} {{ $employee->last_name }}</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('employees.documents.store', $employee) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Employment Contract 2026">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="contract">Contract</option>
                    <option value="certificate">Certificate</option>
                    <option value="cv">CV</option>
                    <option value="id">ID / Passport</option>
                    <option value="other">Other</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>File (PDF, DOC, JPG, PNG – max 5MB)</label>
                <input type="file" name="file" class="form-control" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Upload</button>
            <a href="{{ route('employees.documents.index', $employee) }}" class="btn btn-secondary">Ghairi</a>
        </form>
    </div>
</div>
@endsection