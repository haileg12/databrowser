<?php
// Database connection
function getConnection() {
    $servername = "localhost";
    $username = "Haile";
    $password = "haile";
    $dbname = "projectdatabrowser";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
    }
    return $conn;
}

// Load data (used for initial display and after sorting)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conn = getConnection();
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
    
    $query = "SELECT * FROM onepiece";
    switch($sort) {
        case 'name':
            $query .= " ORDER BY name ASC";
            break;
        case 'default':
        default:
            $query .= " ORDER BY id ASC";
            break;
    }
    
    $result = $conn->query($query);
    $items = [];
    
    while($row = $result->fetch_assoc()) {
        $items[] = [
            "id" => $row['id'],
            "name" => $row['name'],
            "age" => $row['age'],
            "gender" => $row['gender'],
            "height" => $row['height'],
            "strawhat" => $row['strawhat'],
            "img" => $row['img']
        ];
    }
    
    echo json_encode($items);
    $conn->close();
    exit;
}

// Update existing character
if (isset($_POST['id']) && isset($_POST['newData'])) {
    $conn = getConnection();
    $id = (int) $_POST['id'];
    $newData = json_decode($_POST['newData'], true);
    
    $stmt = $conn->prepare("UPDATE onepiece SET name=?, age=?, gender=?, height=?, strawhat=?, img=? WHERE id=?");
    $stmt->bind_param("sissisi", 
        $newData['name'],
        $newData['age'],
        $newData['gender'],
        $newData['height'],
        $newData['strawhat'],
        $newData['img'],
        $id
    );
    
    if ($stmt->execute()) {
        // Fetch the updated record to return
        $result = $conn->query("SELECT * FROM onepiece WHERE id=$id");
        $updatedData = $result->fetch_assoc();
        echo json_encode([
            "status" => "success", 
            "message" => "Data updated successfully",
            "data" => $updatedData
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Delete character
if (isset($_POST['id']) && !isset($_POST['newData'])) {
    $conn = getConnection();
    $id = (int) $_POST['id'];
    
    // First, get the image path before deleting the record
    $stmt = $conn->prepare("SELECT img FROM onepiece WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $imagePath = $row['img'];
    
    // Delete the database record
    $stmt = $conn->prepare("DELETE FROM onepiece WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete the image file if it exists
        if ($imagePath && file_exists($imagePath)) {
            if (unlink($imagePath)) {
                $fileDeleteStatus = "Image file deleted successfully";
            } else {
                $fileDeleteStatus = "Failed to delete image file";
            }
        }
        
        // Reorder the remaining records
        $conn->query("SET @count = 0");
        $conn->query("UPDATE onepiece SET id = @count:= @count + 1 ORDER BY id");
        $conn->query("ALTER TABLE onepiece AUTO_INCREMENT = 1");
        
        echo json_encode([
            "status" => "success", 
            "message" => "Data deleted successfully. " . ($fileDeleteStatus ?? "No image file to delete")
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Add new character
if (isset($_POST['newItem'])) {
    $conn = getConnection();
    $newItem = json_decode($_POST['newItem'], true);
    
    $stmt = $conn->prepare("INSERT INTO onepiece (name, age, gender, height, strawhat, img) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissis", 
        $newItem['name'],
        $newItem['age'],
        $newItem['gender'],
        $newItem['height'],
        $newItem['strawhat'],
        $newItem['img']
    );
    
    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        // Fetch the newly inserted record to return
        $result = $conn->query("SELECT * FROM onepiece WHERE id=$newId");
        $insertedData = $result->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "message" => "New item added successfully",
            "data" => $insertedData
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Search functionality (new addition)
if (isset($_GET['search'])) {
    $conn = getConnection();
    $searchTerm = '%' . $_GET['search'] . '%';
    
    $stmt = $conn->prepare("SELECT * FROM onepiece WHERE name LIKE ? OR gender LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $items = [];
    
    while($row = $result->fetch_assoc()) {
        $items[] = [
            "id" => $row['id'],
            "name" => $row['name'],
            "age" => $row['age'],
            "gender" => $row['gender'],
            "height" => $row['height'],
            "strawhat" => $row['strawhat'],
            "img" => $row['img']
        ];
    }
    
    echo json_encode($items);
    $stmt->close();
    $conn->close();
    exit;
}
?>