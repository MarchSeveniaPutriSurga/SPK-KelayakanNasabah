<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>SPK Kelayakan Nasabah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="asset('SPK-KelayakanNasabah/public/images/logo-bumkalma.png')">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>

<!-- Overlay untuk mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" onclick="toggleSidebar()">
  <i class="fa-solid fa-bars"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
      <img src="{{ asset('images/logo-bumkalma.png') }}" alt="Logo" style="width: 75px; height: 75px; object-fit: contain; display: block; margin: 0 auto 0.75rem;">
      <h6>SPK Penentuan Kelayakan Nasabah</h6>
  </div>

  <ul class="sidebar-menu">

    {{-- UTAMA --}}
    <li class="sidebar-group-label">MAIN MENU</li>

    <li>
      <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-line"></i>
        <span>Dashboard</span>
      </a>
    </li>

    {{-- DATA MASTER --}}
    <li class="sidebar-group-label">Data Master</li>

    <li>
      <a href="{{ route('customers.index') }}" class="{{ Request::routeIs('customers.*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>
        <span>Nasabah</span>
      </a>
    </li>
    <li>
      <a href="{{ route('periods.index') }}" class="{{ Request::routeIs('periods.*') ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-days"></i>
        <span>Periode</span>
      </a>
    </li>
    <li>
      <a href="{{ route('criteria.index') }}" class="{{ Request::routeIs('criteria.*') ? 'active' : '' }}">
        <i class="fa-solid fa-list-check"></i>
        <span>Kriteria</span>
      </a>
    </li>
    <li>
      <a href="{{ route('parameters.index') }}" class="{{ Request::routeIs('parameters.*') ? 'active' : '' }}">
        <i class="fa-solid fa-sliders"></i>
        <span>Parameter</span>
      </a>
    </li>

    {{-- SPK --}}
    <li class="sidebar-group-label">SPK SYSTEM</li>

    <li>
      <a href="{{ route('penilaian.create') }}" class="{{ Request::routeIs('penilaian.create') ? 'active' : '' }}">
        <i class="fa-solid fa-file-pen"></i>
        <span>Penilaian</span>
      </a>
    </li>
    <li>
      <a href="{{ route('smart.index') }}" class="{{ Request::routeIs('smart.index') ? 'active' : '' }}">
        <i class="fa-solid fa-ranking-star"></i>
        <span>Hasil Ranking</span>
      </a>
    </li>
    <li>
      <a href="{{ route('penilaian.riwayat') }}" class="{{ Request::routeIs('penilaian.riwayat') ? 'active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span>Riwayat Penilaian</span>
      </a>
    </li>

    {{-- AKUN --}}
    <li class="sidebar-group-label">Akun</li>

    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </button>
      </form>
    </li>

  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="content-wrapper">
    @if(session('success'))
      <div class="alert alert-success card-soft mb-4">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger card-soft mb-4">
        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
      </div>
    @endif

    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
  }

  document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-toggle');
    if (window.innerWidth <= 768) {
      if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
      }
    }
  });
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>