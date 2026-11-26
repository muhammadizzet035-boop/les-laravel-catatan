@extends('layouts.app')

@section('title', 'Tambah Kontak')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">

            <div class="card-header">Tambah Kontak Baru</div>

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
              <form method="POST" action="/catatan" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Masukkan alamat email">
                    </div>

                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan</label>
                        <textarea
                            name="pesan"
                            id="pesan"
                            class="form-control"
                            rows="5"
                            placeholder="Tuliskan pesan Anda">{{ old('pesan') }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Simpan Kontak
                        </button>
                    </div>

                    <a href="/kontak" class="mt-2 d-block text-center">
                        ← Kembali ke Daftar Kontak
                    </a>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
