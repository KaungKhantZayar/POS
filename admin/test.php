<label>Supplier ID:</label>
<input type="text" id="supplier_id" oninput="fetchSupplierNameFromId()">

<label>Supplier Name:</label>
<input type="text" id="supplier_name" oninput="fetchSupplierIdFromName()">


<script>
function fetchSupplierNameFromId() {
  const supplierId = document.getElementById('supplier_id').value;

  if (supplierId.trim() === "") {
    document.getElementById('supplier_name').value = "";
    return;
  }

  fetch('get_supplier_by_id.php?supplier_id=' + supplierId)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('supplier_name').value = data.supplier_name;
      } else {
        document.getElementById('supplier_name').value = "Not found";
      }
    })
    .catch(err => {
      console.error("Fetch error:", err);
    });
}

function fetchSupplierIdFromName() {
  const supplierName = document.getElementById('supplier_name').value;

  if (supplierName.trim() === "") {
    document.getElementById('supplier_id').value = "";
    return;
  }

  fetch('get_supplier_by_name.php?supplier_name=' + encodeURIComponent(supplierName))
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('supplier_id').value = data.supplier_id;
      } else {
        document.getElementById('supplier_id').value = "Not found";
      }
    })
    .catch(err => {
      console.error("Fetch error:", err);
    });
}
</script>
