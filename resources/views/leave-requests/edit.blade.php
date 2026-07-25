@extends('layouts.app')

@section('title', 'Hariri Ombi la Likizo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Hariri Ombi la Likizo</h2>
                <a href="{{ route('my-leaves') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Rudi Nyuma
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-edit"></i> Rekebisha Taarifa za Likizo</h5>
                </div>
                <div class="card-body p-4">
                    
                    <!-- FOMU INAPOTUMA DATA KWENYE UTARATIBU WA UPDATE -->
                    <form action="{{ route('leave-requests.update', $leaveRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Laravel inahitaji hii kwa ajili ya update/edit requests -->

                        <!-- 1. Aina ya Likizo -->
                        <div class="mb-3">
                            <label for="leave_type_id" class="form-label fw-bold">Aina ya Likizo</label>
                            <select class="form-select @error('leave_type_id') is-invalid @enderror" id="leave_type_id" name="leave_type_id" required>
                                <option value="">-- Chagua Aina ya Likizo --</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id', $leaveRequest->leave_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 2. Tarehe za Likizo -->
                        <div class="row">
                            <!-- Tarehe ya Kuanza -->
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label fw-bold">Tarehe ya Kuanza</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $leaveRequest->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tarehe ya Kuisha -->
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label fw-bold">Tarehe ya Kuisha</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $leaveRequest->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- 3. Sababu ya Maombi -->
                        <div class="mb-4">
                            <label for="reason" class="form-label fw-bold">Sababu ya Likizo</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4" placeholder="Andika sababu ya maombi yako hapa..." required>{{ old('reason', $leaveRequest->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 4. Button za Kusave au Kughairi -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('my-leaves') }}" class="btn btn-light border me-md-2">Ghairi</a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> Hifadhi Marekebisho
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
