<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//saved variables
$userid = $_SESSION['userid'];
$uservoornaam = $_SESSION['uservoornaam'];
$userachternaam = $_SESSION['userachternaam'];
$useremail = $_SESSION['useremail'];

//starting mandatory checks if the user is allowed to be on this page

//logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: .././profile.php"); // if user data is  not present (user is logged out) redirect back to login
}

//dont have to check if the user is editing the correct person since everything is gathered from the session

if (isset($_POST['update'])) { //wait for the update input

    //retrieve posted lines
    $newvoornaam = mysqli_real_escape_string($db, $_POST['voornaam']);
    $newachternaam = mysqli_real_escape_string($db, $_POST['achternaam']);
    $newemail = mysqli_real_escape_string($db, $_POST['email']);


    //check the strings for links
    if (str_contains($newvoornaam, "http") || str_contains($newvoornaam, "https") || str_contains($newvoornaam,"www.")){
        $nolinks = "neen";
    }elseif (str_contains($newachternaam, "http") || str_contains($newachternaam, "https") || str_contains($newachternaam, "www.")){
        $nolinks = "neen";
    }elseif (str_contains($newemail, "http") || str_contains($newemail, "https") || str_contains($newemail, "www.")){
        $nolinks = "neen";
    }else{
        //update the user [userid] in SQL

        try {
            $sql = "UPDATE klant SET voornaam='$newvoornaam', achternaam='$newachternaam', email='$newemail' WHERE id = '$userid'";
            //on success update the saved session variables

            if ($updresult = mysqli_query($db, $sql)){
                //item successfully updated

                $_SESSION['uservoornaam'] = $newvoornaam;
                $_SESSION['userachternaam'] = $newachternaam;
                $_SESSION['useremail'] = $newemail;

                $_SESSION['profupd'] = "success updatde";
                header("Location: .././profile.php");
            }
        }catch(exception $e){
            echo $e;
        }
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
    <p class="text-3xl text-semibold mb-4"> Verander profiel</p>
    <form action="../profile/edit.php?id=<?= $_SESSION['userid'] ?>" method="post">
        <?php if (isset($nolinks)){?>
        <p class="text-red-500">Links zijn niet toegestaan.</p><?php
        unset($nolinks);
        } ?>


        <div class="flex flex-col w-fit m-auto">
            <div class="my-4 w-fit">
                <p class="text-xl text-semibold text-slate-500 mb-2 border-b-4">Voornaam</p>
                <input class="rounded p-2 text-left bg-inherit hover:border text-center" type="text" name="voornaam" id="voornaam" value="<?= $_SESSION['uservoornaam']?>" placeholder="voornaam" required>
            </div>

            <div class="my-4 w-fit">
                <p class="text-xl text-semibold text-slate-500 mb-2 border-b-4">Achternaam</p>
                <input class="rounded p-2 text-left hover:border text-center" type="text" name="achternaam" id="achternaam" value="<?= $_SESSION['userachternaam']?>" placeholder="achternaam" required>
            </div>

            <div class="my-4 w-fit">
                <p class="text-xl text-semibold text-slate-500 mb-2 border-b-4">Email</p>
                <input class="rounded p-2 text-left hover:border text-center" type="email" name="email" id="email" value="<?= $_SESSION['useremail']?>" placeholder="email" required>
            </div>

            <input type="submit" class="bg-green-300 hover:bg-green-500 py-2 px-4 mt-4 rounded" name="update" id="update" value="Sla op"/>
        </div>
    </form>
    <a href="../profile/password.php"><button class="bg-purple-600 hover:bg-purple-800 py-2 px-4 mt-2 rounded">Verander Wachtwoord</button></a>
    <a href="../profile.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Keer terug</button></a>
</div>


</body>
</html>
