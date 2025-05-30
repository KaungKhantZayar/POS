<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
?>

<?php include 'header.php'; ?>

<style media="screen">
.back_btn{
  text-decoration: none;
  border:1px solid black;
  padding:10px;
  border-radius:5px;
  color:black;
  transition:0.5s;
}
.back_btn:hover{
  background-color: black;
  color:white;
}
</style>

<?php
    if (isset($_POST['serach_btn'])) {
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];

      if ($_GET['type'] == 'cash') {
        $stmt = $pdo->prepare("SELECT * FROM cash_purchase WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }else {
        $stmt = $pdo->prepare("SELECT * FROM credit_purchase WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }
    }
 ?>

<?php
  if ($_GET['report_name'] == 'date') {

    ?>
    <br><br>
    <div class="outer" style="width:100%; padding:10px; margin-top:-65px;">
      <form class="" action="" method="post">
      <div class="d-flex">
        <h5><?php
          if ($_GET['type'] == 'cash') {
            echo "Date-အလိုက်ကြည့်ရန် Cash-Purchase ";
          }else {
            echo "Date-အလိုက်ကြည့်ရန် Credit-Purchase";
          }
        ?></h5>
        <label for="" class="mt-2 me-3" style=" margin-left:220px;">Start Date :</label>
          <input type="date" class="form-control" name="start_date" value="" style="width:200px;">

          <label for="" class="mt-2 ms-3">End Date :</label>
          <input type="date" class="ms-3 form-control" name="end_date" value="" style="width:200px;">

          <button type="submit" name="serach_btn" class="btn btn-success ms-3 btn-sm">Search</button>
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
          </tr>
        </thead>
        <tbody>
          <?php
             if (!empty($result)) {
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
            <td><?php echo $id;?></td>
            <td><?php echo $value['date']; ?></td>
            <td><?php echo $value['vr_no']; ?></td>
            <td><?php echo $supplierIdResult['supplier_name']; ?></td>
            <td><?php echo $itemResult['item_name']; ?></td>
            <td><?php echo $value['price'];?></td>
            <td><?php echo $value['qty'];?></td>
          </tr>
          <?php
          $id++;
            }
          }
           ?>
        </tbody>
      </table>
        <div class="fixed-top" style="margin-left:1370px; margin-top:700px;">
          <a href="chose_report.php" type="submit" class="back_btn" name="" style="padding-bottom:7px;padding-top:5px; padding-left:20px;width:77px;">Back</a>
        </div>
      <!-- <a href="" class="fixed-top form-control" type="button" name="button" style="margin-top:200px;">Back</a> -->
    </div>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>



  <?php

  }elseif ($_GET['report_name'] == 'vr_no') {

    if (isset($_POST['serach_btn2'])) {
      $vr_no_search = $_POST['vr_no_search'];

      if ($_GET['type'] == 'cash') {
        $stmt = $pdo->prepare("SELECT * FROM cash_purchase WHERE vr_no LIKE '%$vr_no_search%' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }else {
        $stmt = $pdo->prepare("SELECT * FROM credit_purchase WHERE vr_no LIKE '%$vr_no_search%' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }
    }

    ?>
    <br><br>
    <div class="outer" style="width:100%; padding:10px; margin-top:-65px;">
      <form class="" action="" method="post">
      <div class="d-flex">
        <h5>
          <?php
            if ($_GET['type'] == 'cash') {
              echo "Vouecher-အလိုက်ကြည့်ရန် Cash-Vouecher-No";
            }else {
              echo "Vouecher-အလိုက်ကြည့်ရန် Credit-Vouecher-No";
            }
           ?>
        </h5>
        <label for="" class="mt-2 me-3" style=" margin-left:540px;">Vr_NO :</label>
          <input type="number" class="form-control" name="vr_no_search" value="" style="width:200px;">
          <button type="submit" name="serach_btn2" class="btn btn-success ms-3 btn-sm">Search</button>
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
          </tr>
        </thead>
        <tbody>
          <?php
             if (!empty($result)) {
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
            <td><?php echo $id;?></td>
            <td><?php echo $value['date']; ?></td>
            <td><?php echo $value['vr_no']; ?></td>
            <td><?php echo $supplierIdResult['supplier_name']; ?></td>
            <td><?php echo $itemResult['item_name']; ?></td>
            <td><?php echo $value['price'];?></td>
            <td><?php echo $value['qty'];?></td>
          </tr>
          <?php
          $id++;
            }
          }
           ?>
        </tbody>
      </table>
    </div>
    <div class="fixed-top" style="margin-left:1370px; margin-top:700px;">
      <a href="chose_report.php" type="submit" class="back_btn" name="" style="padding-bottom:7px;padding-top:5px; padding-left:20px;width:77px;">Back</a>
    </div>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>

  <?php

  }elseif ($_GET['report_name'] == 'item') {

    if (isset($_POST['item_search'])) {
      $item_search = $_POST['item_search'];

      if ($_GET['type'] == 'cash') {
        $stmt = $pdo->prepare("SELECT * FROM cash_purchase WHERE vr_no LIKE '%$item_search%' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }else {
        $stmt = $pdo->prepare("SELECT * FROM credit_purchase WHERE vr_no LIKE '%$item_search%' ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
      }
    }

    ?>
    <br><br>
    <div class="outer" style="width:100%; padding:10px; margin-top:-65px;">
      <form class="" action="" method="post">
      <div class="d-flex">
        <h5>
          <?php
            if ($_GET['type'] == 'cash') {
              echo "Cash-Item အလိုက်ကြည့်ရန်";
            }else {
              echo "Credit-Item အလိုက်ကြည့်ရန်";
            }
           ?>
        </h5>
        <label for="" class="mt-2 me-3" style=" margin-left:540px;">Vr_NO :</label>
          <input type="number" class="form-control" name="item_search" value="" style="width:200px;">
          <button type="submit" name="serach_btn2" class="btn btn-success ms-3 btn-sm">Search</button>
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
          </tr>
        </thead>
        <tbody>
          <?php
             if (!empty($result)) {
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
            <td><?php echo $id;?></td>
            <td><?php echo $value['date']; ?></td>
            <td><?php echo $value['vr_no']; ?></td>
            <td><?php echo $supplierIdResult['supplier_name']; ?></td>
            <td><?php echo $itemResult['item_name']; ?></td>
            <td><?php echo $value['price'];?></td>
            <td><?php echo $value['qty'];?></td>
          </tr>
          <?php
          $id++;
            }
          }
           ?>
        </tbody>
      </table>
    </div>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br>

  <?php

  }
?>


<?php include 'footer.html'; ?>
