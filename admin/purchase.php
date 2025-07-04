<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

  ?>
 <?php include 'header.php';?>

 <style media="screen">
 .add_btn{
   background-color:#1c1c1c;
   color:white;
   transition:0.5s;
   border-radius:10px;
   padding:7px;
 }
 .add_btn:hover{
   border:2px solid #1c1c1c;
   background:none;
   color:#1c1c1c;
   transition:0.5s;
   border-radius:10px;
   box-shadow:2px 8px 16px gray;
 }
 .crd{
   width:500px;
 }
 .cb{
   box-shadow:0px 4px 4px gray;
 }


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
 .text{
   background-color:white;
   width:130px;
   margin-left:-15px;
   margin-top:-16px;
   padding-left:6px;
   padding-top:5px;
   padding-bottom:5px;
   border-top-left-radius:5px;
   border-bottom-right-radius:100px;

 }
 </style>


  <?php
    if (!empty($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
    }else {
      $pageno = 1;
    }
    $numOfrecs = 5;
    $offset = ($pageno - 1) * $numOfrecs;

    if (empty($_POST['search'])) {
      $stmt = $pdo->prepare("SELECT * FROM temp_purchase ORDER BY id  DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM temp_purchase ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }else {
      $search = $_POST['search'];
      $stmt = $pdo->prepare("SELECT * FROM temp_purchase WHERE date LIKE '%$search%' ORDER BY id  DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM temp_purchase WHERE date LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }
   ?>


   <?php

  // Add Purchase
   if (isset($_POST['add_btn'])) {
    if (empty($_POST['date']) || empty($_POST['vr_no']) || empty($_POST['supplier_id']) || empty($_POST['item_id']) || empty($_POST['qty'])) {
      if (empty($_POST['date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['vr_no'])) {
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
      $date = $_POST['date'];
      $vr_no = $_POST['vr_no'];
      $supplier_id = $_POST['supplier_id'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $type = $_POST['type'];
      $foc = $_POST['foc'];
      $po_no = $_POST['po_no'];

      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_id=$item_id");
      $stmt->execute();
      $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);

      $price = $totalResult['original_price'];

      if (!empty($_POST['discount'])) {
        $discount_percentage = $_POST['discount'];
        $amount = $price * $qty;

        $percentage_amount = ($amount/100) * $discount_percentage;
        $amount = $amount - $percentage_amount;
        $addstmt = $pdo->prepare("INSERT INTO temp_purchase (date,vr_no,supplier_id,item_id,price,qty,type,percentage,percentage_amount,stock_foc,amount,po_no) VALUES (:date,:vr_no,:supplier_id,:item_id,:price,:qty,:type,:percentage,:percentage_amount,:stock_foc,:amount,:po_no)");
        $addResult = $addstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty, ':type'=>$type, ':percentage'=>$discount_percentage, ':percentage_amount'=>$percentage_amount, ':stock_foc'=>$foc, ':amount'=>$amount, ':po_no'=>$po_no)
        );

      }else {
        $amount = $price * $qty;
  
        $addstmt = $pdo->prepare("INSERT INTO temp_purchase (date,vr_no,supplier_id,item_id,price,qty,type,stock_foc,amount,po_no) VALUES (:date,:vr_no,:supplier_id,:item_id,:price,:qty,:type,:stock_foc,:amount,:po_no)");
        $addResult = $addstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty, ':type'=>$type, ':stock_foc'=>$foc, ':amount'=>$amount, ':po_no'=>$po_no)
        );
  
      }

      if ($addResult) {
        echo "<script>alert('Sussessfully added');window.location.href='purchase.php';</script>";
      }
    }
   }


  //  Save Purchase
   if (isset($_POST['save_btn'])) {
    $stmt = $pdo->prepare("SELECT * FROM temp_purchase");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $value) {
      $date = $value['date'];
      $vr_no = $value['vr_no'];
      $supplier_id = $value['supplier_id'];
      $item_id = $value['item_id'];
      $price = $value['price'];
      $qty = $value['qty'];
      $type = $value['type'];
      $foc = $value['stock_foc'];

      // Add Credit Purchase
      if ($type == "credit") {
        $parstmt = $pdo->prepare("INSERT INTO credit_purchase (date,vr_no,supplier_id,item_id,price,qty) VALUES (:date,:vr_no,:supplier_id,:item_id,:price,:qty)");
        $parResult = $parstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty)
        );

        // Purchase Id
        $purchase_idstmt = $pdo->prepare("SELECT * FROM credit_purchase ORDER BY id DESC");
        $purchase_idstmt->execute();
        $purchase_data = $purchase_idstmt->fetch(PDO::FETCH_ASSOC);

        $purchase_id = $purchase_data['id'];
        $amount = $value['price'] * $value['qty'];

        // Payable Balance
        $payabl_balancestmt = $pdo->prepare("SELECT * FROM payable WHERE supplier_id='$supplier_id' ORDER BY id DESC");
        $payabl_balancestmt->execute();
        $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($payabl_balancedata)) {
          $balance = $payabl_balancedata['balance'] + $amount;
        }else {
          $balance = $amount;
        }

        $payablstmt = $pdo->prepare("INSERT INTO payable (date,vr_no,supplier_id,amount,purchase_id,asc_id,group_id,balance,status) VALUES (:date,:vr_no,:supplier_id,:amount,:purchase_id,:purchase_id,:vr_no,:balance,'Pending')");
        $payabldata = $payablstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':amount'=>$amount, ':purchase_id'=>$purchase_id, ':balance'=>$balance)
        );

      }else {
      // Add Cash Purchase
        $cashstmt = $pdo->prepare("INSERT INTO cash_purchase (date,vr_no,supplier_id,item_id,price,qty) VALUES (:date,:vr_no,:supplier_id,:item_id,:price,:qty)");
        $cashResult = $cashstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty)
        );
      }

      // Add Stock

      // Stock Balance
      $stock_balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
      $stock_balancestmt->execute();
      $stock_balancedata = $stock_balancestmt->fetch(PDO::FETCH_ASSOC);

      if (!empty($stock_balancedata)) {
        $oldbalance = $stock_balancedata['balance'];
      }else{
        $oldbalance = 0;
      }
      $stockbalance = $oldbalance + $qty + $foc;

      // Foc Check
      if($foc != 0){
        $in_qty = $qty + $foc;
      }else{
        $in_qty = $qty;
      }

      $stockstmt = $pdo->prepare("INSERT INTO stock (date,item_id,vr_no,to_from,in_qty,foc_qty,balance) VALUES (:date,:item_id,:vr_no,'purchase',:in_qty,:foc_qty,:balance)");
      $stockdata = $stockstmt->execute(
        array(':date'=>$date, ':vr_no'=>$vr_no, ':item_id'=>$item_id, ':in_qty'=>$in_qty, ':foc_qty'=>$foc, ':balance'=>$stockbalance)
      );


      $id = $value['id'];
      $deletestmt = $pdo->prepare("DELETE FROM temp_purchase WHERE id='$id'");
      $deletestmt->execute();
    }
   }


     $selestmt = $pdo->prepare("SELECT * FROM temp_purchase ORDER BY id DESC");
     $selestmt->execute();
     $seleResult = $selestmt->fetch(PDO::FETCH_ASSOC);

    ?>



    <div class="col-md-12" style="margin-top:-30px;">
      <div class="card">
        <div class="card-body cb" style="background-color:lightgray; border-radius:5px; ">
          <div class="text">
            <h4><b>Purchase</b></h4>
          </div>
          <form class="" action="" method="post" style="margin-top:-25px;">
            <div class="d-flex">
              <div class="col-6 d-flex">
                <div class="col">
                  <label for="" class="mt-4"><b>Date</b></label>
                  <input type="date" class="form-control" placeholder="Date" name="date">
                  <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                </div>
                <div class="col">
                  <label for="" class="mt-4"><b>Vr_No</b></label>
                  <input type="text" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php echo 25 . rand(1,999999) ?>" readonly>
                  <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
                </div>
              <div class="col">
                <label for="" class="mt-4"><b>Purchase Order No</b></label>
                <select name="po_no" id="" class="form-control">
                  <option value="">Select PO_No</option>
                  <?php
                  $po_nostmt = $pdo->prepare("SELECT * FROM purchase_order WHERE status LIKE '%ending%' ORDER BY id DESC");
                  $po_nostmt->execute();
                  $po_nodatas = $po_nostmt->fetchAll();
                  foreach ($po_nodatas as $po_nodata) {
                    ?>
                    <option value="<?php echo $po_nodata['order_no']; ?>"><?php echo $po_nodata['order_no']; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
              </div>
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
            
            <div class="d-flex">
              <div class="col-5 d-flex">
                <div class="col">
                  <label for=""><b>Item_Id</b></label>
                  <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchitemNameFromId()">
                  <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                </div>
                <div class="col">
                  <label for=""><b>Item_Name</b></label>
                  <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchitemIdFromName()">
                </div>
                <div class="col">
                  <label for=""><b>Qty</b></label>
                  <input type="number" class="form-control" placeholder="Qty" name="qty">
                  <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                </div>
              </div>
              <div class="col-5 d-flex">
                <div class="col">
                  <label for="" class=""><b>Discount</b></label>
                  <input type="number" class="form-control" placeholder="Discount" name="discount">
                  <p style="color:red;"></p>
                </div>

                <div class="col">
                  <label for="" class=""><b>Foc</b></label>
                  <input type="number" class="form-control" placeholder="Foc" name="foc">
                  <p style="color:red;"></p>
                </div>

                <div class="col">
                  <label for=""><b>Payment</b></label>
                  <select name="type" class="form-control">
                      <option value="cash">Cash</option>
                      <option value="credit">Credit</option>
                    </select>
                </div>
              </div>
              <div class="col-2 mt-4">
                <div class="">
                  <button type="submit" name="add_btn" class="add_btn form-control mt-2">Add</button>
                </div>
              </div>
            </div>            

          </div>
        </div>
      </form>
          <table class="table table-bordered mt-4 table-hover">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Date</th>
                <th>Vr_No</th>
                <th>Supplier_Name</th>
                <th>Item_Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>percentage_amount</th>
                <th>Foc</th>
                <th>Total</th>
                <th style="width:40px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if ($result) {
                  $id = 1;
                  foreach ($result as $value) {
                    $supplier_id = $value['supplier_id'];

                    $supplierIdstmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id='$supplier_id'");
                    $supplierIdstmt->execute();
                    $supplierIdResult = $supplierIdstmt->fetch(PDO::FETCH_ASSOC);

                    $item_id = $value['item_id'];
                    $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
                    $itemstmt->execute();
                    $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
               ?>
              <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $value['date'];?></td>
                <td><?php echo $value['vr_no'];?></td>
                <td><?php echo $supplierIdResult['supplier_name'];?></td>
                <td><?php echo $itemResult['item_name']; ?></td>
                <td><?php echo $value['price'];?></td>
                <td><?php echo $value['qty']; ?></td>
                <td><?php echo $value['price'] * $value['qty']; ?></td>
                <td><?php echo $value['percentage_amount'];?></td>
                <td><?php echo $value['stock_foc'];?></td>
                <td><?php echo $value['amount']; ?></td>

                <td>
                  <div class="btn-group">
                    <div class="container">
                    <a href="temp_edit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning btn-sm">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                      </svg>
                    </a>
                    </div>
                    <div class="contaienr">
                    <a href="temp_delete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to Delete?');">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                      </svg>
                    </a>
                    </div>
                  </div>
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
      <form class="" action="" method="post">
        <div class="fixed-top" style="margin-left:1370px; margin-top:700px;">
          <button type="submit" class="add_btn" name="save_btn" style="padding-bottom:7px;padding-top:5px; padding-left:20px; padding-right:20px;width:120px;">Save</button>
        </div>
      </form>
        <br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br>
            <br>
      </div>
      </div>

    <!-- Main content -->
    <script>
    function fetchSupplierNameFromId() {
      const supplierId = document.getElementById('supplier_id').value;

      if (supplierId.trim() === "") {
        document.getElementById('supplier_name').value = "";
        return;
      }

      fetch('get_supplier_by_id.php?supplier_id=' + supplierId)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('supplier_name').value = data.supplier_name;
          } else {
            document.getElementById('supplier_name').value = "Not found";
          }
        })
        .catch(err => {
          console.error("Fetch error:", err);
        });
    }

    function fetchSupplierIdFromName() {
      const supplierName = document.getElementById('supplier_name').value;

      if (supplierName.trim() === "") {
        document.getElementById('supplier_id').value = "";
        return;
      }

      fetch('get_supplier_by_name.php?supplier_name=' + encodeURIComponent(supplierName))
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('supplier_id').value = data.supplier_id;
          } else {
            document.getElementById('supplier_id').value = "Not found";
          }
        })
        .catch(err => {
          console.error("Fetch error:", err);
        });
    }
    </script>

    <!-- next_script -->

    <script>
    function fetchitemNameFromId() {
  const itemId = document.getElementById('item_id').value;

  if (itemId.trim() === "") {
    document.getElementById('item_name').value = "";
    return;
  }

  fetch('get_item_by_id.php?item_id=' + itemId)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('item_name').value = data.item_name;
      } else {
        document.getElementById('item_name').value = "Not found";
      }
    })
    .catch(err => {
      console.error("Fetch error:", err);
    });
}


    function fetchitemIdFromName() {
      const itemName = document.getElementById('item_name').value;

      if (itemName.trim() === "") {
        document.getElementById('item_id').value = "";
        return;
      }

      fetch('get_item_by_name.php?item_name=' + encodeURIComponent(itemName))
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('item_id').value = data.item_id;
          } else {
            document.getElementById('item_id').value = "Not found";
          }
        })
        .catch(err => {
          console.error("Fetch error:", err);
        });
    }
    </script>

<?php include 'footer.html'; ?>
