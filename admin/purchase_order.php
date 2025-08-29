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
    if (empty($_POST['order_date']) || empty($_POST['order_no']) || empty($_POST['supplier_id']) || empty($_POST['item_id']) || empty($_POST['qty'])|| empty($_POST['original_price'])) {
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
      if (empty($_POST['original_price'])) {
        $priceError = 'Price is required';
      }
    }else {
      $order_date = $_POST['order_date'];
      $order_no = $_POST['order_no'];
      $supplier_id = $_POST['supplier_id'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $price = $_POST['original_price'];

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
<script>
function fetchSupplierNameFromId() {
    let supplierId = document.getElementById("supplier_id").value.trim();

    if (supplierId !== "") {
        fetch("get_supplier_by_id.php?supplier_id=" + encodeURIComponent(supplierId))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("supplier_name").value = data.supplier_name;
            } else {
                document.getElementById("supplier_name").value = "";
            }
        })
        .catch(err => console.error("Error fetching supplier name:", err));
    } else {
        document.getElementById("supplier_name").value = "";
    }
}

function fetchSupplierIdFromName() {
    let supplierName = document.getElementById("supplier_name").value.trim();

    if (supplierName !== "") {
        fetch("get_supplier_by_name.php?supplier_name=" + encodeURIComponent(supplierName))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("supplier_id").value = data.supplier_id;
            } else {
                document.getElementById("supplier_id").value = "";
            }
        })
        .catch(err => console.error("Error fetching supplier id:", err));
    } else {
        document.getElementById("supplier_id").value = "";
    }
}

function fetchItemNameFromId() {
    let itemId = document.getElementById("item_id").value.trim();

    if (itemId !== "") {
        fetch("get_item_by_id.php?item_id=" + encodeURIComponent(itemId))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("item_name").value = data.item_name;
                document.getElementById("stock_balance").innerText = "Blance Qty is " + data.stock_balance + " pcs";
                document.getElementById("original_price").value = data.original_price; // <-- add this
            } else {
                document.getElementById("item_name").value = "";
                document.getElementById("original_price").value = ""; // <-- and this
                document.getElementById("stock_balance").innerText = "";
            }
        })
        .catch(err => console.error("Error fetching item name:", err));
    } else {
        document.getElementById("item_name").value = "";
        document.getElementById("original_price").value = ""; // <-- and this
        document.getElementById("stock_balance").innerText = "";
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
              document.getElementById("stock_balance").innerText = "Balance Qty is " + data.stock_balance + " pcs";
              document.getElementById("original_price").value = data.original_price;
            } else {
                document.getElementById("item_id").value = "";
                document.getElementById("original_price").value = "";
                document.getElementById("stock_balance").innerText = "";
            }
        })
        .catch(err => console.error("Error fetching item id:", err));
    } else {
        document.getElementById("item_id").value = "";
        document.getElementById("original_price").value = "";
        document.getElementById("stock_balance").innerText = "";
    }
}

</script>


  <div class="col-md-12 mt-4 px-3 pt-1">
    <h4 class="mb-3 d-flex align-items-center justify-content-between">
        Purchase Orders
        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#newSaleForm" aria-expanded="true" aria-controls="newSaleForm">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>
        </button>
      </h4>
    <div class="collapse show" id="newSaleForm">  
      <div class="card">
        <div class="card-body">
          <form class="" action="" method="post" style="margin-top:-20px;">
            <div class="row">
              <div class="col-3">
                <label for="" class="mt-4">Order Date</label>
                <input type="date" class="form-control" placeholder="Date" name="order_date">
                <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4">Order No</label>
                <input type="text" class="form-control" name="order_no" value="<?php echo "PO-" . rand(1,999999) ?>" readonly>
                <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4">Supplier_Id</label>
                <input type="text" id="supplier_id" oninput="fetchSupplierNameFromId()" class="form-control" placeholder="Supplier_Id" name="supplier_id" >
                <p style="color:red;"><?php echo empty($supplier_idError) ? '' : '*'.$supplier_idError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4">Supplier_Name</label>
                <input type="text" id="supplier_name" class="form-control" placeholder="Supplier_Name" name="supplier_name" oninput="fetchSupplierIdFromName()">
              </div>
            </div>
              <!-- Second Row -->
            <div class="row">
                  <div class="col-3">
                    <label for="">Item_Id</label>
                    <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" 
                          oninput="fetchItemNameFromId()">
                    <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                    <span style="color:green; font-size: 15px;" id="stock_balance"></span>
                  </div>

                  <div class="col-3">
                    <label for="">Item_Name</label>
                    <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" 
                          oninput="fetchItemIdFromName()">
                  </div>

                  <div class="col-2">
                    <label for="">Price</label>
                    <input type="number" class="form-control" placeholder="Price" name="original_price" id="original_price">
                  </div>
                  <div class="col-2">
                    <label for="">Qty</label>
                    <input type="number" class="form-control" placeholder="Qty" name="qty">
                    <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                  </div>
                  <div class="col-2 mt-4">
                      <button type="submit" name="add_btn" class="form-control btn btn-purple mt-2 text-light">Add Purchase Order</button>
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
          <td><?php echo number_format($value['amount']); ?></td>
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
