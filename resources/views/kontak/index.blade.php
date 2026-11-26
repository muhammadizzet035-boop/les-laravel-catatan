@extends('layouts.app')

@section('title', 'Daftar Kontak')

@section('content')
<div class="row justify-content-between mb-3">
    <div class="col">
        <h2>Daftar Kontak</h2>
    </div>
    <div class="col-auto">
        <a href="/kontak/tambah" class="btn btn-success">
            + Tambah Kontak
        </a>
    </div>
</div>

    </div>


@if($kontak->count() > 0)
    <div class="row">
        @foreach($kontak as $item)
            <div class="col-md-6 mb-3">
                <div class="card">
                @if($item->lampiran)
    <small class="text-muted">📎 Ada lampiran</small>
   @endif

                    <div class="card-header">
                        {{ $item->nama }}

                        <small class="text-muted float-end">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </small>
                    </div>

                    <div class="card-body">

                        <p class="card-text">
                            <strong>Nama:</strong>
                            {{ $item->nama }}
                        </p>

                        <p class="card-text">
                            <strong>Email:</strong>
                            {{ $item->email }}
                        </p>

                        <p class="card-text">
                            <strong>Pesan:</strong>
                            {{ Str::limit($item->pesan, 100) }}
                        </p>

                        <a href="/kontak/{{ $item->id }}"
                           class="btn btn-sm btn-outline-primary">
                            Lihat
                        </a>

                        <a href="/kontak/{{ $item->id }}/edit"
                           class="btn btn-sm btn-outline-warning">
                            Edit
                        </a>

                        <form action="/kontak/{{ $item->id }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Yakin ingin menghapus kontak ini?')">
                                Hapus
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

@else
    <div class="alert alert-info">
        Belum ada kontak.
        <a href="/kontak/tambah">Buat kontak pertama!</a>
    </div>
@endif
@if($item->lampiran)
    <small class="text-muted">📎 Ada lampiran</small>
@endif

@endsection
