<?php
session_start();

unset($_SESSION['userid']);
unset($_SESSION['uservoornaam']);
unset($_SESSION['userachternaam']);
unset($_SESSION['useremail']);
unset($_SESSION['userwachtwoord']);
unset($_SESSION['IsAdmin']);

header('Location: ./home.php');
exit;

?>