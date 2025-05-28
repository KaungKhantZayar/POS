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
    $stmt = $pdo->prepare("SELECT * FROM parchase ORDER BY id  DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();

    $total_pages = ceil(count($rawResult) / $numOfrecs);

    $stmt = $pdo->prepare("SELECT * FROM parchase ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }else {
    $search = $_POST['search'];
    $stmt = $pdo->prepare("SELECT * FROM parchase WHERE date LIKE '%$search%' ORDER BY id  DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();

    $total_pages = ceil(count($rawResult) / $numOfrecs);

    $stmt = $pdo->prepare("SELECT * FROM parchase WHERE date LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }

 ?>


    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3>Parchase</h3>
        <div class="card-body mt-4">

          <div class="col-3" style="margin-left:896px;">
            <form class="" action="" method="post">
              <div class="input-group" style="margin-top:20px;">
                <input type="date" class="form-control" placeholder="Search Date" name="search">
                <button type="submit" class="input-group-text" id="basic-addon2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search-heart" viewBox="0 0 16 16">
                    <path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018"/>
                    <path d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11"/>
                  </svg>
                </button>
              </div>
            </form>
          </div>

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
                <th>Total</th>
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
                <td><?php echo $value['date']; ?></td>
                <td><?php echo $value['vr_no']; ?></td>
                <td><?php echo $supplierIdResult['supplier_name'];?></td>
                <td><?php echo $itemResult['item_name']; ?></td>
                <td><?php echo $value['price'];?></td>
                <td><?php echo $value['qty']; ?></td>
                <td><?php echo $value['price'] * $value['qty']; ?></td>
              </tr>
              <?php
                $id++;
                  }
                }
               ?>
            </tbody>
          </table>
            <br>
            <nav aria-lable="Page navigation example" style="float:right;">
            <ul class="pagination">
            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php if($pageno <= 1){echo 'disabled';}?>">
            <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);}?>">Previonus</a>
            </li>
            <li class="page-item"><a class="page-link" href="#"><?php echo $pageno;?></a></li>
            <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';}?>">
            <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);}?>">Next</a>
            </li>
            <li class="page-item"><a class="page-link" href="?pagenp=<?php echo $total_pages;?>">Last</a></li>
            </ul>
            </nav>
      </div>
      </div>
    </div>
  </div>

    <!-- Main content -->

<?php include 'footer.html'; ?>
