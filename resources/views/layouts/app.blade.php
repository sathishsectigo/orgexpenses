<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">I-India Space</a>
            @auth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('profile.edit') }}">Profile</a>
                    </li>

                    @if(auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('users.index') }}">User Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('projects.index') }}">Projects</a>
                    </li>
                    @endif

                    <!-- Expenses Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="expensesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Expenses
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
                            <li><a class="dropdown-item" href="{{ route('expenses.index') }}">View Claims</a></li>
                            <li><a class="dropdown-item" href="{{ route('expenses.create') }}">Submit Claim</a></li>
                        </ul>
                    </li>

                    <!-- Wallet -->
                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('wallet.index') }}">Wallet</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light" href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

