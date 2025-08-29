<?php
  session_start();
  require '../Config/config.php';
  require '../Config/common.php';
?>
<?php include 'header.php'; ?>

<?php

if(isset($_POST['edit'])){
  
    $date = $_POST['date'];
    $grn_no = $_POST['grn_no'];
    $supplier_id = $_POST['supplier_id'];
    $amount = $_POST['amount'];
    $payment_no = $_POST['payment_no'];
    $account_name = $_POST['account_name'];
    $group_id = $_POST['group_id'];
    $id = $_POST['id'];
    
    // Payable Last Balance
    $payabl_balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND group_id='$group_id' AND id < '$id' ORDER BY id DESC");
    $payabl_balancestmt->execute();
    $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
    $last_id = $payabl_balancedata['id'];
    $last_asc_id = $payabl_balancedata['asc_id'];
    $last_balance = $payabl_balancedata['balance'];
    // echo "<script>alert('$last_balance');</script>";
    
    $balance = $last_balance - $amount;
    
    if($balance == 0){
      // Update last_row Paid Status
      $pendingstatus_update = $pdo->prepare("UPDATE payable SET payment_no = '$payment_no', account_name = '$account_name', paid='$amount', balance='$balance', status='paid' WHERE supplier_id='$supplier_id' AND id='$id'");
      $pendingstatus_update->execute();
    }else{
      // Update last_row Pending Status
      $pendingstatus_update = $pdo->prepare("UPDATE payable SET payment_no = '$payment_no', account_name = '$account_name', paid='$amount', balance='$balance', status='pending' WHERE supplier_id='$supplier_id' AND id='$id'");
      $pendingstatus_update->execute();
    }

    // For Update Others row
    // Check How Many Line to update
    $other_rowstmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND group_id='$group_id' AND id > '$id'");
    $other_rowstmt->execute();
    $other_rowdatas = $other_rowstmt->fetchAll();
    $i = 1;
    // print "<pre>";
    // print_r($other_rowdatas);
    foreach ($other_rowdatas as $other_rowdata) {
    // echo "<script>alert('Hello');</script>";

      $id = $other_rowdata['id'];
      $supplier_id = $other_rowdata['supplier_id'];
      $group_id = $other_rowdata['group_id'];
      $amount = $other_rowdata['amount'];
      $paid = $other_rowdata['paid'];
      $updatea_ascid = $id + $i;

      $balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND group_id = '$group_id' AND id<'$id' ORDER BY id DESC");
      $balancestmt->execute();
      $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

      $newbalance = $balancedata['balance'] + $amount - $paid;

      $updateupdate = $pdo->prepare("UPDATE payable SET balance='$newbalance', asc_id='$updatea_ascid', status='Pending' WHERE id='$id' AND supplier_id='$supplier_id'");
      $updateupdate->execute();
      $i++;
    }
  }

?>

<?php
    $supplier_id = $_GET['supplier_id'];
    $group_id = $_GET['group_id'];
    $payaplestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' AND group_id='$group_id' ORDER BY asc_id");
    $payaplestmt->execute();
    $payapledata = $payaplestmt->fetchAll();

    // Supplier Name
    $supplierIdstmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
    $supplierIdstmt->execute();
    $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);
 ?>

<div class="col-md-12 px-4 mt-4">
  <div class="d-flex justify-content-between">
    <div>
      <h4>Payment History For Supplier - <?php echo $supplierIdResult['supplier_name']; ?></h4>
    </div>
    <div>
      <?php
      $supplier_id = $_GET['supplier_id'];
      ?>
      <a href="account_payable_detail.php?supplier_id=<?php echo $supplier_id; ?>">
        Back
      </a>
      /
      <a href="account_payable.php">
          Payable
      </a>
    </div>
  </div>
  <div class="" style="margin-top:-10px;">
    <table class="table mt-4 table-hover">
      <thead class="custom-thead">
        <tr>
          <th style="width: 10px">#</th>
          <th>Date</th>
          <th>GRN No</th>
          <th>Payment No</th>
          <th>Account Name</th>
          <th>Amount</th>
          <th>Paid Amount</th>
          <th>Balance</th>
          <th>Action</th>
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
          <td><?php if(str_contains($value['grn_no'], "PR")){ echo $value['grn_no']; ?><span class="badge badge-primary ms-2">Purchase Return</span><?php }else{ echo $value['grn_no']; } ?></td>
          <td><?php echo $value['payment_no'];?></td>
          <td><?php echo $value['account_name'];?></td>
          <td><?php echo number_format($value['amount']);?></td>
          <td><?php echo number_format($value['paid']);?></td>
          <td><?php echo number_format($value['balance']);?></td>
          <td>
            <?php
            if($value['grn_no'] == ''){
              ?>
              <!-- First link styled as button with tooltip -->
            <button 
                class="btn btn-sm btn-warning text-light"
                onclick="openDrawer(<?php echo $value['id']; ?>)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </button>

            <!-- Drawer (Hidden by default) -->
            <div id="drawer<?php echo $value['id']; ?>" class="drawer shadow-lg">
              <div class="drawer-header d-flex justify-content-between align-items-center p-3 border-bottom">
                <h5 class="mb-0 fw-bold text-dark">Edit Payment</h5>
                <button type="button" class="btn-close" onclick="closeDrawer()"></button>
              </div>

              <div class="drawer-body p-4">
                <form action="" method="post">
                  <input type="hidden" name="grn_no" value="<?php echo $value['grn_no'];?>">
                  <input type="hidden" name="group_id" value="<?php echo $value['group_id'];?>">
                  <input type="hidden" name="supplier_id" value="<?php echo $value['supplier_id'];?>">
                  <input type="hidden" name="id" value="<?php echo $value['id'];?>">

                  <div class="mb-3">
                    <label class="form-label fw-semibold">Date</label>
                    <input type="date" class="form-control" value="<?php echo $value['date'];?>" name="date">
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-semibold">Payment No</label>
                    <input type="text" class="form-control" value="<?php echo $value['payment_no'];?>" name="payment_no">
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-semibold">Paid Amount</label>
                    <input type="number" class="form-control" value="<?php echo $value['paid'];?>" name="amount">
                  </div>

                  <div class="mb-4">
                    <label class="form-label fw-semibold">Account Name</label>
                    <select name="account_name" class="form-select form-control">
                      <option value="AYA Bank" <?php if($value['account_name'] == 'AYA Bank'){ echo "selected"; } ?>>AYA Bank</option>
                      <option value="KBZ Bank" <?php if($value['account_name'] == 'KBZ Bank'){ echo "selected"; } ?>>KBZ Bank</option>
                      <option value="Cash" <?php if($value['account_name'] == 'Cash'){ echo "selected"; } ?>>Cash</option>
                    </select>
                  </div>

                  <div class="d-flex justify-content-center gap-2 border-top pt-3">
                    <button type="button" class="btn btn-outline-secondary px-4" onclick="closeDrawer(<?php echo $value['id']; ?>)">
                      Cancel
                    </button>
                    <button type="submit" name="edit" class="btn btn-purple text-light px-4 shadow-sm ml-2">
                      Save Changes
                    </button>
                  </div>

                </form>
              </div>
            </div>
            <!-- Drawer Backdrop -->
            <div id="drawerBackdrop" class="drawer-backdrop" onclick="closeDrawer(<?php echo $value['id']; ?>)"></div>

            <!-- Second link styled as button with tooltip -->
            <a href="account_payable_detail.php?supplier_id=<?php echo $value['supplier_id'];?>"
              class="btn btn-sm btn-danger text-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
            </a>
              <?php
            }
            ?>
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
<script>
function openDrawer(id) {
  document.getElementById("drawer" + id).classList.add("open");
  document.getElementById("drawerBackdrop" + id).classList.add("show");
}

function closeDrawer(id) {
  document.getElementById("drawer" + id).classList.remove("open");
  document.getElementById("drawerBackdrop" + id).classList.remove("show");
}

</script>
  <?php include 'footer.html'; ?>
