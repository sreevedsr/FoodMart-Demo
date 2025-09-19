<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodMart Admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="mb-4">FoodMart</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>

            <!-- Products with Submenu -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#productMenu" role="button"
                    aria-expanded="{{ request()->is('admin/products*') ? 'true' : 'false' }}">
                    Products ▾
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->is('admin/products*') ? 'show' : '' }}"
                    id="productMenu">
                    <li>
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}"
                            href="{{ route('products.index') }}">All Products</a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}"
                            href="{{ route('products.create') }}">Add Product</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#categoryMenu" role="button"
                    aria-expanded="{{ request()->is('admin/categories*') ? 'true' : 'false' }}">
                    Categories ▾
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->is('admin/categories*') ? 'show' : '' }}"
                    id="categoryMenu">
                    <li>
                        <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">All Categories</a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('categories.create') ? 'active' : '' }}"
                            href="{{ route('categories.create') }}">Add Category</a>
                    </li>
                </ul>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logout') }}">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>