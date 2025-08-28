<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
  ?>

  <?php include 'header.php'; ?>
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

<div class="col-md-12 px-3 mt-4">
  <div class="d-flex justify-content-between px-2">
    <div>
      <h4>Stock Listing</h4>
    </div>
    <div>
      <button data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-purple text-light btn-sm">
        Damage Stock
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" style="margin-top: -5px;" viewBox="0 0 16 16">
          <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
          <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
        </svg>
      </button>
    </div>
  </div>
  <div class="outer">
    <table class="table mt-4 table-hover">
      <thead class="custom-thead">
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
              <a href="stock_detail.php?item_id=<?php echo $item_id; ?>"
              class="btn btn-sm btn-primary text-light"
              data-bs-toggle="tooltip" data-bs-placement="top" title="View Stock Details">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                  <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                  <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
                </svg>
            </a>
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
<script>
  document.addEventListener("DOMContentLoaded", function(){
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
<?php include 'footer.html'; ?>
