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
  $supplier_id = $_POST['supplier_id'];
  $amount = $_POST['amount'];

  // Payable Balance
  $payabl_balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' ORDER BY id DESC");
  $payabl_balancestmt->execute();
  $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
  $balance = $payabl_balancedata['balance'] - $amount;  

  $payablstmt = $pdo->prepare("INSERT INTO payable (date,vr_no,supplier_id,paid,balance) VALUES (:date,:vr_no,:supplier_id,:paid,:balance)");
  $payabldata = $payablstmt->execute(
    array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':paid'=>$amount, ':balance'=>$balance)
  );
}

?>

<?php
    $supplierstmt = $pdo->prepare("SELECT DISTINCT supplier_id FROM payable");
    $supplierstmt->execute();
    $supplierdata = $supplierstmt->fetchAll();
 ?>
<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-10 me-5"><b>Account Payable</b></h4>
    <button class="ms-3" data-bs-toggle="modal" data-bs-target="#myModal">Paid Amount</button>
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


<!-- modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Paid Amount</h4>
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
              <label for="">Supplier Name</label>
              <select name="supplier_id" id="" class="form-control border border-dark">
                <?php
                  $supplierstmt = $pdo->prepare("SELECT DISTINCT supplier_id FROM payable ORDER BY id DESC");
                  $supplierstmt->execute();
                  $supplierdata = $supplierstmt->fetchAll();
                  foreach ($supplierdata as $supplier) {
                    $supplier_id = $supplier['supplier_id'];
                    $namestmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
                    $namestmt->execute();
                    $name = $namestmt->fetch(PDO::FETCH_ASSOC);
                    ?>                
                    <option value="<?php echo $supplier_id; ?>"><?php echo $name['supplier_name']; ?></option>
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
