<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <div class="jumbotron">
      <h2 class="text-center font-weight-bold">Your Account Has Been Created by an Admin</h2>
    </div>

    <div class="alert alert-success">
      <p>Welcome, {{ $name }}!</p>
      <p> Your account has been created by an admin. Here are the details of your account:</p>
    </div>

    <ul class="list-group">
      <li class="list-group-item">ID Number (NIS): {{ $nis }}</li>
      <li class="list-group-item">Name: {{ $name }}</li>
      <li class="list-group-item">Email: {{ $email }}</li>
      <li class="list-group-item text-danger">Password: {{$password}}</li>
    </ul>

    <div class="mt-3 alert alert-warning">
      <p>We recommend you to change your password immediately after logging into our application.</p>
    </div>

    <p class="text-center">Thank you for your trust!</p>

    <a href="{{ url('/login') }}" class="btn btn-primary btn-block">Click here to log in</a>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>