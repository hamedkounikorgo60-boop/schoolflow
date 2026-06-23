<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SchoolFlow')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #2563eb;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            margin: 0;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 100;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #334155;
        }
        .sidebar-brand h6 {
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }
        .sidebar-brand small {
            color: #94a3b8;
            font-size: 0.7rem;
        }
        .sidebar-section {
            padding: 16px 12px 4px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.875rem;
            border-radius: 6px;
            margin: 1px 8px;
            transition: all 0.15s;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }
        .sidebar-link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }
        .sidebar-footer {
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid #334155;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 8px;
            background: #334155;
        }
        .sidebar-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            color: #fff;
            font-size: 0.825rem;
            font-weight: 600;
        }
        .sidebar-user-role {
            color: #94a3b8;
            font-size: 0.7rem;
        }

        /* Main */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .page-content {
            padding: 24px;
            flex: 1;
        }

        /* Cards */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            color: #1e293b;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        /* Tables */
        .table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 16px;
        }
        .table td {
            padding: 12px 16px;
            font-size: 0.875rem;
            color: #334155;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f8fafc; }

        /* Buttons */
        .btn { border-radius: 8px; font-size: 0.85rem; font-weight: 500; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-sm { padding: 5px 12px; font-size: 0.8rem; }

        /* Forms */
        .form-label { font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 4px; }
        .form-control, .form-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 8px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* Badges */
        .badge { border-radius: 6px; font-weight: 500; }

        /* Alerts */
        .alert { border-radius: 10px; font-size: 0.875rem; }

        /* Page header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .page-header h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Quick action buttons in sidebar */
        .btn-logout {
            width: 100%;
            background: transparent;
            border: 1px solid #475569;
            color: #94a3b8;
            border-radius: 6px;
            padding: 6px;
            font-size: 0.8rem;
            margin-top: 8px;
            transition: all 0.15s;
        }
        .btn-logout:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: #fff;
        }
    </style>
</head>
<body>

@auth
<div class="sidebar">
    <div class="sidebar-brand">
        <h6>🏫 SchoolFlow</h6>
        <small>Cycle Primaire</small>
    </div>

    @if(Auth::user()->isGestionnaire())
    <div class="sidebar-section">Principal</div>
    <a href="{{ route('gestionnaire.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Tableau de bord
    </a>

    <div class="sidebar-section">Scolarité</div>
    <a href="{{ route('gestionnaire.eleves.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.eleves.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Élèves
    </a>
    <a href="{{ route('gestionnaire.classes.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.classes.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> Classes
    </a>
    <a href="{{ route('gestionnaire.enseignants.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.enseignants.*') ? 'active' : '' }}">
        <i class="bi bi-person-workspace"></i> Enseignants
    </a>
    <a href="{{ route('gestionnaire.parents.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.parents.*') ? 'active' : '' }}">
        <i class="bi bi-person-hearts"></i> Parents
    </a>
    <a href="{{ route('gestionnaire.matieres.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.matieres.*') ? 'active' : '' }}">
        <i class="bi bi-book"></i> Matières
    </a>

    <div class="sidebar-section">Finances</div>
    <a href="{{ route('gestionnaire.paiements.index') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.paiements.*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> Paiements
    </a>
    <a href="{{ route('gestionnaire.paiements.impaye') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.paiements.impaye') ? 'active' : '' }}">
        <i class="bi bi-exclamation-triangle"></i> Impayés
    </a>

    <div class="sidebar-section">Pédagogie</div>
    <a href="{{ route('gestionnaire.notes.classement') }}"
       class="sidebar-link {{ request()->routeIs('gestionnaire.notes.classement') ? 'active' : '' }}">
        <i class="bi bi-trophy"></i> Classement
    </a>
    @endif

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-role">
                    {{ Auth::user()->isGestionnaire() ? 'Gestionnaire' : (Auth::user()->isParent() ? 'Parent' : 'Enseignant') }}
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Déconnexion</button>
        </form>
    </div>
</div>
@endauth

<div class="main-content">
    <div class="topbar">
        <h1 class="topbar-title">@yield('title', 'Tableau de bord')</h1>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:0.8rem;padding:6px 12px">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-x-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
