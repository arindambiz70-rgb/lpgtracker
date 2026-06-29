<?php
header("Content-Type: application/json");

include "../db.php";

$sql = "SELECT * FROM customers ORDER BY id DESC";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>