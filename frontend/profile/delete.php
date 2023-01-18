<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//vars
$userid = $_SESSION['userid'];

if (isset($_POST['delete'])){//wait for post
    //control check word
    if ($_POST['check'] == "verwijder"){
        //control word matches

        // delete every reservation made by this user
        try {
            $sql = "DELETE FROM reservering WHERE klant_id = '$userid'";

            if ($result = mysqli_query($db, $sql)){
                //delete user

                try {
                    $sql2 = "DELETE FROM klant WHERE id = '$userid'";

                    if ($result2 = mysqli_query($db, $sql2)){
                        // clear/terminate sessiom
                        unset($_SESSION['uservoornaam']);
                        unset($_SESSION['userid']);
                        unset($_SESSION['userachternaam']);
                        unset($_SESSION['useremail']);
                        unset($_SESSION['userwachtwoord']);
                        if (isset($_SESSION['IsAdmin'])){
                            unset($_SESSION['IsAdmin']);
                        }
                        session_unset();
                        session_destroy();

                        // redirect with message
                        header("Location: ../home.php?bye='1'");
                    }
                }catch(exception $e){
                    echo $e;
                }
            }
        }catch(exception $e){
            echo $e;
        }
    }else{
        $_SESSION['controlerr'] = "nah";
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

<div class="mx-24 my-36 text-center rounded justify-items-center">
    <p class="text-3xl font-bold">Weet je zeker dat je je account wilt deleten?</p>
    <p>type "verwijder" in en druk dan op de verwijder knop om je aacount echt te verwijderen.</p>

    <form action="../profile/delete.php" method="post">
        <input class="border rounded p-4 m-4 text-center" name="check" id="check" placeholder="controlewoord">
        <br>

        <input type="submit" class="bg-rose-700 hover:bg-rose-900 py-2 px-4 mt-4 rounded" name="delete" id="delete" value="Ja verwijder permanent mijn acount."/>
    </form>
    <a href="../profile.php"><button class="bg-purple-600 hover:bg-purple-800 py-2 px-4 mt-2 rounded">Keer terug</button></a>

    <?php if (isset($_SESSION['controlerr'])){?>
        <p class="text-red-500 text-2xl mt-4 text-center"> Controle woord komt niet overeen.</p>
    <?php } unset($_SESSION['controlerr']); ?>


</div>


</body>
</html>
