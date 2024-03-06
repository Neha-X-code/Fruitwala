<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

// Get data from the database
$result = mysqli_query($conn, "SELECT * FROM fruits");

// Check if there are rows in the result set
if ($result) {
    $dbData = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $dbData = []; // Set an empty array if there are no rows
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Fruits - <?php echo $_SESSION['username'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-rZoB/KDPlXBkZ87FkRY2w8atbMlJ1IM8VHzBiC9RJ6X3jUsh/1S04cBgA8fM5BZJ" crossorigin="anonymous">
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="container my-4">
        <h4 class="mb-3">Your Fruits - <?php echo $_SESSION['username'] ?></h4>

        <!-- Table to display fruit details -->
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
                <?php foreach ($dbData as $fruit) : ?>
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
                            <!-- Edit icon linking to edit_fruits.php -->
                            <a href="/Fruitwala/edit_fruits.php?name=<?php echo $fruit['name']; ?>">
                                <i class="fas fa-pen" style="color: #74C0FC;"></i>
                            </a>
                            <!-- Delete icon linking to delete_fruits.php with confirmation prompt -->
                            <a href="/Fruitwala/delete_fruits.php?name=<?php echo $fruit['name']; ?>" onclick="confirmDelete('<?php echo $fruit['name']; ?>')">
                                <i class="fas fa-trash" style="color: #74C0FC; margin-left: 10px;"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://kit.fontawesome.com/69f4a7d5cb.js" crossorigin="anonymous"></script>
    <script>
        function confirmDelete(fruitName) {
            var confirmDelete = confirm("Are you sure you want to delete " + fruitName + "?");

            if (confirmDelete) {
                window.location.href = "/Fruitwala/delete_fruits.php?name=" + fruitName;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
