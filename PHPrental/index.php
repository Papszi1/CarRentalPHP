<?php

session_start();

$cars = json_decode(file_get_contents("cars.json"), true);
$bookings = json_decode(file_get_contents("bookings.json"), true);

$passengers = $_GET['passengers'] ?? '';
$date1 = $_GET['rent-from'] ?? '';
$date2 = $_GET['rent-to'] ?? '';
$transmission = $_GET['transmission'] ?? '';
$min_price = $_GET['min-price'] ?? '';
$max_price = $_GET['max-price'] ?? '';

$filtered_cars = [];

if ($_GET) {
  foreach ($cars as $car) {
    if ($passengers === '' || $passengers <= $car["passengers"]) {
      if ($transmission === '' || $transmission === $car["transmission"]) {
        if ($min_price === '' || $min_price <= $car["daily_price_huf"]) {
          if ($max_price === '' || $max_price >= $car["daily_price_huf"]) {
            if ($date1 === '' || $date2 === '' || strtotime($date1) > strtotime($date2)) {
              array_push($filtered_cars, $car);
            } else {
              $good = true;
              foreach ($bookings as $booking) {
                if ($booking["car"] === $car["id"]) {
                  if (!((strtotime($date1) <= strtotime($booking["from"]) && strtotime($date2) <= strtotime($booking["from"])) || (strtotime($date1) >= strtotime($booking["to"]) && strtotime($date2) >= strtotime($booking["to"])))) {
                    $good = false;
                    echo $car["id"];
                    break;
                  }
                }
              }
              if ($good) {
                array_push($filtered_cars, $car);
              }
            }
          }
        }
      }
    }
  }
  $cars = $filtered_cars;
}
if (isset($_SESSION["user_id"])) {
  $users = json_decode(file_get_contents("users.json"), true);
  $user = $users[$_SESSION["user_id"]];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKar Rental | Home</title>
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
      <?php if (!isset($_SESSION["user_id"])): ?>
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
      <?php else: ?>
        <div class="collapse navbar-collapse" id="navbarsExample02">
          <ul class="navbar-nav me-auto">
            <?php if ($user["admin"] == true): ?>
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
  <div class="main-h2-container">
    <h2 class="main-h2">Rent cars fast and cheap!</h2>
    <?php if (!isset($_SESSION["user_id"])): ?>
      <a href="signup.php" class="btn btn-warning btn-signup">Signup</a>
    <?php endif; ?>
  </div>
  <div class="filter-container bg-lightdark">
    <form action="index.php" method="GET">
      <div class="row">
        <div class="col-md-6">
          <label for="passengers">Seats:</label>
          <input type="number" name="passengers" id="passengers" value="<?= $passengers ?>" class="form-control">
        </div>
        <div class="col-md-6">
        <label for="transmission">Transmission:</label>
          <select id="transmission" name="transmission" class="form-control">
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
      <label for="rent-from">From:</label>
      <input type="date" name="rent-from" id="rent-from" class="form-control">
      </div>
      <div class="form-group col-md-6">
      <label for="rent-to">To:</label>
      <input type="date" name="rent-to" id="rent-to" class="form-control">
      </div>
      </div>

      <div class="row">
      <div class="form-group col-md-6">
      <label for="min-price"> Min Price: </label>
      <input type="number" name="min-price" id="min-price" class="form-control md-6">
      
      </div>
      <div class="form-group col-md-6">
      <label for="max-price"> Max Price: </label>
      <input type="number" name="max-price" id="max-price" class="form-control md-6">
      </div>
      </div>
      <br>
      <input type="submit" class="btn btn-warning" value="Filter">
    </form>
  </div>
  <div class="container my-4">
    <div class="row">
      <?php foreach ($cars as $car): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
          <div class="card bg-lightdark text-white">
            <a href="car.php?carid=<?= $car["id"] ?>">
              <img src="<?= $car["image"] ?>" class="card-img-top">
            </a>
            <span class="price-tag"><?= $car["daily_price_huf"] ?> Ft</span>
            <div class="card-body">
              <h5 class="card-title"><?= $car["brand"] ?> <span class="bold"><?= $car["model"] ?></span></h5>
              <p><?= $car["passengers"] ?> Seats - <?= $car["transmission"] ?></p>
              <?php if (isset($_SESSION["user_id"])): ?>
                <a href="rent.php?carid=<?= $car["id"] ?>" class="btn btn-warning">Rent</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>

</html>