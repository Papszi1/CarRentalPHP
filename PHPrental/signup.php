<?php

session_start();

if (isset($_SESSION["user_id"])) {
  header("location: index.php");
}

$user = $_POST['user'] ?? '';
$email = $_POST['email'] ?? '';
$password1 = $_POST['password1'] ?? '';
$password2 = $_POST['password2'] ??'';
$errors = [];

if ($_POST) {
    if ($user === '') {
        $errors["user"] = 'Please enter a username!';
    } else if (strlen($user) < 3) { 
        $errors['user'] = 'The username is too short!';
    } else if (strlen($user) > 30) { 
        $errors['user'] = 'The username is too long!';
    }

    if ($email === '') {
        $errors["email"] = 'Please enter an email!';
    } else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) { 
        $errors['email'] = 'Email is invalid!';
    }

    if ($password1 === '') {
        $errors['password1'] = 'please enter a password!';
    } else if (strlen($password1) < 5) {
        $errors['password1'] = 'The password is too short!';
    } else if (strlen($password1) > 30) { 
        $errors['password1'] = 'The password is too long!';
    }

    if ($password2 === '') {
        $errors['password2'] = 'please enter a password!';
    } else if ($password1 !== $password2) {
        $errors['password2'] = 'The two passwords do not match!';
    }

    if (count($errors) === 0) {
      $users = json_decode(file_get_contents('users.json'), true);

      $users[] = [
        "name" => $user,
        "email" => $email,
        "password" => $password1,
        "admin" => false
      ];

      file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
      header("location: index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKar Rental | Signup</title>
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
      <h2 class="main-h2 text-center">Signup</h2>
    </div>
    <div class="d-flex justify-content-center">
    <form action="signup.php" method="POST" style="width: 60%">
    <div class="form-group">
      <label for="user">Username:</label>
      <input type="text" class="form-control bg-lightdark" id="user" placeholder="Enter username" name="user" value="<?= $user ?>">
      <p class="text-danger"><?= $errors['user'] ?? '' ?></p>
    </div>
    <div class="form-group">
      <label for="user">Email:</label>
      <input type="text" class="form-control bg-lightdark" id="email" placeholder="Enter email" name="email" value="<?= $email ?>">
      <p class="text-danger"><?= $errors['email'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="password1">Password:</label>
      <input type="password" class="form-control bg-lightdark" id="password1" placeholder="Enter password" name="password1" value="<?= $password1 ?>">
      <p class="text-danger"><?= $errors['password1'] ?? '' ?></p>
    </div>
    <div class="form-group">
      <label for="password2">Password again:</label>
      <input type="password" class="form-control bg-lightdark" id="password2" placeholder="Enter password" name="password2" value="<?= $password2 ?>">
      <p class="text-danger"><?= $errors['password2'] ?? '' ?></p>
    </div>
    <?php if ($_POST && count($errors) === 0): ?>
        <b class="text-success">Signup successful!</b>
    <?php endif; ?>
    <br>
    <button type="submit" class="btn btn-warning">Signup</button>
  </form>
    </div>
</body>

</html>