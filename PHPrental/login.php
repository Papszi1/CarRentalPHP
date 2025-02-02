<?php
session_start();
$fail = false;

if (isset($_SESSION["user_id"])) {
  header("location: index.php");
}

$user = $_POST['user'] ?? '';
$pass = $_POST['password'] ?? '';

if ($_POST){
    $errors = [];
    if ($user == '') { 
      $errors['user'] = 'Enter an Email!';
    } elseif (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
      $errors['user'] = 'Invalid Email!';
    }

    if ($pass === '') {
      $errors['password'] = 'Enter a password!';
    }

    if (count($errors) === 0) {
      $users = json_decode(file_get_contents('users.json'), true);
      $matches = array_filter($users, fn($u) => $u['email'] == $user);
          if (count($matches) > 0){
              $keys = array_keys($matches);
              $firsthit = $matches[$keys[0]];
              if ($pass === $firsthit['password']){
                  $_SESSION['user_id'] = $keys[0];
                  header("location: login_success.php");
                  exit();
              } else $fail = true;
          } else $fail = true;
      }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKar Rental | Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-dark text-white">
  <nav class="navbar navbar-expand navbar-dark bg-lightdark" aria-label="Second navbar example">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">IKarRental</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02"
        aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
  <div class="container">
    <div class="main-h2-container">
      <h2 class="main-h2 text-center">Login</h2>
    </div>
    <div class="d-flex justify-content-center">
    <form action="login.php" method="POST" style="width: 60%">
    <div class="form-group">
      <label for="user">Email:</label>
      <input type="text" class="form-control bg-lightdark" id="user" placeholder="Enter email" name="user" value="<?= $user ?>">
      <p class="text-danger"><?= $errors['user'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control bg-lightdark" id="password" placeholder="Enter password" name="password" value="<?= $pass ?>">
      <p class="text-danger"><?= $errors['password'] ?? '' ?></p>
    </div>
    <?php if ($fail): ?>
        <b class="text-danger">Login failed!</b>
    <?php endif; ?>
    <br>
    <button type="submit" class="btn btn-warning">Login</button>
  </form>
    </div>
</body>

</html>