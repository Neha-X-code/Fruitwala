<?php
include 'partials/_dbconnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Add your additional CSS stylesheets or links here -->
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="image-container">
        <img src="/Fruitwala/bg.jpg" class="img-fluid" alt="Fruit">
        <div class="container text-over-image">
            <h1>Welcome to फ्रूटwala!</h1>
            <p>Discover a variety of fresh and delicious fruits and their nutrition value</p>
        </div>
    </div>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .image-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .text-over-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: black; /* Set text color as needed */
        }
    </style>
    <!-- Font Awesome script -->
    <script src="https://kit.fontawesome.com/69f4a7d5cb.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
