<?php
//session_start();
include('connection.php');

$item_count = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item']))
{
    $item_id = $_POST['item_id'];

    
    $item_exists_in_session = false;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $cart_item) 
        {
            if ($cart_item['id'] == $item_id) 
            {
                $item_exists_in_session= true;
                break;
            }
        }
    } else 
    {
        $_SESSION['cart'] = []; 
    }

    $item_exists_in_db = false;
    if (isset($_SESSION['login'])) 
    {
        $sql_check_existing = "SELECT * FROM shopping_cart WHERE product_id='$item_id' AND user_id='".$_SESSION['user_id']."'";
        $result_check_existing = mysqli_query($conn, $sql_check_existing);
        if (mysqli_num_rows($result_check_existing) > 0) 
        {
            $item_exists_in_db = true;
        }
    }

    if (!$item_exists_in_session && !$item_exists_in_db) 
    {
        $_SESSION['cart'][] = array('id' => $item_id);

        $sql_price = "SELECT price FROM product_tbl WHERE product_id='$item_id'";
        $result_price = mysqli_query($conn, $sql_price);
        $row_price = mysqli_fetch_assoc($result_price);
        $price = $row_price['price'];

        if (isset($_SESSION['login'])) 
        {
            // $user_id = $_SESSION['user_id'];
            $sql_insert = "INSERT INTO shopping_cart (product_id, user_id, price, quantity) VALUES ('$item_id', '".$_SESSION['user_id']."', '$price', '1')";
            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Product added to cart')</script>";
            } else 
            {
                echo "<script>alert('Failed to add product to cart')</script>";
            }
        } else {
            echo "<script>alert('Product added to cart')</script>";
        }
    } else {
        echo "<script>alert('Product is already in the cart!')</script>";
    }
    echo "<script>window.location.href='index.php'</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) 
{
    $remove_id = $_POST['item_id'];

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $cart_item) {
            if ($cart_item['id'] == $remove_id) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
    if (isset($_SESSION['login'])) {
        $user_id = $_SESSION['user_id'];
        $sql_delete = "DELETE FROM shopping_cart WHERE product_id = '$remove_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $sql_delete)) {
            echo "<script>alert('Selected item has been deleted')</script>";
        } else {
            echo "<script>alert('Failed to delete selected item')</script>";
        }
    }
    echo "<script>window.location.href='index.php'</script>";
}

if (isset($_SESSION['login']) && isset($_SESSION['cart'])) 
{
    $user_id = $_SESSION['user_id'];
    foreach ($_SESSION['cart'] as $cart_item) {
        $item_id = $cart_item['id'];

        $sql_check_existing = "SELECT * FROM shopping_cart WHERE product_id='$item_id' AND user_id='$user_id'";
        $result_check_existing = mysqli_query($conn, $sql_check_existing);
        if (mysqli_num_rows($result_check_existing) == 0) 
        {
            $sql_price = "SELECT price FROM product_tbl WHERE product_id='$item_id'";
            $result_price = mysqli_query($conn, $sql_price);
            $row_price = mysqli_fetch_assoc($result_price);
            $price = $row_price['price'];

            $sql_insert = "INSERT INTO shopping_cart (product_id, user_id, price, quantity) VALUES ('$item_id', '$user_id', '$price', '1')";
            mysqli_query($conn, $sql_insert);
        }
    }
    unset($_SESSION['cart']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="image/favicon.png" type="image/x-icon">
    <title>Giftos</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
</head>
<style>
.table {
    margin-top: 50px;
    border: none;
}
.table td {
    vertical-align: middle;
    border: none;
}
.table img {
    max-width: 100px;
    height: auto;
    display: block;
    margin: auto;
}
.btn-remove {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}
.btn-remove:hover {
    background-color: #ff1a1a;
}
.container1{
    background: #f4f8ff;
    margin-left: 20px;
    margin-bottom: 20px;
}
.btn-box {
    display: inline-block;
    padding: 10px 40px;
    background-color: #f16179;
    color: #ffffff;
    border: 1px solid #f16179;
    border-radius: 5px;
    -webkit-transition: all .3s;
    transition: all .3s;
    margin-left: 12px;
}
</style>
<body>
  <div class="hero_area">
    <header class="header_section">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php">
          <span>Giftos</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav  ">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
          </ul>
          <div class="user_option">
            <a href="login.php">
              <i class="fa fa-user" aria-hidden="true"></i>
              <span>Login</span>
            </a>
            <a href="add_to_cart.php">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>
              <?php
              if($item_count!=0)
              {
                echo $item_count;
              }
              ?>
            </a>
          </div>
        </div>
      </nav>
    </header>
  </div>
  <div class="row">
    <div class="container col-6">
      <table class="table table-bordered">
        <tbody>
          <?php
          $total = 0;

          function displayCartItem($conn, $product_id, $quantity)
           {
              global $total, $item_count;

              $sql_product = "SELECT * FROM product_tbl WHERE product_id='$product_id'";
              $result_product = mysqli_query($conn, $sql_product);
              $row_product = mysqli_fetch_assoc($result_product);

              $price = $row_product['price']; 
              $item_total = $price * $quantity;
              $total += $item_total;
              $item_count++;

              ?>
              <tr>
                  <td>
                      <a href='product_detail.php?id=<?php echo $product_id; ?>'>
                          <img src='image/<?php echo $row_product['image']; ?>' alt='<?php echo $row_product['name']; ?>'>
                      </a>
                  </td>
                  <td>
                      <?php echo $row_product['name']; ?> <br>
                      ₹<?php echo $price; ?><br><br>
                      <input type="hidden" class="form-control col-2 tprice" name="price" value="<?php echo $price; ?>">
                      <input type="hidden" class="form-control col-2 product_id" name="product_id" value="<?php echo $product_id; ?>">
                      <input type="number" class="form-control col-2 tquantity" name="quantity" value="<?php echo $quantity; ?>" onchange="ttotal()">
                      <p class="itotal"></p>
                  </td>
                  <td>
                      <form method="post">
                          <input type="hidden" name="item_id" value="<?php echo $product_id; ?>">
                          <button type="submit" name="remove_item" class="btn btn-remove">Remove</button>
                      </form>
                  </td>
              </tr>
              <?php
          }

          if (isset($_SESSION['cart'])) 
          {
              foreach ($_SESSION['cart'] as $cart_item) 
              {
                  $product_id = $cart_item['id'];
                  $quantity = 1; 
                  displayCartItem($conn, $product_id, $quantity);
              }
          }

          if (isset($_SESSION['login'])) 
          {
              $user_id = $_SESSION['user_id'];
              $sql_cart_items = "SELECT * FROM shopping_cart WHERE user_id='$user_id'";
              $result_cart_items = mysqli_query($conn, $sql_cart_items);
              while ($row_cart_item = mysqli_fetch_assoc($result_cart_items)) 
              {
                  $product_id = $row_cart_item['product_id'];
                  $quantity = $row_cart_item['quantity']; 
                  displayCartItem($conn, $product_id, $quantity);
              }
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="container col-4">
      <table class="table">
        <tbody>
          <h3>Price Details</h3>
          <?php
          echo "Total Item " . $item_count . "<br>";
          echo "Price ₹" . $total . "<br><br>";
          ?>
          <hr>
          <p class="itotal" id="gttl"></p>
          <form method="post" action="add_to_pay.php">
            <button type="submit" class="btn btn-box" name="add_pay">Proceed to Pay</button>
          </form>
        </tbody>
      </table>
    </div>
  </div>
  <section class="info_section  layout_padding2-top">
    <div class="social_container">
      <div class="social_box">
        <a href="">
          <i class="fa fa-facebook" aria-hidden="true"></i>
        </a>
        <a href="">
          <i class="fa fa-twitter" aria-hidden="true"></i>
        </a>
        <a href="">
          <i class="fa fa-instagram" aria-hidden="true"></i>
        </a>
        <a href="">
          <i class="fa fa-youtube" aria-hidden="true"></i>
        </a>
      </div>
    </div>
    <div class="info_container ">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-3">
            <h6>
              ABOUT US
            </h6>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
            </p>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="info_form ">
              <h5>
                Newsletter
              </h5>
              <form action="#">
                <input type="email" placeholder="Enter your email">
                <button>
                  Subscribe
                </button>
              </form>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>
              NEED HELP
            </h6>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet, consectetur adipiscing elit, sed doLorem ipsum dolor sit amet,
            </p>
          </div>
          <div class="col-md-6 col-lg-3">
            <h6>
              CONTACT US
            </h6>
            <div class="info_link-box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span> Gb road 123 london Uk </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>+01 12345678901</span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span> demo@gmail.com</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer section -->
    <footer class=" footer_section">
      <div class="container">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="https://html.design/">Free Html Templates</a>
        </p>
      </div>
    </footer>
    <!-- footer section -->

  </section>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="js/custom.js"></script>
  <script>
      var grandt = 0;
      var iprice = document.getElementsByClassName("tprice");
      var iquantity = document.getElementsByClassName("tquantity");
      var itotal = document.getElementsByClassName("itotal");
      var gttl = document.getElementById("gttl");

      function ttotal() {
          grandt = 0;
          for (var i = 0; i < iprice.length; i++) {
              var price = parseFloat(iprice[i].value);
              var quantity = parseInt(iquantity[i].value);
              grandt += price * quantity;
          }
          gttl.innerHTML = " <p style='font-size:20px;'>Total Amount</p> ₹" + grandt;
      }
      ttotal();

      $(document).ready(function () {
          $('.tquantity').on('change', function () {
              var tquantity = $(this).val();
              var product_id = $(this).siblings('.product_id').val();

              $.ajax({
                  url: 'update_cart.php',
                  method: 'POST',
                  data: { id: product_id, tquantity: tquantity },
                  success: function (response) {
                      ttotal();
                  },
                  error: function () {
                      alert('Failed to update quantity.');
                  }
              });
          });
      });
  </script>
</body>
</html>
