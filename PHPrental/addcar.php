<?php

session_start();

if (!isset($_SESSION["user_id"])) {
  header("location: login.php");
} else {
  $users = json_decode(file_get_contents("users.json"), true);
  $user =  $users[$_SESSION["user_id"]];
  if (!$user["admin"]) {
    header("location: index.php");
  }
}
$szia = 100;
$brand = $_POST['brand'] ?? '';
$model = $_POST['model'] ?? '';
$year = $_POST['year'] ?? '';
$transmission = $_POST['transmission'] ?? '';
$fuel = $_POST['fuel'] ?? '';
$passengers = $_POST['passengers'] ?? '';
$price = $_POST['price'] ?? '';
$image = $_POST['image'] ?? '';

$errors = [];
  if ($_POST){
      if ($brand === '' ){
          $errors['brand'] = 'Please enter a brand!';
      }

      if ($model === '' ){
        $errors['model'] = 'Please enter a model!';
      }

      if ($year === ''){ 
        $errors['year'] = 'Please enter a year!';
      } else if (filter_var($year, FILTER_VALIDATE_INT) === false) {
        $errors['year'] = 'Please enter a whole number!';
      } else if (intval($year) < 1900 || intval($year) > 2025) {
        $errors['year'] = 'Please enter a valid year!';
      }

      if ($transmission === ''){ 
        $errors['transmission'] = 'Please enter a transmission type!';
      } else if ($transmission !== "Automatic" && $transmission !== "Manual") {
        $errors['transmission'] = 'Please enter a valid transmission type!';
      }

      if ($fuel === ''){ 
        $errors['fuel'] = 'Please enter a fuel type!';
      } else if ($fuel !== "Diesel" && $fuel !== "Petrol" && $fuel !== "Electric") {
        $errors['fuel'] = 'Please enter a valid fuel type!';
      }

      if ($passengers === ''){ 
        $errors['passengers'] = 'Please enter the number of passengers!';
      } else if (filter_var($passengers, FILTER_VALIDATE_INT) === false) {
        $errors['passengers'] = 'Please enter a whole number!';
      } else if (intval($passengers) < 1 || intval($passengers) > 99) {
        $errors['passengers'] = 'Please enter a valid number!';
      }

      if ($price === ''){ 
        $errors['price'] = 'Please enter the price!';
      } else if (filter_var($price, FILTER_VALIDATE_INT) === false) {
        $errors['price'] = 'Please enter a whole number!';
      } else if (intval($price) < 1 || intval($price) > 10000000) {
        $errors['price'] = 'Please enter a valid number!';
      }

      if ($image === '' ){
        $image = "https://motozitelive.blob.core.windows.net/motozite-live/newcars_images/1670408218No-Image.jpg";
      }

      if (count($errors) === 0) {
        $cars = json_decode(file_get_contents("cars.json"), true);
        $cars[] = [
          "id" => $cars[count($cars) - 1]["id"] + 1,
          "brand" => $brand,
          "model" => $model,
          "year" => $year,
          "transmission" => $transmission,
          "fuel_type" => $fuel,
          "passengers" => $passengers,
          "daily_price_huf" => $price,
          "image" => $image
        ];
        file_put_contents("cars.json", json_encode($cars, JSON_PRETTY_PRINT));
      }

    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IKar Rental | Add new Car</title>
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
      <h2 class="main-h2 text-center">Add a new car</h2>
    </div>
    <div class="d-flex justify-content-center">
    <form action="addcar.php" method="POST" style="width: 60%">
    <div class="form-group">
      <label for="brand">Brand:</label>
      <input type="text" class="form-control bg-lightdark" id="brand" placeholder="Enter brand" name="brand" value="<?= $brand ?>">
      <p class="text-danger"><?= $errors['brand'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="model">Model:</label>
      <input type="text" class="form-control bg-lightdark" id="model" placeholder="Enter model" name="model" value="<?= $model ?>">
      <p class="text-danger"><?= $errors['model'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="year">Year:</label>
      <input type="number" class="form-control bg-lightdark" id="year" placeholder="Enter year" name="year" value="<?= $year ?>">
      <p class="text-danger"><?= $errors['year'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="transmission">Transmission:</label>
        <select id="transmission" name="transmission" class="form-control bg-lightdark">
          <option value="Manual">Manual</option>
          <option value="Automatic">Automatic</option>
        </select>
        <p class="text-danger"><?= $errors['transmission'] ?? '' ?></p>   
    </div>
    <br>
    <div class="form-group">
      <label for="fuel">Fuel type:</label>
        <select id="fuel" name="fuel" class="form-control bg-lightdark">
          <option value="Diesel">Diesel</option>
          <option value="Petrol">Petrol</option>
          <option value="Electric">Electric</option>
        </select>
        <p class="text-danger"><?= $errors['fuel'] ?? '' ?></p>   
    </div>
    <br>
    <div class="form-group">
      <label for="passengers">Passengers:</label>
      <input type="number" class="form-control bg-lightdark" id="passengers" placeholder="Enter passengers" name="passengers" value="<?= $passengers ?>">
      <p class="text-danger"><?= $errors['passengers'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="price">Price/day:</label>
      <input type="number" class="form-control bg-lightdark" id="price" placeholder="Enter price" name="price" value="<?= $price ?>">
      <p class="text-danger"><?= $errors['price'] ?? '' ?></p>
    </div>
    <br>
    <div class="form-group">
      <label for="image">Image link:</label>
      <input type="text" class="form-control bg-lightdark" id="image" placeholder="Enter image link" name="image">
    </div>
    <?php if ($_POST && count($errors) === 0): ?>
        <b class="text-success">Car added successfully!</b>
    <?php endif; ?>
    <br>
    <button type="submit" class="btn btn-warning">Add car</button>
  </form>
    </div>
</body>

</html>