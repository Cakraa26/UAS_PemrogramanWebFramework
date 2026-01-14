@extends('layouts/contentNavbarLayout')
@section('title', 'Dashboard')

@section('page-script')
  @vite(['resources/assets/js/dashboards-analytics.js'])
@endsection

@section('content')
  <div class="row gy-6">
    <div class="row g-4">
      <!-- Total Penjualan -->
      <div class="col-md-3 col-6">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3">
              <div class="avatar-initial bg-primary rounded">
                <i class="icon-base ri ri-shopping-cart-2-line fs-4 text-white"></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($totalPenjualan) }}</h5>
              <small class="text-muted">Total Penjualan</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Pembelian -->
      <div class="col-md-3 col-6">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3">
              <div class="avatar-initial bg-success rounded">
                <i class="icon-base ri ri-wallet-3-line fs-4 text-white"></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ number_format($totalPembelian) }}</h5>
              <small class="text-muted">Total Pembelian</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Produk -->
      <div class="col-md-3 col-6">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3">
              <div class="avatar-initial bg-warning rounded">
                <i class="icon-base ri ri-box-3-line fs-4 text-white"></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ $totalProduk }}</h5>
              <small class="text-muted">Produk</small>
            </div>
          </div>
        </div>
      </div>

      <!-- User -->
      <div class="col-md-3 col-6">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3">
              <div class="avatar-initial bg-info rounded">
                <i class="icon-base ri ri-user-3-line fs-4 text-white"></i>
              </div>
            </div>
            <div>
              <h5 class="mb-0">{{ $totalUser }}</h5>
              <small class="text-muted">User</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5>Penjualan Bulanan</h5>
      </div>
      <div class="card-body">
        <canvas id="salesChart" height="300"></canvas>
      </div>
    </div>

  </div>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const ctx = document.getElementById('salesChart').getContext('2d');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: @json($bulan),
          datasets: [{
            label: 'Penjualan',
            data: @json($total),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
  </script>

@endsection
