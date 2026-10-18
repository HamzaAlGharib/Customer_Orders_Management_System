<?php 
session_start();
if(isset($_SESSION["customer"])){
    header("location:hreforders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Login Page:</h1>
    <form action="transfer.php" method="post">
    <table>
        <tr>
            <td>Id:</td>
            <td><input type="text" name="id" value="<?php if(isset($_COOKIE["customer"])){echo $_COOKIE["customer"];} ?>"></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" value="<?php if(isset($_COOKIE["pwd"])){echo $_COOKIE["pwd"];} ?>"></td>
        </tr>
        <tr>
            <td><input type="submit" name="btn_action" value="Login"></td></tr>

        <tr>
            <td><input type="submit" name="btn_action"value="add Customer"></td></tr>
        <tr>
            <td><input type="checkbox" name="remember">Remember me</td>
    </table>
     <?php 
     if(isset($_GET["msg"])){
        echo "<p style='color:red'>".$_GET["msg"]."</p>";
     }  ?>