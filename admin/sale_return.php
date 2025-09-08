<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
  
// Update Status

if(isset($_POST['received'])){
  $gin_no = $_POST['gin_no'];
  $item_id = $_POST['item_id'];
  $qty = $_POST['qty'];
  $date = $_POST['date'];
  $amount = $_POST['amount'];
  $grn_no = $_POST['grn_no'];

  // Stock Balance
  $stock_balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
  $stock_balancestmt->execute();
  $stock_balancedata = $stock_balancestmt->fetch(PDO::FETCH_ASSOC);

  if (!empty($stock_balancedata)) {
    if($stock_balancedata['balance'] < $qty){
      echo "<script>alert('Not Enough Stock')</script>";
    }else{
      $stmt = $pdo->prepare("UPDATE sale_return SET status='received' WHERE gin_no = '$gin_no'");
      $stmt->execute();
    }
    $stockbalance = $stock_balancedata['balance'] + $qty;
  }else{
    $stockbalance = 0 + $qty;
  }

  $stockstmt = $pdo->prepare("INSERT INTO stock (date,item_id,gin_no,to_from,in_qty,balance) VALUES (:date,:item_id,:gin_no,'sale_return',:in_qty,:balance)");
  $stockdata = $stockstmt->execute(
    array(':date'=>$date, ':gin_no'=>$gin_no, ':item_id'=>$item_id, ':in_qty'=>$qty, ':balance'=>$stockbalance)
  );


  // Cash reduce
  $cash_checkstmt = $pdo->prepare("SELECT * FROM credit_sale WHERE gin_no='$gin_no' ORDER BY id DESC");
  $cash_checkstmt->execute();
  $cash_checksdata = $cash_checkstmt->fetch(PDO::FETCH_ASSOC);
  if(!empty($cash_checksdata)){
    $customer_id = $cash_checksdata['customer_id'];
    // echo "<script>alert('$customer_id')</script>";

    // receivable Last Balance
    $payabl_balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' AND gin_no='$gin_no'");
    $payabl_balancestmt->execute();
    $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
    $last_id = $payabl_balancedata['id'];
    $last_asc_id = $payabl_balancedata['asc_id'];
    $last_balance = $payabl_balancedata['balance'];

    $balance = $last_balance - $amount;

    // Return Voucher Generate
    $asc_id = $last_asc_id + 1;
    // Insert receivable
    $payablstmt = $pdo->prepare("INSERT INTO receivable (date,grn_no,customer_id,paid,balance,asc_id,group_id) VALUES (:date,:grn_no,:customer_id,:paid,:balance,:asc_id,:group_id)");
      $payabldata = $payablstmt->execute(
        array(':date'=>$date, ':grn_no'=>$grn_no, ':customer_id'=>$customer_id, ':paid'=>$amount, ':asc_id' => $asc_id, ':group_id' => $gin_no, ':balance'=>$balance)
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
    if (empty($_POST['date']) || empty($_POST['gin_no']) || empty($_POST['reason']) || empty($_POST['item_id']) || empty($_POST['qty']) || empty($_POST['return_type'])) {
      if (empty($_POST['date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['gin_no'])) {
        $gin_noError = 'gin_no is required';
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
      $gin_no = $_POST['gin_no'];
      $reason = $_POST['reason'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $return_type = $_POST['return_type'];
      $grn_no = $_POST['grn_no'];

      // amount calculate
      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_id=$item_id");
      $stmt->execute();
      $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);

      $price = $totalResult['selling_price'];

      $amount = $price * $qty;
  
      $addstmt = $pdo->prepare("INSERT INTO sale_return (date,grn_no,item_id,qty,amount,reason,status,return_type,gin_no) VALUES (:date,:grn_no,:item_id,:qty,:amount,:reason,'pending',:return_type,:gin_no)");
      $addResult = $addstmt->execute(
        array(':date'=>$date, 'grn_no'=>$grn_no, ':gin_no'=>$gin_no, ':reason'=>$reason, ':item_id'=>$item_id, ':qty'=>$qty, ':amount'=>$amount, ':return_type'=>$return_type)
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
 <script>
  function fetchItemNameFromId() {
    let itemId = document.getElementById("item_id").value.trim();

    if (itemId !== "") {
        fetch("get_item_by_id.php?item_id=" + encodeURIComponent(itemId))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("item_name").value = data.item_name;
            } else {
                document.getElementById("item_name").value = "";
            }
        })
        .catch(err => console.error("Error fetching item name:", err));
    } else {
        document.getElementById("item_name").value = "";
    }
}

function fetchItemIdFromName() {
    let itemName = document.getElementById("item_name").value.trim();

    if (itemName !== "") {
        fetch("get_item_by_name.php?item_name=" + encodeURIComponent(itemName))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("item_id").value = data.item_id;
            } else {
                document.getElementById("item_id").value = "";
            }
        })
        .catch(err => console.error("Error fetching item id:", err));
    } else {
        document.getElementById("item_id").value = "";
    }
}
 </script>
  <div class="col-md-12 mt-4 px-3 pt-1">
    <h4 class="mb-3 d-flex align-items-center justify-content-between">
        Sale Returns
        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#newSaleForm" aria-expanded="true" aria-controls="newSaleForm">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>
        </button>
      </h4>
      <div class="collapse show" id="newSaleForm">
      <div class="card">
        <div class="card-body">
          <form class="" action="" method="post">
              <div class="row">
                <div class="col-6 d-flex">
                  <div class="col">
                    <label for="">Return Date</label>
                    <input type="date" class="form-control" placeholder="Date" name="date">
                    <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                  </div>
                  <div class="col">
                    <label for="">Return Vr_no</label>
                    <input type="text" class="form-control" placeholder="Date" readonly name="grn_no" value="<?php echo "PR-" . rand(0,999999); ?>">
                    <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                  </div>
                </div>
                <div class="col-3 d-flex">
                  <div class="col">
                    <label for="">Item_Id</label>
                    <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchItemNameFromId()">
                    <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                  </div>
                  <div class="col">
                    <label for="">Item_Name</label>
                    <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchItemIdFromName()">
                  </div>
                </div>
                <div class="col-3">
                  <div class="col">
                    <label for="">Reason</label>
                    <input type="text" class="form-control" placeholder="Pls write ur reason here ..." name="reason">
                    <p style="color:red;"><?php echo empty($reasonError) ? '' : '*'.$reasonError;?></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6 d-flex">
                  <div class="col">
                    <label for="">GIN_No</label>
                    <select name="gin_no" id="" class="form-control">
                      <option value="">Select Goods Issue Note</option>
                      <?php 
                      $gin_nostmt = $pdo->prepare("SELECT DISTINCT gin_no FROM (
                        SELECT gin_no FROM cash_sale
                        UNION
                        SELECT gin_no FROM credit_sale
                      ) AS all_sales
                      ORDER BY gin_no DESC;");
                      $gin_nostmt->execute();
                      $gin_nodatas = $gin_nostmt->fetchAll();
                      foreach ($gin_nodatas as $gin_nodata) {
                        ?>
                        <option value="<?php echo $gin_nodata['gin_no']; ?>"><?php echo $gin_nodata['gin_no']; ?></option>
                        <?php
                      }
                    ?>
                    </select>
                    <p style="color:red;"><?php echo empty($gin_noError) ? '' : '*'.$gin_noError;?></p>
                  </div>
                  <div class="col">
                    <label for="">Qty</label>
                    <input type="number" class="form-control" placeholder="Qty" name="qty">
                    <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                  </div>
                  
                </div>
                <div class="col-6 d-flex">
                  <div class="col">
                    <label for="">Return Type</label>
                    <select name="return_type" class="form-control">
                      <option value="">Select Return Type</option>
                      <option value="damaged">Damaged</option>
                      <option value="wrong">Wrong Item</option>
                      <option value="extra">Extra Quantity</option>
                    </select>
                    <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
                  </div>
                  <div class="col mt-2">
                      <button type="submit" name="add_btn" class="form-control btn btn-purple text-light mt-4">Add Sale Return</button>
                  </div>
                </div>
              </div>
              
        </form>
        </div>
      </div>
    </div>
  <div>
    <table class="table table-hover">
      <thead class="custom-thead">
        <tr>
          <th style="width: 10px">No</th>
          <th>Return Date</th>
          <th>Return GRN_No</th>
          <th>GIN_No</th>
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
          <td><?php echo $value['grn_no']; ?></td>
          <td><?php echo $value['gin_no']; ?></td>
          <td><?php echo $itemIdResult['item_name']; ?></td>
          <td><?php echo $value['qty']; ?></td>
          <td><?php echo $value['reason']; ?></td>
          <td>  
            <div class="badge badge-primary">Pending</div>
          </td>
          <td><?php echo $value['return_type']; ?></td>
          <td>
            <form action="" method="post">
              <input type="hidden" value="<?php echo $value['gin_no']; ?>" name="gin_no">
              <input type="hidden" value="<?php echo $value['grn_no']; ?>" name="grn_no">
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
