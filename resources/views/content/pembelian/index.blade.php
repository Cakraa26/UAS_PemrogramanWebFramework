@extends('layouts/contentNavbarLayout')
@section('title', 'Pembelian')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">@yield('title')</h5>

      <div class="d-flex gap-2">
        <a href="{{ route('pembelian.export.excel') }}" class="btn btn-success">
          <i class="ri ri-file-excel-2-line me-1"></i> Excel
        </a>

        <a href="{{ route('pembelian.export.pdf') }}" class="btn btn-danger">
          <i class="ri ri-file-pdf-line me-1"></i> PDF
        </a>

        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
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
              <th>Supplier</th>
              <th>Total Pembelian</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pembelians as $index => $pembelian)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pembelian->notrs }}</td>
                <td>{{ $pembelian->tgl }}</td>
                <td>{{ $pembelian->supplier->nm }}</td>
                <td>{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                <td>
                  <a href="{{ route('pembelian.edit', $pembelian->pk) }}"
                    class="btn rounded-pill btn-icon btn-sm btn-warning">
                    <i class="icon-base ri ri-pencil-fill"></i>
                  </a>
                  <form action="{{ route('pembelian.destroy', $pembelian->notrs) }}" method="POST" class="d-inline"
                    id="deleteForm-{{ $pembelian->notrs }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn rounded-pill btn-icon btn-sm btn-danger"
                      onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                      <span class="icon-base ri ri-delete-bin-fill"></span>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">
                  <span class="text-muted">Data tidak tersedia</span>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
    </div>
  </div>
@endsection
