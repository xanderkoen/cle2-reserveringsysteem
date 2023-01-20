<?php
require_once 'C:\xampp\htdocs\cle2\backend\connect.php';
session_start();

//retrieve data from id in link
$link = $_GET['id'];
$userid = $_SESSION['userid'];
$sql = "SELECT * FROM reserveringen WHERE id = '$link'";

//starting mandatory checks if the user is allowed to be on this page

//logged in
if (!isset($_SESSION['uservoornaam']) && !isset($_SESSION['userid'])){
    header("Location: .././profile.php"); // if user data is  not present (user is logged out) redirect back to login
}

//PSEUDO reservering EDIT

//wacht op edit input
//check de datum op illegale tijden
    //check success
        //check reservering op illegale datum
            //check success
                //check voor mogelijke links in de extra info tab
                    //check success (geen links)
                        //update de gegevens in de database
                        //redirect user terug naar profiel pagina
                    //check gefaald (wel links)
                        //geef error message
            //check gefaald
                //geef error message
    //check gefaald
        //geef error message

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

    if (isset($_POST['update'])){ //wait for the update input

        //retrieve all the values
        $tijd = $_POST['time'];
        $datum = $_POST['date'];
        $info = mysqli_real_escape_string($db, $_POST['extra']);
        $soort = $_POST['soort'];

        //dubble check time/date before updating
        if ($itemdate < $checkdate){
            $_SESSION['timeerr'] = 'no time';
        }else{
            //check for links in info

            if (str_contains($info, "http") || str_contains($info, "https") || str_contains($info, "www.")){
                $nolinks = "neen";
            }else{
                //user has time left and no links so they can modify the item

                try {

                    $update = "UPDATE reserveringen SET datum='$datum', tijd='$tijd', info='$info', taart='$soort' WHERE id = '$itemid'";

                    if ($updresult = mysqli_query($db, $update)){
                        //item successfully updated

                        $_SESSION['sucupd'] = "success updatde";
                        header("Location: .././profile.php");
                    }

                }catch(exception $e){
                    echo $e;
                }
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
                <button class="dropbtn"><?php echo htmlspecialchars($_SESSION['uservoornaam'])?>
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

<!--HTML HERE-->

<div class="mx-24 mt-24 text-center">
    <p class="text-3xl text-semibold mb-12">Afspraak aanpassen</p>

    <form action="./edit.php?id=<?= $link ?>" method="post">
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
                    <td class="p-4"><input type="date" name="date" id="date" min="<?= date("Y-m-d", strtotime("+1 day"))?>"  value="<?= $row['datum']?>" class="border rounded p-2" required></td>
                    <td class="p-4"><input type="time" name="time" id="time" value="<?= $row['tijd']?>" min="09:00" max="17:00" class="border rounded p-2" required></td>
                    <td class="p-4 ">
                        <?php if (isset($nolinks)){?>
                            <p class="text-red-500">Links zijn niet toegestaan.</p><?php
                            unset($nolinks);
                        } ?>
                        <textarea cols="40" rows="3" class="resize-none border rounded" name="extra" id="extra"><?= $row['info']?></textarea></td>
                    <td class="p-4">
                        <select name="soort" id="soort" class="border rounded p-4" required>
                        <?php if($row['taart'] == 1){?>
                            <option value="1" selected>Cupcakes</option>
                            <option value="2">Kleine taart</option>
                            <option value="3">Grote taart</option><?php
                        }elseif($row['taart'] == 2){?>
                            <option value="1">Cupcakes</option>
                            <option value="2" selected>Kleine taart</option>
                            <option value="3">Grote taart</option><?php
                        }else{?>
                            <option value="1">Cupcakes</option>
                            <option value="2">Kleine taart</option>
                            <option value="3" selected>Grote taart</option><?php
                        }?></select>
                    </td>
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
        <input type="submit" class="bg-green-300 hover:bg-green-500 py-2 px-4 mt-12 rounded" name="update" value="Sla op"/>
    </form>
    <a href="../profile.php" type="post"><button class="bg-purple-200 hover:bg-purple-500 py-2 px-4 mt-12 rounded">Keer terug</button></a>

</div>

</body>
</html>
