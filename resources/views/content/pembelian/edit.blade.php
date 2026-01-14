@extends('layouts/contentNavbarLayout')
@section('title', 'Edit Pembelian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@yield('title')</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('pembelian.update', $pembelian->pk) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">No. Faktur</label>
                        <input type="text" name="notrs" class="form-control"
                            value="{{ old('notrs', $pembelian->notrs) }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tgl" class="form-control" value="{{ old('tgl', $pembelian->tgl) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplierfk" class="form-select">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->pk }}"
                                    {{ $supplier->pk == $pembelian->supplierfk ? 'selected' : '' }}>
                                    {{ $supplier->nm }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <h5>Detail Barang</h5>
                    <button type="button" id="add_item" class="btn btn-sm btn-primary">
                        <i class="ri ri-add-line me-1"></i> Tambah Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="purchase_details_table">
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
                            @foreach ($details as $i => $detail)
                                <tr>
                                    <td>
                                        <select name="products[{{ $i }}][itemfk]"
                                            class="form-select product-select">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach ($products as $p)
                                                <option value="{{ $p->pk }}" data-price="{{ $p->hargabl }}"
                                                    {{ $p->pk == $detail->itemfk ? 'selected' : '' }}>
                                                    {{ $p->nm }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="products[{{ $i }}][hargabl]"
                                            class="form-control hargabl" value="{{ $detail->hargabl }}">
                                    </td>
                                    <td>
                                        <input type="number" name="products[{{ $i }}][qty]"
                                            class="form-control qty" value="{{ $detail->qty }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control subtotal" readonly>
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
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td colspan="2">
                                    <input type="text" id="grand_total_display" class="form-control fw-bold" readonly>
                                    <input type="hidden" name="total" id="grand_total_input">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('pembelian') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let counter = {{ count($details) }};
            const products = @json($products);

            function hitungTotal() {
                let total = 0;
                $('#purchase_details_table tbody tr').each(function() {
                    let price = parseFloat($(this).find('.hargabl').val()) || 0;
                    let qty = parseFloat($(this).find('.qty').val()) || 0;
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
                <select name="products[${counter}][itemfk]"
                    class="form-select product-select">
                    <option value="">-- Pilih Produk --</option>
                    ${products.map(p =>
                        `<option value="${p.pk}" data-price="${p.hargabl}">${p.nm}</option>`
                    ).join('')}
                </select>
            </td>
            <td><input type="number" step="0.01"
                name="products[${counter}][hargabl]"
                class="form-control hargabl" value="0"></td>
            <td><input type="number"
                name="products[${counter}][qty]"
                class="form-control qty" value="1"></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button"
                class="btn btn-danger btn-sm remove-item">Hapus</button></td>
        </tr>`;
                counter++;
                $('#purchase_details_table tbody').append(row);
            });

            $('#purchase_details_table').on('input change', '.qty, .hargabl', hitungTotal);

            $('#purchase_details_table').on('change', '.product-select', function() {
                let price = $(this).find(':selected').data('price') || 0;
                $(this).closest('tr').find('.hargabl').val(price);
                hitungTotal();
            });

            $('#purchase_details_table').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                hitungTotal();
            });
        });
    </script>
@endsection
