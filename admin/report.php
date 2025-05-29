<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
?>

<?php include 'header.php'; ?>

<style media="screen">
</style>

<?php
    if (isset($_POST['serach_btn'])) {
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];

      $stmt = $pdo->prepare("SELECT * FROM parchase WHERE date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }
 ?>

<?php
  if ($_GET['report_name'] == 'date') {
    ?>
    <br><br>
    <div class="outer" style="width:100%; padding:10px; margin-top:-30px;">
      <form class="" action="" method="post">
      <div class="d-flex">
        <h5>Date အလိုက်ကြည့်ရန်</h5>
        <label for="" class="mt-2 me-3" style=" margin-left:400px;">Start Date :</label>
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
            <td><?php echo $value['qty']; ?></td>
          </tr>
          <?php
          $id++;
            }
          }
           ?>
        </tbody>
      </table>
    </div>
    <?php
  }elseif ($_GET['report_name'] == 'vr_no') {
    echo "vr_no";
  }elseif ($_GET['report_name'] == 'item') {
    echo "item";
  }
?>

<?php include 'footer.html'; ?>
