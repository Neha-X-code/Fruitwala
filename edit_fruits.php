<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

$showError = false;
$showAlert = false;

// Check if the fruit ID is provided in the URL parameter
if (isset($_GET['id'])) {
    $fruitId = $_GET['id'];

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Check if the fruit belongs to the current user
    $sql = "SELECT * FROM fruits WHERE id = '$fruitId' AND user_id = '$userId'";
    $result = mysqli_query($conn, $sql);

    $fruitExists = mysqli_num_rows($result) > 0;

    if ($fruitExists) {
        $fruitData = mysqli_fetch_assoc($result);

        // Pre-populate form fields with existing data
        $fruitName = $fruitData['name'];
        $family = $fruitData['family'];
        $order = $fruitData['order'];
        $genus = $fruitData['genus'];
        $calories = $fruitData['calories'];
        $fat = $fruitData['fat'];
        $sugar = $fruitData['sugar'];
        $carbohydrates = $fruitData['carbohydrates'];
        $protein = $fruitData['protein'];
    } else {
        // Redirect if the fruit doesn't belong to the user
        header("location: your_fruits.php");
        exit;
    }
} else {
    // Redirect if no fruit ID is provided
    header("location: your_fruits.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fruitId = $_POST["fruit_id"];
    $fruitName = $_POST["fruit_name"];
    $family = $_POST["family"];
    $order = $_POST["order"];
    $genus = $_POST["genus"];
    $calories = $_POST["calories"];
    $fat = $_POST["fat"];
    $sugar = $_POST["sugar"];
    $carbohydrates = $_POST["carbohydrates"];
    $protein = $_POST["protein"];

    // Update the fruit details in the database
    $sql = "UPDATE fruits SET name='$fruitName', family='$family', `order`='$order', genus='$genus', calories='$calories', fat='$fat', sugar='$sugar', carbohydrates='$carbohydrates', protein='$protein' WHERE id='$fruitId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $showAlert = true;
    } else {
        $showError = "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Fruit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <?php require 'partials/_nav.php' ?>

  <div class="container my-4">
  <h1 class="text-center">Edit Fruit Details</h1>
    <?php
    if ($showAlert) {
      echo '<div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> Fruit details updated successfully.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

    if ($showError) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> ' . $showError . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>
     <!-- Form to edit existing fruit -->
     <form action="/Fruitwala/edit_fruits.php?id=<?php echo $fruitId; ?>" method="post">
            <!-- Add form fields as needed -->
            <input type="hidden" name="fruit_id" value="<?php echo $fruitId; ?>">
            <div class="mb-3">
                <label for="fruit_name" class="form-label">Fruit Name</label>
                <input type="text" class="form-control" id="fruit_name" name="fruit_name" value="<?php echo $fruitName; ?>" required>
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
                <input type="number" class="form-control" id="calories" name="calories" value="<?php echo isset($fruitData['calories']) ? $fruitData['calories'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="fat" class="form-label">Fat</label>
                <input type="number" class="form-control" id="fat" name="fat" value="<?php echo isset($fruitData['fat']) ? $fruitData['fat'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="sugar" class="form-label">Sugar</label>
                <input type="number" class="form-control" id="sugar" name="sugar" value="<?php echo isset($fruitData['sugar']) ? $fruitData['sugar'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="carbohydrates" class="form-label">Carbohydrates</label>
                <input type="number" class="form-control" id="carbohydrates" name="carbohydrates" value="<?php echo isset($fruitData['carbohydrates']) ? $fruitData['carbohydrates'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="protein" class="form-label">Protein</label>
                <input type="number" class="form-control" id="protein" name="protein" value="<?php echo isset($fruitData['protein']) ? $fruitData['protein'] : ''; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Fruit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
      // Close the success alert and redirect after it's closed
      document.getElementById("successAlert").addEventListener('closed.bs.alert', function () {
        window.location.replace("your_fruits.php");
      });
    </script>
</body>

</html>
