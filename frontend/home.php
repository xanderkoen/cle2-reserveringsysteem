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
    <title>Document</title>
</head>
<body>
<div class="bg-slate-50 p-4 flex flex-start justify-between items-center">
    <a href="home.php"><p class="text-3xl border-solid border-r pr-4">MijnEigentaartjes</p></a>
    <div class="flex flex-row w-max self-center">
        <a href="gallerij.php"><p class="px-4 hover:underline">Gallerij</p></a>
        <a href="contact.php" ><p class="px-4 hover:underline">Contact</p></a>
        <a href="about.php"><p class="px-4 hover:underline">Over ons</p></a>
    </div>
    <div class="flex flex-row">
        <?php if ($_SESSION['userid'] != "" && $_SESSION['uservoornaam'] != ""){
            echo "<select>
                    <option>mijn profiel</option>";

            if($_SESSION['IsAdmin'] == 1){
                echo "<option>reserverings overzicht</option>";
            }
                    //TODO logout function
            echo "<option>log uit</option></select>";
        }else{
           echo '<a href="login.php"><p class="mx-4 hover:underline">login</p></a>
        <a href="register.php"><p class="mx-4 hover:underline">Register</p></a>';
        } ?>
    </div>
</div>


<div>
    <p>nice nicen nice</p>

    <p>Font-sans - FONT SANS - font sans</p>
    <h1 class="text-2xl">Header 1</h1>
    <h2 class="text-xl">Header 2</h2>
    <h3 class="text-lg">Header 3</h3>
    <h4 class="text-base">Header 4</h4>
    <p>

    </p>

    <p clas="font-thin">font thin</p>
    <p class="font-extralight">font extralight</p>
    <p class="font-light">font light</p>
    <p class="font-normal">font normal</p>
    <p class="font-medium">font medium</p>
    <p class="font-semibold">font semibold</p>
    <p class="font-bold">font bold</p>
    <p class="font-extrabold">font-extrabold</p>
    <p class="italic">Italic text sentence.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
</div>
</body>
</html>
