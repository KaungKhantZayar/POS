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
    if (empty($_POST['date']) || empty($_POST['vr_no']) || empty($_POST['customer_id']) || empty($_POST['item_id']) || empty($_POST['qty'])) {
      if (empty($_POST['date'])) {
        $dateError = 'Date is required';
      }
      if (empty($_POST['vr_no'])) {
        $vr_noError = 'Vr_No is required';
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
      $vr_no = $_POST['vr_no'];
      $customer_id = $_POST['customer_id'];
      $item_id = $_POST['item_id'];
      $qty = $_POST['qty'];
      $type = $_POST['type'];

      $addstmt = $pdo->prepare("INSERT INTO temp_sale (date,vr_no,customer_id,item_id,qty,type) VALUES (:date,:vr_no,:customer_id,:item_id,:qty,:type)");
      $addResult = $addstmt->execute(
        array(':date'=>$date, ':vr_no'=>$vr_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id, ':qty'=>$qty, ':type'=>$type)
      );
      if ($addResult) {
        echo "<script>alert('Sussessfully added');window.location.href='sale.php';</script>";
      }
    }
   }

   if (isset($_POST['save_btn'])) {
    $stmt = $pdo->prepare("SELECT * FROM temp_sale");
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $value) {
      $date = $value['date'];
      $vr_no = $value['vr_no'];
      $customer_id = $value['customer_id'];
      $item_id = $value['item_id'];
      $qty = $value['qty'];
      $type = $value['type'];

      if ($type == "credit") {
        $parstmt = $pdo->prepare("INSERT INTO credit_sale (date,vr_no,customer_id,item_id,qty) VALUES (:date,:vr_no,:customer_id,:item_id,:qty)");
        $parResult = $parstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id,':qty'=>$qty)
        );

        $purchase_idstmt = $pdo->prepare("SELECT * FROM credit_purchase ORDER BY id DESC");
        $purchase_idstmt->execute();
        $purchase_data = $purchase_idstmt->fetch(PDO::FETCH_ASSOC);

        // $purchase_id = $purchase_data['id'];
        // $amount = $value['price'] * $value['qty'];
        //
        // $payabl_balancestmt = $pdo->prepare("SELECT * FROM payaple WHERE supplier_id='$supplier_id' ORDER BY id DESC");
        // $payabl_balancestmt->execute();
        // $payabl_balancedata = $payabl_balancestmt->fetch(PDO::FETCH_ASSOC);
        //
        // if (!empty($payabl_balancedata)) {
        //   $balance = $payabl_balancedata['balance'] + $amount;
        // }else {
        //   $balance = $amount;
        // }
        //
        // $payablstmt = $pdo->prepare("INSERT INTO payaple (date,vr_no,supplier_id,amount,purchase_id,balance) VALUES (:date,:vr_no,:supplier_id,:amount,:purchase_id,:balance)");
        // $payabldata = $payablstmt->execute(
        //   array(':date'=>$date, ':vr_no'=>$vr_no, ':supplier_id'=>$supplier_id, ':amount'=>$amount, ':purchase_id'=>$purchase_id, ':balance'=>$balance)
        // );

      }else {
        $cashstmt = $pdo->prepare("INSERT INTO cash_sale (date,vr_no,customer_id,item_id,qty) VALUES (:date,:vr_no,:customer_id,:item_id,:qty)");
        $cashResult = $cashstmt->execute(
          array(':date'=>$date, ':vr_no'=>$vr_no, ':customer_id'=>$customer_id, ':item_id'=>$item_id,':qty'=>$qty)
        );
      }

      $id = $value['id'];
      $deletestmt = $pdo->prepare("DELETE FROM temp_sale WHERE id='$id'");
      $deletestmt->execute();
    }
   }


     $selestmt = $pdo->prepare("SELECT * FROM temp_sale ORDER BY id DESC");
     $selestmt->execute();
     $seleResult = $selestmt->fetch(PDO::FETCH_ASSOC);

    ?>



    <div class="col-md-12" style="margin-top:-30px;">
      <div class="card">
        <div class="card-body cb" style="background-color:lightgray; border-radius:5px; ">
          <div class="text">
            <h4 class="ms-4"><b>Sale</b></h4>
          </div>
          <form class="" action="" method="post" style="margin-top:-25px;">
            <div class="row">
              <div class="col-3">
                <label for="" class="mt-4"><b>Date</b></label>
                <input type="date" class="form-control" placeholder="Date" name="date">
                <p style="color:red;"><?php echo empty($dateError) ? '' : '*'.$dateError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4"><b>Vr_No</b></label>
                <input type="text" class="form-control" placeholder="Vr_No" name="" value="<?php if(!empty($seleResult)){ echo $seleResult['vr_no'] + 1; }else{ echo "25001"; } ?>" disabled>
                <input type="hidden" class="form-control" placeholder="Vr_No" name="vr_no" value="<?php if(!empty($seleResult)){ echo $seleResult['vr_no'] + 1; }else{ echo "25001"; } ?>">
                <p style="color:red;"><?php echo empty($vr_noError) ? '' : '*'.$vr_noError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4"><b>Customer_Id</b></label>
                <input type="text" id="customer_id" oninput="fetchSaleNameFromId()" class="form-control" placeholder="Customer_Id" name="customer_id">
                <p style="color:red;"><?php echo empty($customer_idError) ? '' : '*'.$customer_idError;?></p>
              </div>
              <div class="col-3">
                <label for="" class="mt-4"><b>Customer_Name</b></label>
                <input type="text" id="customer_name" class="form-control" placeholder="Customer_Name" name="customer_Name" oninput="fetchSaleIdFromName()">
              </div>
              <div class="col-3" style="margin-top:-20px;">
                <label for="" class="mt-4"><b>Item_Id</b></label>
                <input type="text" id="item_id" class="form-control" placeholder="Item_Id" name="item_id" oninput="fetchitemNameFromId()" >
                <p style="color:red;"><?php echo empty($item_idError) ? '' : '*'.$item_idError;?></p>
              </div>
              <div class="col-3" style="margin-top:-20px;">
                <label for="" class="mt-4"><b>Item_Name</b></label>
                <input type="text" id="item_name" class="form-control" placeholder="Item_Name" name="item_name" oninput="fetchitemIdFromName()">
              </div>
              <div class="col-3 ms-3" style="margin-top:-20px;">
                <label for="" class="mt-4"><b>Payment Type</b></label>
                <select name="type" class="form-control" style="width:100px;">
                    <option value="cash">Cash</option>
                    <option value="credit">Credit</option>
                </select>
              </div>
              <div class="col-3" style="margin-left:-170px; margin-top:-20px;">
                <label for="" class="mt-4"><b>Qty</b></label>
                <input type="number" class="form-control" placeholder="Qty" name="qty" style="width:120px;">
                <p style="color:red;"><?php echo empty($qtyError) ? '' : '*'.$qtyError;?></p>
              </div>

              <div class="col-3" style="margin-top:-55px; margin-left:800px;">
                <button type="submit" name="add_btn" class="add_btn form-control" style="width:280px; margin-left:140px; margin-top:px;">Add</button>
              </div>
            </div>

          </div>
        </div>
      </form>
        <!-- /.card-header -->
        <div class="card-body mt-4">

          <div class="col-3" style="margin-left:50px; margin-top:-30px;">
          </div>

        <div class="outer">

          <table class="table table-bordered mt-4 table-hover">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Date</th>
                <th>Vr_No</th>
                <th>Customer_Name</th>
                <th>Item_Name</th>
                <th>Qty</th>
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
                <td><?php echo $value['vr_no'];?></td>
                <td><?php echo $customerIdResult['customer_name'];?></td>
                <td><?php echo $itemResult['item_name']; ?></td>
                <td><?php echo $value['qty']; ?></td>
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
        <br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br>
            <br>
      </div>
      </div>

    <!-- Main content -->
    <script>
    function fetchSaleNameFromId() {
      const customerId = document.getElementById('customer_id').value;

      if (customerId.trim() === "") {
        document.getElementById('customer_name').value = "";
        return;
      }

      fetch('get_sale_by_id.php?customer_id=' + customerId)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('customer_name').value = data.customer_name;
          } else {
            document.getElementById('customer_name').value = "Not found";
          }
        })
        .catch(err => {
          console.error("Fetch error:", err);
        });
    }

    function fetchSaleIdFromName() {
      const customerName = document.getElementById('customer_name').value;

      if (supplierName.trim() === "") {
        document.getElementById('customer_id').value = "";
        return;
      }

      fetch('get_sale_by_name.php?customer_name=' + encodeURIComponent(customerName))
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.getElementById('customer_id').value = data.customer_id;
          } else {
            document.getElementById('customer_id').value = "Not found";
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
