<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('content.user.index', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'email' => 'required|email',
            'tlp' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:8',
        ]);

        User::create([
            'nm' => $request->nm,
            'email' => $request->email,
            'tlp' => $request->tlp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }
    public function update(Request $request, $pk)
    {
        $request->validate([
            'nm' => 'required|string|max:100',
            'email' => 'required|email',
            'tlp' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'password' => 'nullable',
        ]);

        $updateData = [
            'nm' => $request->nm,
            'email' => $request->email,
            'tlp' => $request->tlp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        User::where('pk', $pk)->update($updateData);

        return redirect()->back()->with('success', 'User berhasil diperbarui');
    }
    public function destroy($pk){
        $users = User::findOrFail($pk);
        $users->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
