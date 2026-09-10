@extends('layouts.app')

@section('title', 'Documents - ' . $employee->first_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Documents: {{ $employee->first_name }} {{ $employee->last_name }}</h2>
    <div>
        <a href="{{ route('employees.documents.create', $employee) }}" class="btn btn-primary">
            <i class="fas fa-upload"></i> Upload Document
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Rudi</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>File</th>
                    <th>Uploaded</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
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
                    <td colspan="5" class="text-center py-4">Hakuna documents bado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection