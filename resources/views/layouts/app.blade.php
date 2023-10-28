<!DOCTYPE html>
<html>

<head>
  <!-- Tambahkan Bootstrap CSS dan JavaScript di sini -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  @yield('content')

  <!-- Tambahkan Bootstrap JavaScript di sini -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  @stack('scripts')
</body>

</html>