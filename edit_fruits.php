<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

$showAlert = false;
$showError = false;
$fruitData = []; // Initialize an empty array to store fruit data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $oldFruitName = $_POST["old_fruit_name"];
    $fruitName = $_POST["fruit_name"];
    $family = $_POST["family"];
    $order = $_POST["order"];
    $genus = $_POST["genus"];
    $calories = $_POST["calories"];
    $fat = $_POST["fat"];
    $sugar = $_POST["sugar"];
    $carbohydrates = $_POST["carbohydrates"];
    $protein = $_POST["protein"];

    // Check if the new fruit name already exists in the database (excluding the current entry)
    $result = mysqli_query($conn, "SELECT * FROM fruits WHERE LOWER(name) = LOWER('$fruitName') AND name != '$oldFruitName'");
    $fruitExistsInDb = mysqli_num_rows($result) > 0;

    if ($fruitExistsInDb) {
        $showError = "Error: Fruit with the name '$fruitName' already exists in the database.";
    } else {
        // Update existing fruit details in the API using cURL and PUT method
        $api_url_update = "https://www.fruityvice.com/api/fruit/$oldFruitName";
        $data = array(
            'genus' => $genus,
            'name' => $fruitName,
            'family' => $family,
            'order' => $order,
            'nutritions' => array(
                'carbohydrates' => $carbohydrates,
                'protein' => $protein,
                'fat' => $fat,
                'calories' => $calories,
                'sugar' => $sugar
            )
        );

        $options_update = array(
            CURLOPT_URL => $api_url_update,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        );

        $curl_update = curl_init();
        curl_setopt_array($curl_update, $options_update);
        $apiResult_update = curl_exec($curl_update);
        $curl_error_update = curl_error($curl_update);
        curl_close($curl_update);

        if ($curl_error_update) {
            $showError = "Error updating fruit in API: " . $curl_error_update;
        } else {
            // Update fruit details in the database
            $sql = "UPDATE `fruits` SET 
                `name`='$fruitName',
                `family`='$family',
                `order`='$order',
                `genus`='$genus',
                `calories`='$calories',
                `fat`='$fat',
                `sugar`='$sugar',
                `carbohydrates`='$carbohydrates',
                `protein`='$protein' 
                WHERE name = '$oldFruitName'";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                $showAlert = true;
                // Fetch updated data from the database
                $result = mysqli_query($conn, "SELECT * FROM fruits WHERE name = '$fruitName'");
                $fruitData = mysqli_fetch_assoc($result);
            } else {
                $showError = "Error updating fruit in the database: " . mysqli_error($conn);

                // If updating the database fails, roll back the API update
                $curl_rollback = curl_init();
                curl_setopt_array($curl_rollback, $options_update);
                $rollbackResult = curl_exec($curl_rollback);
                curl_close($curl_rollback);

                if (!$rollbackResult) {
                    $showError .= "<br>Error rolling back API update: " . curl_error($curl_rollback);
                }
            }
        }
    }
} else {
    // Fetch existing fruit details from the database based on the provided fruit name
    $oldFruitName = isset($_GET['name']) ? $_GET['name'] : '';
    if (!empty($oldFruitName)) {
        $result = mysqli_query($conn, "SELECT * FROM fruits WHERE name = '$oldFruitName'");
        if ($result) {
            $fruitData = mysqli_fetch_assoc($result);
        } else {
            $showError = "Error fetching fruit details: " . mysqli_error($conn);
        }
    } else {
        $showError = "Error: Fruit name not provided.";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Fruit Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php require 'partials/_nav.php' ?>

    <div class="container my-4">
        <h1 class="text-center">Edit Fruit Details</h1>

        <?php
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Fruit details updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            // Add a link/button to redirect back to fruits.php
            echo '<a href="/Fruitwala/your_fruits.php?updated=true" class="btn btn-primary">Go to Fruits</a>';
        }

        if ($showError) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ' . $showError . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
        ?>

        <!-- Form to edit existing fruit -->
<form action="/Fruitwala/edit_fruits.php" method="post">
    <!-- Add form fields as needed -->
    <input type="hidden" name="id" value="<?php echo isset($fruitData['id']) ? $fruitData['id'] : ''; ?>">
    <div class="mb-3">
        <label for="fruit_name" class="form-label">Fruit Name</label>
        <input type="text" class="form-control" id="fruit_name" name="fruit_name" value="<?php echo isset($fruitData['name']) ? $fruitData['name'] : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="family" class="form-label">Family</label>
        <input type="text" class="form-control" id="family" name="family" value="<?php echo isset($fruitData['family']) ? $fruitData['family'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="order" class="form-label">Order</label>
        <input type="text" class="form-control" id="order" name="order" value="<?php echo isset($fruitData['order']) ? $fruitData['order'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="genus" class="form-label">Genus</label>
        <input type="text" class="form-control" id="genus" name="genus" value="<?php echo isset($fruitData['genus']) ? $fruitData['genus'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="calories" class="form-label">Calories</label>
        <input type="number" class="form-control" id="calories" name="calories" value="<?php echo isset($fruitData['nutritions']['calories']) ? $fruitData['nutritions']['calories'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="fat" class="form-label">Fat</label>
        <input type="number" class="form-control" id="fat" name="fat" value="<?php echo isset($fruitData['nutritions']['fat']) ? $fruitData['nutritions']['fat'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="sugar" class="form-label">Sugar</label>
        <input type="number" class="form-control" id="sugar" name="sugar" value="<?php echo isset($fruitData['nutritions']['sugar']) ? $fruitData['nutritions']['sugar'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="carbohydrates" class="form-label">Carbohydrates</label>
        <input type="number" class="form-control" id="carbohydrates" name="carbohydrates" value="<?php echo isset($fruitData['nutritions']['carbohydrates']) ? $fruitData['nutritions']['carbohydrates'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="protein" class="form-label">Protein</label>
        <input type="number" class="form-control" id="protein" name="protein" value="<?php echo isset($fruitData['nutritions']['protein']) ? $fruitData['nutritions']['protein'] : ''; ?>" required>
    </div>
    <input type="hidden" name="old_fruit_name" value="<?php echo isset($fruitData['name']) ? $fruitData['name'] : ''; ?>">
    <button type="submit" class="btn btn-primary">Update Fruit</button>
</form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
