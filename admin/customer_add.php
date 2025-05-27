<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';


  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <style media="screen">
  .add_btn{
    background-color:#1c1c1c;
    color:white;
    transition:0.5s;
    border-radius:10px;
    padding:7px;
  }
  .add_btn:hover{
    border:2px solid #1c1c1c;
    background:none;
    color:#1c1c1c;
    transition:0.5s;
    border-radius:10px;
    box-shadow:2px 8px 16px gray;
  }
  .crd{
    width:500px;
  }
  </style>
  <body>
    <?php include 'header.php';?>

    <?php
      if ($_POST) {
        if (empty($_POST['customer_id']) || empty($_POST['customer_name']) || empty($_POST['customer_phone']) || empty($_POST['customer_address'])) {
          if (empty($_POST['customer_id'])) {
            $customeridError = 'Customer_Id is required';
          }
          if (empty($_POST['customer_name'])) {
            $customernameError = 'Customer_name is required';
          }
          if (empty($_POST['customer_phone'])) {
            $customerphoneError = 'Customer_phone is required';
          }
          if (empty($_POST['customer_address'])) {
            $customeraddressError = 'Customer_address is required';
          }
        }else {
          $customer_id = $_POST['customer_id'];
          $customer_name = $_POST['customer_name'];
          $customer_phone = $_POST['customer_phone'];
          $customer_address = $_POST['customer_address'];

          $stmt = $pdo->prepare("INSERT INTO customer (customer_id,customer_name,customer_phone,customer_address) VALUES (:customer_id,:customer_name,:customer_phone,:customer_address)");
          $result = $stmt->execute(
            array(':customer_id'=>$customer_id,':customer_name'=>$customer_name,':customer_phone'=>$customer_phone,':customer_address'=>$customer_address)
          );
          if ($result) {
            echo "<script>alert('Sussessfully added');window.location.href='customer.php';</script>";
          }
        }
      }

     ?>

    <div class="" style="margin-left:350px; margin-top:100px;">
      <div class="card crd">
        <div class="card-body">
          <h2>Create Page</h2>
          <form class="" action="" method="post">
            <label for="" class="mt-4"><b>Customer_Id</b></label><p style="color:red;"><?php echo empty($customeridError) ? '' : '*'.$customeridError;?></p>
            <input type="text" class="form-control" placeholder="Categories_Code" name="customer_id">

            <label for="" class="mt-4"><b>Customer_Name</b></label><p style="color:red;"><?php echo empty($customernameError) ? '' : '*'.$customernameError;?></p>
            <input type="text" class="form-control" placeholder="Categories_Name" name="customer_name">

            <label for="" class="mt-4"><b>Customer_Phone</b></label><p style="color:red;"><?php echo empty($customerphoneError) ? '' : '*'.$customerphoneError;?></p>
            <input type="number" class="form-control" placeholder="Customer_Phone" name="customer_phone">

            <label for="" class="mt-4"><b>Customer_Address</b></label><p style="color:red;"><?php echo empty($customeraddressError) ? '' : '*'.$customeraddressError;?></p>
            <input type="text" class="form-control" placeholder="Customer_Address" name="customer_address">

            <div class="d-flex">
              <button type="submit" name="button" class="add_btn form-control mt-3">Add</button>
              <a href="customer.php" style="width:450px;"><button type="button" name="button" class="add_btn form-control mt-3">Back</button></a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php include 'footer.html'; ?>
  </body>
</html>
