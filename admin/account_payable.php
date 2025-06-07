<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
  
  $supplierstmt = $pdo->prepare("SELECT DISTINCT supplier_id FROM payable");
  $supplierstmt->execute();
  $supplierdata = $supplierstmt->fetchAll();
 ?>
<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-10 me-5"><b>Account Payable</b></h4>
    <!-- <button class="ms-3">Paid Amount</button> -->
  </div>
  <div class="outer" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th style="width: 10px">No</th>
          <th>Supplier_Name</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Balance</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($supplierdata) {
            $id = 1;
            foreach ($supplierdata as $value) {
              $supplier_id = $value['supplier_id'];

              // Supplier Name
              $supplierIdstmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
              $supplierIdstmt->execute();
              $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);

              // Total Receivable Amount
              $total_amtstmt = $pdo->prepare("SELECT SUM(amount) AS total_amt FROM payable WHERE supplier_id='$supplier_id'");
              $total_amtstmt->execute();
              $total_amtdata = $total_amtstmt->fetch(PDO::FETCH_ASSOC);
              
              // Total Paid Amount
              $total_paidstmt = $pdo->prepare("SELECT SUM(paid) AS total_paid FROM payable WHERE supplier_id='$supplier_id'");
              $total_paidstmt->execute();
              $total_paiddata = $total_paidstmt->fetch(PDO::FETCH_ASSOC);

              $balance = $total_amtdata['total_amt'] - $total_paiddata['total_paid'];
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $supplierIdResult['supplier_name'];?></td>
          <td><?php echo $total_amtdata['total_amt'];?></td>
          <td><?php echo $total_paiddata['total_paid'];?></td>
          <td><?php echo $balance;?></td>
          <td>
            <a href="account_payable_detail.php?supplier_id=<?php echo $value['supplier_id'];?>"><button>View Detail</button></a>
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

  <?php include 'footer.html'; ?>
