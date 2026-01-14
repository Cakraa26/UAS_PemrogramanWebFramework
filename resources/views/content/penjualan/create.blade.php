@extends('layouts/contentNavbarLayout')
@section('title', 'Tambah Penjualan')

@section('content')
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">@yield('title')</h5>
    </div>

    <div class="card-body">
      @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session()->get('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          {{ session()->get('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <div class="row g-3 mb-4">
          <div class="col-12 col-md-4">
            <label for="notrs" class="form-label">No. Faktur Penjualan</label>
            <input type="text" class="form-control @error('notrs') is-invalid @enderror" id="notrs" name="notrs"
              value="{{ old('notrs') }}" />
            @error('notrs')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12 col-md-4">
            <label for="tgl" class="form-label">Tanggal Penjualan</label>
            <input type="date" class="form-control @error('tgl') is-invalid @enderror" id="tgl" name="tgl"
              value="{{ old('tgl', date('Y-m-d')) }}" />
            @error('tgl')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-12 col-md-4">
            <label for="konsumenfk" class="form-label">Nama Konsumen</label>
            <select name="konsumenfk" id="konsumenfk" class="form-select @error('konsumenfk') is-invalid @enderror"
              required>
              <option value="">-- Pilih Konsumen --</option>
              @foreach ($konsumens as $konsumen)
                <option value="{{ $konsumen->pk }}" {{ old('konsumenfk') == $konsumen->pk ? 'selected' : '' }}>
                  {{ $konsumen->nm }}
                </option>
              @endforeach
            </select>
            @error('konsumenfk')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <hr>
        <div class="d-flex justify-content-between">
          <h5 class="mb-3">Detail Barang Dijual</h5>
          <button type="button" class="btn btn-sm btn-primary mb-4" id="add_item">
            <i class="tf-icons ri-add-line me-1"></i> Tambah Item
          </button>
        </div>

        <div class="table-responsive mb-3">
          <table class="table table-bordered" id="penjualan_details_table">
            <thead>
              <tr>
                <th style="width: 35%;">Produk</th>
                <th style="width: 20%;">Harga Jual Satuan</th>
                <th style="width: 20%;">Kuantitas (Stok Tersedia)</th>
                <th style="width: 15%;">Subtotal</th>
                <th style="width: 10%;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              {{-- Baris Item akan dimasukkan di sini oleh JavaScript --}}
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total Penjualan:</td>
                <td colspan="2">
                  <input type="text" id="grand_total_display" class="form-control fw-bold" readonly value="0.00">
                  <input type="hidden" name="total" id="grand_total_input" value="0.00">
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="mt-2 text-end">
          <button type="submit" class="btn btn-success me-2">Simpan</button>
          <a href="{{ route('penjualan') }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('page-script')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {

      function generateNoTrs() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hour = String(now.getHours()).padStart(2, '0');
        const minute = String(now.getMinutes()).padStart(2, '0');
        const second = String(now.getSeconds()).padStart(2, '0');

        return `PJ${year}${month}${day}${hour}${minute}${second}`;
      }

      $('#notrs').val(generateNoTrs());

    });
  </script>
  <script>
    $(document).ready(function() {
      let itemCounter = 0;
      const items = @json($items);

      function calculateTotals() {
        let grandTotal = 0;
        $('#penjualan_details_table tbody tr').each(function() {
          const $row = $(this);
          const price = parseFloat($row.find('.unit-price').val()) || 0;
          const quantity = parseFloat($row.find('.quantity').val()) || 0;

          const subtotal = price * quantity;
          grandTotal += subtotal;

          $row.find('.subtotal').val(subtotal.toFixed(2));
          $row.find('.hidden-subtotal').val(subtotal);
        });

        $('#grand_total_display').val(grandTotal.toFixed(2));
        $('#grand_total_input').val(grandTotal.toFixed(2));
      }

      function addNewItemRow() {
        const rowId = itemCounter++;
        const productOptions = items.map(p =>
          `<option value="${p.pk}" data-price="${p.hargajl}" data-stock="${p.stok}">${p.nm} (Stok: ${p.stok})</option>`
        ).join('');

        const row = `
                <tr id="row-${rowId}">
                    <td>
                        <select name="items[${rowId}][itemfk]" class="form-select product-select" required>
                            <option value="">-- Pilih Produk --</option>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="items[${rowId}][hargajl]" class="form-control unit-price" required value="0">
                    </td>
                    <td>
                        <input type="number" name="items[${rowId}][qty]" class="form-control quantity" required min="1" value="1">
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal" readonly value="0.00">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item">Hapus</button>
                    </td>
                </tr>
            `;
        $('#penjualan_details_table tbody').append(row);
        calculateTotals();
      }

      $('#add_item').on('click', addNewItemRow);

      $('#penjualan_details_table').on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        calculateTotals();
      });

      $('#penjualan_details_table').on('input change', '.unit-price, .quantity', calculateTotals);

      $('#penjualan_details_table').on('change', '.product-select', function() {
        const selectedOption = $(this).find('option:selected');
        const selectedPrice = selectedOption.data('price') || 0;
        const availableStock = selectedOption.data('stock') || 0;
        const $row = $(this).closest('tr');

        $row.find('.unit-price').val(selectedPrice);
        $row.find('.quantity').attr('max', availableStock);

        calculateTotals();
      });

      addNewItemRow();
    });
  </script>
@endsection
