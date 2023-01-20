<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';

//check for user data
session_start();
if (isset($_SESSION['voornaam']) && isset($_SESSION['klantid'])){
    header("Location: ./home.php"); // if user data is present (user is logged in) redirect back to homepage
}

//PSEUDO login

//wacht op submit input
    //check alle vars of ze niet leeg zijn
        //check success
            //check of de gegevens geen links hebben
                //check success
                    //haal de user met de email op uit de database
                        //user gevonden
                            //vergelijk wachtwoorden met elkaar
                                //wachtwoorden kloppen
                                    //log de user in in de session en sla alle data op op de session
                                    //redirect terug naar homepage (met session data opgeslagen nu)
                                //wachtwoorden kloppen niet
                                    //geef error message
                        //user niet gevonden
                            //geef error messgae
                //check gefaald
                    //geef error message
        //check gefaald
            //geef error message

if(isset($_POST['login'])) {    //check if login button is pressed
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $wachtwoord1 = mysqli_real_escape_string($db, $_POST['wachtwoord1']);

    if ($email !="" && $wachtwoord1 !=""){ //check if data is not empty

        //check if input contains no links

        if (str_contains($email, "http") || str_contains($email, "https") || str_contains($email, "www.")){
            $nolinks = "neen";
        }elseif (str_contains($wachtwoord1, "http") || str_contains($wachtwoord1, "https") || str_contains($wachtwoord1, 'www.')){
            $nolinks = "neen";
        }else{

            // no links found proceed to login
            try {
                $sql = "SELECT * FROM klanten WHERE email = '$email'";

                if ($result = mysqli_query($db, $sql)){//fetch data from database

                    if (mysqli_num_rows($result) == 0){
                        $error_msg = "account niet gevonden";
                    }

                    $fetchedid = "";
                    $fetchedvoornaam = "";
                    $fetchedachternaam = "";
                    $fetchedemail = "";
                    $fetchedwachtwoord = "";
                    $fetchedadmin = "";


                    foreach($result as $r) {       //temp save the fetched data
                        $fetchedid = $r['id'];
                        $fetchedvoornaam = $r['voornaam'];
                        $fetchedachternaam = $r['achternaam'];
                        $fetchedemail = $r['email'];
                        $fetchedwachtwoord = $r['wachtwoord'];
                        $fetchedadmin = $r['IsAdmin'];
                    }

                    if (password_verify($wachtwoord1, $fetchedwachtwoord)){ //check if password is correct
                        //save data to session
                        $_SESSION['userid'] = $fetchedid;
                        $_SESSION['uservoornaam'] = $fetchedvoornaam;
                        $_SESSION['userachternaam'] = $fetchedachternaam;
                        $_SESSION['useremail'] = $fetchedemail;
                        $_SESSION['userwachtwoord'] = $fetchedwachtwoord;
                        $_SESSION['IsAdmin'] = $fetchedadmin;

                        header("Location: home.php");
                        die();

                    }else{
                        $error_msg = "Gegevens zijn incorrect.";
                    }
                }else{
                    $error_msg = "ERROR: could not execute $sql". mysqli_error($db);
                }
            }catch(exception $e){
                echo $e;
            }
        }

    }else{
        $error_msg = "Niet alle velden zijn ingevuld"; //if empty give error message
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

    if (isset($nolinks)){?>
        <p class='text-red-500'>Links zijn niet toegestaan.</p><?php
        unset($nolinks);
    }

    if (isset($_SESSION['reg-email']) && isset($_SESSION['reg-pass'])){?>
        <p class="text-xl text-green-500">Account successvol geregistreerd!</p>
   <?php } ?>

    <form action="./login.php" method="post" class="w-full">

        <div class="bg-slate-50 flex flex-col rounded-lg shadow-lg mt-24 px-4 mx-auto w-fit text-center">
            <p class="text-3xl font-semibold mb-4">Login</p>

            <p class="text-xl text-left border-b-4 mb-2">Email adres</p>
            <input class="border border-solid rounded w-96 p-2 mb-4 shadow-lg" type="email" name="email" placeholder="email" <?php if(isset($_SESSION['reg-email'])){ $setemail = $_SESSION['reg-email']; echo "value='$setemail'";}?>>
            <p class="text-xl text-left border-b-4 mb-2">Wachtwoord</p>
            <input class="border border-solid rounded p-2 shadow-lg" type="password" name="wachtwoord1" placeholder="Wachtwoord" <?php if(isset($_SESSION['reg-pass'])){ $setpass = $_SESSION['reg-pass']; echo "value='$setpass'";}?>>
            <input type="submit" class="bg-green-500 hover:bg-green-700 rounded text-white w-fit mx-auto px-8 py-2 font-semibold text-xl shadow-lg my-4" name="login" value="Log in"/>
        </div>

    </form>

    <?php unset($_SESSION['reg-email']);unset($_SESSION['reg-pass']); ?>
    <p class="mt-4">Heb je nog geen account? klik <a href="register.php" class="text-blue-500 hover:underline">Hier</a> om in te registreren.</p>
</div>
</body>
</html>
