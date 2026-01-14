@extends('layouts/contentNavbarLayout')
@section('title', 'Tambah Pembelian')

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

            <form action="{{ route('pembelian.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="notrs" class="form-label">No. Faktur</label>
                        <input type="text" class="form-control" id="notrs" name="notrs" />
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="tgl" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" />
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="tgl" class="form-label">Supplier</label>
                        <select name="supplierfk" id="supplierfk"
                            class="form-select @error('supplierfk') is-invalid @enderror">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->pk }}"
                                    {{ old('supplierfk') == $supplier->pk ? 'selected' : '' }}>
                                    {{ $supplier->nm }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplierfk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5 class="mb-3">Detail Barang Masuk</h5>
                    <button type="button" class="btn btn-sm btn-primary mb-4" id="add_item">
                        <i class="icon-base ri ri-add-line me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="purchase_details_table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Produk</th>
                                <th style="width: 20%;">Harga Beli Satuan</th>
                                <th style="width: 20%;">Kuantitas</th>
                                <th style="width: 15%;">Subtotal</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Pembelian:</td>
                                <td colspan="2">
                                    <input type="text" id="grand_total_display" class="form-control fw-bold" readonly
                                        value="0.00">
                                    <input type="hidden" name="total" id="grand_total_input" value="0.00">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-2 text-end">
                    <button type="submit" class="btn btn-success me-2">Simpan</button>
                    <a href="{{ route('pembelian') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let itemCounter = 0;
            const products = @json($products);

            function calculateTotals() {
                let grandTotal = 0;
                $('#purchase_details_table tbody tr').each(function() {
                    const $row = $(this);
                    const price = parseFloat($row.find('.hargabl').val()) || 0;
                    const qty = parseFloat($row.find('.qty').val()) || 0;

                    const subtotal = price * qty;
                    grandTotal += subtotal;

                    $row.find('.subtotal').val(subtotal.toFixed(2));
                    $row.find('.hidden-subtotal').val(subtotal);
                });

                $('#grand_total_display').val(grandTotal.toFixed(2));
                $('#grand_total_input').val(grandTotal.toFixed(2));
            }

            function addNewItemRow() {
                const rowId = itemCounter++;
                const productOptions = products.map(p =>
                    `<option value="${p.pk}" data-price="${p.hargabl}">${p.nm}</option>`
                ).join('');

                const row = `
                <tr id="row-${rowId}">
                    <td>
                        <select name="products[${rowId}][itemfk]" class="form-select product-select" required>
                            <option value="">-- Pilih Produk --</option>
                            ${productOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="products[${rowId}][hargabl]" class="form-control hargabl" required value="0">
                    </td>
                    <td>
                        <input type="number" name="products[${rowId}][qty]" class="form-control qty" required min="1" value="1">
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal" readonly value="0.00">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item">Hapus</button>
                    </td>
                </tr>
            `;
                $('#purchase_details_table tbody').append(row);
                calculateTotals();
            }

            $('#add_item').on('click', addNewItemRow);

            $('#purchase_details_table').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                calculateTotals();
            });

            $('#purchase_details_table').on('input change', '.hargabl, .qty', calculateTotals);

            $('#purchase_details_table').on('change', '.product-select', function() {
                const selectedPrice = $(this).find('option:selected').data('price') || 0;
                const $row = $(this).closest('tr');

                $row.find('.hargabl').val(selectedPrice);
                calculateTotals();
            });

            addNewItemRow();
        });
    </script>
@endsection
