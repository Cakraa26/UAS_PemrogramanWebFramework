<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan</title>
  <style>
    body {
      font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif,
        'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
    }

    p {
      font-size: 13px;
      color: #343a40;
    }

    .table-bordered {
      width: 100%;
      border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
      border: 1px solid #dee2e6;
      padding: 6px;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <h2 style="text-align:center; margin-bottom: 20px;">Laporan Penjualan</h2>

  @if ($penjualan->count())
    <table class="table-bordered">
      <thead>
        <tr>
          <th style="text-align: left;">No</th>
          <th style="text-align: left;">No. Faktur</th>
          <th style="text-align: left;">Tanggal</th>
          <th style="text-align: left;">Pelanggan</th>
          <th style="text-align: right;">Total Penjualan</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($penjualan as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->notrs }}</td>
            <td>{{ $item->tgl }}</td>
            <td>{{ $item->konsumen->nm }}</td>
            <td style="text-align: right;">{{ number_format($item->total) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p><em>Tidak ada data.</em></p>
  @endif
</body>

</html>
