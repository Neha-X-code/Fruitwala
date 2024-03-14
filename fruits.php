<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

include 'partials/_dbconnect.php';

// Fetch data from the API
$searchTerm = isset($_GET['search']) ? urlencode($_GET['search']) : '';
$userId = $_SESSION['user_id'];

// Fetch all fruits from the database
$result = mysqli_query($conn, "SELECT id, name, family, `order`, genus, calories, fat, sugar, carbohydrates, protein FROM fruits");

if ($result === false) {
    die("Error in SQL query: " . mysqli_error($conn));
}

$userData = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Filter data based on search term
if (!empty($searchTerm)) {
    $filteredData = array_filter($userData, function ($fruit) use ($searchTerm) {
        return stristr($fruit['name'], $searchTerm) !== false;
    });

    $userData = array_values($filteredData);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - <?php echo $_SESSION['username'] ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="container my-4">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading"> Welcome - <?php echo $_SESSION['username'] ?></h4>
            <p>Hey, find information about various fruits and their nutritional content.</p>
        </div>
        <form action="fruits.php" method="GET" class="mb-3">
            <label for="search">Search:</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Enter fruit name" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>
        <a href="/Fruitwala/add_fruits.php" class="btn btn-success mb-3">Add Fruit</a>

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
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
