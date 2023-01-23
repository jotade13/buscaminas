<?php session_start();
if(isset($_SESSION["matriz"]))
{
    unset($_SESSION["matriz"]);
    unset($_SESSION["numero"]);
    unset($_SESSION["minaAlrededor"]);
    unset($_SESSION["mina"]);
    unset($_SESSION["primer"]);
}
header("location:buscaminas.php");
die();
?>