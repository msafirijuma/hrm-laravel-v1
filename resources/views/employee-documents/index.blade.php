@extends('layouts.app')

@section('title', 'Documents - ' . $employee->first_name)

@section('content')
<div class="d-md-flex justify-content-between align-items-center mb-4">
    <h2>Documents: {{ $employee->first_name }} {{ $employee->last_name }}</h2>
    <div class="d-flex justify-content-between">
        <a href="{{ route('employees.documents.create', $employee) }}" class="btn btn-primary me-3">
            <i class="fas fa-upload"></i> Upload Document
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover mb-0" id="employeeTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th style="width: 120px; min-width: 120px">Title</th>
                        <th style="width: 100px; min-width: 100px">Type</th>
                        <th>File</th>
                        <th style="width: 120px; min-width: 120px">Uploaded</th>
                        <th style="width: 120px; min-width: 120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $doc->title }}</strong></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($doc->type) }}</span></td>
                        <td>{{ $doc->file_name }}</td>
                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i>
                            </a>
                            <form action="{{ route('documents.destroy', $doc) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Futa document?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No any document yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection