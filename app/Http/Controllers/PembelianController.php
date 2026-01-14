<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\PembelianData;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PembelianExport;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::all();
        return view('content.pembelian.index', compact('pembelians'));
    }
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Produk::all();
        return view('content.pembelian.create', compact('suppliers', 'products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplierfk' => 'required',
            'notrs' => 'required|unique:t_beli_hd,notrs',
            'tgl' => 'required',
            'total' => 'required',
        ]);

        $pembelian = Pembelian::insert([
            'supplierfk' => $request->supplierfk,
            'notrs' => $request->notrs,
            'tgl' => $request->tgl,
            'total' => $request->total,
            'addedbyfk' => Auth::id(),
        ]);

        $pembelianDetails = [];

        foreach ($request->products as $item) {
            $pembelianDetails[] = [
                'notrs' => $pembelian->notrs,
                'itemfk' => $item['itemfk'],
                'qty' => $item['qty'],
                'hargabl' => $item['hargabl'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            Produk::where('pk', $item['itemfk'])->increment('stok', $item['qty']);

            Produk::where('pk', $item['itemfk'])->update([
                'hargabl' => $item['hargabl']
            ]);
        }

        PembelianData::insert($pembelianDetails);

        return redirect()->route('pembelian')->with('success', 'Transaksi Pembelian berhasil disimpan dan stok telah diperbarui.');
    }

    public function edit($pk)
    {
        $pembelian = Pembelian::where('pk', $pk)->firstOrFail();

        $details = PembelianData::where('notrs', $pembelian->notrs)->get();

        $suppliers = Supplier::orderBy('nm')->get();
        $products  = Produk::orderBy('nm')->get();

        return view('content.pembelian.edit', compact(
            'pembelian',
            'details',
            'suppliers',
            'products'
        ));
    }

    public function update(Request $request, $pk)
    {
        $request->validate([
            'supplierfk' => 'required',
            'tgl' => 'required',
            'total' => 'required',
        ]);

        $pembelian = Pembelian::where('pk', $pk)->firstOrFail();

        $oldDetails = PembelianData::where('notrs', $pembelian->notrs)->get();

        foreach ($oldDetails as $detail) {
            Produk::where('pk', $detail->itemfk)
                ->decrement('stok', $detail->qty);
        }

        PembelianData::where('notrs', $pembelian->notrs)->delete();

        $pembelian->update([
            'supplierfk' => $request->supplierfk,
            'tgl' => $request->tgl,
            'total' => $request->total,
            'updatedbyfk' => Auth::id()
        ]);

        foreach ($request->products as $item) {
            PembelianData::create([
                'notrs' => $pembelian->notrs,
                'itemfk' => $item['itemfk'],
                'qty' => $item['qty'],
                'hargabl' => $item['hargabl'],
            ]);

            Produk::where('pk', $item['itemfk'])->increment('stok', $item['qty']);

            Produk::where('pk', $item['itemfk'])->update([
                'hargabl' => $item['hargabl']
            ]);
        }

        return redirect()->route('pembelian')
            ->with('success', 'Pembelian berhasil diperbarui.');
    }
    public function destroy($notrs)
    {
        PembelianData::where('notrs', $notrs)->delete();
        Pembelian::where('notrs', $notrs)->delete();

        return redirect()->back()->with('success', 'Pembelian berhasil dihapus.');
    }
    public function excel()
    {
        return Excel::download(new PembelianExport, 'pembelian.xlsx');
    }
    public function pdf()
    {
        $pembelian = Pembelian::all();

        $pdf = Pdf::loadView('content.pembelian.pdf', compact('pembelian'))->setPaper('A4', 'landscape');
        return $pdf->download('laporan-pembelian.pdf');
    }
}
