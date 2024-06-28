<?php
session_start();
echo $_SESSION['login'];
if (isset($_POST['add_pay']))
{
    if(empty($_SESSION['login']))
    {
        echo "<script>alert('login first');</script>";
        echo "<script>window.location.href='login.php';</script>";
        exit();
    }else{
        echo "<script>alert('Order Placed');</script>";
        echo "<script>window.location.href='index.php';</script>";
    }
}
?>