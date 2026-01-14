@extends('layouts/contentNavbarLayout')
@section('title', 'Penjualan')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">@yield('title')</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('penjualan.export.excel') }}" class="btn btn-success">
          <i class="ri ri-file-excel-2-line me-1"></i> Excel
        </a>

        <a href="{{ route('penjualan.export.pdf') }}" class="btn btn-danger">
          <i class="ri ri-file-pdf-line me-1"></i> PDF
        </a>

        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
          <i class="ri ri-add-fill me-1"></i> Tambah
        </a>
      </div>
    </div>

    <div class="card-body">
      @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session()->get('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>No. Faktur</th>
              <th>Tanggal</th>
              <th>Pelanggan</th>
              <th>Total Penjualan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($penjualans as $index => $penjualan)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $penjualan->notrs }}</td>
                <td>{{ $penjualan->tgl }}</td>
                <td>{{ $penjualan->konsumen->nm }}</td>
                <td>{{ number_format($penjualan->total, 0, ',', '.') }}</td>
                <td>
                  <a href="{{ route('penjualan.edit', $penjualan->notrs) }}"
                    class="btn rounded-pill btn-icon btn-warning btn-sm">
                    <i class="icon-base ri ri-pencil-fill"></i>
                  </a>

                  <form action="{{ route('penjualan.destroy', $penjualan->notrs) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn rounded-pill btn-icon btn-danger btn-sm"
                      onclick="return confirm('Hapus data ini?')">
                      <i class="icon-base ri ri-delete-bin-fill"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">
                  Data tidak tersedia
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
