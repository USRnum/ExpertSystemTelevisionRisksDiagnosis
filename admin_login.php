<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<title>Admin Login</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
  body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    font-family: 'Arial', sans-serif;
    color: #fff;
  }
  .login-container {
    width: 100%;
    max-width: 400px;
    padding: 30px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    color: #333;
  }
  .login-container h1 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #6a11cb;
  }
  .form-group label {
    font-weight: bold;
  }
  .form-control {
    border-radius: 8px;
  }
  .btn-primary {
    background: #6a11cb;
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background: #2575fc;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
  .input-group .btn {
    border-radius: 0 8px 8px 0;
  }
  .input-group .form-control {
    border-radius: 8px 0 0 8px;
  }
</style>
</head>
<body>
  <div class="login-container">
    <h1>Sistem Pakar Admin Login</h1>
    <form method="POST" action="process_login.php">
      <!-- Username input -->
      <div class="form-group">
        <label for="username">Username</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
          </div>
          <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
        </div>
      </div>

      <!-- Password input -->
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
          </div>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
      </div>
      <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
          const passwordInput = document.getElementById('password');
          const icon = this.querySelector('i');
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        });
      </script>

      <!-- Submit button -->
      <button type="submit" class="btn btn-primary btn-block">Sign in</button>
    </form>
  </div>
</body>
</html>
