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

if(isset($_POST['save'])){
  $date = $_POST['date'];
  $vr_no = $_POST['vr_no'];
  $customer_id = $_POST['customer_id'];
  $amount = $_POST['amount'];

  // receivable Balance
  $receivable_balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' ORDER BY id DESC");
  $receivable_balancestmt->execute();
  $receivable_balancedata = $receivable_balancestmt->fetch(PDO::FETCH_ASSOC);
  $balance = $receivable_balancedata['balance'] - $amount;  

  $receivablestmt = $pdo->prepare("INSERT INTO receivable (date,vr_no,customer_id,paid,balance) VALUES (:date,:vr_no,:customer_id,:paid,:balance)");
  $receivabledata = $receivablestmt->execute(
    array(':date'=>$date, ':vr_no'=>$vr_no, ':customer_id'=>$customer_id, ':paid'=>$amount, ':balance'=>$balance)
  );
}

?>

<?php
    $customerstmt = $pdo->prepare("SELECT DISTINCT customer_id FROM receivable");
    $customerstmt->execute();
    $customerdata = $customerstmt->fetchAll();
 ?>

 <!-- <form class="" action="" method="post">
   <div class="d-flex" style="margin-left:950px; margin-top:-15px;">
     <input type="date" name="" value="" class="form-control" placeholder="Search customer_Name" style="width:200px;">
     <button type="submit" name="search" class="search_btn ms-3">Search</button>
  </div>
 </form> -->

<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-10 me-5"><b>Account Receivable</b></h4>
    <button class="ms-3" data-bs-toggle="modal" data-bs-target="#myModal">Received Amount</button>
  </div>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Received Amount</h4>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <div class="row">
            <div class="col">
              <label for="">Date</label>
              <input type="date" class="border border-dark form-control" name="date">
            </div>
            <div class="col">
              <label for="">Vr_no</label>
              <input type="text" class="form-control border border-dark" name="vr_no">
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label for="">customer Name</label>
              <select name="customer_id" id="" class="form-control border border-dark">
                <?php
                  $customerstmt = $pdo->prepare("SELECT DISTINCT customer_id FROM receivable ORDER BY id DESC");
                  $customerstmt->execute();
                  $customerdata = $customerstmt->fetchAll();
                  foreach ($customerdata as $customer) {
                    $customer_id = $customer['customer_id'];
                    $namestmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
                    $namestmt->execute();
                    $name = $namestmt->fetch(PDO::FETCH_ASSOC);
                    ?>                
                    <option value="<?php echo $customer_id; ?>"><?php echo $name['customer_name']; ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="col">
              <label for="">Amount</label>
              <input type="number" class="form-control border border-dark" name="amount">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="save">Save</button>
          <button type="button" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>
  <?php include 'footer.html'; ?>
