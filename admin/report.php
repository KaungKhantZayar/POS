<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';
?>
<?php include 'header.php'; ?>

<?php
if($_GET['report_name'] === 'stock_inventory_summary'){
?>
  <div class="col-md-12 px-4 mt-4">
    <div class="d-flex justify-content-between">
      <h4>
          Stock Inventory Summary Report
        </h4>
      <div>
        <h4>
        <?php
            if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              if($_GET['start_date'] === $_GET['end_date']){
                echo "Date : " . date('d-m-y', strtotime($_GET['start_date']));
              }else{
                echo "Date : " . date('d-m-y', strtotime($_GET['start_date'])) ." To ". date('d-m-y', strtotime($_GET['end_date']));
              }
            }
          ?>
        </h4>
      </div>
    </div>
    <table class="table mt-4 table-hover">
      <thead class="custom-thead">
        <tr>
          <th style="width: 50px">No</th>
          <th>Item Name</th>
          <th>Total In</th>
          <th>Total Out</th>
          <!-- Stock FOC -->
          <?php 
            if(!empty($_GET['stock_foc'])){
              if($_GET['stock_foc'] == 'purchase_foc'){
                ?>
                  <th><?php echo "Purchase FOC"; ?></th>
                <?php
                  }elseif($_GET['stock_foc'] == 'sale_foc'){
                ?>
                  <th><?php echo "Sale FOC"; ?></th>
                <?php
                  }elseif($_GET['stock_foc'] == 'all'){
                ?>
                <th>Purchase Foc</th>
                <th>Sale Foc</th>
              <?php
              }
            }
          ?>
          <!-- Return Stock -->
          <?php 
            if(!empty($_GET['return_stock'])){
              if($_GET['stock_foc'] == 'purchase_return'){
                ?>
                  <th><?php echo "Purchase FOC"; ?></th>
                <?php
                  }elseif($_GET['return_stock'] == 'sale_return'){
                ?>
                  <th><?php echo "Sale FOC"; ?></th>
                <?php
                  }elseif($_GET['return_stock'] == 'all'){
                ?>
                <th>Purchase Return</th>
                <th>Sale Return</th>
              <?php
              }
            }
          ?>
          <!-- Damage Stock  -->
          <?php
          if(!empty($_GET['damage_stock'])){
          ?>
            <th>Damage</th>
          <?php
          }
          ?>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if(!empty($_GET['item_id'])) {
          $item_id = $_GET['item_id'];

          // Date Between
          // if($_GET['start_date'] && $_GET['end_date']){
          //   $start_date = $_GET['start_date'];
          //   $end_date = $_GET['end_date'];

          //   // Each Stock's Total In
          //   $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
          //   $total_instmt->execute();
          //   $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
          //   // Each Stock's Total Out 
          //   $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
          //   $total_outstmt->execute();
          //   $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);
          // }else{
            // Total In
            $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
            $total_instmt->execute();
            $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
            // Total Out 
            $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
            $total_outstmt->execute();
            $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);

            // Balance 
            $balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
            $balancestmt->execute();
            $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

            // Total FOC
            $total_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase'");
            $total_focstmt->execute();
            $total_focdata = $total_focstmt->fetch(PDO::FETCH_ASSOC);
          // }

          // Item Name
          $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
          $itemstmt->execute();
          $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <tr style="<?php if($total_in['total_in'] == 0 && $total_out['total_out'] == 0){ echo "display:none;"; } ?>">
            <td><?php echo "1"; ?></td>
            <td><?php echo $itemResult['item_name'];?></td>
            <td><?php echo $total_in['total_in'];?></td>
            <td><?php echo $total_out['total_out'];?></td>
            <td><?php echo $total_focdata['total_foc'];?></td>
            <td><?php echo $balancedata['balance'];?></td>
          </tr>
          <?php
        }else{
          // All Item Stock In / Out
          $stockstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock ORDER BY id DESC");
          $stockstmt->execute();
          $stockdata = $stockstmt->fetchAll();
          $id = 1;
          foreach($stockdata as $data){
            $item_id = $data['item_id'];

            if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              $start_date = $_GET['start_date'];
              $end_date = $_GET['end_date'];

              // Each Stock's Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_instmt->execute();
              $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Each Stock's Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_outstmt->execute();
              $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);

            }else{
              // Each Stock's Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
              $total_instmt->execute();
              $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Each Stock's Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
              $total_outstmt->execute();
              $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);
            }

            // Item Name
            $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
            $itemstmt->execute();
            $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
          ?>
            <tr style="<?php if($total_in['total_in'] == 0 && $total_out['total_out'] == 0){ echo "display:none;"; } ?>">
              <td><?php echo $id; ?></td>
              <td><?php echo $itemResult['item_name'];?></td>
              <td><?php echo $total_in['total_in'];?></td>
              <td><?php echo $total_out['total_out'];?></td>
            </tr>
          <?php
          }
        }
          ?>
          </tbody>
    </table>
   </div> 
<?php
}elseif($_GET['report_name'] === 'stock_in_out'){
  ?>
  <div class="col-md-12 px-4 mt-4">
    <div class="d-flex justify-content-between">
      <h4>
          Stock In / Out Report
        </h4>
      <div>
        <h4>
        <?php
            if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              if($_GET['start_date'] === $_GET['end_date']){
                echo "Date : " . date('d-m-y', strtotime($_GET['start_date']));
              }else{
                echo "Date : " . date('d-m-y', strtotime($_GET['start_date'])) ." To ". date('d-m-y', strtotime($_GET['end_date']));
              }
            }
          ?>
        </h4>
      </div>
    </div>  
    <div class="report-outer">
      <table class="table mt-4 table-hover">
        <thead class="custom-thead">
          <tr>
            <th style="width: 100px">No</th>
            <th>Item Name</th>
            <th>Total In Qty</th>
            <th>Total Out Qty</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($_GET['item_id'])) {
          $item_id = $_GET['item_id'];

          // Date Between
          if($_GET['start_date'] && $_GET['end_date']){
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];

            // Each Stock's Total In
            $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_instmt->execute();
            $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
            // Each Stock's Total Out 
            $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_outstmt->execute();
            $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);
          }else{
            // Each Stock's Total In
            $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
            $total_instmt->execute();
            $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
            // Each Stock's Total Out 
            $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
            $total_outstmt->execute();
            $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);
          }

          // Item Name
          $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
          $itemstmt->execute();
          $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <tr style="<?php if($total_in['total_in'] == 0 && $total_out['total_out'] == 0){ echo "display:none;"; } ?>">
            <td><?php echo "1"; ?></td>
            <td><?php echo $itemResult['item_name'];?></td>
            <td><?php echo $total_in['total_in'];?></td>
            <td><?php echo $total_out['total_out'];?></td>
          </tr>
          <?php
        }else{
          // All Item Stock In / Out
          $stockstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock ORDER BY id DESC");
          $stockstmt->execute();
          $stockdata = $stockstmt->fetchAll();
          $id = 1;
          foreach($stockdata as $data){
            $item_id = $data['item_id'];

            if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              $start_date = $_GET['start_date'];
              $end_date = $_GET['end_date'];

              // Each Stock's Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_instmt->execute();
              $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Each Stock's Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_outstmt->execute();
              $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);

            }else{
              // Each Stock's Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
              $total_instmt->execute();
              $total_in = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Each Stock's Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
              $total_outstmt->execute();
              $total_out = $total_outstmt->fetch(PDO::FETCH_ASSOC);
            }

            // Item Name
            $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
            $itemstmt->execute();
            $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
          ?>
            <tr style="<?php if($total_in['total_in'] == 0 && $total_out['total_out'] == 0){ echo "display:none;"; } ?>">
              <td><?php echo $id; ?></td>
              <td><?php echo $itemResult['item_name'];?></td>
              <td><?php echo $total_in['total_in'];?></td>
              <td><?php echo $total_out['total_out'];?></td>
            </tr>
          <?php
          }
        }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
}elseif($_GET['report_name'] === 'stock_balance'){
  ?>
  <div class="col-md-12 px-4 mt-4">
    <div class="d-flex justify-content-between">
    <h4>
        Stock Balance Report
      </h4>
    <div>
      <h4> 
      <?php
          if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
            if($_GET['start_date'] === $_GET['end_date']){
              echo "Date : " . date('d-m-y', strtotime($_GET['start_date']));
            }else{
              echo "Date : " . date('d-m-y', strtotime($_GET['start_date'])) ." To ". date('d-m-y', strtotime($_GET['end_date']));
            }
          }
        ?>
      </h4>
    </div>
  </div> 
    <div class="report-outer">
      <table class="table mt-4 table-hover">
        <thead class="custom-thead">
          <tr>
            <th style="width: 100px">No</th>
            <th>Item Name</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($_GET['item_id'])) {
              $item_id = $_GET['item_id'];

              if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
                $start_date = $_GET['start_date'];
                $end_date = $_GET['end_date'];

                $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
              }else{
                $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
              }
              $stockstmt->execute();
              $stockdata = $stockstmt->fetch(PDO::FETCH_ASSOC);

              $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $itemstmt->execute();
              $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
              ?>
                <tr>
                  <td><?php echo "1"; ?></td>
                  <td><?php echo $itemResult['item_name'] ?></td>
                  <td><?php echo $stockdata['balance'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
          <?php
        }else{
          // All Item Balance
          $stockstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock ORDER BY id DESC");
          $stockstmt->execute();
          $stockdata = $stockstmt->fetchAll();
          $id = 1;
          foreach($stockdata as $data){
            $item_id = $data['item_id'];
            $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
            $stockstmt->execute();
            $stockdata = $stockstmt->fetch(PDO::FETCH_ASSOC);
            
            $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
            $itemstmt->execute();
            $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
              <td><?php echo $id; ?></td>
              <td><?php echo $itemResult['item_name'] ?></td>
              <td><?php echo $stockdata['balance'] ?></td>
            </tr>
            <?php
            $id++;
          }
          ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php
  }
    ?>
    <?php
}elseif($_GET['report_name'] === 'balance_by_category'){
  ?>
  <div class="col-md-12 px-4 mt-4">
    <div class="d-flex justify-content-between">
    <h4>
        Balance By Category Report
      </h4>
    <div>
      <h4> 
      <?php
          if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
            if($_GET['start_date'] === $_GET['end_date']){
              echo "Date : " . date('d-m-y', strtotime($_GET['start_date']));
            }else{
              echo "Date : " . date('d-m-y', strtotime($_GET['start_date'])) ." To ". date('d-m-y', strtotime($_GET['end_date']));
            }
          }
        ?>
        <?php
        if(!empty($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
            // Category Name
            $catstmt = $pdo->prepare("SELECT * FROM categories WHERE categories_code='$category_id'");
            $catstmt->execute();
            $catResult= $catstmt->fetch(PDO::FETCH_ASSOC);
            echo " Category Name : ". $catResult['categories_name'];
        }
        ?>
      </h4>
    </div>
  </div> 
    <div class="report-outer">
      <table class="table mt-4 table-hover">
        <thead class="custom-thead">
          <tr>
            <th style="width: 100px">No</th>
            <th>Item Name</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(!empty($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
            
            $cat_itemstmt = $pdo->prepare("SELECT * FROM item WHERE categories_id='$category_id' ORDER BY id DESC");
            $cat_itemstmt->execute();
            $cat_itemdata = $cat_itemstmt->fetchAll();
            $id = 1;
            foreach($cat_itemdata as $item){
              $item_id = $item['item_id'];
              // echo "<script>alert('$item_id')</script>";
              $cat_stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
              $cat_stockstmt->execute();
              $cat_stockdata = $cat_stockstmt->fetch(PDO::FETCH_ASSOC);

              $stockstmt = $pdo->prepare("SELECT * FROM stock ORDER BY id DESC");
              $stockstmt->execute();
              $stockdata = $stockstmt->fetch(PDO::FETCH_ASSOC);

              $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
              $itemstmt->execute();
              $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);

              ?>
              <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $itemResult['item_name'] ?></td>
                <td><?php //echo $stockdata['balance'] ?></td>
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
  <?php
}
?>

<?php include 'footer.html'; ?>
