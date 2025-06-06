<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
  ?>

  <?php include 'header.php'; ?>

<style media="screen">
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
.search_btn{
  background-color:#1c1c1c;
  color:white;
  transition:0.5s;
  border-radius:10px;
  padding:7px;
  padding:-29px;
  font-size:13px;
}
.search_btn:hover{
  border:2px solid #1c1c1c;
  background:none;
  color:#1c1c1c;
  transition:0.5s;
  border-radius:10px;
  box-shadow:2px 8px 16px gray;
}
</style>


<?php
    $stockstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock");
    $stockstmt->execute();
    $stockdata = $stockstmt->fetchAll();
 ?>

 <!-- <form class="" action="" method="post">
   <div class="d-flex" style="margin-left:950px; margin-top:-15px;">
     <input type="date" name="" value="" class="form-control" placeholder="Search Supplier_Name" style="width:200px;">
     <button type="submit" name="search" class="search_btn ms-3">Search</button>
  </div>
 </form> -->

<div class="container">
  <h4 style="margin-top:-17px;"><b>Stock Control</b></h4>
  <div class="outer" style="margin-top:-10px;">
    <table class="table table-bordered mt-4 table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Item Name</th>
          <th>In</th>
          <th>Out</th>
          <th>Balance</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($stockdata) {
            $id = 1;
            foreach ($stockdata as $value) {
              $item_id = $value['item_id'];

              // Item Name
              $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $itemstmt->execute();
              $item = $itemstmt->fetch(PDO::FETCH_ASSOC);

              // Total Receivable Amount
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
              $total_instmt->execute();
              $total_indata = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Total Paid Amount
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
              $total_outstmt->execute();
              $total_outdata = $total_outstmt->fetch(PDO::FETCH_ASSOC);

              $balance = $total_indata['total_in'] - $total_outdata['total_out'];
         ?>
        <tr style="<?php if($balance < 50){ echo "background-color: red !important;"; } ?>">
          <td><?php echo $id; ?></td>
          <td><?php echo $item['item_name'];?></td>
          <td><?php echo $total_indata['total_in'];?></td>
          <td><?php echo $total_outdata['total_out'];?></td>
          <td><?php echo $balance;?></td>
          <td>
              <a href="stock_detail.php?item_id=<?php echo $item_id; ?>"><button>View Detail</button></a>
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
  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  <!-- <br><br><br><br><br><br><br><br><br><br><br> -->
  <!-- <br><br><br><br><br><br><br><br><br><br><br> -->


  <?php include 'footer.html'; ?>
