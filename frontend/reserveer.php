<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

if (isset($_POST['redirect'])){
    header("Location: ./profile.php". $_POST['successmsg'."yippie"]);

}



if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: ./login.php"); // if user data is  not present (user is logged out) redirect back to login
}

//first we check if user submits the form
if(isset($_POST["reserveer"])){
    //check if user forgot to select a cake size
    if ($_POST['soort'] == "0"){
        $soortmsg = "Kies een optie.";
    }else{
        //retrieve all values
        $date = $_POST['date'];
        $time = $_POST['time'];
        $soort = $_POST['soort'];
        $userid = $_SESSION['userid'];

        //check if message is written then post to database with or without the extra message
        if (strlen($_POST['extra']) > 0){
            //message is filled
            $extra = $_POST['extra'];
            $sql = "INSERT INTO reservering (id, datum, tijd, info, taart, klant_id) VALUES ('', '$date', '$time', '$extra', '$soort', '$userid')";

            if (mysqli_query($db, $sql)){
                echo "reservering successvol aangemaakt";
            }else{
                $error_msg = "ERROR: could not execute $sql". mysqli_error($db);
            }
        }else{
            //message is empty
            $sql = "INSERT INTO reservering (id, datum, tijd, info, taart, klant_id) VALUES ('', '$date', '$time', '', '$soort', '$userid')";

            if (mysqli_query($db, $sql)){
                echo "reservering successvol aangemaakt";
            }else{
                $error_msg = "ERROR: could not execute $sql". mysqli_error($db);
            }
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
                    <a href="./profile.php">Mijn profiel</a>
                    <?php if($_SESSION['IsAdmin'] == 1){
                        echo '<a href="#">Reservering overzicht</a>';}?>
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
    <p class="text-3xl">Reserveer afspraak</p>

    <form action="./reserveer.php" method="post">
        <p class="text-xl mt-4">datum</p>
        <input type="date" name="date" id="date" required>

        <p class="text-xl mt-4">tijd</p>
        <input type="time" name="time" id="time" required>

        <p class="text-xl mt-4">Soort taart</p>
        <?php if (isset($soortmsg)){
            echo "<p class='text-red-500'>$soortmsg</p>";
        } ?>
        <select name="soort" id="soort" required>
            <option selected value="0">Kies een optie</option>
            <option value="1">Cupcakes</option>
            <option value="2">Kleine taart</option>
            <option value="3">Grote taart</option>
        </select>

        <p class="text-xl mt-4">Extra informatie</p>
        <p class="txt-xs">Als je geen extra commentaar hebt mag je dit leeglaten</p>
        <textarea cols="40" rows="3" class="resize-none" name="extra" id="extra"></textarea>
        <br>
        <input type="submit" class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded" name="reserveer" value="reserveer">
        <input type="submit" class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded" name="redirect" value="redirect">

    </form>
</div>


</body>
</html>
