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
    $customerstmt = $pdo->prepare("SELECT DISTINCT customer_id FROM receivable");
    $customerstmt->execute();
    $customerdata = $customerstmt->fetchAll();
 ?>

 <!-- <form class="" action="" method="post">
   <div class="d-flex" style="margin-left:950px; margin-top:-15px;">
     <input type="date" name="" value="" class="form-control" placeholder="Search Supplier_Name" style="width:200px;">
     <button type="submit" name="search" class="search_btn ms-3">Search</button>
  </div>
 </form> -->

<div class="container">
  <h4 style="margin-top:-17px;"><b>Account Receivable</b></h4>
  <div class="outer" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Customer Name</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Balance</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($customerdata) {
            $id = 1;
            foreach ($customerdata as $value) {
              $customer_id = $value['customer_id'];

              // Customer Name
              $customerstmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
              $customerstmt->execute();
              $customer = $customerstmt->fetch(PDO::FETCH_ASSOC);

              // Total Receivable Amount
              $total_amtstmt = $pdo->prepare("SELECT SUM(amount) AS total_amt FROM receivable WHERE customer_id='$customer_id'");
              $total_amtstmt->execute();
              $total_amtdata = $total_amtstmt->fetch(PDO::FETCH_ASSOC);
              
              // Total Paid Amount
              $total_paidstmt = $pdo->prepare("SELECT SUM(paid) AS total_paid FROM receivable WHERE customer_id='$customer_id'");
              $total_paidstmt->execute();
              $total_paiddata = $total_paidstmt->fetch(PDO::FETCH_ASSOC);

              $balance = $total_amtdata['total_amt'] - $total_paiddata['total_paid'];
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $customer['customer_name'];?></td>
          <td><?php echo $total_amtdata['total_amt'];?></td>
          <td><?php echo $total_paiddata['total_paid'];?></td>
          <td><?php echo $balance;?></td>
          <td>
            <a href="account_receivable_detail.php?customer_id=<?php echo $value['customer_id'];?>"><button>View Detail</button></a>
          </td>
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
