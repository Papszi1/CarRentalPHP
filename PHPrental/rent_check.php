<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
}

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

$bookings = json_decode(file_get_contents("bookings.json"), true);

if ($_POST) {
    $error = "";

    $date1 = $_POST["date1"] ?? "";
    $date2 = $_POST["date2"] ?? "";

    if ($date1 !== "" && $date2 !== "") {
        if (strtotime($date1) <= strtotime($date2) && strtotime($date1) >= strtotime(date("Y-m-d"))) {
            foreach ($bookings as $booking) {
                if ($booking["car"] == $car_id) {
                    // echo "Foglalt kezdés: " . strtotime($booking["from"]). "<br>";
                    // echo "Foglalás kezdés: ". strtotime($date1). "<br>";
                    // echo "Foglalt vége: ". strtotime($booking["to"]). "<br>";
                    // echo "Foglalás vége: ". strtotime($date2). "<br>";
                    if (!((strtotime($date1) <= strtotime($booking["from"]) && strtotime($date2) <= strtotime($booking["from"])) || (strtotime($date1) >= strtotime($booking["to"]) && strtotime($date2) >= strtotime($booking["to"])))) {
                        $error = "Car is already booked on selected dates!";
                        break;
                    }
                }
            }
        } else {
            $error = "Invalid dates!";
        }
    } else {
        $error = "No date(s) given!";
    }
    if ($error === "") {
        $users = json_decode(file_get_contents("users.json"), true);
        $user = $users[$_SESSION["user_id"]];

        $bookings[] = [
            "from" => $date1,
            "to" => $date2,
            "email" => $user["email"],
            "car" => $car_id
        ];

        file_put_contents("bookings.json", json_encode($bookings, JSON_PRETTY_PRINT));

    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKar Rental | Rent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
    <?php if ($error === "") : ?>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <i class="bi bi-patch-check-fill" style="font-size: 10rem; color: #198754"></i>
            <h1 class="fw-bold mt-3">Successful Booking!</h1>
            <p class="text-muted">The <?= $car["brand"] ?> <span class="bold"><?= $car["model"] ?></span> has been
                booked
                for:
                <br>
                <span class="italic"><?= $date1 ?> - <?= $date2 ?></span>
                <br>
                Keep track of your bookings on your <a href="profile.php">profile</a>
                </p>
                <a href="index.php" class="btn btn-warning">Return to homepage</a>
        </div>
    </div>
    <?php else : ?>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <i class="bi bi-patch-exclamation-fill" style="font-size: 10rem; color: #DC3545"></i>
            <h1 class="fw-bold mt-3">Unsuccessful Booking!</h1>
            <p class="text-muted">An error has occured while booking <?= $car["brand"] ?> <span class="bold"><?= $car["model"] ?></span>
                for:
                <br>
                <span class="italic"><?= $date1 ?> - <?= $date2 ?></span>
                <br>
                Error: <span class="bold"><?= $error ?></span>
                </p>
                <a href="index.php" class="btn btn-warning">Return to homepage</a>
        </div>
    </div>
    <?php endif; ?>
</body>

</html>