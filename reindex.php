<?php
// reindex.php
function reindexDatabase() {
    $conn = new mysqli("localhost", "Haile", "haile", "projectdatabrowser");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create temporary table
    $conn->query("CREATE TABLE temp_onepiece LIKE onepiece");
    
    // Copy data with new auto-incremented IDs
    $conn->query("INSERT INTO temp_onepiece (name, age, gender, height, img)
                 SELECT name, age, gender, height, img
                 FROM onepiece
                 ORDER BY id");
    
    // Drop original table
    $conn->query("DROP TABLE onepiece");
    
    // Rename temp table to original
    $conn->query("RENAME TABLE temp_onepiece TO onepiece");
    
    $conn->close();
}

// Call this function after deletions if you want to reindex
if (isset($_POST['reindex']) && $_POST['reindex'] === 'true') {
    reindexDatabase();
    echo json_encode(["status" => "success", "message" => "Database reindexed successfully"]);
    exit;
}
?>