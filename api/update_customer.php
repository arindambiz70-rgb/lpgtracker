<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
UPDATE customers
SET
name=?,
consno=?,
lpgid=?,
pw=?,
lastDelivery=?,
lastBooking=?
WHERE id=?
");

$stmt->bind_param(
"ssssssi",
$data["name"],
$data["consno"],
$data["lpgid"],
$data["pw"],
$data["lastDelivery"],
$data["lastBooking"],
$data["id"]
);

echo json_encode([
"success"=>$stmt->execute()
]);

$conn->close();
?>