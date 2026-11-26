<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Catatan::where('user_id', auth::id())
            ->where('is_archived', false);

        if ($request->filled('cari')) {
            $keyword = '%' . $request->cari . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                    ->orWhere('isi', 'like', $keyword);
            });
        }

        $urut = $request->get('urut', 'terbaru');
        if ($urut == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Ganti get() dengan paginate(5)
        $catatan = $query->paginate(4); // 5 catatan per halaman
        $jumlah_aktif = Catatan::where('user_id', auth::id())->where('is_archived', false)->count();

        return view('catatan.index', compact('catatan', 'jumlah_aktif'));
    }

    public function create()
    {
        return view('catatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048'
        ]);

        $data = $request->only(['judul', 'isi']);
        $data['user_id'] = auth::id();

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->hashName();
            $file->storeAs('lampiran', $filename, 'public');
            $data['lampiran'] = 'lampiran/' . $filename;
        }

        Catatan::create($data);
        return redirect('/catatan')->with('sukses', 'Catatan berhasil disimpan!');
    }

    public function show($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth::id())
            ->findOrFail($id);

        return view('catatan.show', compact('catatan'));
    }

    public function edit($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth::id())
            ->findOrFail($id);

        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, $id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth::id())
            ->findOrFail($id);

        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048'
        ]);


        $data = $request->only(['judul', 'isi']);

        if ($request->hasFile('lampiran')) {
            if ($catatan->lampiran && Storage::disk('public')->exists($catatan->lampiran)) {
                Storage::disk('public')->delete($catatan->lampiran);
            }
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->hashName();
            $file->storeAs('lampiran', $filename, 'public');
            $data['lampiran'] = 'lampiran/' . $filename;
        }

        $catatan->update($data);
        return redirect('/catatan')
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth::id())
            ->findOrFail($id);

        if ($catatan->lampiran && Storage::disk('public')->exists($catatan->lampiran)) {
            Storage::disk('public')->delete($catatan->lampiran);
        }

        $catatan->delete();

        return redirect('/catatan')
            ->with('success', 'Catatan berhasil dihapus!');
    }

    public function arsip(Request $request)
    {
        $query = Catatan::where('user_id', auth::id())
            ->where('is_archived', true);

        if ($request->filled('cari')) {
            $keyword = '%' . $request->cari . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                    ->orWhere('isi', 'like', $keyword);
            });
        }

        $urut = $request->get('urut', 'terbaru');
        $query->orderBy('created_at', $urut == 'terlama' ? 'asc' : 'desc');

        $catatan = $query->paginate(6);
        $jumlah_arsip = Catatan::where('user_id', auth::id())->where('is_archived', true)->count();

        return view('catatan.arsip', compact('catatan', 'jumlah_arsip'));
    }

    public function archive($id)
    {
        $catatan = Catatan::where('user_id', auth::id())
            ->where('is_archived', false)
            ->findOrFail($id);
        $jumlah_arsip = Catatan::where('user_id', auth::id())->where('is_archived', true)->count();

        $catatan->update(['is_archived' => true]);

        return redirect('/catatan')
            ->with('success', 'Catatan berhasil diarsipkan!');
    }

    public function unarchive($id)
    {
        $catatan = Catatan::where('user_id', auth::id())
            ->where('is_archived', true)
            ->findOrFail($id);

        $catatan->update(['is_archived' => false]);

        return redirect('/catatan')
            ->with('success', 'Arsip berhasil dibatalkan!');
    }

public function importForm()
{
    return view('catatan.import');
}


    public function export()
    {
        $catatan = Catatan::where('user_id', auth::id())
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'catatan_aktif_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($catatan) {

            $handle = fopen('php://output', 'w');

            // Supaya karakter terbaca dengan baik di Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Judul tabel
            fputcsv($handle, [
                'No',
                'Judul Catatan',
                'Isi Catatan',
                'Tanggal Dibuat'
            ], ';');

            // Isi tabel
            foreach ($catatan as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item->judul,
                    $item->isi,
                    $item->created_at->format('d-m-Y H:i')
                ], ';');
            }

            fclose($handle);
        }, $filename, $headers);
    }

    
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt|max:2048'
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getPathname(), 'r');

    if (!$handle) {
        return redirect('/catatan')
            ->with('error', 'File gagal dibaca!');
    }

    // Baca header CSV
    $header = fgetcsv($handle, 0, ';');

    // Hapus BOM jika ada
    if (!empty($header[0])) {
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
    }

    $jumlah = 0;

    while (($row = fgetcsv($handle, 0, ';')) !== false) {

        // Pastikan kolom tersedia
        if (count($row) < 3) {
            continue;
        }

        // Format CSV hasil export:
        // No, Judul Catatan, Isi Catatan, Tanggal Dibuat

        $judul = trim($row[1] ?? '');
        $isi = trim($row[2] ?? '');

        if ($judul === '' || $isi === '') {
            continue;
        }

        Catatan::create([
            'user_id' => Auth::id(),
            'judul' => $judul,
            'isi' => $isi,
            'is_archived' => false,
        ]);

        $jumlah++;
    }

    fclose($handle);

    return redirect('/catatan')
        ->with('success', "Berhasil mengimpor {$jumlah} catatan.");
}

}
