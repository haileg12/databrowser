<?php
// Load JSON data
$jsonData = file_get_contents('opdata.json');
$items = json_decode($jsonData, true);

// Check if editing an item
if (isset($_POST['id']) && isset($_POST['newData'])) {
    $id = (int) $_POST['id'];
    $newData = json_decode($_POST['newData'], true);

    // Update the specific item in the array
    if (isset($items[$id])) {
        $items[$id] = $newData;
        file_put_contents('opdata.json', json_encode($items));
        echo json_encode(["status" => "success", "message" => "Data updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Item not found"]);
    }
    exit;
}

// Check if deleting an item
if (isset($_POST['id']) && !isset($_POST['newData'])) {
    $id = (int) $_POST['id'];

    if (isset($items[$id])) {
        unset($items[$id]);
        $items = array_values($items); // Re-index array
        file_put_contents('opdata.json', json_encode($items));
        echo json_encode(["status" => "success", "message" => "Data deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Item not found"]);
    }
    exit;
}

// Check if inserting a new item
if (isset($_POST['newItem'])) {
    $newItem = json_decode($_POST['newItem'], true);

    $items[] = $newItem;
    file_put_contents('opdata.json', json_encode($items));
    echo json_encode(["status" => "success", "message" => "New item added successfully"]);
    exit;
}

// Default response if no action matches
echo json_encode($items);
?>
