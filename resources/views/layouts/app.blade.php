<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'FoodMart')</title>

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="format-detection" content="telephone=no">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">


  {{-- SVG Sprite for Icons --}}
  <svg style="display: none;">
    <symbol id="arrow-right" viewBox="0 0 24 24">
      <path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" fill="none" stroke-width="2" />
    </symbol>
    <symbol id="heart" viewBox="0 0 24 24">
      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
           2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
           C13.09 3.81 14.76 3 16.5 3 
           19.58 3 22 5.42 22 8.5
           c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="currentColor" stroke="currentColor" stroke-width="2"
        transform="scale(0.9) translate(1.5,1.5)" />
    </symbol>



    <!-- Shopping Cart -->
    <symbol id="cart" viewBox="0 0 24 24">
      <path d="M6 6h15l-1.5 9h-13zM6 6l-2-2H1M9 21a1 1 0 110-2 1 1 0 010 2zm9 0a1 1 0 110-2 1 1 0 010 2z"
        stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    </symbol>

    <symbol id="search" viewBox="0 0 24 24">
      <circle cx="11" cy="11" r="7" stroke="currentColor" fill="none" stroke-width="2" />
      <line x1="16.65" y1="16.65" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
    </symbol>

    <!-- User -->
    <symbol id="user-icon" viewBox="0 0 24 24">
      <circle cx="12" cy="7" r="4" stroke="currentColor" fill="none" stroke-width="2" />
      <path d="M4 21c0-4 4-7 8-7s8 3 8 7" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" />
    </symbol>
    
    <symbol id="home-icon" viewBox="0 0 24 24">
      <title>Home</title>
      <path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
        d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z" />
    </symbol>

    <symbol id="star-solid" viewBox="0 0 24 24">
      <path d="M12 2l3.09 6.26L22 9.27
           17 14.14l1.18 6.88L12 17.77
           5.82 21l1.18-6.86L2 9.27
           8.91 8.26 12 2z" fill="currentColor" transform="scale(0.8) translate(3,3)" />
    </symbol>

    <symbol id="plus" viewBox="0 0 24 24">
      <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
    </symbol>

    <symbol id="minus" viewBox="0 0 24 24">
      <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
    </symbol>

  </svg>

  {{-- CSS --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  @yield('scripts')
</head>

<body>

  {{-- Header --}}
  @include('partials.header')

  {{-- Page Content --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  {{-- JS --}}
  <script src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>
  <script src="{{ asset('assets/js/modernizr.js') }}"></script>
  <script src="{{ asset('assets/js/plugins.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const addToCartUrl = "{{ route('cart.add') }}";
  </script>
  <script src="{{ asset('assets/js/script.js') }}"></script>

</body>

</html>