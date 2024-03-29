<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('list-makanan')}}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">GIZI <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @if(Auth::user()->role == 'superadmin')
    <li class="nav-item">
        <a class="nav-link" href="/ahli-gizi">
            <i class="fa fa-users"></i>
            <span>AhliGizi</span></a>
    </li>
    @endif
    <li class="nav-item active">
        <a class="nav-link" href="/list-makanan">
            <i class="fa fa-bacon"></i>
            <span>Bahan Makanan</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/faktor-aktivitas">
            <i class="fas fa-fw fa-hiking"></i>
            <span>Faktor Aktivitas</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/faktor-stress">
            <i class="fas fa-fw fa-angry"></i>
            <span>Faktor Stress</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/pasien">
            <i class="fas fa-fw fa-user"></i>
            <span>Pasien</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/hitung-kalori">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Hitung Kalori</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/nv-bayes">
            <i class="fas fa-fw fa-user"></i>
            <span>Saran makanan</span></a>
    </li>



</ul>
