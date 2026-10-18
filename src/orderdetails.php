<?php
session_start();
if(!isset($_GET['order_id'])||!isset($_SESSION["customer"])){
     die("Access denied");
}

echo "Order number:".$_GET['order_id'];
try{
    $pdo=new PDO('mysql:host=localhost;dbname=dbshop;charset=utf8','root','',[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    
        
    
    }
    catch(PDOException $e){
        die( 'Exception:'.$e->getMessage());
    }
    if(isset($_POST['btn_action'])){
    if($_POST['btn_action']=="add/edit"){
        
    $sql='select * from orderdetails where id=:order_id and item=:item_id';
    $stmt=$pdo->prepare($sql);
    $stmt->execute(["order_id"=>$_GET['order_id'], "item_id"=>$_POST['item']]);

    if($stmt->rowCount()==1)
    {
            $sql="update orderdetails set quantity=:quantity, invoiceprice=:invoiceprice where id=:order_id and item=:item_id and 
            id in (select id from orders where customer=:customer)";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(["quantity"=>$_POST['quantity'], "invoiceprice"=>$_POST['invoiceprice'], "order_id"=>$_GET['order_id'], "customer"=>$_SESSION["customer"], "item_id"=>$_POST['item']]);
        }
        else{
            $sql='select * from orders where id=:order_id and customer=:customer';
            $stmt=$pdo->prepare($sql);  
            $stmt->execute(["order_id"=>$_GET['order_id'], "customer"=>$_SESSION["customer"]]);
            if($stmt->rowCount()==1){
            $sql="insert into orderdetails (id, item, quantity, invoiceprice) values (:order_id, :item, :quantity, :invoiceprice)";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(["order_id"=>$_GET['order_id'], "item"=>$_POST['item'], "quantity"=>$_POST['quantity'], "invoiceprice"=>$_POST['invoiceprice']]);
            }
        }
      
    }
  }
  if(isset($_GET['btn_action']) && $_GET['btn_action']=="delete"){
    $sql="delete from orderdetails where id=:order_id and item=:item_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(["order_id"=>$_GET['order_id'], "item_id"=>$_GET['item_id']]);
    
  }

    $sql="Select orders.id,item,quantity,invoiceprice from orderdetails inner join orders on orderdetails.id=orders.id where orders.id=:order_id and customer=:customer";
$stmt=$pdo->prepare($sql);
$stmt->execute(["order_id"=>$_GET['order_id'], "customer"=>$_SESSION["customer"]]);
echo "<table border='1'><tr><th>ID</th><th>Item</th><th>Quantity</th><th>Invoice price</th><th>Action</th></tr>";
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr><td>{$row['id']}</td><td>{$row['item']}</td><td>{$row['quantity']}</td><td>{$row['invoiceprice']}</td><td><a href='?btn_action=delete&order_id=".$_GET['order_id']."&item_id={$row['item']}'>Delete</a> <a href='?btn_action=edit&order_id=".$_GET['order_id']."&item_id={$row['item']}'>Edit</a></td></tr>";
}
echo "</table>";
echo "<form action='' method='post'>
<table><tr><th>Item</th><th>Quantity</th><th>Invoice price</th></tr>
<tr><td><select name='item'>";
 try{
    $pdo=new PDO('mysql:host=localhost;dbname=dbshop;charset=utf8','root','',[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    $sql="select id, description from items";
    $stmt=$pdo->query($sql);
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<option value='{$row['id']}' ".(isset($_GET['item_id']) && $_GET['item_id'] == $row['id'] ? "selected" : (isset($_POST['item']) && $_POST['item'] == $row['id'] ? "selected" : "")).">{$row['description']}</option>";
        
    }   
    }
    catch(PDOException $e){
        die( 'Exception:'.$e->getMessage());
    }
  if(isset($_GET['item_id'])){
    $sql="select quantity, invoiceprice from orderdetails where id=:order_id and item=:item_id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(["order_id"=>$_GET['order_id'], "item_id"=>$_GET['item_id']]);
    if($stmt->rowCount()==1){
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
    }
  }
  
echo "</select></td><td><input type='number' name='quantity' step='1' value='".(isset($row['quantity']) ? $row['quantity'] : "")."'></td><td><input type='number' name='invoiceprice' step='0.01' value='".(isset($row['invoiceprice']) ? $row['invoiceprice'] : "")."'></td><td><input type='submit' name='btn_action' value='add/edit'></form></tr></table>";


echo "<a href='transfer.php?btn_action=Logout'>Logout</a><br><br>";
