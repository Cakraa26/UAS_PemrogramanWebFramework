@extends('layouts/contentNavbarLayout')
@section('title', 'Produk')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">@yield('title')</h5>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" data-mode="tambah">
        <i class="icon-base ri ri-add-fill me-2"></i> TAMBAH
      </button>
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
              <th>Nama Produk</th>
              <th>Stok</th>
              <th>Harga Beli</th>
              <th>Harga Jual</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($produks as $index => $produk)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $produk->nm }}</td>
                <td>{{ $produk->stok }}</td>
                <td>{{ number_format($produk->hargabl, 0, ',', '.') }}</td>
                <td>{{ number_format($produk->hargajl, 0, ',', '.') }}</td>
                <td>
                  <button type="button" class="btn rounded-pill btn-icon btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal{{ $produk->pk }}" data-mode="edit">
                    <i class="icon-base ri ri-pencil-fill"></i>
                  </button>
                  <form action="{{ route('produk.destroy', $produk->pk) }}" method="POST" class="d-inline"
                    id="deleteForm-{{ $produk->pk }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn rounded-pill btn-icon btn-sm btn-danger"
                      onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                      <span class="icon-base ri ri-delete-bin-fill"></span>
                    </button>
                  </form>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="editModal{{ $produk->pk }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalCenterTitle">Edit Produk</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('produk.update', $produk->pk) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-12 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="text" id="nm" name="nm" class="form-control"
                                placeholder="Masukkan Nama" value="{{ old('nm', $produk->nm) }}" />
                              <label for="nm">Nama</label>
                              @error('nm')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="col-12 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="number" id="stok" name="stok" class="form-control"
                                placeholder="xxxx@xxx.xx" value="{{ old('stok', $produk->stok) }}" />
                              <label for="stok">Stok</label>
                              @error('stok')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="number" id="hargabl" name="hargabl" class="form-control"
                                placeholder="xxxx@xxx.xx" value="{{ old('hargabl', $produk->hargabl) }}" />
                              <label for="hargabl">Harga Beli</label>
                              @error('hargabl')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="col-12 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="number" id="hargajl" name="hargajl" class="form-control"
                                placeholder="xxxx@xxx.xx" value="{{ old('hargajl', $produk->hargajl) }}" />
                              <label for="hargajl">Harga Jual</label>
                              @error('hargajl')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
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

  <!-- Modal Tambah -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('produk.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nm" name="nm" class="form-control"
                    placeholder="Masukkan Nama" />
                  <label for="nm">Nama</label>
                  @error('nm')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-12 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="number" id="stok" name="stok" class="form-control"
                    placeholder="Masukkan stok" />
                  <label for="stok">Stok</label>
                  @error('stok')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="number" id="hargabl" name="hargabl" class="form-control"
                    placeholder="Masukkan harga beli" />
                  <label for="hargabl">Harga Beli</label>
                  @error('hargabl')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-12 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="number" id="hargajl" name="hargajl" class="form-control"
                    placeholder="Masukkan harga jual" />
                  <label for="hargajl">Harga Jual</label>
                  @error('hargajl')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
