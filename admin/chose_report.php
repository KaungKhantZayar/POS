<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

// Get current report
$report_name = isset($_GET['report_name']) ? $_GET['report_name'] : null;

// Default: all disabled
$filterCategory = $filterItem = $fromDate = $toDate = $filterCustomer = $filterSupplier = $filterStockFoc = $filterDamageStock = $filterReturnStock = true;

// Enable/disable logic
if (in_array($report_name, ["stock_inventory_summary"])) {
  $filterCategory = true;
  $filterItem = false;
  $fromDate = false;
  $toDate = false;
  $filterDamageStock = false;
  $filterReturnStock = false;
  $filterStockFoc = false;

} elseif ($report_name === "balance_by_category") {
  $filterCategory = false;
  $filterItem = true;
  $fromDate = false;
  $toDate = false;
  $filterDamageStock = false;
  $filterReturnStock = false;
  $filterStockFoc = false;
} elseif (in_array($report_name, ["sales_summary", "total_sales"])) {
  $fromDate = false;
  $toDate = false;
  $filterCustomer = false;
} elseif ($report_name === "royal_customer") {
  $fromDate = false;
  $toDate = false;
} elseif (in_array($report_name, ["purchase_summary", "total_purchase"])) {
  $fromDate = false;
  $toDate = false;
  $filterSupplier = false;
}

?>
<?php include 'header.php'; ?>

<div class="container-fluid py-3 px-3">
  <div class="row">
    <!-- Sidebar (75%) -->
    <div class="col-lg-8">
      <h4>Select Report To View Or Print</h4>
      <nav class="report-sidebar mt-3">
        <ul class="p-0" style="list-style:none;">
          <!-- Stock Reports -->
          <li class="mb-3 fw-bold fs-5 mt-4">Stock</li>
          <li class="ms-3 mb-2 fs-6">
            <a href="?report_name=stock_inventory_summary"
               class="text-decoration-none text-dark <?php if($report_name === 'stock_inventory_summary'){ echo "active"; } ?>">
               📄 Stock Inventory Summary
            </a>
          </li>
          <li class="ms-3 mb-2 fs-6">
            <a href="?report_name=balance_by_category"
               class="text-decoration-none text-dark <?php if($report_name === 'balance_by_category'){ echo "active"; } ?>">
               📄 Balance by Category
            </a>
          </li>

          <!-- Sales Reports -->
          <li class="mb-3 fw-bold fs-5">Sales</li>
          <li class="ms-3 mb-2 fs-6"><a href="?report_name=sales_summary" class="text-decoration-none text-dark <?php if($report_name === 'sales_summary'){ echo "active"; } ?>">📄 Sales Summary</a></li>
          <li class="ms-3 mb-2 fs-6"><a href="?report_name=total_sales" class="text-decoration-none text-dark <?php if($report_name === 'total_sales'){ echo "active"; } ?>">📄 Total Sales</a></li>
          <li class="ms-3 mb-2 fs-6"><a href="?report_name=royal_customer" class="text-decoration-none text-dark <?php if($report_name === 'royal_customer'){ echo "active"; } ?>">📄 Royal Customer</a></li>

          <!-- Purchase Reports -->
          <li class="mb-3 fw-bold fs-5 mt-4">Purchase</li>
          <li class="ms-3 mb-2 fs-6"><a href="?report_name=purchase_summary" class="text-decoration-none text-dark <?php if($report_name === 'purchase_summary'){ echo "active"; } ?>">📄 Purchase Summary</a></li>
          <li class="ms-3 mb-2 fs-6"><a href="?report_name=total_purchase" class="text-decoration-none text-dark <?php if($report_name === 'total_purchase'){ echo "active"; } ?>">📄 Total Purchase</a></li>
        </ul>
      </nav>
    </div>

    <!-- Filter Panel (25%) -->
    <div class="col-md-3 filter-box">
      <h4 id="reportTitle" class="mb-3 text-dark">Report Filters</h4>

      <div id="reportFilters" class="mb-3">
        <form class="row g-2" action="report.php" method="GET" target="_blank">
          <input type="hidden" name="report_name" value="<?php echo htmlspecialchars($report_name); ?>">
          <div class="col-12 mb-2 d-flex">
            <div class="col-6">
              <label class="form-label">Category</label>
              <select id="filterCategory" 
                      class="form-control report-input chzn-select"
                      <?php echo $filterCategory ? "disabled" : ""; ?> 
                      name="category_id">
                <option value="all">All</option>
                <?php 
                $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $value) {
                  ?>
                  <option value="<?php echo $value['categories_code']; ?>">
                    <?php echo $value['categories_name']; ?>
                  </option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Item</label>
              <select id="filterItem" class="form-control report-input" <?php echo $filterItem ? "disabled" : ""; ?> name="item_id">
                <option value="">All</option>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM item ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $value) {
                  ?>
                  <option value="<?php echo $value['item_id']; ?>"><?php echo $value['item_name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-12 mb-2 d-flex">
            <div class="col-6">
              <label class="form-label">Supplier</label>
              <select id="filterSupplier" class="form-control report-input" <?php echo $filterSupplier ? "disabled" : ""; ?> name="supplier_id">
                <option value="all">All</option>
                <?php 
                $stmt = $pdo->prepare("SELECT * FROM supplier ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $value) {
                  ?>
                  <option value="<?php echo $value['supplier_id']; ?>"><?php echo $value['supplier_name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Customer</label>
              <select id="filterCustomer" class="form-control report-input" <?php echo $filterCustomer ? "disabled" : ""; ?> name="customer_id">
                <option value="">All</option>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM customer ORDER BY id DESC");
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $value) {
                  ?>
                  <option value="<?php echo $value['customer_id']; ?>"><?php echo $value['customer_name']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-12 mb-2 d-flex">
            <div class="col-6">
              <label class="form-label">FOC</label>
              <select id="filterStockFoc" class="form-control report-input" name="stock_foc" <?php echo $filterStockFoc ? "disabled" : ""; ?>>
                <option value="all">All</option>
                <option value="purchase_foc">Purchase FOC</option>
                <option value="sale_foc">Sale FOC</option>
                <option value="">Do Not Show</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">Damage</label>
              <select id="filterDamageStock" class="form-control report-input" name="damage_stock" <?php echo $filterDamageStock ? "disabled" : ""; ?>>
                <option value="all">All</option>
                <option value="">Do Not Show</option>
              </select>
            </div>
          </div>
          <div class="col-12 mb-2 px-3">
            <label class="form-label">Return</label>
            <select id="filterReturnStock" class="form-control report-input" name="return_stock" <?php echo $filterReturnStock ? "disabled" : ""; ?>>
              <option value="all">All</option>
              <option value="purchase_return">Purchase Return</option>
              <option value="sale_return">Sale Return</option>
              <option value="">Do Not Show</option>
            </select>
          </div>
          <div class="col-12 mb-2 d-flex">
            <div class="col-6">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control report-input" name="start_date" id="fromDate" <?php echo $fromDate ? "disabled" : ""; ?>>
            </div>
            <div class="col-6">
              <label class="form-label">End Date</label>
              <input type="date" class="form-control report-input" name="end_date" id="toDate" <?php echo $toDate ? "disabled" : ""; ?>>
            </div>
          </div>

          <!-- Buttons Row 1 -->
          <div class="col-12 mb-3 d-flex gap-2 mt-3">
            <div class="col-6">
              <button type="submit" class="btn flex-fill btn-outline-dark" style="background-color: #69ad1f; color: white;">
                <i class="fas fa-eye"></i> Show Report
              </button>
            </div>
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark">
                <i class="fas fa-print"></i> Print
              </button>
            </div>
          </div>

          <!-- Buttons Row 2 -->
          <div class="col-12 mb-2 d-flex gap-2">
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark">
                <i class="fas fa-file-excel"></i> Excel
              </button>
            </div>
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark">
                <i class="fas fa-file-pdf"></i> PDF
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Report Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if(isset($reportData)): ?>
          <!-- Render your report table here -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Item</th>
                <th>In</th>
                <th>Out</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($reportData as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['item_name']) ?></td>
                  <td><?= $row['in_qty'] ?></td>
                  <td><?= $row['out_qty'] ?></td>
                  <td><?= $row['balance'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No report generated yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $(".chosen-select").chosen({
      width: "100%",        // makes it fit bootstrap column
      placeholder_text_single: "Select a category",
      no_results_text: "No match found!"
    });
  });
</script>

<?php include 'footer.html'; ?>
