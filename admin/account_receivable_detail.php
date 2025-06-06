<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
  ?>

  <?php include 'header.php'; ?>

<style media="screen">
.outer {
overflow-y: auto;
height: 300px;
}

.outer{
width: 100%;
-layout: fixed;
}

.outer th {
text-align: left;
top: 0;
position: sticky;
background-color: white;
}
.search_btn{
  background-color:#1c1c1c;
  color:white;
  transition:0.5s;
  border-radius:10px;
  padding:7px;
  padding:-29px;
  font-size:13px;
}
.search_btn:hover{
  border:2px solid #1c1c1c;
  background:none;
  color:#1c1c1c;
  transition:0.5s;
  border-radius:10px;
  box-shadow:2px 8px 16px gray;
}
</style>


<?php
    $customer_id = $_GET['customer_id'];
    $receivablestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id'");
    $receivablestmt->execute();
    $receivabledata = $receivablestmt->fetchAll();

    // Customer Name
    $customerstmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
    $customerstmt->execute();
    $customer = $customerstmt->fetch(PDO::FETCH_ASSOC);
 ?>

 <!-- <form class="" action="" method="post">
   <div class="d-flex" style="margin-left:950px; margin-top:-15px;">
     <input type="date" name="" value="" class="form-control" placeholder="Search Supplier_Name" style="width:200px;">
     <button type="submit" name="search" class="search_btn ms-3">Search</button>
  </div>
 </form> -->

<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-11"><b>Account Receivable ( <?php echo $customer['customer_name']; ?> )</b></h4>
    <a href="account_receivable.php"><button class="">Back</button></a>
  </div>
  <div class="outer" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th style="width: 10px">No</th>
          <th>Date</th>
          <th>Vr_No</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($receivabledata) {
            $id = 1;
            foreach ($receivabledata as $value) {
              $customer_id = $value['customer_id'];

              $customerstmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
              $customerstmt->execute();
              $customer = $customerstmt->fetch(PDO::FETCH_ASSOC);
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $value['date'];?></td>
          <td><?php echo $value['vr_no'];?></td>
          <td><?php echo $value['amount'];?></td>
          <td><?php echo $value['paid'];?></td>
          <td><?php echo $value['balance'];?></td>
        </tr>
        <?php
          $id++;
            }
          }
         ?>
      </tbody>
    </table>
  </div>
</div>
  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  <!-- <br><br><br><br><br><br><br><br><br><br><br> -->
  <!-- <br><br><br><br><br><br><br><br><br><br><br> -->


  <?php include 'footer.html'; ?>
