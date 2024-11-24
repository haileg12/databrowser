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
    age VARCHAR(10),
    gender VARCHAR(10),
    height VARCHAR(10),
    img VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Initial data array
$initialData = [
    ['Luffy', '19', 'Male', '5\'9"', 'images/luffy.jpg'],
    ['Nami', '20', 'Female', '5\'7"', 'images/nami.jpg'],
    ['Zoro', '21', 'Male', '5\'11"', 'images/zoro.jpg'],
    ['Usopp', '19', 'Male', '5\'10"', 'images/usopp.jpg'],
    ['Sanji', '21', 'Male', '5\'11"', 'images/sanji.jpg'],
    ['Chopper', '17', 'Male', '2\'9"', 'images/chopper.jpg'],
    ['Robin', '30', 'Female', '6\'2"', 'images/robin.jpg'],
    ['Brook', '90', 'Male', '9\'1"', 'images/brook.jpg'],
    ['Jinbe', '46', 'Male', '9\'11"', 'images/jinbe.jpg'],
    ['Garp', '78', 'Male', '9\'5"', 'images/garp.jpg'],
    ['Koby', '18', 'Male', '5\'6"', 'images/koby.jpg'],
    ['Kuzan', '49', 'Male', '9\'10"', 'images/kuzan.jpg'],
    ['Vivi', '18', 'Female', '5\'7"', 'images/vivi.jpg'],
    ['Kuma', '47', 'Male', '22\'7"', 'images/kuma.jpg'],
    ['Buggy', '39', 'Male', '6\'4"', 'images/buggy.jpg'],
    ['Mihawk', '43', 'Male', '6\'6"', 'images/mihawk.jpg'],
    ['Crocodile', '46', 'Male', '8\'4"', 'images/crocodile.jpg'],
    ['Law', '26', 'Male', '6\'3"', 'images/law.jpg'],
    ['Perona', '25', 'Female', '5\'3"', 'images/perona.jpg'],
    ['Pudding', '16', 'Female', '5\'5"', 'images/pudding.jpg']
];

// Clear existing data
$conn->query("TRUNCATE TABLE onepiece");

// Prepare and execute insert statement
$stmt = $conn->prepare("INSERT INTO onepiece (name, age, gender, height, img) VALUES (?, ?, ?, ?, ?)");

$insertCount = 0;
foreach ($initialData as $row) {
    $stmt->bind_param("sssss", $row[0], $row[1], $row[2], $row[3], $row[4]);
    if ($stmt->execute()) {
        $insertCount++;
    } else {
        echo "Error inserting row for " . $row[0] . ": " . $stmt->error . "<br>";
    }
}

echo "Successfully inserted " . $insertCount . " records<br>";

// Close statement and connection
$stmt->close();
$conn->close();

echo "Database setup completed!";
?>