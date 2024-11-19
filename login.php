<?php
session_start();

// Username dan password yang valid
$valid_username = 'user';
$valid_password = 'admin';

// Mengecek jika sudah login, arahkan ke halaman utama
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Mengecek jika form login disubmit
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifikasi username dan password
    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION['username'] = $username;  // Menyimpan session username
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            background-image: url('gedunginspek.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 login-container">
                <h3 class="text-center mb-4">Login to Your Account</h3>
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger"><?= $error_message; ?></div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-danger w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
