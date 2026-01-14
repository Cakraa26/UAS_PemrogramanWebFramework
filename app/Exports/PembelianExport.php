<?php

namespace App\Exports;

use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembelianExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pembelian::with('supplier')->get();
    }

    private $index = 0;

    public function map($row): array
    {
        return [
            ++$this->index,
            $row->notrs,
            $row->tgl,
            $row->supplier->nm,
            number_format($row->total),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Faktur',
            'Tanggal',
            'Supplier',
            'Total Pembelian',
        ];
    }
}
