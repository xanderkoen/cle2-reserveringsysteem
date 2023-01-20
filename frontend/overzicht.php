<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//variables
$date = date("Y-m-d");

//mandatory checks
//user logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
header("Location: ./login.php");} // if user data is  not present (user is logged out) redirect back to login
//user is admin
if ($_SESSION['IsAdmin'] != 1){
    header("Location: ./home.php");
}

//PSEUDO admin index reservering
    //haal alle reservaties op voor vandaag en in de toekomst

//retrieve reservatie count
try {
    $countsql = "SELECT id FROM reserveringen WHERE datum = '$date'";

    if($today = mysqli_query($db, $countsql)){
        $reserveringtoday = mysqli_num_rows($today);
    }
}catch(exception $e){
    echo $e;
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
<div class="bg-slate-50 border-b shadow-lg p-4 flex flex-start justify-between items-center">
    <a href="home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex">
        <?php if (isset($_SESSION['userid']) && isset($_SESSION['uservoornaam'])){?>
            <div class="dropdown flex">
                <button class="dropbtn"><?php echo htmlspecialchars($_SESSION['uservoornaam']); ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="./profile.php">Mijn profiel</a>
                    <?php if($_SESSION['IsAdmin'] == 1){?>
                        <a href="./overzicht.php" style="background-color: #ddd;">Reservering overzicht</a><?php } ?>
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
<div class="bg-slate-50 shadow-lg rounded-lg mt-4 mx-24 text-center">
    <p class="text-3xl text-semibold">Reserverings overzicht</p>
    <?php if (isset($_SESSION['admin'])){?>
    <p class="text-green-500">Reservering successvol geannuleerd.</p><?php
        unset($_SESSION['admin']);
    }?>
    <p>Je hebt <?= $reserveringtoday?> reservaties vandaag</p>
    <p class="mb-4">druk op de knop voor de reservering voor meer informatie</p>

    <table class="border-collapse table-auto w-full text-sm">
        <tr>
            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3">voornaam</th>
            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3">achternaam</th>
            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3">datum</th>
            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3">tijd</th>
            <th></th>
        </tr>
        <?php
            //retrieve data for page
            try {
            $sql = "SELECT reserveringen.id, datum, tijd, klanten.voornaam, klanten.achternaam FROM reserveringen INNER JOIN klanten ON reserveringen.klant_id = klanten.id ORDER BY reserveringen.datum ASC, reserveringen.tijd ASC;";

            if ($result = mysqli_query($db, $sql)){
                while ($row = $result->fetch_assoc()){
                    if ($row['datum'] >= date("Y-m-d")){ // filter out all the reservations from the past?>
                        <tr>
                            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3"><?= htmlspecialchars($row['voornaam']); ?></th>
                            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3"><?= htmlspecialchars($row['achternaam']); ?></th>
                            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3"><?= htmlspecialchars($row['datum']);?></th>
                            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3"><?= htmlspecialchars($row['tijd']);?></th>
                            <th class="border-b font-medium p-4 pl-8 pt-0 pb-3"><a href="../frontend/reservering/reservering.php?id=<?= $row['id']?>"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Meer info</button></a></th>
                        </tr>
                        <?php
                    }
                }
            }
            }catch(exception $e){
            echo $e;
            }?>
    </table>

</div>


</body>
</html>
