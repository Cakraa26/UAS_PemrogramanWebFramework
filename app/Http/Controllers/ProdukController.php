<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        return view('content.produk.index', compact('produks'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'stok' => 'required',
            'hargabl' => 'required',
            'hargajl' => 'required',
        ]);

        Produk::create([
            'nm' => $request->nm,
            'stok' => $request->stok,
            'hargabl' => $request->hargabl,
            'hargajl' => $request->hargajl,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }
    public function update(Request $request, $pk)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'stok' => 'required',
            'hargabl' => 'required',
            'hargajl' => 'required',
        ]);

        Produk::where('pk', $pk)->update([
            'nm' => $request->nm,
            'stok' => $request->stok,
            'hargabl' => $request->hargabl,
            'hargajl' => $request->hargajl,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui');
    }
    public function destroy($pk)
    {
        $produks = Produk::findOrFail($pk);
        $produks->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
