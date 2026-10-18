<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    //Customers (id, pwd, lname, fname, address, city, country, Credit);
    session_start(); 
    if(!isset($_SESSION["customer"])){
        echo "add new customer:<br><br>"; 
    }
    else{
        echo "Modify customer information :{$_SESSION["customer"]}<br><br>";
        try{
            $db=new PDO("mysql:host=localhost;dbname=dbshop","root","");
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $sql="SELECT * FROM customers WHERE id=:id";
            $stmt=$db->prepare($sql);
            $stmt->execute([
                ":id"=>$_SESSION["customer"]
            ]);
            if($stmt->rowCount()==1){
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                die("Customer not found");
            }
        }catch(PDOException $e){
            die("Error: ".$e->getMessage());}

    }
    ?>
    <form action="transfer.php" method="post">
        <table>
            <tr>
                <td>Id:</td>
                <td><input type="text" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>" <?php echo(isset($_SESSION["customer"]) ? "readonly" : ""); ?>></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" value=""></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td><input type="text" name="lname" value="<?php echo isset($row['lname']) ? $row['lname'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>First Name:</td>
                <td><input type="text" name="fname" value="<?php echo isset($row['fname']) ? $row['fname'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Address:</td>
                <td><input type="text" name="address" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>City:</td>
                <td><input type="text" name="city" value="<?php echo isset($row['city']) ? $row['city'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Country:</td>
                <td><input type="text" name="country" value="<?php echo isset($row['country']) ? $row['country'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Credit:</td>
                <td><input type="number" name="credit" step="0.01" value="<?php echo isset($row['Credit']) ? $row['Credit'] : ''; ?>"></td>
            </tr>
        </table>
        <input type="submit" name="btn_action" value="<?php echo isset($_SESSION["customer"])?'Modify':'add new Customer'; ?>">
    </form>
   <?php 
     if(isset($_GET["msg"])){
        echo "<p style='color:red'>".$_GET["msg"]."</p>";
     }  ?>