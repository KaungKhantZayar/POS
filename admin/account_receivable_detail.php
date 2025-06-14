<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
  ?>

  <?php include 'header.php'; ?>

<style media="screen">
.outer {
overflow-y: auto;
height: 700px;
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
    $receivablestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' ORDER BY asc_id");
    $receivablestmt->execute();
    $receivabledata = $receivablestmt->fetchAll();

    // Customer Name
    $customerstmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
    $customerstmt->execute();
    $customer = $customerstmt->fetch(PDO::FETCH_ASSOC);

    // Add Payment
    if(isset($_POST['save'])){
      $date = $_POST['date'];
      $vr_no = $_POST['vr_no'];
      $customer_id = $_POST['customer_id'];
      $amount = $_POST['amount'];

      // Payable Last Balance
      $receivable_balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' AND vr_no='$vr_no'");
      $receivable_balancestmt->execute();
      $receivable_balancedata = $receivable_balancestmt->fetch(PDO::FETCH_ASSOC);
      $last_id = $receivable_balancedata['id'];
      $last_asc_id = $receivable_balancedata['asc_id'];
      $last_balance = $receivable_balancedata['balance'];
      
      // Update Row above last_row Paid Status
      $paidstatus_update = $pdo->prepare("UPDATE receivable SET status='paid' WHERE customer_id='$customer_id' AND asc_id<'$last_asc_id'");
      $paidstatus_update->execute();
      
      $balance = $last_balance - $amount;
      
      if($balance == 0){
        // Update last_row Paid Status
        $pendingstatus_update = $pdo->prepare("UPDATE receivable SET status='paid' WHERE customer_id='$customer_id' AND id='$last_id'");
        $pendingstatus_update->execute();        
        $receivable_payment_stmt = $pdo->prepare("INSERT INTO receivable (date,vr_no,customer_id,paid,balance,asc_id,group_id,status) VALUES (:date,:paymentvr_no,:customer_id,:paid,:balance,:asc_id,:group_id,'paid')");
      }else{
        // Update last_row Pending Status
        $pendingstatus_update = $pdo->prepare("UPDATE receivable SET status='pending' WHERE customer_id='$customer_id' AND id='$last_id'");
        $pendingstatus_update->execute();
        $receivable_payment_stmt = $pdo->prepare("INSERT INTO receivable (date,vr_no,customer_id,paid,balance,asc_id,group_id,status) VALUES (:date,:paymentvr_no,:customer_id,:paid,:balance,:asc_id,:group_id,'pending')");
      }

      // Add Paid Amount And Asc_id
      $paymentvr_no =  25 . rand(0,999999);
      $asc_id = $last_asc_id + 1;
      $receivable_payment_data = $receivable_payment_stmt->execute(
        array(':date'=>$date, ':paymentvr_no'=>$paymentvr_no, ':customer_id'=>$customer_id, ':paid'=>$amount, ':asc_id' => $asc_id, ':group_id' => $vr_no, ':balance'=>$balance)
      );

      // Current Id
      $current_idstmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' ORDER BY id DESC");
      $current_idstmt->execute();
      $current_iddata = $current_idstmt->fetch(PDO::FETCH_ASSOC);
      $current_id = $current_iddata['id'];
      $current_ascid = $current_iddata['asc_id'];
      $current_balance = $current_iddata['balance'];

      // For Update Others row
      // Check How Many Line to update
      $other_rowstmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' AND id!='$current_id' AND asc_id!='$last_asc_id' AND asc_id>$last_asc_id");
      $other_rowstmt->execute();
      $other_rowdatas = $other_rowstmt->fetchAll();
      $i = 1;
      // print "<pre>";
      // print_r($other_rowdatas);
      foreach ($other_rowdatas as $other_rowdata) {
      // echo "<script>alert('Hello');</script>";

        $id = $other_rowdata['id'];
        $customer_id = $other_rowdata['customer_id'];
        $amount = $other_rowdata['amount'];
        $paid = $other_rowdata['paid'];
        $updatea_ascid = $current_ascid + $i;

        if($i == 1){
            $newbalance = $current_balance + $amount - $paid;
        }else{
            $balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' AND id<'$id' ORDER BY id DESC");
            $balancestmt->execute();
            $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

            $newbalance = $balancedata['balance'] + $amount - $paid;
        }
        

        $updateupdate = $pdo->prepare("UPDATE receivable SET balance='$newbalance', asc_id='$updatea_ascid' WHERE id='$id' AND customer_id='$customer_id'");
        $updateupdate->execute();
        $i++;
      }
    }
 ?>

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
          <th>Status</th>
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
        <tr data-bs-toggle="modal" data-bs-target="#myModal<?php echo $value['id']; ?>">
          <td><?php echo $id; ?></td>
          <td><?php echo $value['date'];?></td>
          <td><?php echo $value['vr_no'];?></td>
          <td><?php echo $value['amount'];?></td>
          <td><?php echo $value['paid'];?></td>
          <td><?php echo $value['balance'];?></td>
          <td><span class="badge <?php if($value['status'] == 'paid'){ echo "badge-success"; }elseif($value['status'] == 'pending'){ echo "badge-primary"; } ?>"><?php echo $value['status'];?></span></td>
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
                      <input type="hidden" name="customer_id" value="<?php echo $value['customer_id'];?>">
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
