@extends('layouts/contentNavbarLayout')
@section('title', 'Supplier')

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
              <th>Nama</th>
              <th>Alamat</th>
              <th>No. Telpon</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($suppliers as $index => $supplier)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $supplier->nm }}</td>
                <td>{{ $supplier->alamat }}</td>
                <td>{{ $supplier->tlp }}</td>
                <td>
                  <button type="button" class="btn rounded-pill btn-icon btn-sm btn-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal{{ $supplier->pk }}" data-mode="edit">
                    <i class="icon-base ri ri-pencil-fill"></i>
                  </button>
                  <form action="{{ route('supplier.destroy', $supplier->pk) }}" method="POST" class="d-inline"
                    id="deleteForm-{{ $supplier->pk }}">
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
              <div class="modal fade" id="editModal{{ $supplier->pk }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalCenterTitle">Edit Supplier</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('supplier.update', $supplier->pk) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body">
                        <div class="row g-4">
                          <div class="col-12 col-md-6 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="text" id="nm" name="nm" class="form-control"
                                placeholder="Masukkan Nama" value="{{ old('nm', $supplier->nm) }}" />
                              <label for="nm">Nama</label>
                              @error('nm')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                          <div class="col-12 col-md-6 mb-2">
                            <div class="form-floating form-floating-outline">
                              <input type="text" id="tlp" name="tlp" class="form-control"
                                placeholder="xxxx@xxx.xx" value="{{ old('tlp', $supplier->tlp) }}" />
                              <label for="tlp">No. Telpon</label>
                              @error('tlp')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 mb-2">
                            <div class="form-floating form-floating-outline">
                              <textarea class="form-control h-px-100" id="alamat" name="alamat" placeholder="Ketik di sini...">{{ old('alamat', $supplier->alamat) }}</textarea>
                              <label for="alamat">Alamat</label>
                              @error('alamat')
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Tambah Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('supplier.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row g-4">
              <div class="col-12 col-md-6 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="nm" name="nm" class="form-control"
                    placeholder="Masukkan Nama" />
                  <label for="nm">Nama</label>
                  @error('nm')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-12 col-md-6 mb-2">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="tlp" name="tlp" class="form-control"
                    placeholder="xxxx@xxx.xx" />
                  <label for="tlp">No. Telpon</label>
                  @error('tlp')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 mb-2">
                <div class="form-floating form-floating-outline">
                  <textarea class="form-control h-px-100" id="alamat" name="alamat" placeholder="Ketik di sini..."></textarea>
                  <label for="alamat">Alamat</label>
                  @error('alamat')
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
