<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use Illuminate\Http\Request;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumens = Konsumen::all();
        return view('content.konsumen.index', compact('konsumens'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kd' => 'required',
            'nm' => 'required|string|max:100',
            'tlp' => 'required',
            'alamat' => 'required',
            'email' => 'required',
            'status' => 'required',
        ]);

        Konsumen::create([
            'kd' => $request->kd,
            'nm' => $request->nm,
            'tlp' => $request->tlp,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Konsumen berhasil ditambahkan');
    }
    public function update(Request $request, $pk)
    {
        $request->validate([
            'kd' => 'required',
            'nm' => 'required|string|max:100',
            'tlp' => 'required',
            'alamat' => 'required',
            'email' => 'required',
            'status' => 'required',
        ]);

        Konsumen::where('pk', $pk)->update([
            'kd' => $request->kd,
            'nm' => $request->nm,
            'tlp' => $request->tlp,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Konsumen berhasil diperbarui');
    }
    public function destroy($pk)
    {
        $konsumens = Konsumen::findOrFail($pk);
        $konsumens->delete();

        return redirect()->back()->with('success', 'Konsumen berhasil dihapus.');
    }
}
