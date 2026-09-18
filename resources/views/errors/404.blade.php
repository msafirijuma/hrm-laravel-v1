@extends('layouts.app')

@section('title', 'Not Found')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5 px-4">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-10"
                          style="width: 72px; height: 72px;">
                        <i class="fas fa-search text-secondary fa-2x"></i>
                    </span>
                </div>

                <h1 class="display-5 fw-bold text-muted mb-2">404</h1>
                <h4 class="fw-semibold mb-3">Ukurasa haupatikani</h4>
                <p class="text-muted mb-4">
                    Ukurasa uliouomba haupo au umehamishwa.
                </p>

                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-1"></i> Rudi Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection