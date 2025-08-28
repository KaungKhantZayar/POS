<!DOCTYPE html>
<?php
// session_start();
// require '../Config/common.php';
$current_page = basename($_SERVER['PHP_SELF']);

// Define treeview pages
$purchase_pages = ['purchase_order.php', 'purchase.php', 'purchase_return.php'];
$sale_pages = ['sale_order.php', 'sale.php', 'sale_return.php'];
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>ProTech - Inventory Management</title>

  <link href="bootstrap-4.0.0-dist/css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!-- Bootstrap CDN -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</head>
<style>
  .logout{ border-radius:200px; }
  .dropdown:hover>.dropdown-menu { display: block; }
  .dropdown>.dropdown-toggle:active { pointer-events: none; }
  .a_href{ text-decoration: none; }
  .drome{ width:234px; background-color:gray; }
  /* Active sidebar link */
  .nav-sidebar .nav-link.active {
      background-color: rgba(105,173,31,1) !important;
      color: #fff !important;
  }

  /* Active link hover effect (optional) */
  .nav-sidebar .nav-link.active:hover {
      background-color: rgba(105,173,31,1) !important;
      color: #fff !important;
  }

  /* Treeview menu active link */
  .nav-sidebar .nav-treeview .nav-link.active {
      background-color: rgba(255,255,255,0.3) !important;
      color: #fff !important;
  }

  /* Optional: change icon color for active link */
  .nav-sidebar .nav-link.active i,
  .nav-sidebar .nav-treeview .nav-link.active i {
      color: #fff !important;
}

  .card-title{
    font-size: 25px;
    font-weight: 500;
  }
  .btn-purple{
    background-color: rgba(105,173,31,1) !important;
  }
  .btn-blue{
    background-color: lightblue;
  }
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
.left-sider .main-sidebar {
    position: fixed !important;   /* Fix to viewport */
    top: 0;
    left: 0;
    height: 108.7vh !important;     /* Full screen height */
    overflow-y: auto !important;  /* Scroll if menu is long */
    z-index: 1030;         /* Stay above content */
}
/* Keep the user panel sticky */
.main-sidebar .user-panel {
    position: sticky;
    top: 0;                 /* Stick to the top */
    z-index: 1050;          /* Above other sidebar items */
    background-color: #343a40; /* Match sidebar background */
    padding-top: 1rem;
    padding-bottom: 1rem;
}
.title{
  font-size: 23px;
}
/* table > thead{
  background-color: #d0f0c0;
} */
.table thead.custom-thead th {
background-color: #d0f0c0;
}

.table td, 
.table tr {
  padding: 5px;
}
.tooltip-square {
  width: 25px;
  height: 25px;
  border-radius: 4px;
  position: relative; /* for tooltip positioning */
  cursor: pointer;
}

.tooltip-text {
  visibility: hidden;
  width: max-content;
  background-color: #333;
  color: #fff;
  text-align: center;
  padding: 4px 8px;
  border-radius: 4px;
  position: absolute;
  bottom: 125%; /* above the square */
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
  font-size: 12px;
  z-index: 100;
  opacity: 0;
  transition: opacity 0.3s;
}

/* small arrow */
.tooltip-text::after {
  content: "";
  position: absolute;
  top: 100%; /* bottom of tooltip */
  left: 50%;
  transform: translateX(-50%);
  border-width: 5px;
  border-style: solid;
  border-color: #333 transparent transparent transparent;
}

.tooltip-square:hover .tooltip-text {
  visibility: visible;
  opacity: 1;
}
</style>
<body class="hold-transition sidebar-mini">

<div class="wrapper left-sider">

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="image d-flex">
          <h4 style="color:white;" class="mt-2 title">ProTech - Inventory</h4>
        </div>
      </div>

      <!-- Sidebar Menu -->
       <!-- <div class="outer"> -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

          <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo $current_page=='index.php'?'active':''; ?>">
              <svg style="margin-left:6px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-fill ms-2" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
              </svg>
              <p style="margin-left:8px;">Home</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="category.php" class="nav-link <?php echo $current_page=='category.php'?'active':''; ?>">
              <svg style="margin-left:6px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list  ms-2" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
              </svg>
              <p style="margin-left:8px;">Category</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="item.php" class="nav-link <?php echo $current_page=='item.php'?'active':''; ?>">
              <i class="nav-icon fas fa-th ml-1"></i>
              <p>Item</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="supplier.php" class="nav-link <?php echo $current_page=='supplier.php'?'active':''; ?>">
              <svg style="margin-left:6px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people-fill  ms-2" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
              </svg>
              <p style="margin-left:8px;">Supplier</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="customer.php" class="nav-link <?php echo $current_page=='customer.php'?'active':''; ?>">
              <svg style="margin-left:6px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle ms-2" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
              </svg>
              <p style="margin-left:8px;">Customers</p>
            </a>
          </li>

          <!-- Purchase Treeview -->
          <li class="nav-item has-treeview <?php echo in_array($current_page,$purchase_pages)?'menu-open':''; ?>">
            <a href="#" class="nav-link <?php echo in_array($current_page,$purchase_pages)?'active':''; ?>">
              <i class="nav-icon fas fa-shopping-bag mr-2"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="purchase_order.php" class="nav-link <?php echo $current_page=='purchase_order.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="purchase.php" class="nav-link <?php echo $current_page=='purchase.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="purchase_return.php" class="nav-link <?php echo $current_page=='purchase_return.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Return</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Sale Treeview -->
          <li class="nav-item has-treeview <?php echo in_array($current_page,$sale_pages)?'menu-open':''; ?>">
            <a href="#" class="nav-link <?php echo in_array($current_page,$sale_pages)?'active':''; ?>">
              <i class="nav-icon fas fa-shopping-cart mr-2"></i>
              <p>
                Sale
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="sale_order.php" class="nav-link <?php echo $current_page=='sale_order.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale Order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="sale.php" class="nav-link <?php echo $current_page=='sale.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="sale_return.php" class="nav-link <?php echo $current_page=='sale_return.php'?'active':''; ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sale Return</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Rest of sidebar links -->
          <li class="nav-item">
            <a href="account_payable.php" class="nav-link <?php echo $current_page=='account_payable.php'?'active':''; ?>">
              <i class="nav-icon fas fa-money-bill-wave"></i>
              <p style="margin-left:8px;">Account Payable</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="account_receivable.php" class="nav-link <?php echo $current_page=='account_receivable.php'?'active':''; ?>">
              <i class="nav-icon fas fa-money-bill-wave"></i>
              <p style="margin-left:8px;">Account Receivable</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="stock_control.php" class="nav-link <?php echo $current_page=='stock_control.php'?'active':''; ?>">
              <i class="nav-icon fas fa-box"></i>
              <p style="margin-left:8px;">Stock Control</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="chose_report.php" class="nav-link <?php echo $current_page=='chose_report.php'?'active':''; ?>">
              <i class="nav-icon fas fa-calendar-plus"></i>
              <p style="margin-left:8px;">Report</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- </div> -->
    </div>

  </aside>

  <div class="content-wrapper">
    <div class="content-header" style="padding: 0px !important;">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div>
        </div>
      </div>
    </div>
