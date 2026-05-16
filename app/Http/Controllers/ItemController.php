<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // Menampilkan semua barang donasi (halaman beranda/katalog)
    public function index()
    {
        $items = Item::with('firstImage', 'user', 'location')
            ->where('status_barang', 'available')
            ->latest()
            ->get();

        return view('items.index', compact('items'));
    }

    // Menampilkan form donasi barang
    public function create()
    {
        $locations = Location::all();
        return view('items.create', compact('locations'));
    }

    // Menyimpan barang donasi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kategori'     => 'required|string',
            'variasi'      => 'nullable|string',
            'ukuran'       => 'nullable|string',
            'location_id'  => 'required|exists:locations,id',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $item = Item::create([
            'user_id'      => Auth::id(),
            'location_id'  => $request->location_id,
            'nama_barang'  => $request->nama_barang,
            'deskripsi'    => $request->deskripsi,
            'kategori'     => $request->kategori,
            'variasi'      => $request->variasi,
            'ukuran'       => $request->ukuran,
            'status_barang' => 'available',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                ItemImage::create([
                    'item_id'   => $item->id,
                    'image_url' => $path,
                ]);
            }
        }

        return redirect()->route('items.index')
            ->with('success', 'Barang donasi berhasil diposting!');
    }

    // Menampilkan detail satu barang
    public function show(Item $item)
    {
        $item->load('images', 'user', 'location');
        return view('items.show', compact('item'));
    }

    // Form edit barang (hanya pemilik & status available)
    public function edit(Item $item)
    {
        // Pastikan hanya pemilik yang bisa edit
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa edit jika masih available
        if ($item->status_barang !== 'available') {
            return back()->with('error', 'Barang tidak bisa diedit karena sudah ada pengajuan.');
        }

        $locations = Location::all();
        return view('items.edit', compact('item', 'locations'));
    }

    // Simpan perubahan barang
    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kategori'     => 'required|string',
            'variasi'      => 'nullable|string',
            'ukuran'       => 'nullable|string',
            'location_id'  => 'required|exists:locations,id',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $item->update([
            'location_id'  => $request->location_id,
            'nama_barang'  => $request->nama_barang,
            'deskripsi'    => $request->deskripsi,
            'kategori'     => $request->kategori,
            'variasi'      => $request->variasi,
            'ukuran'       => $request->ukuran,
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items', 'public');
                ItemImage::create([
                    'item_id'   => $item->id,
                    'image_url' => $path,
                ]);
            }
        }

        return redirect()->route('items.show', $item->id)
            ->with('success', 'Barang berhasil diperbarui!');
    }

    // Hapus barang (hanya jika belum ada pengajuan)
    public function destroy(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }

        if ($item->pengajuan()->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena sudah ada pengajuan.');
        }

        $item->delete();
        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    // Pencarian & filter barang
    public function search(Request $request)
    {
        $query = Item::with('firstImage', 'location')
            ->where('status_barang', 'available');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kategori', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('wilayah')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('wilayah', $request->wilayah);
            });
        }

        if ($request->filled('kecamatan')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('kecamatan', $request->kecamatan);
            });
        }

        $items = $query->get();

        return view('items.index', compact('items'));
    }
}