<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//mandatory checks
//user logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: ../login.php");} // if user data is  not present (user is logged out) redirect back to login
//user is admin
if ($_SESSION['IsAdmin'] != 1){
    header("Location: ../home.php");
}

//vars
$linkid = $_GET['id'];
$reserveringid = "";
$reserveringdatum = "";
$reserveringtijd = "";
$reserveringinfo = "";
$reserveringtaart = "";
$klantid = "";
$klantvoornaam = "";
$klantachternaam = "";
$klantemail = "";


//retrieve all the data
try {
    $sql = "SELECT reserveringen.id, datum, tijd, info, taart, klant_id, klanten.voornaam, klanten.achternaam, klanten.email FROM reserveringen INNER JOIN klanten ON reserveringen.klant_id = klanten.id WHERE reserveringen.id = '$linkid'";

    if ($result = mysqli_query($db, $sql)){
        foreach($result as $r){
            $reserveringid = $r['id'];
            $reserveringdatum = $r['datum'];
            $reserveringtijd = $r['tijd'];
            $reserveringinfo = $r['info'];
            $reserveringtaart = $r['taart'];
            $klantid = $r['klant_id'];
            $klantvoornaam = $r['voornaam'];
            $klantachternaam = $r['achternaam'];
            $klantemail = $r['email'];
        }
    }
}catch(exception $e){
    echo $e;
}

//wait for delete input if admin wants to cancel reservation
if (isset($_POST['delete'])){
    try {
        //cancel reservation
        $deletesql = "DELETE FROM reserveringen WHERE id = '$linkid'";

        if ($result = mysqli_query($db, $deletesql)){
            //redirect user back to overview with notif
            $_SESSION['admin'] = "gone.";
            header("Location: .././overzicht.php");
        }
    }catch(exception $e){
        echo $e;
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
<div class="bg-slate-50 border-b shadow-lg p-4 flex flex-start justify-between items-center">
    <a href="../home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="../gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="../contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="../about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex">
        <?php if (isset($_SESSION['userid']) && isset($_SESSION['uservoornaam'])){?>
            <div class="dropdown flex">
                <button class="dropbtn"><?php echo htmlspecialchars($_SESSION['uservoornaam']); ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href=".././profile.php">Mijn profiel</a>
                    <?php if($_SESSION['IsAdmin'] == 1){?>
                        <a href=".././overzicht.php">Reservering overzicht</a><?php } ?>
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
<div class="bg-pink-50 rounded mt-4 mx-24 text-center">
    <p class="text-3xl">Reservering info</p>
    <div class="mt-12">
        <p class="text-2xl text-semibold">Klant info : </p>
        <p><?= htmlspecialchars($klantvoornaam);?> <?= htmlspecialchars($klantachternaam);?></p>
        <p>email : <?= htmlspecialchars($klantemail);?></p>
        <p>datum, tijd, info, taart , voornaam, achternaam, email</p>

        <p class="mt-12 text-2xl text-semibold">Reservatie info :</p>
        <p>Datum : <?= htmlspecialchars($reserveringdatum);?> - Tijd : <?= htmlspecialchars($reserveringtijd); ?></p>
        <p> Soort taart : <?php if ($reserveringtaart == 1){
            echo "cupcakes";
            }elseif($reserveringtaart == 2){
            echo "kleine taart";
            }else{
            echo "Grote taart";
            }?></p>

        <?php if (strlen($reserveringinfo) > 0){?>
        <p class="text-xl mt-4  text-semibold">Extra informatie :</p>
        <p><?= htmlspecialchars($reserveringinfo); ?></p><?php
        }?>
    </div>
    <?php if (isset($_POST['check'])){?>
    <div>
        <p class="text-xl text-semibold">Weet je zeker dat je deze afspraak wilt annuleren?</p>
        <form action="../reservering/reservering.php?id=<?=$linkid?>" method="post">
            <input type="submit" class="bg-rose-700 hover:bg-rose-900 py-2 px-4 mt-4 rounded" name="delete" id="delete" value="Ja."/>
        </form>
        <a href="../reservering/reservering.php?id=<?=$linkid?>"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Nee</button></a>
    </div><?php
    }else{?>
    <div class="mt-4">

        <form action="../reservering/reservering.php?id=<?=$linkid?>" method="post">
            <input type="submit" class="bg-rose-700 hover:bg-rose-900 py-2 px-4 mt-4 rounded" name="check" id="check" value="Annuleer deze reservatie."/>
        </form>
        <a href=".././overzicht.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Ga terug</button></a>
    </div><?php
    }?>
</div>


</body>
</html>
