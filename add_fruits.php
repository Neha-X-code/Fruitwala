<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fruitName = $_POST["fruit_name"];
    $family = $_POST["family"];
    $order = $_POST["order"];
    $genus = $_POST["genus"];
    $calories = $_POST["calories"];
    $fat = $_POST["fat"];
    $sugar = $_POST["sugar"];
    $carbohydrates = $_POST["carbohydrates"];
    $protein = $_POST["protein"];

    // Check if the fruit name already exists in the API
    $api_url = "https://www.fruityvice.com/api/fruit/all";
    $response = file_get_contents($api_url);
    $apiData = json_decode($response, true);

    $fruitExistsInApi = in_array(strtolower($fruitName), array_map('strtolower', array_column($apiData, 'name')));

    // Check if the fruit name already exists in the database
    $result = mysqli_query($conn, "SELECT * FROM fruits WHERE LOWER(name) = LOWER('$fruitName')");
    $fruitExistsInDb = mysqli_num_rows($result) > 0;

    if ($fruitExistsInApi || $fruitExistsInDb) {
        $showError = "Error: Fruit with the name '$fruitName' already exists.";
    } else {
        // Perform necessary validation (you can add more as needed)

        // Insert new fruit details into the database
        $sql = "INSERT INTO `fruits` (`name`, `family`, `order`, `genus`, `calories`, `fat`, `sugar`, `carbohydrates`, `protein`)
                VALUES ('$fruitName', '$family', '$order', '$genus', '$calories', '$fat', '$sugar', '$carbohydrates', '$protein')";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $showAlert = true;
        } else {
            $showError = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Fruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require 'partials/_nav.php' ?>

    <div class="container my-4">
        <h1 class="text-center">Add New Fruit</h1>

        <?php
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Fruit details added successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            // Add a link/button to redirect back to fruits.php
            echo '<a href="/Fruitwala/your_fruits.php?added=true" class="btn btn-primary">Go to Fruits</a>';
        }
        

        if ($showError) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ' . $showError . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
        ?>

        <!-- Form to add new fruit -->
        <form action="/Fruitwala/add_fruits.php" method="post">
            <!-- Add form fields as needed -->
            <div class="mb-3">
                <label for="fruit_name" class="form-label">Fruit Name</label>
                <input type="text" class="form-control" id="fruit_name" name="fruit_name" required>
            </div>
            <div class="mb-3">
        <label for="family" class="form-label">Family</label>
        <input type="text" class="form-control" id="family" name="family" required>
    </div>

    <div class="mb-3">
        <label for="order" class="form-label">Order</label>
        <input type="text" class="form-control" id="order" name="order" required>
    </div>

    <div class="mb-3">
        <label for="genus" class="form-label">Genus</label>
        <input type="text" class="form-control" id="genus" name="genus" required>
    </div>

    <div class="mb-3">
        <label for="calories" class="form-label">Calories</label>
        <input type="number" class="form-control" id="calories" name="calories" required>
    </div>

    <div class="mb-3">
        <label for="fat" class="form-label">Fat</label>
        <input type="number" class="form-control" id="fat" name="fat" required>
    </div>

    <div class="mb-3">
        <label for="sugar" class="form-label">Sugar</label>
        <input type="number" class="form-control" id="sugar" name="sugar" required>
    </div>

    <div class="mb-3">
        <label for="carbohydrates" class="form-label">Carbohydrates</label>
        <input type="number" class="form-control" id="carbohydrates" name="carbohydrates" required>
    </div>

    <div class="mb-3">
        <label for="protein" class="form-label">Protein</label>
        <input type="number" class="form-control" id="protein" name="protein" required>
    </div>
            <!-- Add more form fields for family, order, genus, and nutritional content -->

            <button type="submit" class="btn btn-primary">Add Fruit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
