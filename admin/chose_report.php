<?php
session_start();
require '../Config/config.php';
require '../Config/common.php';

  ?>
<?php include 'header.php'; ?>
<div class="container-fluid py-3 px-3">
  <div class="row">
    <!-- Sidebar (75%) -->
    <div class="col-lg-8">
      <h4>Select Report To View Or Print</h4>
    <nav class="report-sidebar mt-3">
      <ul class="p-0" style="list-style:none;">
        <!-- Sales Reports -->
        <li class="mb-3 fw-bold fs-5">Sales</li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('sales_summary')" class="text-decoration-none text-dark">📄 Sales Summary</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('sales_by_customer')" class="text-decoration-none text-dark">📄 Sales by Customer</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('sales_by_item')" class="text-decoration-none text-dark">📄 Sales by Item</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('sales_by_region')" class="text-decoration-none text-dark">📄 Sales by Region</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('sales_by_channel')" class="text-decoration-none text-dark">📄 Sales by Channel</a></li>

        <!-- Purchase Reports -->
        <li class="mb-3 fw-bold fs-5 mt-4">Purchase</li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('purchase_summary')" class="text-decoration-none text-dark">📄 Purchase Summary</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('purchase_by_supplier')" class="text-decoration-none text-dark">📄 Purchase by Supplier</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('purchase_by_item')" class="text-decoration-none text-dark">📄 Purchase by Item</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('purchase_pending')" class="text-decoration-none text-dark">📄 Pending Purchases</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('purchase_history')" class="text-decoration-none text-dark">📄 Purchase History</a></li>

        <!-- Stock Reports -->
        <li class="mb-3 fw-bold fs-5 mt-4">Stock</li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('stock_summary')" class="text-decoration-none text-dark">📄 Stock Summary</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('low_stock')" class="text-decoration-none text-dark">📄 Low Stock</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('stock_by_category')" class="text-decoration-none text-dark">📄 Stock by Category</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('stock_by_location')" class="text-decoration-none text-dark">📄 Stock by Location</a></li>
        <li class="ms-3 mb-2 fs-6"><a href="#" onclick="loadReport('stock_audit')" class="text-decoration-none text-dark">📄 Stock Audit</a></li>
      </ul>
    </nav>
  </div>


    <!-- Filter Panel (25%) -->
    <div class="col-md-3 filter-box">
      <h5 id="reportTitle" class="mb-3 text-dark">Select a report</h5>

      <div id="reportFilters" class="mb-3" style="display:none;">
        <form class="row g-2">
          <div class="col-12 mb-2">
            <label class="form-label">Category</label>
            <select id="filterCategory" class="form-control">
              <option value="">1</option>
              <option value="">2</option>
              <option value="">3</option>
            </select>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label">Item</label>
            <select id="filterItem" class="form-control">
              <option value="">1</option>
              <option value="">2</option>
              <option value="">3</option>
            </select>
          </div>
          <div class="col-12 mb-2">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="col-12 mb-2">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" id="toDate">
          </div>

          <!-- Buttons Row 1 -->
          <div class="col-12 mb-3 d-flex gap-2 mt-3">
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark" onclick="showReport()">
                <i class="fas fa-eye"></i> Show Report
              </button>
            </div>
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark" onclick="printReport()">
                <i class="fas fa-print"></i> Print
              </button>
            </div>
          </div>

          <!-- Buttons Row 2 -->
          <div class="col-12 mb-2 d-flex gap-2">
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark" onclick="exportExcel()">
                <i class="fas fa-file-excel"></i> Excel
              </button>
            </div>
            <div class="col-6">
              <button type="button" class="btn flex-fill btn-outline-dark" onclick="exportPdf()">
                <i class="fas fa-file-pdf"></i> PDF
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
    function showReport() {
      const from = document.getElementById('fromDate').value;
      const to = document.getElementById('toDate').value;
      const keyword = document.getElementById('keyword').value;
      document.getElementById('reportContent').innerHTML = `
        <p>Showing report from <strong>${from || 'N/A'}</strong> to <strong>${to || 'N/A'}</strong> 
        with keyword: <strong>${keyword || 'N/A'}</strong></p>`;
    }

    function printReport() {
      alert('Print report action!');
    }

    function exportExcel() {
      alert('Export to Excel!');
    }

    function exportPdf() {
      alert('Export to PDF!');
    }
    </script>

  </div>
</div>

<script>

const reportFiltersConfig = {
  sales_summary: ["fromDate", "toDate"],
  sales_by_customer: ["filterCategory", "fromDate", "toDate"],
  sales_by_item: ["filterCategory", "filterItem", "fromDate", "toDate"],
  stock_by_category: ["filterCategory"],
  stock_by_location: ["filterCategory"],
  balance_by_category: ["filterCategory"], // your example
  stock_audit: ["fromDate", "toDate"],
  // ... add more as needed
};


function loadReport(name) {
  // Remove active highlight from all links
  document.querySelectorAll('.report-sidebar a').forEach(el => {
    el.classList.remove('active');
  });

  // Highlight clicked link
  event.target.classList.add('active');

  // Update title and show filters
  document.getElementById('reportTitle').innerText = name.replace(/_/g, ' ').toUpperCase();
  document.getElementById('reportFilters').style.display = 'block';
  document.getElementById('reportContent').innerHTML = `<p class="text-muted">Loading ${name}...</p>`;

  // Disable all filters first
  document.querySelectorAll('#reportFilters select, #reportFilters input').forEach(el => {
    el.disabled = true;
  });

  // Enable only filters needed for this report
  if (reportFiltersConfig[name]) {
    reportFiltersConfig[name].forEach(id => {
      document.getElementById(id).disabled = false;
    });
  }
}


function applyFilter(){
  const from = document.getElementById('fromDate').value;
  const to = document.getElementById('toDate').value;
  const keyword = document.getElementById('keyword').value;
  document.getElementById('reportContent').innerHTML = `<p>Filters Applied: From ${from || 'N/A'} To ${to || 'N/A'} Keyword: ${keyword || 'N/A'}</p>`;
}
</script>
<script>
function loadReport(name) {
  // Remove active from all links
  document.querySelectorAll('.report-sidebar a').forEach(el => {
    el.classList.remove('active');
  });

  // Add active to the clicked link
  event.target.classList.add('active');

  // Update title and filters
  document.getElementById('reportTitle').innerText = name.replace(/_/g, ' ').toUpperCase();
  document.getElementById('reportFilters').style.display = 'block';
  document.getElementById('reportContent').innerHTML = `<p class="text-muted">Loading ${name}...</p>`;
}
</script>

<?php include 'footer.html'; ?>
