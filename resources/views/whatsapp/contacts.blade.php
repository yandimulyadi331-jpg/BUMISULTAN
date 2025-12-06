@extends('layouts.app')
@section('titlepage', 'Contacts - WhatsApp')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><a href="{{ route('whatsapp.index') }}">WhatsApp</a> /</span> Contacts
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Contact Management</h5>
            <button type="button" class="btn btn-primary" onclick="syncContacts()">
                <i class="ti ti-refresh me-1"></i> Sync dari Karyawan
            </button>
        </div>
        <div class="card-body">
            @if($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nomor WhatsApp</th>
                                <th>Type</th>
                                <th>NIK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr>
                                <td><strong>{{ $contact->name }}</strong></td>
                                <td>{{ $contact->phone_number }}</td>
                                <td>
                                    <span class="badge bg-label-info">{{ ucfirst($contact->type) }}</span>
                                </td>
                                <td>{{ $contact->karyawan_nik ?? '-' }}</td>
                                <td>
                                    @if($contact->is_blacklisted)
                                        <span class="badge bg-danger">Blacklisted</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $contacts->links() }}
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle me-2"></i>
                    Belum ada kontak. Klik "Sync dari Karyawan" untuk import kontak otomatis.
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

@section('myscript')
<script>
    function syncContacts() {
        Swal.fire({
            title: 'Sync Kontak',
            text: 'Mengambil data dari database karyawan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '{{ route("whatsapp.contacts.sync") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Gagal!', 'Tidak dapat sync kontak', 'error');
            }
        });
    }
</script>
@endsection
