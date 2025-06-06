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

if(isset($_POST['save'])){
  $date = $_POST['date'];
  $vr_no = $_POST['vr_no'];
  $item_id = $_POST['item_id'];
  $qty = $_POST['qty'];

  // receivable Balance
  $stock_balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
  $stock_balancestmt->execute();
  $stock_balancedata = $stock_balancestmt->fetch(PDO::FETCH_ASSOC);
  $balance = $stock_balancedata['balance'] - $qty;  

  $receivablestmt = $pdo->prepare("INSERT INTO stock (date,vr_no,item_id,to_from,out_qty,balance) VALUES (:date,:vr_no,:item_id,'damage',:out_qty,:balance)");
  $receivabledata = $receivablestmt->execute(
    array(':date'=>$date, ':vr_no'=>$vr_no, ':item_id'=>$item_id, ':out_qty'=>$qty, ':balance'=>$balance)
  );
}

?>
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
  <div class="d-flex" style="margin-top:-17px;">
    <h4 class="col-10 me-5"><b>Stock Control</b></h4>
    <button class="ms-3" data-bs-toggle="modal" data-bs-target="#myModal">Damage Stock</button>
  </div>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Damage Stock</h4>
      </div>
      <div class="modal-body">
        <form action="" method="post">
          <div class="row">
            <div class="col">
              <label for="">Date</label>
              <input type="date" class="border border-dark form-control" name="date">
            </div>
            <div class="col">
              <label for="">Vr_no</label>
              <input type="text" class="form-control border border-dark" name="vr_no">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label for="">Item Name</label>
              <select name="item_id" id="" class="form-control border border-dark">
                <?php
                  $itemstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock ORDER BY id DESC");
                  $itemstmt->execute();
                  $itemdata = $itemstmt->fetchAll();
                  foreach ($itemdata as $item) {
                    $item_id = $item['item_id'];
                    $namestmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
                    $namestmt->execute();
                    $name = $namestmt->fetch(PDO::FETCH_ASSOC);
                    ?>                
                    <option value="<?php echo $item['item_id']; ?>"><?php echo $name['item_name']; ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
            <div class="col">
              <label for="">Qty</label>
              <input type="number" class="form-control border border-dark" name="qty">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="save">Save</button>
          <button type="button" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>
  <?php include 'footer.html'; ?>
