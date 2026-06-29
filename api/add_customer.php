<?php

header("Content-Type: application/json");

include "../db.php";

$data=json_decode(file_get_contents("php://input"),true);

$stmt=$conn->prepare("INSERT INTO customers(name,consno,lpgid,pw,lastDelivery,lastBooking)
VALUES(?,?,?,?,?,?)");

$stmt->bind_param(
"ssssss",
$data["name"],
$data["consno"],
$data["lpgid"],
$data["pw"],
$data["lastDelivery"],
$data["lastBooking"]
);

if($stmt->execute()){
    echo json_encode(["success"=>true]);
}else{
    echo json_encode(["success"=>false]);
}

$conn->close();

?>