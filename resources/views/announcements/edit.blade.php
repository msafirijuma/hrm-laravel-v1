@extends('layouts.app')

@section('title', 'Edit Announcement')

@section('content')
<h2 class="mb-4">Edit Announcement</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('announcements.update', $announcement) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required 
                       value="{{ old('title', $announcement->title) }}">
            </div>

            <div class="mb-3">
                <label>Body</label>
                <textarea name="body" class="form-control" rows="5" required>{{ old('body', $announcement->body) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="normal" {{ $announcement->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="important" {{ $announcement->priority == 'important' ? 'selected' : '' }}>Important</option>
                    <option value="urgent" {{ $announcement->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ $announcement->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Published At</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ $announcement->published_at?->format('Y-m-d\TH:i') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Expires At</label>
                    <input type="datetime-local" name="expires_at" class="form-control"
                           value="{{ $announcement->expires_at?->format('Y-m-d\TH:i') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Sasisha</button>
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Ghairi</a>
        </form>
    </div>
</div>
@endsection