<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//retrieve data from id in link
$link = $_GET['id'];
$sql = "SELECT * FROM reserveringen WHERE id = '$link'";

//PSEUDO reservering DELETE

//wacht op delete input
    //check de datum of er nog genoeg tijd is om de afspraak te cancellen
        //check success
            //delete de reservering van de database
            //redirect terug naar profiel pagina
        //check gefaald
            //geef error message



//starting mandatory checks if the user is allowed to be on this page

//logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: .././profile.php"); // if user data is  not present (user is logged out) redirect back to login
}

//if user owns reservation
if ($result = mysqli_query($db, $sql)){
    $klantid = "";
    $itemdate = "";
    $itemid = "";

    foreach($result as $row){
        $klantid = $row['klant_id'];
        $itemid = $row['id'];
        $itemdate = $row['datum']." ".$row['tijd'];
    }

    if ($klantid != $_SESSION['userid']){ //if user_id is not the same as the user_id in reservation send back to profile
        $_SESSION['norights'] = "nah bro";
        header("Location: .././profile.php");
    }

    //item date checks
    $checkdate = date('Y-m-d H:i:s', strtotime('+1 day 1 hour'));

    if ($itemdate < $checkdate){
        $_SESSION['timeerr'] = 'no time';
        header("Location: .././profile.php");
    }

    if (isset($_POST['delete'])){ //wait for the delete input
        //dubble check time/date before deleting

        if ($itemdate < $checkdate){
            $_SESSION['timeerr'] = 'no time';
            header("Location: .././profile.php");
        }else{
            //user has time left so they can modify item

            $del = "DELETE FROM reserveringen WHERE id = '$itemid' ";

            if ($delresult = mysqli_query($db, $del)){
                //item successfully deleted

                $_SESSION['sucdel'] = "success delete";
                header("Location: .././profile.php");
            }
            else{
                $error_msg = "ERROR: could not execute $del". mysqli_error($db);
                echo $error_msg;
            }
        }
    }


}
else{
    $error_msg = "ERROR: could not execute $sql". mysqli_error($db);
    echo $error_msg;
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

<!--HTML HERE-->

<div class="mx-24 mt-24 text-center">
    <p class="text-3xl text-semibold mb-12">Weet je zeker dat je deze afspraak wilt annuleren?</p>

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

    foreach($result as $row){?>
    <tr>
        <td class="p-4 "> <?= htmlspecialchars($row['datum']);?></td>
        <td class="p-4 "><?= htmlspecialchars($row['tijd']);?></td>
        <td class="p-4 "> <?= htmlspecialchars($row['info']);?></td>
        <td class="p-4 "> <?php if($row['taart'] == 1){
                echo "Cupcake";
            }elseif($row['taart'] == 2){
                echo "Kleine taart";
            }else{
                echo "Grote Taart";
            }?></td>
    </tr>
    <?php } ?>

<?php
}
else{
$error_msg = "ERROR: could not execute $sql". mysqli_error($db);
echo $error_msg;
} ?>
    </tbody>
    </table>

    <form action="./delete.php?id=<?= $link ?>" method="post">
        <input type="submit" class="bg-red-300 hover:bg-red-500 py-2 px-4 mt-12 rounded" name="delete" value="Ja annuleer mijn afspraak"/>
    </form>
    <a href="../profile.php" type="post"><button class="bg-green-400 hover:bg-green-600 py-2 px-4 mt-12 rounded">Nee behoud mijn afspraak</button></a>


</div>

</body>
</html>
