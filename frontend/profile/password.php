<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//saved variables
$userid = $_SESSION['userid'];
$userwachtwoord = $_SESSION['userwachtwoord'];




//starting mandatory checks if the user is allowed to be on this page

//logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: .././profile.php"); // if user data is  not present (user is logged out) redirect back to login
}

//dont have to check if the user is editing the correct person since everything is gathered from the session
if(isset($_POST['update'])){//waiting for button submit press

    //check if everything is filled in
    if (strlen($_POST['formoldpass']) > 0 ){
        //old password is filled in
        if (strlen($_POST['newpass']) >= 5 && strlen($_POST['newpass1']) >= 5){
            //new passwords are long enough
            //check if newpassword == newpassword2

            if ($_POST['newpass'] == $_POST['newpass1']){
                //new passwords match
                //check if formoldpassword == dboldpassword

                if (password_verify($_POST['formoldpass'], $_SESSION['userwachtwoord'])){
                    //old passwords match

                    //hash newpassword
                    $hashedpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);

                    //SQL update klant wachtwoord='$hashedpassword'
                        //onsuccess redirect to profile.php with message
                    try {
                        $sql = "UPDATE klant SET wachtwoord='$hashedpass' WHERE id = '$userid'";

                        if ($result = mysqli_query($db, $sql)){
                            //update session password
                            $_SESSION['userwachtwoord'];
                            $_SESSION['passreset'] = "password has been successfully reset";
                            header("Location: ../profile.php");
                            die();
                        }
                    }catch(exception $e){
                        echo $e;
                    }

                }else{
                    $_SESSION['wrongold'] = 'old dont match';
                }

            }else{
                //new passwords are not the same
                $_SESSION['newsim'] = 'not the same';
            }


        }else{
            //new passwords are not long enough
            $_SESSION['newleng'] = "not long";
        }

    }else{
        //old password is filled
        $_SESSION['oldleng'] = "fill";
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
    <link rel="stylesheet" href="../../css/output.css">
    <style>

        .dropdown {
            float: left;
            overflow: hidden;
            margin-right: 50px;
        }

        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            padding: 14px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .dropdown:hover .dropbtn {
            background-color: #fbcfe8;
        }

        .dropdown-content {
            margin-top: 53px;
            display: none;
            position: absolute;
            background-color: #f8fafc;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
    <title>MijnEigentaartjes</title>
</head>
<body>
<div class="bg-slate-50 p-4 flex flex-start justify-between items-center">
    <a href="../home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="../gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="../contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="../about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex">
        <?php if (isset($_SESSION['userid']) && isset($_SESSION['uservoornaam'])){?>
            <div class="dropdown flex">
                <button class="dropbtn"><?php echo $_SESSION['uservoornaam']?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href=".././profile.php">Mijn profiel</a>
                    <?php if($_SESSION['IsAdmin'] == 1){
                        echo '<a href=".././overzicht.php">Reservering overzicht</a>';}?>
                    <a href=".././logout.php">Log uit</a>
                </div>
            </div>
            <?php
        }else{
            ?><a href="../login.php"><p class="mx-4 hover:underline">login</p></a>
            <a href="../register.php"><p class="mx-4 hover:underline">Register</p></a><?php
        } ?>
    </div>
</div>

<!--START HTML-->

<div class="mx-24 mt-4 text-center rounded">

    <p class="text-3xl text-semibold">Verander Wachtwoord</p>

    <p class="mb-12">om je wachtwoord te veranderen moet je voor de veiligheid je oude wachtwoord invoeren</p>

    <form action="../profile/password.php" method="post">

        <div class="rounded border-2 border-solid w-72 p-4 mb-6 mx-auto">
            <?php if (isset($_SESSION['wrongold'])){ ?>
                <p class="text-red-500">incorrect wachtwoord.</p>
            <?php
            unset($_SESSION['wrongold']);
            }if (isset($_SESSION['oldleng'])){?>
                <p class="text-red-500">Vul het oude wachtwoord in.</p>
                <?php
                unset($_SESSION['oldleng']);
            } ?>
            <p class="text-xl">Oud wachtwoord</p>
            <input  class="p-2 text-center rounded hover:border" type="password" name="formoldpass" placeholder="oud wachtwoord">
        </div>

        <div class="rounded border-2 border-solid w-72 p-4 mb-6 mx-auto">
            <?php if (isset($_SESSION['wrongnew'])){ ?>
                <p class="text-red-500">Nieuwe wachtwoorden komen niet overeen.</p>
            <?php
            unset($_SESSION['wrongnew']);
            }

            if (isset($_SESSION['newleng'])){?>
                <p class="text-red-500">nieuw wachtwoord is niet lang genoeg.</p>
                <?php
                unset($_SESSION['newleng']);
            }if (isset($_SESSION['newsim'])){?>
                <p class="text-red-500">nieuwe wachtwoorden zijn niet gelijk.</p>
                <?php
                unset($_SESSION['newsim']);
            } ?>
            <p class="text-xl">Nieuw wachtwoord</p>
            <input  class="p-2 text-center rounded hover:border w-full" type="password" name="newpass" placeholder="Nieuw wachtwoord">
            <br>
            <input  class="p-2 text-center rounded hover:border w-full" type="password" name="newpass1" placeholder="Herhaal Nieuw wachtwoord">
        </div>


    <input class="bg-green-400 hover:bg-green-600 py-2 px-4 mt-2 rounded" type="submit" name="update" value="Verander Wachtwoord">
    </form>

    <a href="../profile.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Keer terug</button></a>
</div>


</body>
</html>
