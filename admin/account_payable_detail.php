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
    $supplier_id = $_GET['supplier_id'];
    $payaplestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' ORDER BY asc_id");
    $payaplestmt->execute();
    $payapledata = $payaplestmt->fetchAll();

    // Supplier Name
    $supplierIdstmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
    $supplierIdstmt->execute();
    $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);
 
    // Add Payment
    if(isset($_POST['save'])){
      $date = $_POST['date'];
      $vr_no = $_POST['vr_no'];
      $supplier_id = $_POST['supplier_id'];
      $amount = $_POST['amount'];

      // Payable Last Balance
      $payabl_balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND vr_no='$vr_no'");
      $payabl_balancestmt->execute();
      $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
      $last_id = $payabl_balancedata['id'];
      $last_asc_id = $payabl_balancedata['asc_id'];
      $last_balance = $payabl_balancedata['balance'];
  
      // Update Row above last_row Paid Status
      $paidstatus_update = $pdo->prepare("UPDATE payable SET status='paid' WHERE supplier_id='$supplier_id' AND asc_id<'$last_asc_id'");
      $paidstatus_update->execute();

      $balance = $last_balance - $amount;
      
      if($balance == 0){
        // Update last_row Paid Status
        $pendingstatus_update = $pdo->prepare("UPDATE payable SET status='paid' WHERE supplier_id='$supplier_id' AND id='$last_id'");
        $pendingstatus_update->execute();        
        $payablstmt = $pdo->prepare("INSERT INTO payable (date,vr_no,supplier_id,paid,balance,asc_id,group_id,status) VALUES (:date,:paymentvr_no,:supplier_id,:paid,:balance,:asc_id,:group_id,'paid')");
      }else{
        // Update last_row Pending Status
        $pendingstatus_update = $pdo->prepare("UPDATE payable SET status='pending' WHERE supplier_id='$supplier_id' AND id='$last_id'");
        $pendingstatus_update->execute();
        $payablstmt = $pdo->prepare("INSERT INTO payable (date,vr_no,supplier_id,paid,balance,asc_id,group_id,status) VALUES (:date,:paymentvr_no,:supplier_id,:paid,:balance,:asc_id,:group_id,'pending')");
      }

      // Add Paid Amount And Asc_id
      $paymentvr_no =  52 . rand(0,999999);
      $asc_id = $last_asc_id + 1;
      $payabldata = $payablstmt->execute(
        array(':date'=>$date, ':paymentvr_no'=>$paymentvr_no, ':supplier_id'=>$supplier_id, ':paid'=>$amount, ':asc_id' => $asc_id, ':group_id' => $vr_no, ':balance'=>$balance)
      );

      // Current Id
      $current_idstmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' ORDER BY id DESC");
      $current_idstmt->execute();
      $current_iddata = $current_idstmt->fetch(PDO::FETCH_ASSOC);
      $current_id = $current_iddata['id'];
      $current_ascid = $current_iddata['asc_id'];
      $current_balance = $current_iddata['balance'];

      // For Update Others row
      // Check How Many Line to update
      $other_rowstmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND id!='$current_id' AND asc_id!='$last_asc_id' AND asc_id>$last_asc_id");
      $other_rowstmt->execute();
      $other_rowdatas = $other_rowstmt->fetchAll();
      $i = 1;
      // print "<pre>";
      // print_r($other_rowdatas);
      foreach ($other_rowdatas as $other_rowdata) {
      // echo "<script>alert('Hello');</script>";

        $id = $other_rowdata['id'];
        $supplier_id = $other_rowdata['supplier_id'];
        $amount = $other_rowdata['amount'];
        $paid = $other_rowdata['paid'];
        $updatea_ascid = $current_ascid + $i;

        if($i == 1){
            $newbalance = $current_balance + $amount - $paid;
        }else{
            $balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND id<'$id' ORDER BY id DESC");
            $balancestmt->execute();
            $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

            $newbalance = $balancedata['balance'] + $amount - $paid;
        }
        

        $updateupdate = $pdo->prepare("UPDATE payable SET balance='$newbalance', asc_id='$updatea_ascid', status='Pending' WHERE id='$id' AND supplier_id='$supplier_id'");
        $updateupdate->execute();
        $i++;
      }
    }
 ?>

<div class="container">
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-11"><b>Account Payable ( <?php echo $supplierIdResult['supplier_name']; ?> )</b></h4>
    <a href="account_payable.php"><button class="">Back</button></a>
  </div>
  <div class="" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Date</th>
          <th>Vr_No</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Balance</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($payapledata) {
            $id = 1;
            foreach ($payapledata as $value) {
              $supplier_id = $value['supplier_id'];
         ?>
        <tr data-bs-toggle="modal" data-bs-target="#myModal<?php echo $value['id']; ?>">
          <td><?php echo $id; ?></td>
          <td><?php echo $value['date'];?></td>
          <td><?php if(str_contains($value['vr_no'], "PR")){ echo $value['vr_no']; ?><span class="badge badge-primary ms-2">Purchase Return</span><?php }else{ echo $value['vr_no']; } ?></td>
          <td><?php echo $value['amount'];?></td>
          <td><?php echo $value['paid'];?></td>
          <td><?php echo $value['balance'];?></td>
          <td><span class="badge <?php if($value['status'] == 'paid'){ echo "badge-success"; }else{ echo "badge-primary"; } ?>"><?php echo $value['status'];?></span></td>
        </tr>
        <!-- modal -->
        <div id="myModal<?php echo $value['id']; ?>" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Add Paid Amount</h4>
              </div>
              <div class="modal-body">
                <form action="" method="post">
                  <input type="hidden" name="vr_no" value="<?php echo $value['vr_no'];?>">
                  <input type="hidden" name="supplier_id" value="<?php echo $value['supplier_id'];?>">
                    <div class="row mb-2">
                      <div class="col">
                        <label for="">Date</label>
                        <input type="date" class="border border-dark form-control" name="date">
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
