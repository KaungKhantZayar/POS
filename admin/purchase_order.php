<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
include 'header.php';
  
  $purchase_orderstmt = $pdo->prepare("SELECT * FROM purchase_order");
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
              <input type="order_date" class="form-control" placeholder="Date" name="date">
              <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
            </div>
            <div class="col-3">
              <label for="" class="mt-4"><b>Order No</b></label>
              <input type="text" class="form-control" placeholder="Vr_No" name="" value="<?php echo 25 . rand(1,999999) ?>" disabled>
              <input type="hidden" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php echo 25 . rand(1,999999) ?>">
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
            <div class="col-5 d-flex">
                <div class="col" >
                  <label for=""><b>Item_Id</b></label>
                  <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchitemNameFromId()">
                  <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                </div>
                <div class="col" >
                  <label for=""><b>Item_Name</b></label>
                  <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchitemIdFromName()">
                </div>
                <div class="col" >
                  <label for=""><b>Qty</b></label>
                  <input type="number" class="form-control" placeholder="Qty" name="qty">
                  <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                </div>
            </div>

            <div class="col-5 d-flex">
              <div class="col" >
                <label for=""><b>Discount</b></label>
                <input type="number" class="form-control" placeholder="Discount" name="discount">
                <p style="color:red;"></p>
              </div>

              <div class="col" >
                <label for=""><b>Foc</b></label>
                <input type="number" class="form-control" placeholder="Foc" name="foc">
                <p style="color:red;"></p>
              </div>

              <div class="col">
                <label for=""><b>Payment</b></label>
                <select name="type" class="form-control" >
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                  </select>
              </div>
            </div>

            <div class="col-2 mt-4">
                <button type="submit" name="add_btn" class="form-control btn btn-primary mt-2">Add</button>
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
              $supplierIdstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $supplierIdstmt->execute();
              $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);
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
