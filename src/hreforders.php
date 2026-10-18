<?php
session_start();
if(!isset($_SESSION["customer"])){
    die("Access denied");
}
echo "<a href='transfer.php?btn_action=Logout'>Logout</a><br><br>";

echo "<a href='addCustomer.php'>Modify</a><br><br>";
echo "Customer:".$_SESSION["customer"]."<br><br>made these orders:<a href='neworder.php'>Add New Order</a><br><br>";
try{
    $pdo=new PDO('mysql:host=localhost;dbname=dbshop;charset=utf8','root','',[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    
        
    
    }
    catch(PDOException $e){
        die( 'Exception:'.$e->getMessage());
    }
$sql="Select id, dateorder from orders where customer=:customer";
$stmt=$pdo->prepare($sql);
$stmt->execute(["customer"=>$_SESSION["customer"]]);
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){     
    echo "on {$row['dateorder']} order number: <a href='orderdetails.php?order_id={$row['id']}'>{$row['id']}</a><br>";
}
?>