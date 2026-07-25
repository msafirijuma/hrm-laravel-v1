@extends('layouts.app')

@section('title', 'Omba Likizo')

@section('content')
    <div class="container">
        <h2 class="mb-4">Omba Likizo</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('leave-requests.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Aina ya Likizo</label>
                                <select name="leave_type_id" class="form-control" required>
                                    <option value="" disabled selected>-- Chagua Aina ya Likizo --</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }} (Siku {{ $type->max_days_per_year }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Kuanza</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tarehe ya Mwisho</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Sababu / Maelezo</label>
                        <textarea name="reason" class="form-control" rows="4" required placeholder="Eleza sababu ya kuomba likizo..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-paper-plane"></i> Wasilisha Ombi
                    </button>
                    <a href="{{ route('my-leaves') }}" class="btn btn-secondary">Rudi</a>
                </form>
            </div>
        </div>
    </div>
@endsection