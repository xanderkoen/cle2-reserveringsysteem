<?php
session_start();
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
<div class="bg-slate-50 p-4 border-b shadow-lg flex flex-start justify-between items-center">
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
<?php if (isset($_GET["bye"])){?>
    <p class="w-full text-3xl text-center bg-pink-200">Sorry om te zien dat je weggaat 👋</p>
    <p class="w-full text-xl text-center bg-pink-200">Je account samen met je reservaties zijn successvol verwijderd.</p>
<?php } ?>

<div class="grid grid-cols-3 gap-4 place-content-center place-items-center mt-4 mx-4">

    <div class="col-span-1 rounded">
        <img src="../images/pink-cake.jpg" alt="Roze taart" width="360" height="360" class="rounded shadow-lg">
    </div>

    <div class="place-self-center col-span-2 text-left rounded">
        <p class="font-semibold text-3xl mb-2">Beste taartjes in Spijkenisse</p>
        <p class="text-xl">Mijn naam is Monique en ik maak en decoreer taarten als hobby. Daarom heb ik besloten om een eigen bedrijfje te starten genaamd MijnEigenTaartjes.nl</p>
    </div>

    <div class="rounded text-right mt-24">
        <p class="font-semibold text-3xl mb-2">Reserveer afspraak</p>
        <p class="text-xl">Reserveer hier een afspraak moment voor uw volgende taart</p>
    </div>

    <div class="rounded flex flex-col mt-24">
        <?php if(isset($_SESSION['userid']) && isset($_SESSION['uservoornaam'])){
            //user is logged in and can make a appointment
            echo '<a href="./reserveer.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 rounded">Reserveer</button></a>';
        }else{
            //user is not logged and and will be reffered to the login page
            echo "<p class='contents'>Je moet een account hebben om een afspraak te maken.</p>";
            echo '<a href="./login.php"><button class="bg-purple-200 hover:bg-purple-400 py-2 px-4 mt-2 sm:ml-80 rounded">Login</button></a>';
        } ?>
    </div>

    <div class="col-span-1 rounded mt-24">
        <img src="../images/bakey-cakey.jpg" height="360" width="360" class="rounded shadow-lg">
    </div>


</div>
</body>
</html>
