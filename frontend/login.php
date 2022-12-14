<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';

session_start(); //check for user data
if (isset($_SESSION['voornaam']) && isset($_SESSION['klantid'])){
    header("Location: ./home.php"); // if user data is present (user is logged in) redirect back to homepage
}

if(isset($_POST['login'])) {    //check if login button is pressed
    $email = $_POST['email'];
    $wachtwoord1 = $_POST['wachtwoord1'];
}

//check if data is not empty
    //if empty give error message
//hash inputted password
//fetch data from database

//compare data
    //if not correct show invalid data error
    //if correct save to PHP Sessions

//redirect back to homepage.

//make homepage check if you are logged in and change page accordingly.


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/output.css">
    <title>Document</title>
</head>
<body>
<div class="bg-slate-50 p-4 flex flex-start justify-between items-center">
    <a href="home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex flex-row">
        <a href="login.php"><p class="mx-4 hover:underline">Login</p></a>
        <a href="register.php" ><p class="mx-4 hover:underline">Register</p></a>
    </div>
</div>


<div class="flex flex-col w-full items-center">
    <?php
    if (isset($error_msg)){
        echo"<p class='text-red-500'>$error_msg</p>";
    }
    ?>

    <form action="./login.php" method="post">

        <div class="flex flex-col w-max items-center bg-slate-100 rounded mx-4">
            <p>Login</p>

            <input class="border border-solid rounded" type="email" name="email" placeholder="email">
            <input class="border border-solid rounded" type="password" name="wachtwoord1" placeholder="Wachtwoord">
            <input type="submit" class="bg-green-600 rounded p-1 m-4" name="login" value="login"/>
        </div>

    </form>

    <p>Heb je nog geen account? klik <a href="register.php" class="text-blue-500 hover:underline">Hier</a> om in te registreren.</p>
</div>
</body>
</html>
