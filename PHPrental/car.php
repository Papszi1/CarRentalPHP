<?php

session_start();

$cars = json_decode(file_get_contents("cars.json"), true);
$car_id = $_GET["carid"] ?? "";


$car = "";

foreach ($cars as $car_loop) {
  if ($car_loop["id"] == $car_id) {
    $car = $car_loop;
    break;
  }
}

if ($car === "") {
  header("location: index.php");
}

if (isset($_SESSION["user_id"])) {
  $users = json_decode(file_get_contents("users.json"), true);
  $user =  $users[$_SESSION["user_id"]];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKar Rental | <?= $car["brand"] . " " . $car["model"] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .image-container img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
    }

    .description-box {
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100%;
    }
  </style>
</head>

<body class="bg-dark text-white">
<nav class="navbar navbar-expand navbar-dark bg-lightdark" aria-label="Second navbar example">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">IKarRental</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample02"
        aria-controls="navbarsExample02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <?php if (!isset($_SESSION["user_id"])) : ?>
      <div class="collapse navbar-collapse" id="navbarsExample02">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="signup.php">Signup</a>
          </li>
        </ul>
      </div>
      <?php else : ?>
      <div class="collapse navbar-collapse" id="navbarsExample02">
        <ul class="navbar-nav me-auto">
        <?php if ($user["admin"] == true) : ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="addcar.php">Add Car</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="bookings.php">View Bookings</a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">My Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </nav>
  <div class="container">
    <div class="main-h2-container">
      <h2 class="main-h2 text-center"><?= $car["brand"] ?> <span class="bold"><?= $car["model"] ?></span></h2>
    </div>
    <div class="d-flex justify-content-center">
      <div class="card bg-lightdark text-white">
        <img src="<?= $car["image"] ?>" class="card-img-top">
        <div class="card-body">
          <table class="table table-lightdark">
            <tbody>
              <tr>
                <th scope="row">Fuel</th>
                <td><?= $car["fuel_type"] ?></td>
              </tr>
              <tr>
                <th scope="row">Transmission</th>
                <td><?= $car["transmission"] ?></td>
              </tr>
              <tr>
                <th scope="row">Seats</th>
                <td><?= $car["passengers"] ?></td>
              </tr>
              <tr>
                <th scope="row">Year</th>
                <td><?= $car["year"] ?></td>
              </tr>
            </tbody>
          </table>
          <?php if (isset($_SESSION["user_id"])) : ?>
            <a href="rent.php?carid=<?= $car["id"]?>" class="btn btn-warning">Rent</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
</body>

</html>