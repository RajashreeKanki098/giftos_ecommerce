<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['id'];
    $tquantity = $_POST['tquantity'];
    $price=$_POST['grandt'];

    foreach($_SESSION['cart'] as &$item)
    {
        if($item['id']==$product_id)
        {
            $item['quantity']=$tquantity;
            break;
        }
    }

    $sql = "UPDATE shopping_cart SET quantity = $tquantity WHERE product_id = '$product_id' AND user_id='".$_SESSION['user_id']."'";
    if(mysqli_query($conn,$sql))
    {
        header('Location: add_to_cart.php'); 
    }   
}

?>

