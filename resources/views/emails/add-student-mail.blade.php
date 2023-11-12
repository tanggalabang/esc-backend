<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background-color: #edf2f7;
    color: #718096;
">

  <div class="container-fluid mt-5 mb-5">
    <div class="mt-4 mb-10">
      <div class="d-flex justify-content-center align-items-center mb-4 mt-10"> <!-- Updated this line -->
        <img src="http://0.0.0.0/assets/images/logo.png" class="logo" height="60" alt="esc logo">
        <h1 class="font-weight-bold" style="font-size:40px; margin-top: 8px;
    color: #3d4852;
        ">ESC</h1>

      </div>
      <div class="card mx-auto" style="width: 500px; border:none">
        <div class="card-header bg-primary text-white">
          <h5 class="font-weight-bold">Account Created</h5>
        </div>
        <div class="card-body">
          <h6 class="card-title">Welcome, {{ $name }}!</h6>
          <p class="card-text">Your account has been created by an admin. Here are the details of your account:</p>
          <ul class="list-group">
            <li class="list-group-item">Name: {{ $name }}</li>
            <li class="list-group-item">Email: {{ $email }}</li>
            <li class="list-group-item text-danger">Password: {{$password}}</li>
          </ul>
          <div class="mt-3 alert alert-warning">
            <p>We recommend you to change your password immediately after logging into our application.</p>
          </div>
          <p class="text-center">Thank you for your trust!</p>
          <a href="http://localhost:3000/auth/login" class="btn btn-primary btn-block">Click here to log in</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>