<?php session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
</head>
<body>

<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=dbshop;charset=utf8',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

$id="";
$password="";
$error="";
if(isset($_SESSION['error'])){
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // remove message after showing
}

# load cookies only if no error
if(empty($error)){
    if(isset($_COOKIE['id'])){
        $id=$_COOKIE['id'];
    }

    if(isset($_COOKIE['password'])){
        $password=$_COOKIE['password'];
    }
}
?>

<?php
if($error!=""){
    echo "<p style='color:red'>$error</p>";
}
?>
<center style="display: flex; align-items: center; justify-content: center; height: 100vh;">
<form method="post" autocomplete="off" >

id:
<input type="text" name="id" value="<?php echo $id ?>"><br><br>

password:
<input type="password" name="password" value="<?php echo $password ?>"><br><br>

<input type="checkbox" name="remember"> Remember me?<br><br>

<input type="submit" name="login" value="Login">
<input type="submit" name="add" value="add Customer">

</form>
</center>

<?php

if(isset($_POST['login'])){

    if(empty($_POST['id']) || empty($_POST['password'])){
        $_SESSION['error'] = "Please fill in all fields";
        header("Location: customerlogin.php");
        exit();
    }

    
    else{
        $id=$_POST['id'];
        $password=$_POST['password'];
        $sql="SELECT pwd FROM customers WHERE id=:id ";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id'=>$id
            
        ]);
        $row=$stmt->fetch();
        if(!$row){
            $_SESSION['error'] = "Login failed, customer doesn't exist ; add a new customer or check your credentials";
            header("Location: customerlogin.php");
            exit();
        }
        $hashed_password=$row['pwd'];

            if(!password_verify($password,$hashed_password)){
                $_SESSION['error'] = "Login failed, wrong password ; add a new customer or check your credentials";
                header("Location: customerlogin.php");
                exit();
            }
            else{
                if(isset($_POST['remember'])){
            setcookie('id',$id,time()+3600);
            // setcookie('password',$password,time()+3600);
        }

        header("Location: hreforders1.php?cid=$id");
        exit();
            }
        

        
    }

}
if(isset($_POST['add'])){
    header("Location: addCustomer.php");
    exit();
}
?>

</body>
</html>