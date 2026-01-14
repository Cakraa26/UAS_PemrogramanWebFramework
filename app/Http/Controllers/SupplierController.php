<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('content.supplier.index', compact('suppliers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'tlp' => 'required',
            'alamat' => 'required',
        ]);

        Supplier::create([
            'nm' => $request->nm,
            'tlp' => $request->tlp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan');
    }
    public function update(Request $request, $pk)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'tlp' => 'required',
            'alamat' => 'required',
        ]);

        Supplier::where('pk', $pk)->update([
            'nm' => $request->nm,
            'tlp' => $request->tlp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil diperbarui');
    }
    public function destroy($pk)
    {
        $suppliers = Supplier::findOrFail($pk);
        $suppliers->delete();

        return redirect()->back()->with('success', 'Supplier berhasil dihapus.');
    }
}
