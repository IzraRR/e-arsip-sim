<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Arsip') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu .menu-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu .menu-item:hover,
        .sidebar-menu .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu .menu-item i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* Content Area */
        .content-area {
            padding: 2rem;
        }
        
        /* Cards */
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: calc(var(--sidebar-width) * -1);
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-folder2-open" style="font-size: 2rem;"></i>
            <h4>E-ARSIP</h4>
            <small>Tata Usaha</small>
        </div>
        
        <div class="sidebar-menu">
            <x-sidebar />
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-outline-primary d-md-none" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 d-inline-block ms-2">@yield('page-title')</h5>
            </div>

            <div class="dropdown me-3">
                <a href="#" class="position-relative text-decoration-none" 
                id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-4 text-dark"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                        id="notif-badge" style="display: none;">
                        0
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow" style="width: 350px; max-height: 400px; overflow-y: auto;">
                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Notifikasi</h6>
                        <a href="{{ route('notifikasi.index') }}" class="small">Lihat Semua</a>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div id="notif-preview-list">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
                   data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                         alt="Avatar" class="rounded-circle me-2" width="35" height="35">
                    <div class="text-start">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small class="d-block text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i> Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-white border-top mt-5 py-3">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            &copy; {{ date('Y') }} E-Arsip System. All rights reserved.
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            <strong>Developed by:</strong> 
                            Izra Rafif Rabbani, Adniel Rama Ezaputra, Muhammad Rizky
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')

    <!-- Notifikasi Real-time Script -->
    <script>
    // Load notifikasi preview
    function loadNotifikasiPreview() {
        fetch('{{ route("notifikasi.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Load preview list saat dropdown dibuka
    document.getElementById('notifDropdown')?.addEventListener('click', function() {
        fetch('{{ route("notifikasi.latest") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('notif-preview-list').innerHTML = data.html;
            })
            .catch(error => console.error('Error:', error));
    });

    // Update badge setiap 30 detik
    setInterval(loadNotifikasiPreview, 3000);

    // Load pertama kali
    loadNotifikasiPreview();
</script>
</body>
</html>