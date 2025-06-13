<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
  

// Update Status

if(isset($_POST['received'])){
  $order_no = $_POST['order_no'];

  $stmt = $pdo->prepare("UPDATE purchase_order SET status='received' WHERE order_no = '$order_no'");
  $stmt->execute();
}

if(isset($_POST['cancel'])){
  $order_no = $_POST['order_no'];

  $stmt = $pdo->prepare("UPDATE purchase_order SET status='cancel' WHERE order_no = '$order_no'");
  $stmt->execute();
}

// Add Purchase Order
   if (isset($_POST['add_btn'])) {
    if (empty($_POST['order_date']) || empty($_POST['order_no']) || empty($_POST['supplier_id']) || empty($_POST['item_id']) || empty($_POST['qty'])) {
      if (empty($_POST['order_date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['order_no'])) {
        $vr_noError = 'Vr_No is required';
      }
      if (empty($_POST['supplier_id'])) {
        $supplier_idError = 'Supplier is required';
      }
      if (empty($_POST['item_id'])) {
        $item_idError = 'Item_Id is required';
      }
      if (empty($_POST['qty'])) {
        $qtyError = 'Qty is required';
      }
    }else {
      $order_date = $_POST['order_date'];
      $order_no = $_POST['order_no'];
      $supplier_id = $_POST['supplier_id'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];

      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_id=$item_id");
      $stmt->execute();
      $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);

      $price = $totalResult['original_price'];

      $amount = $price * $qty;
  
      $addstmt = $pdo->prepare("INSERT INTO purchase_order (order_date,order_no,supplier_id,item_id,qty,amount,status) VALUES (:order_date,:order_no,:supplier_id,:item_id,:qty,:amount,'Pending')");
      $addResult = $addstmt->execute(
        array(':order_date'=>$order_date, ':order_no'=>$order_no, ':supplier_id'=>$supplier_id, ':item_id'=>$item_id, ':qty'=>$qty, ':amount'=>$amount)
      );
  
      if ($addResult) {
        echo "<script>alert('Sussessfully added');window.location.href='purchase_order.php';</script>";
      }
    }
   }

$purchase_orderstmt = $pdo->prepare("SELECT * FROM purchase_order WHERE status='pending' ORDER BY id DESC");
$purchase_orderstmt->execute();
$purchase_orderdata = $purchase_orderstmt->fetchAll();
 ?>
  <div class="container" style="margin-top:-30px;">
    <div class="card">
      <div class="card-body">
        <h4>Purchase Order</h4>
        <form class="" action="" method="post" style="margin-top:-20px;">
          <div class="row">
            <div class="col-3">
              <label for="" class="mt-4"><b>Order Date</b></label>
              <input type="date" class="form-control" placeholder="Date" name="order_date">
              <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Order No</b></label>
              <input type="text" class="form-control" name="" value="<?php echo "PO-" . rand(1,999999) ?>" disabled>
              <input type="hidden" class="form-control" name="order_no" value="<?php echo "PO-" . rand(1,999999) ?>">
              <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Supplier_Id</b></label>
              <input type="text" id="supplier_id" oninput="fetchSupplierNameFromId()" class="form-control" placeholder="Supplier_Id" name="supplier_id" >
              <p style="color:red;"><?php echo empty($supplier_idError) ? '' : '*'.$supplier_idError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Supplier_Name</b></label>
              <input type="text" id="supplier_name" class="form-control" placeholder="Supplier_Name" name="supplier_name" oninput="fetchSupplierIdFromName()">
            </div>
          </div>
            <!-- Second Row -->
          <div class="row">
            <div class="col-6 d-flex">
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
              <div class="col-6 d-flex">
                <div class="col">
                  <label for=""><b>Qty</b></label>
                  <input type="number" class="form-control" placeholder="Qty" name="qty">
                  <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                </div>
                <div class="col mt-4">
                    <button type="submit" name="add_btn" class="form-control btn btn-primary mt-2">Add</button>
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
          <th>Order No</th>
          <th>Supplier_Name</th>
          <th>Order Date</th>
          <th>Item Name</th>
          <th>Qty</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($purchase_orderdata) {
            $id = 1;
            foreach ($purchase_orderdata as $value) {
              $supplier_id = $value['supplier_id'];
              $item_id = $value['item_id'];

              // Supplier Name
              $supplierIdstmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
              $supplierIdstmt->execute();
              $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);

              // Item Name
              $itemIdstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $itemIdstmt->execute();
              $itemIdResult = $itemIdstmt->fetch(PDO::FETCH_ASSOC);
         ?>
        <tr>
          <td><?php echo $id; ?></td>
          <td><?php echo $value['order_no']; ?></td>
          <td><?php echo $supplierIdResult['supplier_name']; ?></td>
          <td><?php echo $value['order_date']; ?></td>
          <td><?php echo $itemIdResult['item_name']; ?></td>
          <td><?php echo $value['qty']; ?></td>
          <td><?php echo $value['amount']; ?></td>
          <td>
            <div class="badge badge-primary">Pending</div>
          </td>
          <td>
            <form action="" method="post">
              <input type="hidden" value="<?php echo $value['order_no']; ?>" name="order_no">
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
