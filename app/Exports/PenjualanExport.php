<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Penjualan::with('konsumen')->get();
    }

    private $index = 0;

    public function map($row): array
    {
        return [
            ++$this->index,
            $row->notrs,
            $row->tgl,
            $row->konsumen->nm,
            number_format($row->total),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Faktur',
            'Tanggal',
            'Pelanggan',
            'Total Penjualan',
        ];
    }
}
