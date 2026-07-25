@extends('layouts.app')

@section('title', 'Idara')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Idara za Kampuni</h2>
        <a href="{{ route('departments.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Ongeza Idara Mpya
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover" id="departmentsTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Jina la Idara</th>
                        <th>Code</th>
                        <th>Maelezo</th>
                        <th>Wafanyakazi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $dept->name }}</strong></td>
                        <td>
                            <span class="badge bg-primary">{{ $dept->code }}</span>
                        </td>
                        <td>{{ $dept->description ?? '—' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $dept->employees_count ?? $dept->employees()->count() }}
                            </span>
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <button onclick="triggerEdit('{{ route('departments.edit', $dept) }}')" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <!-- Delete Form (Tumeongeza ID sahihi hapa kwenye FORM) -->
                            <form id="delete-form-{{ $dept->id }}" action="{{ route('departments.destroy', $dept) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <!-- Tumebadili type kuwa button ili isijisumbite yenyewe bila ruhusa ya SweetAlert -->
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="triggerDelete({{ $dept->id }}, '{{ $dept->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">Hakuna idara bado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<!-- Link sahihi na rasmi ya SweetAlert2 CDN -->
<script src="https://jsdelivr.net"></script>

<script>
    $(document).ready(function() {
        $('#departmentsTable').DataTable({
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                }
            }
        });
    });

    // Loader function during page navigation
    function showPageLoader(message) {
        Swal.fire({
            title: 'Tafadhali subiri...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // SweetAlert during edit action
    function triggerEdit(url) {
        showPageLoader('Tunatayarisha fomu ya marekebisho...');
        window.location.href = url;
    }

    // SweetAlert confirmation during deletion
    function triggerDelete(id, name) {
        Swal.fire({
            title: 'Una uhakika?',
            text: `Utafuta idara ya "${name}" kabisa kutoka kwenye mfumo!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ndio, Futa!',
            cancelButtonText: 'Ghairi',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: 'Tunafuta...',
                    text: 'Tafadhali subiri wakati idara ikifutwa.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                // Submit fomu baada ya thibitisho kupatikana
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
