<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Konsumen;
use App\Models\Supplier;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\PenjualanData;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PenjualanExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::all();
        return view('content.penjualan.index', compact('penjualans'));
    }
    public function create()
    {
        $konsumens = Konsumen::all();
        $items = Produk::all();
        return view('content.penjualan.create', compact('konsumens', 'items'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'notrs' => 'required',
            'tgl' => 'required',
            'total' => 'required',
            'konsumenfk' => 'required',
        ]);

        $penjualan = Penjualan::create([
            'notrs' => $request->notrs,
            'konsumenfk' => $request->konsumenfk,
            'tgl' => $request->tgl,
            'total' => $request->total,
            'created_at' => now(),
            'updated_at' => now(),
            'addedbyfk' => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            $produk = Produk::findOrFail($item['itemfk']);

            if (!$produk) {
                throw ValidationException::withMessages([
                    'products' => 'Produk tidak ditemukan'
                ]);
            }

            if ($produk->stok < $item['qty']) {
                throw ValidationException::withMessages([
                    'products' => "Stok {$produk->nm} tidak mencukupi"
                ]);
            }
            $subtotal = $item['hargajl'] * $item['qty'];

            PenjualanData::create([
                'notrs' => $penjualan->notrs,
                'itemfk' => $item['itemfk'],
                'hargajl' => $item['hargajl'],
                'qty' => $item['qty'],
                'subtotal' => $subtotal,
            ]);

            Produk::where('pk', $item['itemfk'])->update([
                'stok' => $produk->stok - $item['qty']
            ]);
        }

        return redirect()->route('penjualan')->with('success', 'Penjualan berhasil disimpan');
    }
    public function edit($notrs)
    {
        $penjualan = Penjualan::where('notrs', $notrs)->firstOrFail();
        $penjualanDetails = PenjualanData::where('notrs', $penjualan->notrs)->get();
        $konsumens = Konsumen::orderBy('nm')->get();
        $items  = Produk::orderBy('nm')->get();
        return view('content.penjualan.edit', compact(
            'penjualan',
            'penjualanDetails',
            'konsumens',
            'items'
        ));
    }
    public function update(Request $request, $notrs)
    {
        $request->validate([
            'konsumenfk' => 'required',
            'tgl'        => 'required',
            'total'      => 'required',
            'items'      => 'required|array',
        ]);

        $penjualan = Penjualan::where('notrs', $notrs)->firstOrFail();

        $oldDetails = PenjualanData::where('notrs', $penjualan->notrs)->get();

        foreach ($oldDetails as $detail) {
            Produk::where('pk', $detail->itemfk)
                ->increment('stok', $detail->qty);
        }

        PenjualanData::where('notrs', $penjualan->notrs)->delete();

        $penjualan->update([
            'konsumenfk'  => $request->konsumenfk,
            'tgl'         => $request->tgl,
            'total'       => $request->total,
            'updatedbyfk' => Auth::id(), 
        ]);

        foreach ($request->items as $item) {

            PenjualanData::create([
                'notrs'    => $penjualan->notrs,
                'itemfk'   => $item['itemfk'],
                'qty'      => $item['qty'],
                'hargajl'  => $item['hargajl'],
                'subtotal' => $item['qty'] * $item['hargajl'],
            ]);

            Produk::where('pk', $item['itemfk'])
                ->decrement('stok', $item['qty']);
        }

        return redirect()->route('penjualan')
            ->with('success', 'Penjualan berhasil diperbarui.');
    }
    public function destroy($notrs)
    {
        PenjualanData::where('notrs', $notrs)->delete();
        Penjualan::where('notrs', $notrs)->delete();

        return redirect()->back()->with('success', 'Penjualan berhasil dihapus.');
    }
    public function excel()
    {
        return Excel::download(new PenjualanExport, 'penjualan.xlsx');
    }
    public function pdf()
    {
        $penjualan = Penjualan::all();

        $pdf = Pdf::loadView('content.penjualan.pdf', compact('penjualan'))->setPaper('A4', 'landscape');
        return $pdf->download('laporan-penjualan.pdf');
    }
}
