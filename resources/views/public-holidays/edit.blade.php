@extends('layouts.app')

@section('title', 'Edit Public Holiday')

@section('content')
<h2 class="mb-4">Edit Public Holiday</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('public-holidays.update', $publicHoliday) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $publicHoliday->name }}" required>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="{{ $publicHoliday->date->format('Y-m-d') }}" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_recurring" value="1" class="form-check-input" id="recurring"
                       {{ $publicHoliday->is_recurring ? 'checked' : '' }}>
                <label class="form-check-label" for="recurring">Recurring</label>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $publicHoliday->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('public-holidays.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection