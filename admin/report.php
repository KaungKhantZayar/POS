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
              if($_GET['return_stock'] == 'purchase_return'){
                ?>
                  <th><?php echo "Purchase Return"; ?></th>
                <?php
                  }elseif($_GET['return_stock'] == 'sale_return'){
                ?>
                  <th><?php echo "Sale Return"; ?></th>
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
        // One Item's stock summary
        if(!empty($_GET['item_id'])) {
          $item_id = $_GET['item_id'];

          // Date Between
          if($_GET['start_date'] && $_GET['end_date']){
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];

            // Total In
            $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_instmt->execute();
            $total_indata = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
            // Total Out 
            $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_outstmt->execute();
            $total_outdata = $total_outstmt->fetch(PDO::FETCH_ASSOC);

            // Balance 
            $balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
            $balancestmt->execute();
            $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

            // Total Purchase FOC
            $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_purchase_focstmt->execute();
            $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);

            // Total Sale FOC
            $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_sale_focstmt->execute();
            $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);

            // Total Purchase Return
            $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_purchase_returnstmt->execute();
            $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);

            // Total Sale Return
            $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return' AND date BETWEEN '$start_date' AND '$end_date'");
            $total_sale_returnstmt->execute();
            $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);

            // Damage Stock
            $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage' AND date BETWEEN '$start_date' AND '$end_date'");
            $damagestmt->execute();
            $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

          }else{

            // Balance 
            $balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
            $balancestmt->execute();
            $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

            // Total Purchase FOC
            $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase'");
            $total_purchase_focstmt->execute();
            $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);

            // Total Sale FOC
            $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale'");
            $total_sale_focstmt->execute();
            $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);

            // Total Purchase Return
            $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return'");
            $total_purchase_returnstmt->execute();
            $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);

            // Total Sale Return
            $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return'");
            $total_sale_returnstmt->execute();
            $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);

            // Damage Stock
            $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage'");
            $damagestmt->execute();
            $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

            // Total In
            $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
            $total_instmt->execute();
            $total_indata = $total_instmt->fetch(PDO::FETCH_ASSOC);
            
            // Total Out 
            $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
            $total_outstmt->execute();
            $total_outdata = $total_outstmt->fetch(PDO::FETCH_ASSOC);
          
          }

            // Adjust Total In Qty
            if(!empty(($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'purchase_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'sale_return')){ // if show purchase foc and sale return
              $total_in = ($total_indata['total_in'] - $total_purchase_focdata['total_purchase_foc']) - $total_sale_returndata['total_sale_return'];
            }elseif(!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'purchase_foc'){ // if show purchase foc and foc all
              $total_in = $total_indata['total_in'] - $total_purchase_focdata['total_purchase_foc'];
            }elseif(!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'sale_return'){ // if show sale return and return all
              $total_in = $total_indata['total_in'] - $total_sale_returndata['total_sale_return'];
            }else{
              $total_in = $total_indata['total_in'];
            }

            // Adjust Total Out Qty
            if((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show sale foc and purchase return and damage
              $total_out = (($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $total_purchase_returndata['total_purchase_return']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show sale foc and damage
              $total_out = ($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show return and damage
              $total_out = ($total_outdata['total_out'] - $total_purchase_returndata['total_purchase_return']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return')){ // if show FOC and return
              $total_out = ($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $total_purchase_returndata['total_purchase_return'];
            }elseif(!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc'){ // if show sale foc and foc all
              $total_out = $total_outdata['total_out'] - $total_sale_focdata['total_sale_foc'];
            }elseif(!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return'){ // if show purchase return and return all
              $total_out = $total_outdata['total_out'] - $total_purchase_returndata['total_purchase_return'];
            }elseif(!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all'){ // if show damage stock
              $total_out = $total_outdata['total_out'] - $damagedata['total_damage'];
            }else{
              $total_out = $total_outdata['total_out'];
            }

          // Item Name
          $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
          $itemstmt->execute();
          $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <tr style="<?php if($total_in == 0 && $total_out == 0){ echo "display:none;"; } ?>">
            <td class="pl-3"><?php echo "1"; ?></td>
            <td class="pl-3"><?php echo $itemResult['item_name'];?></td>
            <td class="pl-3"><?php if($total_in != 0){ echo $total_in; }else{ echo "-"; }; ?></td>
            <td class="pl-3"><?php if($total_out != 0){ echo $total_out; }else{ echo "-"; }; ?></td>
            <!-- Stock FOC -->
            <?php 
              if(!empty($_GET['stock_foc'])){
                if($_GET['stock_foc'] == 'purchase_foc'){
                  ?>
                    <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-"; }; ?></td>
                  <?php
                    }elseif($_GET['stock_foc'] == 'sale_foc'){
                  ?>
                    <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-"; }; ?></td>
                  <?php
                    }elseif($_GET['stock_foc'] == 'all'){
                  ?>
                  <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-"; }; ?></td>
                  <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-"; }; ?></td>
                <?php
                }
              }
            ?>
            <!-- Stock return -->
            <?php 
              if(!empty($_GET['return_stock'])){
                if($_GET['return_stock'] == 'purchase_return'){
                  ?>
                    <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-"; } ?></td>
                  <?php
                    }elseif($_GET['return_stock'] == 'sale_return'){
                  ?>
                    <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-"; } ?></td>
                  <?php
                    }elseif($_GET['return_stock'] == 'all'){
                  ?>
                  <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-"; } ?></td>
                  <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-"; } ?></td>
                <?php
                }
              }
            ?>
          <!-- Damage Stock -->
          <?php
            if(!empty($_GET['damage_stock'])){
              ?>
              <td class="pl-3"><?php if($damagedata['total_damage'] != 0){ echo $damagedata['total_damage']; }else{ echo "-"; } ?></td>
              <?php
            }
          ?>
          <!-- Balance Stock -->
            <td class="pl-3"><?php if($balancedata['balance'] != 0){ echo $balancedata['balance']; }else{ echo "-"; } ?></td>
          </tr>
          <?php
        }else{
          // All Item Stock Summary
          $stockstmt = $pdo->prepare("SELECT DISTINCT item_id FROM stock ORDER BY id DESC");
          $stockstmt->execute();
          $stockdata = $stockstmt->fetchAll();
          $id = 1;
          foreach($stockdata as $data){
            $item_id = $data['item_id'];
            if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              $start_date = $_GET['start_date'];
              $end_date = $_GET['end_date'];

              // Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_instmt->execute();
              $total_indata = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_outstmt->execute();
              $total_outdata = $total_outstmt->fetch(PDO::FETCH_ASSOC);

              // Balance 
              $balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY id DESC");
              $balancestmt->execute();
              $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

              // Total Purchase FOC
              $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_purchase_focstmt->execute();
              $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);

              // Total Sale FOC
              $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_sale_focstmt->execute();
              $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);

              // Total Purchase Return
              $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_purchase_returnstmt->execute();
              $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);

              // Total Sale Return
              $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return' AND date BETWEEN '$start_date' AND '$end_date'");
              $total_sale_returnstmt->execute();
              $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);

              // Damage Stock
              $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage' AND date BETWEEN '$start_date' AND '$end_date'");
              $damagestmt->execute();
              $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

            }else{

              // Balance 
              $balancestmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
              $balancestmt->execute();
              $balancedata = $balancestmt->fetch(PDO::FETCH_ASSOC);

              // Total Purchase FOC
              $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase'");
              $total_purchase_focstmt->execute();
              $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);

              // Total Sale FOC
              $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale'");
              $total_sale_focstmt->execute();
              $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);

              // Total Purchase Return
              $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return'");
              $total_purchase_returnstmt->execute();
              $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);

              // Total Sale Return
              $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return'");
              $total_sale_returnstmt->execute();
              $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);

              // Damage Stock
              $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage'");
              $damagestmt->execute();
              $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

              // Total In
              $total_instmt = $pdo->prepare("SELECT SUM(in_qty) AS total_in FROM stock WHERE item_id='$item_id'");
              $total_instmt->execute();
              $total_indata = $total_instmt->fetch(PDO::FETCH_ASSOC);
              
              // Total Out 
              $total_outstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_out FROM stock WHERE item_id='$item_id'");
              $total_outstmt->execute();
              $total_outdata = $total_outstmt->fetch(PDO::FETCH_ASSOC);

            }

            // Adjust Total In Qty
            if(!empty(($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'purchase_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'sale_return')){ // if show purchase foc and sale return
              $total_in = ($total_indata['total_in'] - $total_purchase_focdata['total_purchase_foc']) - $total_sale_returndata['total_sale_return'];
            }elseif(!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'purchase_foc'){ // if show purchase foc and foc all
              $total_in = $total_indata['total_in'] - $total_purchase_focdata['total_purchase_foc'];
            }elseif(!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'sale_return'){ // if show sale return and return all
              $total_in = $total_indata['total_in'] - $total_sale_returndata['total_sale_return'];
            }else{
              $total_in = $total_indata['total_in'];
            }

            // Adjust Total Out Qty
            if((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show sale foc and purchase return and damage
              $total_out = (($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $total_purchase_returndata['total_purchase_return']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show sale foc and damage
              $total_out = ($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return') AND (!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all')){ // if show return and damage
              $total_out = ($total_outdata['total_out'] - $total_purchase_returndata['total_purchase_return']) - $damagedata['total_damage'];
            }elseif((!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc') AND (!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return')){ // if show FOC and return
              $total_out = ($total_outdata['total_out'] - $total_sale_focdata['total_sale_foc']) - $total_purchase_returndata['total_purchase_return'];
            }elseif(!empty($_GET['stock_foc']) && $_GET['stock_foc'] == 'all' OR $_GET['stock_foc'] == 'sale_foc'){ // if show sale foc and foc all
              $total_out = $total_outdata['total_out'] - $total_sale_focdata['total_sale_foc'];
            }elseif(!empty($_GET['return_stock']) && $_GET['return_stock'] == 'all' OR $_GET['return_stock'] == 'purchase_return'){ // if show purchase return and return all
              $total_out = $total_outdata['total_out'] - $total_purchase_returndata['total_purchase_return'];
            }elseif(!empty($_GET['damage_stock']) && $_GET['damage_stock'] == 'all'){ // if show damage stock
              $total_out = $total_outdata['total_out'] - $damagedata['total_damage'];
            }else{
              $total_out = $total_outdata['total_out'];
            }

            // Item Name
            $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
            $itemstmt->execute();
            $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr style="<?php if($total_in == 0 && $total_out == 0){ echo "display:none;"; } ?>">
              <td><?php echo $id; ?></td>
              <td><?php echo $itemResult['item_name'];?></td>
              <td><?php if($total_in != 0){ echo $total_in; }else{ echo "-"; }?></td>
              <td><?php if($total_out != 0){ echo $total_out; }else{ echo "-"; }?></td>
              <!-- Stock FOC -->
            <?php 
              if(!empty($_GET['stock_foc'])){
                if($_GET['stock_foc'] == 'purchase_foc'){
                  ?>
                    <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-";} ?></td>
                  <?php
                    }elseif($_GET['stock_foc'] == 'sale_foc'){
                  ?>
                    <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-";} ?></td>
                  <?php
                    }elseif($_GET['stock_foc'] == 'all'){
                  ?>
                  <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-";} ?></td>
                  <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-";} ?></td>
                <?php
                }
              }
            ?>
            <!-- Stock return -->
            <?php 
              if(!empty($_GET['return_stock'])){
                if($_GET['return_stock'] == 'purchase_return'){
                  ?>
                    <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-";} ?></td>
                  <?php
                    }elseif($_GET['return_stock'] == 'sale_return'){
                  ?>
                    <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-";} ?></td>
                  <?php
                    }elseif($_GET['return_stock'] == 'all'){
                  ?>
                  <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-";} ?></td>
                  <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-";} ?></td>
                <?php
                }
              }
            ?>
            <!-- Damage Stock -->
            <?php
              if(!empty($_GET['damage_stock'])){
                ?>
                <td class="pl-3"><?php if($damagedata['total_damage'] != 0){ echo $damagedata['total_damage']; }else{ echo "-";} ?></td>
                <?php
              }
            ?>
            <!-- Balance Stock -->
              <td class="pl-3"><?php if($balancedata['balance'] != 0){ echo $balancedata['balance']; }else{ echo "-";}?></td>
            </tr>
          <?php
          }
        }
          ?>
          </tbody>
    </table>
   </div> 
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
                if($_GET['return_stock'] == 'purchase_return'){
                  ?>
                    <th><?php echo "Purchase Return"; ?></th>
                  <?php
                    }elseif($_GET['return_stock'] == 'sale_return'){
                  ?>
                    <th><?php echo "Sale Return"; ?></th>
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
          if(!empty($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
            
            $cat_itemstmt = $pdo->prepare("SELECT * FROM item WHERE categories_id='$category_id' ORDER BY id DESC");
            $cat_itemstmt->execute();
            $cat_itemdata = $cat_itemstmt->fetchAll();
            $id = 0;
            foreach($cat_itemdata as $item){
              $item_id = $item['item_id'];
              // echo "<script>alert('$item_id');</script>";
              // $_SESSION['cat_item_id'] = $item_id;

              // if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
              //   $item_id = $_SESSION['cat_item_id'];
              //   $start_date = $_GET['start_date'];
              //   $end_date = $_GET['end_date'];

              //   // stock check & balance
              //   $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' AND date BETWEEN $start_date AND $end_date ORDER BY id DESC");
              //   $stockstmt->execute();
              //   $stockdata = $stockstmt->fetch(PDO::FETCH_ASSOC);
              //   print_r($stockdata);

              //   // Total Purchase FOC
              //   $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase' AND date BETWEEN $start_date AND $end_date");
              //   $total_purchase_focstmt->execute();
              //   $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);

              //   // Total Sale FOC
              //   $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale' AND date BETWEEN $start_date AND $end_date");
              //   $total_sale_focstmt->execute();
              //   $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);

              //   // Total Purchase Return
              //   $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return' AND date BETWEEN $start_date AND $end_date");
              //   $total_purchase_returnstmt->execute();
              //   $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);

              //   // Total Sale Return
              //   $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return' AND date BETWEEN $start_date AND $end_date");
              //   $total_sale_returnstmt->execute();
              //   $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);

              //   // Damage Stock
              //   $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage' AND date BETWEEN $start_date AND $end_date");
              //   $damagestmt->execute();
              //   $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

              // }else{

                // stock check & balance
                $stockstmt = $pdo->prepare("SELECT * FROM stock WHERE item_id='$item_id' ORDER BY id DESC");
                $stockstmt->execute();
                $stockdata = $stockstmt->fetch(PDO::FETCH_ASSOC);
  
                // Total Purchase FOC
                $total_purchase_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_purchase_foc FROM stock WHERE item_id='$item_id' AND to_from = 'purchase'");
                $total_purchase_focstmt->execute();
                $total_purchase_focdata = $total_purchase_focstmt->fetch(PDO::FETCH_ASSOC);
  
                // Total Sale FOC
                $total_sale_focstmt = $pdo->prepare("SELECT SUM(foc_qty) AS total_sale_foc FROM stock WHERE item_id='$item_id' AND to_from = 'sale'");
                $total_sale_focstmt->execute();
                $total_sale_focdata = $total_sale_focstmt->fetch(PDO::FETCH_ASSOC);
  
                // Total Purchase Return
                $total_purchase_returnstmt = $pdo->prepare("SELECT SUM(out_qty) AS total_purchase_return FROM stock WHERE item_id='$item_id' AND to_from = 'purchase_return'");
                $total_purchase_returnstmt->execute();
                $total_purchase_returndata = $total_purchase_returnstmt->fetch(PDO::FETCH_ASSOC);
  
                // Total Sale Return
                $total_sale_returnstmt = $pdo->prepare("SELECT SUM(in_qty) AS total_sale_return FROM stock WHERE item_id='$item_id' AND to_from = 'sale_return'");
                $total_sale_returnstmt->execute();
                $total_sale_returndata = $total_sale_returnstmt->fetch(PDO::FETCH_ASSOC);
  
                // Damage Stock
                $damagestmt = $pdo->prepare("SELECT SUM(out_qty) AS total_damage FROM stock WHERE item_id='$item_id' AND to_from='damage'");
                $damagestmt->execute();
                $damagedata = $damagestmt->fetch(PDO::FETCH_ASSOC);

              // }

              if(!empty($stockdata)){
                $id++;

                $itemstmt = $pdo->prepare("SELECT * FROM item WHERE item_id='$item_id'");
                $itemstmt->execute();
                $itemResult= $itemstmt->fetch(PDO::FETCH_ASSOC);
                
                ?>
                <tr>
                  <td><?php echo $id; ?></td>
                  <td><?php echo $itemResult['item_name'] ?></td>
                  <!-- Stock FOC -->
                    <?php 
                      if(!empty($_GET['stock_foc'])){
                        if($_GET['stock_foc'] == 'purchase_foc'){
                          ?>
                            <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-";} ?></td>
                          <?php
                            }elseif($_GET['stock_foc'] == 'sale_foc'){
                          ?>
                            <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-";} ?></td>
                          <?php
                            }elseif($_GET['stock_foc'] == 'all'){
                          ?>
                          <td class="pl-3"><?php if($total_purchase_focdata['total_purchase_foc'] != 0){ echo $total_purchase_focdata['total_purchase_foc']; }else{ echo "-";} ?></td>
                          <td class="pl-3"><?php if($total_sale_focdata['total_sale_foc'] != 0){ echo $total_sale_focdata['total_sale_foc']; }else{ echo "-";} ?></td>
                        <?php
                        }
                      }
                    ?>
                    <!-- Stock return -->
                    <?php 
                      if(!empty($_GET['return_stock'])){
                        if($_GET['return_stock'] == 'purchase_return'){
                          ?>
                            <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-";} ?></td>
                          <?php
                            }elseif($_GET['return_stock'] == 'sale_return'){
                          ?>
                            <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-";} ?></td>
                          <?php
                            }elseif($_GET['return_stock'] == 'all'){
                          ?>
                          <td class="pl-3"><?php if($total_purchase_returndata['total_purchase_return'] != 0){ echo $total_purchase_returndata['total_purchase_return']; }else{ echo "-";} ?></td>
                          <td class="pl-3"><?php if($total_sale_returndata['total_sale_return'] != 0){ echo $total_sale_returndata['total_sale_return']; }else{ echo "-";} ?></td>
                        <?php
                        }
                      }
                    ?>
                    <!-- Damage Stock -->
                    <?php
                      if(!empty($_GET['damage_stock'])){
                        ?>
                        <td class="pl-3"><?php if($damagedata['total_damage'] != 0){ echo $damagedata['total_damage']; }else{ echo "-";} ?></td>
                        <?php
                      }
                    ?>
                  <td><?php echo $stockdata['balance'] ?></td>
                </tr>
                <?php
              }
              ?>
              <?php
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
