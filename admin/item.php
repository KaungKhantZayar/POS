<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

  ?>
 <?php include 'header.php';?>

 <style media="screen">
 .outer {
 overflow-y: auto;
 height: 500px;
 }

 .outer {
 width: 100%;
 -layout: fixed;
 }

 .outer th {
 text-align: left;
 top: 0;
 position: sticky;
 background-color: white;
 }
 </style>

  <?php
    if (!empty($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
    }else {
      $pageno = 1;
    }
    $numOfrecs = 10;
    $offset = ($pageno - 1) * $numOfrecs;

    if (empty($_POST['search'])) {
      $stmt = $pdo->prepare("SELECT * FROM item ORDER BY id DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM item ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }else {
      $search = $_POST['search'];
      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_name LIKE '%$search%' ORDER BY id  DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();

      $total_pages = ceil(count($rawResult) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM item WHERE item_name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
    }
   ?>

    <div class="col-md-12 px-3">
      <div class="d-flex mt-4 mb-4 justify-content-between">
        <div class="">
          <h1 class="card-title">Item Listings</h1>
        </div>
        <div class="d-flex text-right">
          <div class="ml-1">
            <form class="" action="" method="post">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Item_Name" name="search">
                <button type="submit" class="input-group-text" id="basic-addon2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search-heart" viewBox="0 0 16 16">
                    <path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018"/>
                    <path d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11"/>
                  </svg>
                </button>
              </div>
            </form>
          </div>
          <div class="col">
            <a href="item_add.php" type="button" class="btn btn-purple text-light">Create New Item</a>
          </div>
        </div>
      </div>

      <div class="outer">
        <table class="table table-hover">
          <thead class="custom-thead">
            <tr>
              <th style="width: 10px">#</th>
              <th>Item_id</th>
              <th>Item_name</th>
              <th>Category_id</th>
              <th>Original_Price</th>
              <th>Selling_Price</th>
              <th>Reorder Level</th>
              <th style="width:40px;">Actions</th>
            </tr>
          </thead>
          <?php

            ?>
          <tbody>
            <?php
              if ($result) {
                $idd = 1;
                foreach ($result as $value) {
                  $id = $value['categories_id'];

                  $catStmt = $pdo->prepare("SELECT * FROM categories WHERE categories_code='$id'");
                  $catStmt->execute();
                  $catResult = $catStmt->fetch(PDO::FETCH_ASSOC);
              ?>
            <tr>
              <td><?php echo $idd; ?></td>
              <td><?php echo $value['item_id'];?></td>
              <td><?php echo $value['item_name'];?></td>
              <td><?php echo $catResult['categories_name']; ?></td>
              <td><?php echo $value['original_price']; ?></td>
              <td><?php echo $value['selling_price']; ?></td>
              <td><?php echo $value['reorder_level']; ?></td>
              <td>
                <div class="btn-group">
                  <div class="container">
                  <a href="item_edit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                  </a>
                  </div>
                  <div class="contaienr">
                  <a href="item_delete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-sm"  onclick="return confirm('Are you sure you want to Delete?');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                      <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                    </svg>
                  </a>
                  </div>
                </div>
              </td>
            </tr>
            <?php
              $idd++;
                }
              }
              ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Main content -->

<?php include 'footer.html'; ?>
