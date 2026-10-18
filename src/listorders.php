
<?php
if(!isset($_GET["customer"])){
    die("Access denied");
}
echo "Customer:".$_GET["customer"]."<br><br>made these orders:<br><br><form action=\"orderdetails.php\" method='get'><select name='order_id'>";
try{
    $pdo=new PDO('mysql:host=localhost;dbname=dbshop;charset=utf8','root','',[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
    
        
    
    }
    catch(PDOException $e){
        die( 'Exception:'.$e->getMessage());
    }
$sql="Select id from orders where customer=:customer";
$stmt=$pdo->prepare($sql);
$stmt->execute(["customer"=>$_GET["customer"]]);
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){     
    echo "<option value='{$row['id']}'>{$row['id']}</option>";
}

echo"</select><br><br><input type='submit' value='Details'></form>";

?>