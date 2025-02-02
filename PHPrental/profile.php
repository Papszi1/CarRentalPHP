<?php
session_start();

$email = "";

if (isset($_SESSION["user_id"])) {
    $users = json_decode(file_get_contents("users.json"), true);
    $user = $users[$_SESSION["user_id"]];
    $email = $user["email"];
} else {
    header("location:index.php");
}

$cars = json_decode(file_get_contents("cars.json"), true);
$bookings = json_decode(file_get_contents("bookings.json"), true);

$filtered_bookings = array_filter($bookings, function($item) use($email) {
    return $item['email'] === $email;
});

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKar Rental | <?= $user["name"]?> Profile</title>
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
    <h2 class="main-h2">Bookings for <span class="bold"><?= $user["name"]?></span></h2>
    <?php if (!isset($_SESSION["user_id"])) : ?>
      <a href="signup.php" class="btn btn-warning btn-signup">Signup</a>
    <?php endif; ?>
    </div>

  <div class="container my-4">
    <div class="row">
    <?php foreach ($filtered_bookings as $booking):

        $car = "";

        foreach ($cars as $c) {
            if ($c["id"] == $booking["car"]) {
                $car = $c;
            }
        }

        if ($car !== "") :

        ?>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card bg-lightdark text-white">
          <a href="car.php?carid=<?= $car["id"]?>">
            <img src="<?= $car["image"]?>" class="card-img-top">
          </a>
          <span class="date-tag"><?= date("m.d", strtotime($booking["from"])) . " - " . date("m.d", strtotime($booking["to"])) ?></span>
          <div class="card-body">
            <h5 class="card-title"><?= $car["brand"]?> <span class="bold"><?= $car["model"]?></span></h5>
            <p><?= $car["passengers"]?> Seats - <?= $car["transmission"]?></p>

          </div>
        </div>
      </div>
      <?php endif;?>
      <?php endforeach; ?>
    </div>
  </div>
</body>

</html>