<?php
session_start();
if(!isset($_SESSION["customer"])){
    die("Access denied");
}
if(isset($_GET["msg"])){
    echo $_GET["msg"]."<br><br>";
}   
?>
<form action='transfer.php' method='post'>
    Order Number:<input type='text' name='order_number' required><br><br>
   Date of order  <input type='date' name='order_date' value='<?php echo date('Y-m-d');?>' required><br><br>
   <input type='submit' value='Add Order' name='btn_action' >   
</form>
