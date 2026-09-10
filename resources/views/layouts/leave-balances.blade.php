<!-- Leave Balances -->
<div class="row g-3 mb-4">
    @forelse($leaveBalances as $balance)
    @php
        $percent = $balance['total'] > 0 
            ? round(($balance['used'] / $balance['total']) * 100) 
            : 0;
        $barColor = $percent >= 80 ? 'bg-danger' : ($percent >= 50 ? 'bg-warning' : 'bg-success');
        $textColor = $percent >= 80 ? 'text-danger' : ($percent >= 50 ? 'text-warning' : 'text-success');

        $icon = 'fa-calendar-check';
        $iconBg = 'bg-primary';

        if (str_contains(strtolower($balance['name']), 'sick')) {
            $icon = 'fa-notes-medical';
            $iconBg = 'bg-danger';
        } elseif (str_contains(strtolower($balance['name']), 'annual')) {
            $icon = 'fa-umbrella-beach';
            $iconBg = 'bg-success';
        } elseif (str_contains(strtolower($balance['name']), 'maternity')) {
            $icon = 'fa-baby';
            $iconBg = 'bg-info';
        } elseif (str_contains(strtolower($balance['name']), 'paternity')) {
            $icon = 'fa-baby-carriage';
            $iconBg = 'bg-info';
        } elseif (str_contains(strtolower($balance['name']), 'unpaid')) {
            $icon = 'fa-calendar-times';
            $iconBg = 'bg-secondary';
        }
    @endphp

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="{{ $iconBg }} text-white rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <h6 class="text-muted mb-0 fw-semibold">{{ $balance['name'] }}</h6>
                    </div>
                    <span class="badge bg-light text-dark border">{{ $balance['total'] }} days</span>
                </div>

                <h2 class="fw-bold mb-1 {{ $textColor }}">
                    {{ $balance['remaining'] }}
                </h2>
                <p class="text-muted small mb-3">days remaining</p>

                <!-- Progress Bar -->
                <div class="progress mb-2" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar {{ $barColor }}" 
                         style="width: {{ $percent }}%; border-radius: 10px;">
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-muted">
                    <span>Used: <strong>{{ $balance['used'] }}</strong></span>
                    <span>{{ $percent }}%</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info mb-0">
            No leave balances available. Please contact your administrator for support.
        </div>
    </div>
    @endforelse
</div>