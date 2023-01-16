<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';

session_start();

if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: ./login.php"); // if user data is  not present (user is logged out) redirect back to login
}

//save currentdatetime to variable
$checkdate = date('Y-m-d H:i:s', strtotime('+1 day 1 hour'));

//save user ID for sql query
$userid = $_SESSION['userid'];

//retrieve all user reservations
$sql = " SELECT id, datum, tijd, info, taart FROM reservering WHERE klant_id = '$userid' " ;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/output.css">
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
    <a href="home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex">
        <?php if (isset($_SESSION['userid']) && isset($_SESSION['uservoornaam'])){?>
            <div class="dropdown flex">
                <button class="dropbtn"><?php echo $_SESSION['uservoornaam']?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="./profile.php" class="bg-slate-150" style="background-color: #ddd;">Mijn profiel</a>
                    <?php if($_SESSION['IsAdmin'] == 1){
                        echo '<a href="./overzicht.php">Reservering overzicht</a>';}?>
                    <a href="./logout.php">Log uit</a>
                </div>
            </div>
            <?php
        }else{
            ?><a href="login.php"><p class="mx-4 hover:underline">login</p></a>
            <a href="register.php"><p class="mx-4 hover:underline">Register</p></a><?php
        } ?>
    </div>
</div>

<!--START HTML-->

<div class="bg-pink-50 rounded mt-4 mx-24 text-center">
    <?php if (isset($_SESSION['successmsg'])){
        echo'<p class="text-green-500"> Reservering successvol aangemaakt!</p>';// create success message
        unset($_SESSION['successmsg']); //delete success message after showing
    }

    if (isset($_SESSION['sucdel'])){
        echo'<p class="text-green-500"> Reservering successvol geannuleerd!</p>';// delete success  message
        unset($_SESSION['sucdel']); //delete success message after showing
    }

    if (isset($_SESSION['passreset'])){
        echo'<p class="text-green-500"> Wachtwoord successvol veranderd!</p>';// delete success  message
        unset($_SESSION['passreset']); //delete success message after showing
    }

    if (isset($_SESSION['sucupd'])){
        echo'<p class="text-green-500"> Reservering successvol aangepast!</p>';// delete success  message
        unset($_SESSION['sucupd']); //delete success message after showing
    }

    if (isset($_SESSION['profupd'])){
        echo'<p class="text-green-500"> Account successvol aangepast!</p>';// delete success  message
        unset($_SESSION['profupd']); //delete success message after showing
    }

    if (isset($_SESSION['norights'])){
        echo'<p class="text-red-500"> Je hebt geen toestemming tot deze reservering!</p>';//error for user does something with sum they dont own
        unset($_SESSION['norights']); //delete success message after showing
    }

    if (isset($_SESSION['timeerr'])){
        echo'<p class="text-yellow-500"> Je hebt geen tijd meer om de reservering te veranderen!</p>';//error for when no time left
        unset($_SESSION['timeerr']); //delete success message after showing
    }?>


    <p class="text-3xl font-semibold">Welkom <?php echo $_SESSION['uservoornaam']?></p>


    <div class="grid grid-cols-2  mx-4 rounded mb-4">
        <div>
            <p class="text-2xl">User gegevens</p>
            <br>
            <p>Voornaam : <?php echo $_SESSION['uservoornaam']; ?></p>
            <p>Achternaam : <?php echo $_SESSION['userachternaam']; ?></p>
            <p>Email : <?php echo $_SESSION['useremail']; ?> </p>

            <a href="./profile/edit.php"><button class="bg-yellow-200 hover:bg-yellow-400 py-2 px-4 mt-2 rounded">Verander Gegevens</button></a>
            <a href="./profile/delete.php"><button class="bg-red-300 hover:bg-red-500 py-2 px-4 mt-2 rounded">Verwijder Account</button></a>
            <?php if ($_SESSION['IsAdmin']){?>
            <a href="./overzicht.php"><button class="bg-orange-300 hover:bg-orange-500 py-2 px-4 mt-2 rounded">Alle Reservaties [Admin]</button></a>
            <?php } ?>

        </div>
        <div>
            maybe profiel foto later?
        </div>

        <div class="col-span-2 mt-12">
            <p class="text-3xl">Mijn Reserveringen</p>
            <a href="./reserveer.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Maak Reservering</button></a>
            <p class="w-full border-b-4 mt-4"></p>

            <?php if (isset($noresult)){?>
            <p class="text-xl text-semibold">Je hebt nog geen reseravatie aangemaakt. ☹</p>
                <a href="./reserveer.php"><button class="bg-purple-300 hover:bg-purple-500 py-2 px-4 mt-2 rounded">Maak een reservatie aan</button></a>
            <?php } ?>


            <table class="border-collapse table-auto w-full text-sm">
                    <tr>
                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 ">
                            datum
                        </th>
                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 ">
                            tijd
                        </th>
                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 ">
                            info
                        </th>
                        <th class="border-b font-medium p-4 pl-8 pt-0 pb-3 ">
                            Soort taart
                        </th>
                        <th class="border-b font-medium pl-8 pt-0 pb-3 "></th>


                    </tr>
                <tbody>
                        <?php

                        if ($result = mysqli_query($db, $sql)){
                            if (mysqli_num_rows($result) < 0 ){
                                //user has no reservations
                                $noresult = "nope";
                            }

                            while($row = $result->fetch_assoc()){ //put all the user reservations down in a table
                                foreach($result as $row){
                                    if ($row['datum'] > date('Y-m-d', strtotime('-1 day'))){?>
                                        <tr>
                                            <td class="border-b border-slate-100 p-4 "> <?= $row['datum']?></td>
                                            <td class="border-b border-slate-100 p-4 "><?= $row['tijd']?></td>
                                            <td class="border-b border-slate-100 p-4 "> <?= $row['info']?></td>
                                            <td class="border-b border-slate-100 p-4 "> <?php if($row['taart'] == 1){
                                            echo "Cupcake";
                                            }elseif($row['taart'] == 2){
                                            echo "Kleine taart";
                                            }else{
                                            echo "Grote Taart";
                                            }?></td>

                                            <?php
                                            $itemdate = $row['datum']." ".$row['tijd'];//datetime from the row item to be compared by the checkdate

                                            if($itemdate > $checkdate){ ?>
                                                <td><a href="./reservering/edit.php?id=<?= $row['id']?>"><button class="bg-yellow-200 hover:bg-yellow-400 p-2 rounded mr-4">Verander Reservering</button></a><a href="./reservering/delete.php?id=<?=$row['id']?>"><button class="bg-red-300 hover:bg-red-500 p-2 rounded">Annuleer Reservering</button></a></td>
                                        </tr>
                                    <?php }} ?>
                                <?php
                                }
                            }
                        }
                        else{
                            $error_msg = "ERROR: could not execute $sql". mysqli_error($db);
                            echo $error_msg;
                        }
                        ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
