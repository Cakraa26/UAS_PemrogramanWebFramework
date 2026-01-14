@extends('layouts/contentNavbarLayout')
@section('title', 'Edit Penjualan')

@section('content')
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">@yield('title')</h5>
    </div>

    <div class="card-body">
      <form action="{{ route('penjualan.update', $penjualan->notrs) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-4">
          <div class="col-12 col-md-4">
            <label class="form-label">No. Faktur Penjualan</label>
            <input type="text" class="form-control" name="notrs" value="{{ $penjualan->notrs }}" readonly>
          </div>

          <div class="col-12 col-md-4">
            <label class="form-label">Tanggal Penjualan</label>
            <input type="date" class="form-control" name="tgl" value="{{ $penjualan->tgl }}">
          </div>

          <div class="col-12 col-md-4">
            <label class="form-label">Nama Konsumen</label>
            <select name="konsumenfk" class="form-select" required>
              @foreach ($konsumens as $k)
                <option value="{{ $k->pk }}" {{ $penjualan->konsumenfk == $k->pk ? 'selected' : '' }}>
                  {{ $k->nm }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between">
          <h5 class="mb-3">Detail Barang Dijual</h5>
          <button type="button" class="btn btn-sm btn-primary mb-4" id="add_item">
            Tambah Item
          </button>
        </div>

        <div class="table-responsive mb-3">
          <table class="table table-bordered" id="penjualan_details_table">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($penjualanDetails as $i => $d)
                <tr>
                  <td>
                    <select name="items[{{ $i }}][itemfk]" class="form-select product-select">
                      @foreach ($items as $p)
                        <option value="{{ $p->pk }}" data-price="{{ $p->hargajl }}"
                          data-stock="{{ $p->stok }}" {{ $d->itemfk == $p->pk ? 'selected' : '' }}>
                          {{ $p->nm }}
                        </option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <input type="number" name="items[{{ $i }}][hargajl]" class="form-control unit-price"
                      value="{{ $d->hargajl }}">
                  </td>
                  <td>
                    <input type="number" name="items[{{ $i }}][qty]" class="form-control quantity"
                      value="{{ $d->qty }}">
                  </td>
                  <td>
                    <input type="text" class="form-control subtotal" value="{{ $d->subtotal }}" readonly>
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                      Hapus
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total:</td>
                <td colspan="2">
                  <input type="text" id="grand_total_display" class="form-control fw-bold"
                    value="{{ $penjualan->total }}" readonly>
                  <input type="hidden" name="total" id="grand_total_input" value="{{ $penjualan->total }}">
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="text-end">
          <button class="btn btn-success">Update</button>
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
      let counter = {{ count($penjualanDetails) }};
      const products = @json($items);

      function hitungTotal() {
        let total = 0;

        $('#penjualan_details_table tbody tr').each(function() {
          let price = parseFloat($(this).find('.unit-price').val()) || 0;
          let qty = parseFloat($(this).find('.quantity').val()) || 0;
          let sub = price * qty;

          $(this).find('.subtotal').val(sub.toFixed(2));
          total += sub;
        });

        $('#grand_total_display').val(total.toFixed(2));
        $('#grand_total_input').val(total.toFixed(2));
      }

      hitungTotal();

      $('#add_item').click(function() {

        let row = `
        <tr>
            <td>
                <select name="items[${counter}][itemfk]" class="form-select product-select" required>
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p =>
                        `<option value="${p.pk}" data-price="${p.hargajl}">
                              ${p.nm}
                          </option>`
                    ).join('')}
                </select>
            </td>
            <td>
                <input type="number" step="0.01"
                    name="items[${counter}][hargajl]"
                    class="form-control unit-price" value="0">
            </td>
            <td>
                <input type="number"
                    name="items[${counter}][qty]"
                    class="form-control quantity" value="1">
            </td>
            <td>
                <input type="text" class="form-control subtotal" readonly>
            </td>
            <td>
                <button type="button"
                    class="btn btn-danger btn-sm remove-item">Hapus</button>
            </td>
        </tr>
        `;

        counter++;
        $('#penjualan_details_table tbody').append(row);
      });

      $('#penjualan_details_table').on('input change', '.quantity, .unit-price', hitungTotal);

      $('#penjualan_details_table').on('change', '.product-select', function() {
        let price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.unit-price').val(price);
        hitungTotal();
      });

      $('#penjualan_details_table').on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        hitungTotal();
      });

    });
  </script>

@endsection
