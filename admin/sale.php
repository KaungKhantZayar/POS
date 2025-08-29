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
      $stmt = $pdo->prepare("SELECT * FROM temp_sale ORDER BY id  DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM temp_sale ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }else {
      $search = $_POST['search'];
      $stmt = $pdo->prepare("SELECT * FROM temp_sale WHERE date LIKE '%$search%' ORDER BY id  DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM temp_sale WHERE date LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }
   ?>
   
   <?php

   if (isset($_POST['add_btn'])) {
    if (empty($_POST['date']) || empty($_POST['gin_no']) || empty($_POST['customer_id']) || empty($_POST['item_id']) || empty($_POST['qty'])) {
      if (empty($_POST['date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['gin_no'])) {
        $gin_noError = 'gin_no is required';
      }
      if (empty($_POST['customer_id'])) {
        $customer_idError = 'Customer is required';
      }
      if (empty($_POST['item_id'])) {
        $item_idError = 'Item_Id is required';
      }
      if (empty($_POST['qty'])) {
        $qtyError = 'Qty is required';
      }
    }else {
      $date = $_POST['date'];
      $gin_no = $_POST['gin_no'];
      $customer_id = $_POST['customer_id'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $type = $_POST['type'];
      $foc = $_POST['foc'];
      $so_no = $_POST['so_no'];

      // Stock Balance Check
      $stock_balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id=$item_id ORDER BY id DESC");
      $stock_balancestmt->execute();
      $stock_balance = $stock_balancestmt->fetch(PDO::FETCH_ASSOC);
      
      if($qty > $stock_balance['balance']){
        echo "<script>alert('Stock is not enough');window.location.href='sale.php';</script>";
      }else{
        $stmt = $pdo->prepare("SELECT * FROM item WHERE item_id=$item_id");
        $stmt->execute();
        $totalResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $price = $totalResult['selling_price'];
  
        if (!empty($_POST['discount'])) {
          $discount_percentage = $_POST['discount'];
          $amount = $price * $qty;
  
          $percentage_amount = ($amount/100) * $discount_percentage;
          $amount = $amount - $percentage_amount;
          
          $addstmt = $pdo->prepare("INSERT INTO temp_sale (date,gin_no,customer_id,item_id,price,qty,type,percentage,percentage_amount,stock_foc,amount,so_no) VALUES (:date,:gin_no,:customer_id,:item_id,:price,:qty,:type,:percentage,:percentage_amount,:stock_foc,:amount,:so_no)");
          $addResult = $addstmt->execute(
            array(':date'=>$date, ':gin_no'=>$gin_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty, ':type'=>$type, ':percentage'=>$discount_percentage, ':percentage_amount'=>$percentage_amount, ':stock_foc'=>$foc, ':amount'=>$amount, ':so_no'=>$so_no)
          );
        
        }else {
          $amount = $price * $qty;
          $addstmt = $pdo->prepare("INSERT INTO temp_sale (date,gin_no,customer_id,item_id,price,qty,type,stock_foc,amount,so_no) VALUES (:date,:gin_no,:customer_id,:item_id,:price,:qty,:type,:stock_foc,:amount,:so_no)");
          $addResult = $addstmt->execute(
            array(':date'=>$date, ':gin_no'=>$gin_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id, ':price'=>$price, ':qty'=>$qty, ':type'=>$type, ':stock_foc'=>$foc, ':amount'=>$amount, ':so_no'=>$so_no)
          );
        }
  
        if ($addResult) {
          echo "<script>alert('Sussessfully added');window.location.href='sale.php';</script>";
        }
      }
    }
   }

   if (isset($_POST['save_btn'])) {
    $stmt = $pdo->prepare("SELECT * FROM temp_sale");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $value) {
      $date = $value['date'];
      $gin_no = $value['gin_no'];
      $customer_id = $value['customer_id'];
      $item_id = $value['item_id'];
      $qty = $value['qty'];
      $type = $value['type'];
      $foc = $value['stock_foc'];
      
      // Add Credit Sale
      if ($type == "credit") {
        $parstmt = $pdo->prepare("INSERT INTO credit_sale (date,gin_no,customer_id,item_id,qty) VALUES (:date,:gin_no,:customer_id,:item_id,:qty)");
        $parResult = $parstmt->execute(
          array(':date'=>$date, ':gin_no'=>$gin_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id,':qty'=>$qty)
        );

        $sale_idstmt = $pdo->prepare("SELECT * FROM credit_sale ORDER BY id DESC");
        $sale_idstmt->execute();
        $sale_iddata = $sale_idstmt->fetch(PDO::FETCH_ASSOC);

        $sale_id = $sale_iddata['id'];
        $amount = $value['price'] * $value['qty'];
        
        // receivable balance
        $receivable_balancestmt = $pdo->prepare("SELECT * FROM receivable WHERE customer_id='$customer_id' ORDER BY asc_id DESC");
        $receivable_balancestmt->execute();
        $receivable_balancedata = $receivable_balancestmt->fetch(PDO::FETCH_ASSOC);
        
        $asc_id = $receivable_balancedata['asc_id'] + 1;
        if (!empty($receivable_balancedata)) {
          $balance = $receivable_balancedata['balance'] + $amount;
        }else {
          $balance = $amount;
        }
        
        $salestmt = $pdo->prepare("INSERT INTO receivable (date,gin_no,customer_id,amount,sale_id,asc_id,group_id,balance) VALUES (:date,:gin_no,:customer_id,:amount,:sale_id,:asc_id,:gin_no,:balance)");
        $saledata = $salestmt->execute(
          array(':date'=>$date, ':gin_no'=>$gin_no, ':customer_id'=>$customer_id, ':amount'=>$amount, ':sale_id'=>$sale_id, ':asc_id'=>$asc_id, ':balance'=>$balance)
        );

      }else {
      // Add Cash Sale
        $cashstmt = $pdo->prepare("INSERT INTO cash_sale (date,gin_no,customer_id,item_id,qty) VALUES (:date,:gin_no,:customer_id,:item_id,:qty)");
        $cashResult = $cashstmt->execute(
          array(':date'=>$date, ':gin_no'=>$gin_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id,':qty'=>$qty)
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
      $stockbalance = $oldbalance - ($qty + $foc);

      // Foc Check
      if($foc != 0){
        $out_qty = $qty + $foc;
      }else{
        $out_qty = $qty;
      }

      $stockstmt = $pdo->prepare("INSERT INTO stock (date,item_id,gin_no,to_from,out_qty,foc_qty,balance) VALUES (:date,:item_id,:gin_no,'sale',:out_qty,:foc_qty,:balance)");
      $stockdata = $stockstmt->execute(
        array(':date'=>$date, ':gin_no'=>$gin_no, ':item_id'=>$item_id, ':out_qty'=>$out_qty, ':foc_qty'=>$foc, ':balance'=>$stockbalance)
      );

      // Delete Temp Sale
      $id = $value['id'];
      $deletestmt = $pdo->prepare("DELETE FROM temp_sale WHERE id='$id'");
      $deletestmt->execute();

      echo "<script>window.location.href='sale.php';</script>";
    }
   }


     $selestmt = $pdo->prepare("SELECT * FROM temp_sale ORDER BY id DESC");
     $selestmt->execute();
     $seleResult = $selestmt->fetch(PDO::FETCH_ASSOC);

    ?>
<script>
function fetchCustomerNameFromId() {
    let customerId = document.getElementById("customer_id").value;
    if (customerId.trim() === "") return;

    fetch("get_customer_by_id.php?customer_id=" + customerId)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("customer_name").value = data.customer_name;
            } else {
                document.getElementById("customer_name").value = "";
            }
        })
        .catch(err => console.error("Error:", err));
}

function fetchCustomerIdFromName() {
    let customerName = document.getElementById("customer_name").value;
    if (customerName.trim() === "") return;

    fetch("get_customer_by_name.php?customer_name=" + customerName)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("customer_id").value = data.customer_id;
            } else {
                document.getElementById("customer_id").value = "";
            }
        })
        .catch(err => console.error("Error:", err));
}

function fetchItemNameFromId() {
    let itemId = document.getElementById("item_id").value.trim();

    if (itemId !== "") {
        fetch("get_item_by_id.php?item_id=" + encodeURIComponent(itemId))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("item_name").value = data.item_name;
                document.getElementById("stock_balance").innerText = "Balance Qty is " + data.stock_balance + " pcs";
                document.getElementById("selling_price").value = data.selling_price;
            } else {
                document.getElementById("item_name").value = "";
                document.getElementById("stock_balance").innerText = "";
                document.getElementById("selling_price").value = "";
            }
        })
        .catch(err => console.error("Error fetching item name:", err));
    } else {
        document.getElementById("item_name").value = "";
        document.getElementById("stock_balance").innerText = "";
        document.getElementById("selling_price").value = "";
    }
}

function fetchItemIdFromName() {
    let itemName = document.getElementById("item_name").value.trim()  ;

    if (itemName !== "") {
        fetch("get_item_by_name.php?item_name=" + encodeURIComponent(itemName))
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById("item_id").value = data.item_id;
                document.getElementById("stock_balance").innerText = "Balance Qty is " + data.stock_balance + " pcs";
                document.getElementById("selling_price").value = data.selling_price;
            } else {
                document.getElementById("item_id").value = "";
                document.getElementById("stock_balance").innerText = "";
                document.getElementById("selling_price").value = "";
            }
        })
        .catch(err => console.error("Error fetching item id:", err));
    } else {
        document.getElementById("item_id").value = "";
        document.getElementById("stock_balance").innerText = "";
        document.getElementById("selling_price").value = "";
    }
}
</script>
    <div class="col-md-12 mt-4 px-3 pt-1">
      <h4 class="mb-3 d-flex align-items-center justify-content-between">
        Sale Listing
        <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#newSaleForm" aria-expanded="true" aria-controls="newSaleForm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5"/>
            </svg>
          </button>
      </h4>
      <div class="collapse show" id="newSaleForm">
      <div class="card">
          <div class="card-body cb" style="border-radius:5px;">
            <div class="text">
            </div>
            <form class="" action="" method="post" style="margin-top:-25px;">
              <div class="row">
                <div class="col-6 d-flex">
                  <div class="col">
                    <label for="" class="mt-4">Date</label>
                    <input type="date" class="form-control" placeholder="Date" name="date">
                    <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
                  </div>
                  <div class="col">
                    <label for="" class="mt-4">GIN_No</label>
                    <input type="text" class="form-control" placeholder="gin_no" name="gin_no" readonly value="<?php echo 35 . rand(1,999999) ?>">
                    <p style="color:red;"><?php echo empty($gin_noError) ? '' : '*'.$gin_noError;?></p>
                  </div>
                  <div class="col">
                    <label for="" class="mt-4">SO No</label>
                    <select name="so_no" id="" class="form-control">
                      <option value="">Select SO_No</option>
                      <?php
                      $so_nostmt = $pdo->prepare("SELECT * FROM sale_order WHERE status LIKE '%ending%' ORDER BY id DESC");
                      $so_nostmt->execute();
                      $so_nodatas = $so_nostmt->fetchAll();
                      foreach ($so_nodatas as $so_nodata) {
                        ?>
                        <option value="<?php echo $so_nodata['order_no']; ?>"><?php echo $so_nodata['order_no']; ?></option>
                        <?php
                      }
                      ?>
                    </select>
                    <p style="color:red;"><?php echo empty($gin_noError) ? '' : '*'.$gin_noError;?></p>
                  </div>
                </div>
                <div class="col-6 d-flex">
                  <div class="col">
                    <label for="" class="mt-4">Customer_Id</label>
                    <input type="text" id="customer_id" oninput="fetchCustomerNameFromId()" class="form-control" placeholder="Customer_Id" name="customer_id">
                    <p style="color:red;"><?php echo empty($customer_idError) ? '' : '*'.$customer_idError;?></p>
                  </div>
                  <div class="col">
                    <label for="" class="mt-4">Customer_Name</label>
                    <input type="text" id="customer_name" class="form-control" placeholder="Customer_Name" name="customer_Name" oninput="fetchCustomerIdFromName()">
                  </div>
                  <div class="col">
                    <label class="mt-4">Payment</label>
                    <select name="type" class="form-control">
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                      </select>
                  </div>
                </div>
              </div>
  
                <div class="d-flex">
                  <div class="col-2">
                    <label for="">Item_Id</label>
                    <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchItemNameFromId()">
                    <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
                    <span style="color:green; font-size: 13px;" id="stock_balance"></span>
                  </div>
                  <div class="col-2">
                    <label for="">Item_Name</label>
                    <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchItemIdFromName()">
                  </div>
                  <div class="col-2">
                    <label for="">Selling Price</label>
                    <input type="number" class="form-control" placeholder="Selling Price" name="selling_price" id="selling_price">
                    <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                  </div>
                  <div class="col">
                    <label for="">Qty</label>
                    <input type="number" class="form-control" placeholder="Qty" name="qty">
                    <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
                  </div>
                  <div class="col">
                    <label for="" class="">Discount</label>
                    <input type="number" class="form-control" placeholder="Discount" name="discount">
                    <p style="color:red;"></p>
                  </div>
  
                  <div class="col">
                    <label for="" class="">Foc</label>
                    <input type="number" class="form-control" placeholder="Foc" name="foc">
                    <p style="color:red;"></p>
                  </div>
                  <div class="col-2 mt-4">
                      <button type="submit" name="add_btn" class="add_btn form-control mt-2">Add</button>
                  </div>
                </div>

              
            </div>
          </div>
        </form>  
      </div>

        <div class="outer">
          <table class="table table-hover">
            <thead class="custom-thead">
              <tr>
                <th style="width: 10px">#</th>
                <th>Date</th>
                <th>GIN_No</th>
                <th>Customer_Name</th>
                <th>Item_Name</th>
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
                    $customer_id = $value['customer_id'];

                    $customerIdstmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id='$customer_id'");
                    $customerIdstmt->execute();
                    $customerIdResult = $customerIdstmt->fetch(PDO::FETCH_ASSOC);

                    $item_id = $value['item_id'];
                    $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
                    $itemstmt->execute();
                    $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
               ?>
              <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $value['date'];?></td>
                <td><?php echo $value['gin_no'];?></td>
                <td><?php echo $customerIdResult['customer_name'];?></td>
                <td><?php echo $itemResult['item_name']; ?></td>
                <td><?php echo $value['qty']; ?></td>
                <td><?php echo $value['price'] * $value['qty']; ?></td>
                <td><?php echo $value['percentage_amount'];?></td>
                <td><?php echo $value['stock_foc'];?></td>
                <td><?php echo number_format($value['amount']); ?></td>
                <td>
                  <div class="btn-group">
                    <div class="container">
                    <a href="sale_edit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning btn-sm">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                      </svg>
                    </a>
                    </div>
                    <div class="contaienr">
                    <a href="sale_delete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to Delete?');">
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
      </div>
      </div>

<?php include 'footer.html'; ?>
