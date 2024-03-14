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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Delete the fruit from the database
            $sql_delete = "DELETE FROM fruits WHERE id='$fruitId'";
            $result_delete = mysqli_query($conn, $sql_delete);

            if ($result_delete) {
                $showAlert = true;
                header("location: your_fruits.php");
                exit;
            } else {
                $showError = "Error: Unable to delete fruit. Please try again later.";
            }
        }
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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Fruit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require 'partials/_nav.php' ?>

<div class="container my-4">
    <?php
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Fruit deleted successfully.
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
    <div class="my-4">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this fruit?</p>
        <form action="/Fruitwala/delete_fruits.php?id=<?php echo $fruitId; ?>" method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="your_fruits.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
