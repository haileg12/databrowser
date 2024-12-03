<?php

// Database connection parameters
$servername = "localhost";
$username = "Haile";
$password = "haile";

// Create connection to MySQL server (without database)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS projectdatabrowser";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db("projectdatabrowser");

// Create table
$sql = "CREATE TABLE IF NOT EXISTS onepiece (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    gender VARCHAR(10),
    height VARCHAR(10),
    strawhat BOOLEAN,
    img VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Read and decode JSON
$json = file_get_contents('opdata.json');
$initialData = json_decode($json, true);

// Clear existing data
$conn->query("TRUNCATE TABLE onepiece");

// Prepare and execute insert statement
$stmt = $conn->prepare("INSERT INTO onepiece (name, age, gender, height, strawhat, img) VALUES (?, ?, ?, ?, ?, ?)");

$insertCount = 0;
foreach ($initialData as $row) {
    // Convert boolean to integer (0 or 1)
    
    $stmt->bind_param("sissis", 
        $row['name'], 
        $row['age'], 
        $row['gender'],
        $row['height'],
        $row['strawhat'],
        $row['img']
    );
    
    if ($stmt->execute()) {
        $insertCount++;
    } else {
        echo "Error inserting row for " . $row['name'] . ": " . $stmt->error . "<br>";
    }
}

echo "Successfully inserted " . $insertCount . " records<br>";

// Close statement and connection
$stmt->close();
$conn->close();

echo "Database setup completed!";
?>