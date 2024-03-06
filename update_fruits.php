<?php

// Include database connection file
include 'partials/_dbconnect.php';

// API URL to fetch fruit data
$api_url = 'https://www.fruityvice.com/api/fruit/all';

// Fetch data from the API
$response = file_get_contents($api_url);

// Decode the JSON response
$data = json_decode($response, true);

// Check if data is not empty
if (!empty($data)) {

    // Truncate the existing fruits table to remove old data
    $truncate_sql = "TRUNCATE TABLE fruits";
    mysqli_query($conn, $truncate_sql);

    // Insert new data into the fruits table
    foreach ($data as $fruit) {
        $name = mysqli_real_escape_string($conn, $fruit['name']);
        $family = mysqli_real_escape_string($conn, $fruit['family']);
        $order_name = mysqli_real_escape_string($conn, $fruit['order']);
        $genus = mysqli_real_escape_string($conn, $fruit['genus']);
        $calories = $fruit['nutritions']['calories'];
        $fat = $fruit['nutritions']['fat'];
        $sugar = $fruit['nutritions']['sugar'];
        $carbohydrates = $fruit['nutritions']['carbohydrates'];
        $protein = $fruit['nutritions']['protein'];

        $insert_sql = "INSERT INTO fruits (name, family, order_name, genus, calories, fat, sugar, carbohydrates, protein) VALUES ('$name', '$family', '$order_name', '$genus', $calories, $fat, $sugar, $carbohydrates, $protein)";
        mysqli_query($conn, $insert_sql);
    }

    echo "Fruit data updated successfully!";
} else {
    echo "Failed to fetch data from the API.";
}

?>
