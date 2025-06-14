<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
  
// Update Status

if(isset($_POST['received'])){
  $sale_vr_no = $_POST['sale_vr_no'];
  $item_id = $_POST['item_id'];
  $qty = $_POST['qty'];
  $date = $_POST['date'];
  $amount = $_POST['amount'];
  $return_vr_no = $_POST['return_vr_no'];

  // Stock Balance
  $stock_balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
  $stock_balancestmt->execute();
  $stock_balancedata = $stock_balancestmt->fetch(PDO::FETCH_ASSOC);

  if (!empty($stock_balancedata)) {
    if($stock_balancedata['balance'] < $qty){
      echo "<script>alert('Not Enough Stock')</script>";
    }else{
      $stmt = $pdo->prepare("UPDATE sale_return SET status='received' WHERE sale_vr_no = '$sale_vr_no'");
      $stmt->execute();
    }
    $stockbalance = $stock_balancedata['balance'] + $qty;
  }else{
    $stockbalance = 0 + $qty;
  }

  $stockstmt = $pdo->prepare("INSERT INTO stock (date,item_id,vr_no,to_from,in_qty,balance) VALUES (:date,:item_id,:vr_no,'sale return',:in_qty,:balance)");
  $stockdata = $stockstmt->execute(
    array(':date'=>$date, ':vr_no'=>$sale_vr_no, ':item_id'=>$item_id, ':in_qty'=>$qty, ':balance'=>$stockbalance)
  );


  // Cash reduce
  $cash_checkstmt = $pdo->prepare("SELECT * FROM credit_sale WHERE vr_no='$sale_vr_no' ORDER BY id DESC");
  $cash_checkstmt->execute();
  $cash_checksdata = $cash_checkstmt->fetch(PDO::FETCH_ASSOC);
  if(!empty($cash_checksdata)){
    $customer_id = $cash_checksdata['customer_id'];
    // echo "<script>alert('$customer_id')</script>";

    // receivable Last Balance
    $payabl_balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' AND vr_no='$sale_vr_no'");
    $payabl_balancestmt->execute();
    $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
    $last_id = $payabl_balancedata['id'];
    $last_asc_id = $payabl_balancedata['asc_id'];
    $last_balance = $payabl_balancedata['balance'];

    $balance = $last_balance - $amount;

    // Return Voucher Generate
    $asc_id = $last_asc_id + 1;
    // Insert receivable
    $payablstmt = $pdo->prepare("INSERT INTO receivable (date,vr_no,customer_id,paid,balance,asc_id,group_id) VALUES (:date,:returnvr_no,:customer_id,:paid,:balance,:asc_id,:group_id)");
      $payabldata = $payablstmt->execute(
        array(':date'=>$date, ':returnvr_no'=>$return_vr_no, ':customer_id'=>$customer_id, ':paid'=>$amount, ':asc_id' => $asc_id, ':group_id' => $sale_vr_no, ':balance'=>$balance)
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

}

// if(isset($_POST['cancel'])){
//   $order_no = $_POST['order_no'];

//   $stmt = $pdo->prepare("UPDATE sale_order SET status='cancel' WHERE order_no = '$order_no'");
//   $stmt->execute();
// }

// Add sale Order
   if (isset($_POST['add_btn'])) {
    if (empty($_POST['date']) || empty($_POST['sale_vr_no']) || empty($_POST['reason']) || empty($_POST['item_id']) || empty($_POST['qty']) || empty($_POST['return_type'])) {
      if (empty($_POST['date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['sale_vr_no'])) {
        $sale_vr_noError = 'sale_vr_no is required';
      }
      if (empty($_POST['reason'])) {
        $reasonError = 'Reason is required';
      }
      if (empty($_POST['item_id'])) {
        $item_idError = 'Item_Id is required';
      }
      if (empty($_POST['qty'])) {
        $qtyError = 'Qty is required';
      }
      if (empty($_POST['return_type'])) {
        $qtyError = 'return_type is required';
      }
    }else {
      $date = $_POST['date'];
      $sale_vr_no = $_POST['sale_vr_no'];
      $reason = $_POST['reason'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $return_type = $_POST['return_type'];
      $return_vr_no = $_POST['return_vr_no'];

      // amount calculate
      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_id=$item_id");
      $stmt->execute();
      $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);

      $price = $totalResult['selling_price'];

      $amount = $price * $qty;
  
      $addstmt = $pdo->prepare("INSERT INTO sale_return (date,return_vr_no,item_id,qty,amount,reason,status,return_type,sale_vr_no) VALUES (:date,:return_vr_no,:item_id,:qty,:amount,:reason,'pending',:return_type,:sale_vr_no)");
      $addResult = $addstmt->execute(
        array(':date'=>$date, 'return_vr_no'=>$return_vr_no, ':sale_vr_no'=>$sale_vr_no, ':reason'=>$reason, ':item_id'=>$item_id, ':qty'=>$qty, ':amount'=>$amount, ':return_type'=>$return_type)
      );
  
      if ($addResult) {
        echo "<script>alert('Sussessfully added');window.location.href='sale_return.php';</script>";
      }
    }
   }

$sale_returnstmt = $pdo->prepare("SELECT * FROM sale_return WHERE status='pending' ORDER BY id DESC");
$sale_returnstmt->execute();
$sale_returndata = $sale_returnstmt->fetchAll();
 ?>
  <div class="container" style="margin-top:-30px;">
    <div class="card">
      <div class="card-body">
        <h4>Sale Return</h4>
        <form class="" action="" method="post">
            <div class="row">
              <div class="col-6 d-flex">
                <div class="col">
                  <label for=""><b>Return Date</b></label>
                  <input type="date" class="form-control" placeholder="Date" name="date">
                  <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                </div>
                <div class="col">
                  <label for=""><b>Return Vr_no</b></label>
                  <input type="text" class="form-control" placeholder="Date" readonly name="return_vr_no" value="<?php echo "PR-" . rand(0,999999); ?>">
                  <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                </div>
              </div>
              <div class="col-3 d-flex">
                <div class="col">
                  <label for=""><b>Item_Id</b></label>
                  <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchitemNameFromId()">
                  <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                </div>
                <div class="col">
                  <label for=""><b>Item_Name</b></label>
                  <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchitemIdFromName()">
                </div>
              </div>
              <div class="col-3">
                <div class="col">
                  <label for=""><b>Reason</b></label>
                  <input type="text" class="form-control" placeholder="Pls write ur reason here ..." name="reason">
                  <p style="color:red;"><?php echo empty($reasonError) ? '' : '*'.$reasonError;?></p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 d-flex">
                <div class="col">
                  <label for=""><b>sale Vr_no</b></label>
                  <select name="sale_vr_no" id="" class="form-control">
                    <?php 
                    $vr_nostmt = $pdo->prepare("SELECT DISTINCT vr_no FROM (
                      SELECT vr_no FROM cash_sale
                      UNION
                      SELECT vr_no FROM credit_sale
                    ) AS all_sales
                    ORDER BY vr_no DESC;");
                    $vr_nostmt->execute();
                    $vr_nodatas = $vr_nostmt->fetchAll();
                    foreach ($vr_nodatas as $vr_nodata) {
                      ?>
                      <option value="<?php echo $vr_nodata['vr_no']; ?>"><?php echo $vr_nodata['vr_no']; ?></option>
                      <?php
                    }
                  ?>
                  </select>
                  <p style="color:red;"><?php echo empty($sale_vr_noError) ? '' : '*'.$sale_vr_noError;?></p>
                </div>
                <div class="col">
                  <label for=""><b>Qty</b></label>
                  <input type="number" class="form-control" placeholder="Qty" name="qty">
                  <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                </div>
                
              </div>
              <div class="col-6 d-flex">
                <div class="col">
                  <label for=""><b>Return Type</b></label>
                  <select name="return_type" class="form-control">
                    <option value="">Select Return Type</option>
                    <option value="damaged">Damaged</option>
                    <option value="wrong">Wrong Item</option>
                    <option value="extra">Extra Quantity</option>
                  </select>
                  <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
                </div>
                <div class="col mt-2">
                    <button type="submit" name="add_btn" class="form-control btn btn-primary mt-4">Add</button>
                </div>
              </div>
            </div>
            
      </form>
      </div>
    </div>
  <div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="width: 10px">No</th>
          <th>Return Date</th>
          <th>sale Vr_no</th>
          <th>Item Name</th>
          <th>Qty</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Return Type</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($sale_returndata) {
            $id = 1;
            foreach ($sale_returndata as $value) {
              $item_id = $value['item_id'];

              // Item Name
              $itemIdstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $itemIdstmt->execute();
              $itemIdResult = $itemIdstmt->fetch(PDO::FETCH_ASSOC);
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $value['date']; ?></td>
          <td><?php echo $value['sale_vr_no']; ?></td>
          <td><?php echo $itemIdResult['item_name']; ?></td>
          <td><?php echo $value['qty']; ?></td>
          <td><?php echo $value['reason']; ?></td>
          <td>  
            <div class="badge badge-primary">Pending</div>
          </td>
          <td><?php echo $value['return_type']; ?></td>
          <td>
            <form action="" method="post">
              <input type="hidden" value="<?php echo $value['sale_vr_no']; ?>" name="sale_vr_no">
              <input type="hidden" value="<?php echo $value['return_vr_no']; ?>" name="return_vr_no">
              <input type="hidden" value="<?php echo $value['item_id']; ?>" name="item_id">
              <input type="hidden" value="<?php echo $value['date']; ?>" name="date">
              <input type="hidden" value="<?php echo $value['qty']; ?>" name="qty">
              <input type="hidden" value="<?php echo $value['amount']; ?>" name="amount">
              <button type="submit" name="received" class="btn btn-sm btn-success">Received</button>
              <button type="submit" name="cancel" class="btn btn-sm btn-danger">Cancel</button>
            </form>
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
