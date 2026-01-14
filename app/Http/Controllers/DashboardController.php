<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenjualan = Penjualan::sum('total');
        $totalPembelian = Pembelian::sum('total');
        $totalProduk    = Produk::count();
        $totalUser      = User::count();

        $penjualanBulanan = Penjualan::select(
            DB::raw('MONTH(tgl) as bulan'),
            DB::raw('SUM(total) as total')
        )
            ->whereYear('tgl', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = date('M', mktime(0, 0, 0, $i));
            $total[$i] = 0; 
        }

        foreach ($penjualanBulanan as $row) {
            $total[$row->bulan] = $row->total;
        }

        $total = array_values($total);

        return view('content.dashboard.index', compact(
            'totalPenjualan',
            'totalPembelian',
            'totalProduk',
            'totalUser',
            'bulan',
            'total'
        ));
    }
}
