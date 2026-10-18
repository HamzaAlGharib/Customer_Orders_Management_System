<?php

        session_start();
if(isset($_REQUEST['btn_action'])){
switch($_REQUEST['btn_action']){
    case "Add Order":
        $sql="insert into orders (id,customer, dateorder) values (:id, :customer, :dateorder)";
        try{
            $pdo=new PDO('mysql:host=localhost;dbname=dbshop;charset=utf8','root','',[
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
                ]);
            $stmt=$pdo->prepare($sql);
            $stmt->execute(["id"=>$_POST['order_number'], "customer"=>$_SESSION["customer"], "dateorder"=>$_POST['order_date']]);
            header("Location: hreforders.php");}
            catch(PDOException $e){
                header("Location: neworder.php?msg=Error adding order: ". $e->getMessage());
               
            }
        break;
    case "add new Customer":
        try{
            $db=new PDO("mysql:host=localhost;dbname=dbshop","root","");
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $sql="INSERT INTO customers (id,pwd,lname,fname,address,city,country,Credit) VALUES (:id,:password,:lname,:fname,:address,:city,:country,:credit)";
            $stmt=$db->prepare($sql);
            $stmt->execute([
                ":id"=>$_POST["id"],
                ":password"=>md5($_POST["password"]),
                ":lname"=>$_POST["lname"],
                ":fname"=>$_POST["fname"],
                ":address"=>$_POST["address"],
                ":city"=>$_POST["city"],
                ":country"=>$_POST["country"],
                ":credit"=>$_POST["credit"]
            ]);
            header("location:index.php?msg=Customer added successfully");
            exit;   
            }catch(PDOException $e){
                header("location:index.php?msg=problem adding customer: ".$e->getMessage());
            }   
            exit;
    case "Modify":
        try{
            $db=new PDO("mysql:host=localhost;dbname=dbshop","root","");
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $modpwdstring=!empty($_POST["password"]) ? " pwd=:password," : "";
            $sql="UPDATE customers SET lname=:lname,fname=:fname,address=:address,city=:city,country=:country,Credit=:credit WHERE id=".$_SESSION["customer"];
            $stmt=$db->prepare($sql);
            if(!empty($_POST["password"])){
                $stmt->bindValue(":password",md5($_POST["password"]));
            }

                $stmt->execute([
                ":lname"=>$_POST["lname"],
                ":fname"=>$_POST["fname"],
                ":address"=>$_POST["address"],
                ":city"=>$_POST["city"],
                ":country"=>$_POST["country"],
                ":credit"=>$_POST["credit"]
            ]);
            header("location:addCustomer.php?msg=Customer modified successfully");
            exit;   
            }catch(PDOException $e){
                header("location:addCustomer.php?msg=problem modifying customer: ".$e->getMessage());
            }   
            exit;
    case "Logout":
        session_destroy();
        header("location:index.php");
        exit;
    case "Login":
        if(isset($_POST["remember"])){
            setcookie("customer",$_POST["id"],time()+3600);
            setcookie("pwd",$_POST["password"],time()+3600);
            
        }

        try{
            $db=new PDO("mysql:host=localhost;dbname=dbshop","root","");
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $sql="SELECT * FROM customers WHERE id=:id AND pwd=:password";
            $stmt=$db->prepare($sql);
            $stmt->execute([
                ":id"=>$_POST["id"],
                ":password"=>md5($_POST["password"])
            ]);
            if($stmt->rowCount()==1){
                session_start();
                $_SESSION["customer"]=$_POST["id"];
                header("location:hreforders.php");
                exit;
            }else{
                header("location:index.php?msg=Invalid id or password");
            }
        }catch(PDOException $e){
            die("Error: ".$e->getMessage());}

        exit;
    case "add Customer":
        header("location:addcustomer.php");
        exit;
    case "List orders":
        header("location:listorders.php?customer=".$_GET["customer"]);
        exit;
     case "Href orders":
        session_start();
        $_SESSION["customer"]=$_GET["customer"];
        header("location:Hreforders.php");

        exit;    
}

}
die("Access denied");

?>
