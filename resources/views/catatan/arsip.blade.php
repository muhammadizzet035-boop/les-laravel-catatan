@extends('layouts.app')

@section('title', 'Catatan Arsip')

@section('content')
<div class="row justify-content-between mb-3">
    <div class="col">
        <h2>Catatan Arsip</h2>
                <p class="text-muted">Kamu punya {{ $jumlah_arsip }} catatan di arsip.</p>
    </div>
    <div class="col-auto">
        <a href="/catatan" class="btn btn-outline-primary">← Aktif</a>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <form method="GET" action="/catatan/arsip" class="flex-fill">
        <div class="input-group">
            <input type="text" name="cari" class="form-control" placeholder="Cari di arsip..." value="{{ request('cari') }}">
            <button class="btn btn-outline-primary" type="submit">Cari</button>
            @if(request('cari'))
            <a href="/catatan/arsip" class="btn btn-outline-secondary">✕ Reset</a>
            @endif
        </div>
    </form>

    <form method="GET" action="/catatan/arsip" class="ms-2">
        <input type="hidden" name="cari" value="{{ request('cari') }}">
        <select name="urut" class="form-select" onchange="this.form.submit()">
            <option value="terbaru" {{ request('urut') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlama" {{ request('urut') == 'terlama' ? 'selected' : '' }}>Terlama</option>
        </select>
    </form>
</div>

@if($catatan->count() > 0)
<div class="row">
    @foreach($catatan as $item)
    <div class="col-md-6 mb-3">
        <div class="card border-secondary">
            <div class="card-header text-muted">
    <h5 class="mb-1">{{ $item->judul }}</h5>
    <small>Dibuat pada: {{ $item->created_at->format('d F Y \p\u\k\u\l H.i') }}</small>
</div>
            <div class="card-body">
                <p class="card-text">{{ Str::limit($item->isi, 100) }}</p>
                <a href="/catatan/{{ $item->id }}" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="fas fa-eye"></i></a>
                <form action="/catatan/{{ $item->id }}/unarchive" method="POST" class="d-inline" title="Pulihkan">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-undo"></i>
                    </button>
                </form>
                <form action="/catatan/{{ $item->id }}" method="POST" class="d-inline" title="Hapus permanen">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger"
                        onclick="bukaModalHapus('/catatan/{{ $item->id }}', '{{ addslashes($item->judul) }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-info">
    Tidak ada catatan di arsip.
    <a href="/catatan" class="ms-2">Kembali ke daftar aktif</a>
</div>
@endif
{{ $catatan->links('pagination::bootstrap-5') }}
@endsection