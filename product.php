<?php
include('connection.php');
$msg="";
if(isset($_POST['save_data']))
{
    $name=$_POST['name'];
    $description=$_POST['description'];
    $price=$_POST['price']; 
    $image_nm=$_FILES['image']['name'];
    $image_type=$_FILES['image']['type'];
    $image_size=$_FILES['image']['size'];
    $image_loc=$_FILES['image']['tmp_name'];
    $store="image/".$image_nm;
    date_default_timezone_set('Asia/Kolkata');
    $date = date('y-m-d');
    $sql="insert into product_tbl (name,description,price,image,date) values ('$name','$description','$price','$image_nm','$date')";
    if(mysqli_query($conn,$sql))
    {
        if(move_uploaded_file($image_loc,$store))
        {
        echo "<script>alert('added');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

  <title>
    Giftos
  </title>
    
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
<header class="header_section">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.html">
          <span>
            Giftos
          </span>
        </a>
      </nav>
</header>
  <div class="container" style="border: 2px solid black; padding: 30px 30px; ">
    <div class="col-12 md-6">
        <form class="col-lg-6 offset-lg-3" method="post" enctype="multipart/form-data">
            <h1>Product</h1>
            <div class="form-group">
                <label for="exampleInputEmail1"> Name</label>
                <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter product name" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Description</label>
                <textarea rows="10" cols="150" id="my_summernote" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Price</label>
                <input type="text" class="form-control" name="price" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Price" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Upload Image</label>
                <input type="file" class="form-control" name="image" id="exampleInputEmail1" aria-describedby="emailHelp" required>
            </div>            
            <button type="submit" class="btn btn-primary" name="save_data">Submit</button>
        </form> 
    </div>
  </div>
  
    <!-- footer section -->
  <section  class="info_section" style="margin-top:20%;">
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

  <!-- end info section -->


  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <script src="js/custom.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#my_summernote").summernote();
        $('.dropdown-toggle').dropdown();
    });
    </script>
</body>

</html>