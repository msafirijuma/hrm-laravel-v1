@extends('layouts.app')

@section('title', 'Add Public Holiday')

@section('content')
<h2 class="mb-4">Add Public Holiday</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('public-holidays.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_recurring" value="1" class="form-check-input" id="recurring">
                <label class="form-check-label" for="recurring">Recurring</label>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('public-holidays.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection