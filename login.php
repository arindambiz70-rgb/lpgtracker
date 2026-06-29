<?php
session_start();

$PASSWORD = "706356";   // Change this

if(isset($_POST["password"])){

    if($_POST["password"] == $PASSWORD){

        $_SESSION["loggedin"]=true;

        header("Location: lpg-booking-tracker.php");
         exit();
    }else{
        $error="Wrong Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>LPG Login</title>

<style>

body{
    font-family:Arial;
    background:#1565c0;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{

    background:white;
    padding:35px;
    width:330px;
    border-radius:10px;
    text-align:center;
}

input{

    width:100%;
    padding:10px;
    margin:15px 0;
}

button{

    width:100%;
    padding:10px;
    background:#1565c0;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.error{

color:red;

}

</style>

</head>

<body>

<div class="box">

<h2>LPG Tracker Login By Arindam</h2>

<?php if(isset($error)){ ?>

<p class="error"><?php echo $error; ?></p>

<?php } ?>

<form method="post">

<input
type="password"
name="password"
placeholder="Enter Password">

<button>Login</button>

</form>

</div>

</body>
</html>