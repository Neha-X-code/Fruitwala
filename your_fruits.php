<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

$userId = $_SESSION['user_id'];

// Fetch fruits added by the specific user
$result = mysqli_query($conn, "SELECT id, name, family, `order`, genus, calories, fat, sugar, carbohydrates, protein FROM fruits WHERE user_id = '$userId'");

if ($result === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$userData = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Fruits - <?php echo $_SESSION['username'] ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="container my-4">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading"> Welcome - <?php echo $_SESSION['username'] ?></h4>
            <p>Hey, here is the list of fruits you added.</p>
        </div>

        <!-- Table to display user's fruit details -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Family</th>
                    <th scope="col">Order</th>
                    <th scope="col">Genus</th>
                    <th scope="col">Calories</th>
                    <th scope="col">Fat</th>
                    <th scope="col">Sugar</th>
                    <th scope="col">Carbohydrates</th>
                    <th scope="col">Protein</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($userData as $fruit) : ?>
                <tr>
                    <td><?php echo isset($fruit['name']) ? $fruit['name'] : ''; ?></td>
                    <td><?php echo isset($fruit['family']) ? $fruit['family'] : ''; ?></td>
                    <td><?php echo isset($fruit['order']) ? $fruit['order'] : ''; ?></td>
                    <td><?php echo isset($fruit['genus']) ? $fruit['genus'] : ''; ?></td>
                    <td><?php echo isset($fruit['calories']) ? $fruit['calories'] : ''; ?></td>
                    <td><?php echo isset($fruit['fat']) ? $fruit['fat'] : ''; ?></td>
                    <td><?php echo isset($fruit['sugar']) ? $fruit['sugar'] : ''; ?></td>
                    <td><?php echo isset($fruit['carbohydrates']) ? $fruit['carbohydrates'] : ''; ?></td>
                    <td><?php echo isset($fruit['protein']) ? $fruit['protein'] : ''; ?></td>
                    <td>
                        <a href="/Fruitwala/edit_fruits.php?id=<?php echo $fruit['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/Fruitwala/delete_fruits.php?id=<?php echo $fruit['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
