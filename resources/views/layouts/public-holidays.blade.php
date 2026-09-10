<div class="row my-4 mb-5">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">
            <div class="card-body p-0">
                <div class="p-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-white-50 text-uppercase fw-semibold" style="letter-spacing: 0.5px;">
                                Next Public Holiday
                            </small>
                            @if($nextHoliday)
                                <h4 class="fw-bold mb-1 mt-1">{{ $nextHoliday->name }}</h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $nextHoliday->date->format('d M Y') }}
                                </p>
                            @else
                                <h5 class="mt-2 mb-0">No upcoming public holidays.</h5>
                            @endif
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" 
                            style="width: 48px; height: 48px;">
                            <i class="fas fa-umbrella-beach fa-lg text-white"></i>
                        </div>
                    </div>
                </div>

                @if($nextHoliday)
                <div class="p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Days remaining</span>
                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            {{ $nextHoliday->date->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>