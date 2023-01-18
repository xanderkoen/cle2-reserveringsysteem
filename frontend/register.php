<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';

//check for user data
session_start();
if (isset($_SESSION['voornaam']) && isset($_SESSION['klantid'])){
    header("Location: ./home.php"); // if user data is present (user is logged in) redirect back to homepage
}

if(isset($_POST['register'])){
    //get all the inputted data
    $voornaam = mysqli_real_escape_string($db, $_POST['voornaam']);
    $achternaam = mysqli_real_escape_string($db, $_POST['achternaam']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $wachtwoord1 = mysqli_real_escape_string($db, $_POST['wachtwoord1']);
    $wachtwoord2 = mysqli_real_escape_string($db, $_POST['wachtwoord2']);

    if ($voornaam !="" && $achternaam !="" && $email !="" && $wachtwoord1 !="" && $wachtwoord2 !=""){
        //if all data is not empty go further
        //check if the email is unique

        //check every input for links
        if (str_contains($voornaam, 'http') || str_contains($voornaam, 'https') || str_contains($voornaam, 'www.')){
            $nolinks = "neen";
        }elseif (str_contains($achternaam, "http") || str_contains($achternaam, "https") || str_contains($achternaam, "www.")){
            $nolinks = "neen";
        }elseif (str_contains($email, "http") || str_contains($email, 'https') || str_contains($email, 'www.')){
            $nolinks = "neen";
        }elseif (str_contains($wachtwoord1, 'http') || str_contains($wachtwoord1, 'https') || str_contains($wachtwoord1, 'www.')){
            $nolinks = "neen";
        }elseif (str_contains($wachtwoord2, 'http') || str_contains($wachtwoord2, 'https') || str_contains($wachtwoord2, 'www.')){
            $nolinks = "neen";
        }else{
            //no links found so the user can be registered

            try {
                $sql = "SELECT * FROM klant WHERE email = '$email'";

                if ($result = mysqli_query($db, $sql)){
                    $count = mysqli_num_rows($result);

                    if ($count == 0){
                        //email is unique
                        //check if password is long enough
                        if (strlen($wachtwoord1) >= 5 && strlen($wachtwoord2) >= 5){
                            //passwords are long enough

                            //check if passwords match
                            if ($wachtwoord1 === $wachtwoord2) {

                                //if passwords match hash the password
                                $hashedpassword = password_hash($wachtwoord1, PASSWORD_DEFAULT);
                                try {

                                    //send all the user data to database
                                    $sql = "INSERT INTO klant (voornaam, achternaam, email, wachtwoord) VALUES ('$voornaam', '$achternaam', '$email', '$hashedpassword')";

                                    if (mysqli_query($db, $sql)){
                                        //redirect with success message and all the shit filled in the inputs.

                                        $_SESSION['reg-email'] = $email;
                                        $_SESSION['reg-pass'] = $_POST['wachtwoord1'];

                                        header("Location: ./login.php");
                                    }

                                }catch(exception $e){
                                    $error_msg = $e;
                                }
                            }else{
                                $error_msg = "Wachtwoorden zijn niet gelijk";
                            }
                        }else{
                            $error_msg = "wachtwoorden moeten minimaal 5 letters bevatten.";
                        }
                    }else{
                        $error_msg = "Er bestaat al een account met dit email.";
                        var_dump($count);
                    }
                }
            }catch(exception $e){
                $error_msg = $e;
            }
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
        if (isset($error_msg)){?>
            <p class='text-red-500 text-xl'>$error_msg</p><?php
            unset($error_msg);
        }

        if (isset($nolinks)){?>
    <p class="text-red-500 text-xl">Links zijn niet toegestaan</p><?php
            unset($nolinks);
    }
    ?>

    <form action="./register.php" method="post">

    <div class="bg-slate-50 flex flex-col rounded-lg shadow-lg mt-24 px-4 mx-auto w-fit text-center">
        <p class="text-3xl font-semibold mb-4">Registreer account</p>

        <p class="text-xl text-left border-b-4 mb-2">Voornaam</p>
        <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="text" name="voornaam" placeholder="Voornaam">
        <p class="text-xl text-left border-b-4 mb-2">Achternaam</p>
        <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="text" name="achternaam" placeholder="Achternaam">
        <p class="text-xl text-left border-b-4 mb-2">Email Adres</p>
        <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="email" name="email" placeholder="email">
        <p class="text-xl text-left border-b-4 mb-2">Wachtwoord</p>
        <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="password" name="wachtwoord1" placeholder="Wachtwoord">
        <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="password" name="wachtwoord2" placeholder="Herhaal wachtwoord">
        <input type="submit" class="bg-green-500 hover:bg-green-700 rounded text-white w-fit mx-auto px-8 py-2 font-semibold text-xl shadow-lg my-4" name="register" value="register"/>
    </div>

    </form>

    <p class="mt-4">Heb je al een account? klik <a href="./login.php" class="text-blue-500 hover:underline">Hier</a> om in te loggen.</p>
</div>
</body>
</html>
