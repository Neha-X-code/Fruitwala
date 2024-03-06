delete_fruits.php
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['name'])) {
    $fruitName = $_GET['name'];

    // Delete the fruit from the database
    $sql = "DELETE FROM fruits WHERE name = '$fruitName'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect back to fruits.php after successful deletion
        header("location: your_fruits.php");
        exit;
    } else {
        // Display an error message if deletion fails
        echo "Error deleting fruit: " . mysqli_error($conn);
    }
} else {
    // Redirect to fruits.php if name parameter is not set
    header("location: your_fruits.php");
    exit;
}
?>