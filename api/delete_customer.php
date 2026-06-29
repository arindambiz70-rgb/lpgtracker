<?php

header("Content-Type: application/json");

include "../db.php";

$data=json_decode(file_get_contents("php://input"),true);

$stmt=$conn->prepare("DELETE FROM customers WHERE id=?");

$stmt->bind_param("i",$data["id"]);

echo json_encode([
"success"=>$stmt->execute()
]);

$conn->close();

?>