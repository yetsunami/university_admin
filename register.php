<?php
session_start();
include "../config.php";
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
    $name       =   trim($_POST['name']);
    $email       =   trim($_POST['phone']);
    $password       =   trim($_POST['pass']);
    $confirm       =   trim($_POST['confirm']);

    $errors = [];
    if(empty($name)){
        $errors[] =$message['name']."requires";
    }
    if(empty($email) || !filter_var($email ,FILTER_VALIDATE_EMAIL)){
        $errors[]="invalid Email";
    }
    $checkEmail = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "هذا البريد الإلكتروني مستخدم بالفعل!";
    }
    if(empty($password)){
        $errors[] = " password Required";
    }
    if($password !== $confirm){
        $errors[] =  "password does not match";
    }
    if(empty($errors)){
        $sql = "INSERT INTO users(name,password,phone) values('$name','$password','$email','$now')";
        
        if($conn->multi_query($sql)){
            echo "inserted done";
        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/register.css">
    <title>Document</title>
</head>

<body>
    <?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach($errors as $error) echo "<p>$error</p>"; ?>
    </div>
<?php endif; ?>


    <div class="container">
        <form action="" method="post">
            <div>
                <label for="name"><?= $message['name'] ?></label>
                <input type="text" name="name">
            </div>
            <div>
                <label for="email"><?= $message['email'] ?></label>
                <input type="email" name="email">
            </div>
            <div>
                <label for="pass"><?= $message['password'] ?></label>
                <input type="password" name="pass">
            </div>
            <div>
                <label for="confirm"><?= $message['confirm'] ?></label>
                <input type="password" name="confirm">
            </div>
            <input type="submit" value="submit" name="register">


        </form>
    </div>
</body>

</html>