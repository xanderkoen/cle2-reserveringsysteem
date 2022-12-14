<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';

session_start(); //check for user data
if (isset($_SESSION['voornaam']) && isset($_SESSION['klantid'])){
    header("Location: ./home.php"); // if user data is present (user is logged in) redirect back to homepage
}

if(isset($_POST['register'])){
    $voornaam = $_POST['voornaam'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $wachtwoord1 = $_POST['wachtwoord1'];
    $wachtwoord2 = $_POST['wachtwoord2'];
    //get all the inputted data


    if ($voornaam !="" && $achternaam !="" && $email !="" && $wachtwoord1 !="" && $wachtwoord2 !=""){
        //if all data is not empty go further

        if ($wachtwoord1 === $wachtwoord2) {
            //check if passwords match
            //if passwords match hash the password
            $hashedpassword = password_hash($wachtwoord1, PASSWORD_DEFAULT);

            //send all the user data to database
            $sql = "INSERT INTO klant (voornaam, achternaam, email, wachtwoord) 
            VALUES ('$voornaam', '$achternaam', '$email', '$hashedpassword')";

            if (mysqli_query($db, $sql)){
                echo "klant successvol geregistreerd";
            }else{
                echo "ERROR: could not execute $sql". mysqli_error($db);
            }
        }else{
            $error_msg = "Wachtwoorden zijn niet gelijk";
        }
    }else{
        $error_msg = "Niet alle velden zijn ingevuld";
    }
}

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

    <form action="./register.php" method="post">

    <div class="flex flex-col w-max items-center bg-slate-100 rounded mx-4">
        <p>Registreer account</p>

        <input class="border border-solid rounded" type="text" name="voornaam" placeholder="Voornaam">
        <input class="border border-solid rounded" type="text" name="achternaam" placeholder="Achternaam">
        <input class="border border-solid rounded" type="email" name="email" placeholder="email">
        <input class="border border-solid rounded" type="password" name="wachtwoord1" placeholder="Wachtwoord">
        <input class="border border-solid rounded" type="password" name="wachtwoord2" placeholder="Herhaal wachtwoord">
        <input type="submit" class="bg-green-600 rounded p-1 m-4" name="register" value="register"/>
    </div>

    </form>

    <p>Heb je al een account? klik <a href="./login.php" class="text-blue-500 hover:underline">Hier</a> om in te loggen.</p>
</div>
</body>
</html>
