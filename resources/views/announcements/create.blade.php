@extends('layouts.app')

@section('title', 'New Announcement')

@section('content')
<h2 class="mb-4">New Announcement</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('announcements.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
            </div>
            <div class="mb-3">
                <label>Body</label>
                <textarea name="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
            </div>
            <div class="mb-3">
                <label>Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="normal">Normal</option>
                    <option value="important">Important</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Published At (Optional)</label>
                    <input type="datetime-local" name="published_at" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Expires At (Optional)</label>
                    <input type="datetime-local" name="expires_at" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Publish</button>
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Ghairi</a>
        </form>
    </div>
</div>
@endsection