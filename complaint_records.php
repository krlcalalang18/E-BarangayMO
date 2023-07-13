<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the table name
$tableName = "complaint";

// Define the columns to be fetched
$columns = [
    "complaintID",
    "ComplainantName",
    "ComplainantCellphoneNo",
    "complaintDateAndTime",
    "complaintAddress",
    "City",
    "Barangay",
    "complaintDetails",
    "complaintType",
    "priorityLevel",
    "complaintStatus",
    "remarks"
];

// Define the search columns
$searchColumns = [
    "ComplainantName",
    "complaintAddress",
    "complaintType",
    "complaintStatus",
    "remarks"
];

// Set the default sorting column and order
$defaultSortColumn = "complaintID";
$defaultSortOrder = "DESC";

// Process the incoming parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$sortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

// Construct the sorting query
$sortColumn = isset($columns[$columnIndex]) ? $columns[$columnIndex] : $defaultSortColumn;
$sortOrder = ($sortOrder == 'asc') ? 'ASC' : 'DESC';
$sortQuery = "ORDER BY $sortColumn $sortOrder";

// Construct the search query
$searchQuery = "";
if (!empty($searchValue)) {
    $searchQuery = "WHERE (";
    foreach ($searchColumns as $column) {
        $searchQuery .= "$column LIKE '%$searchValue%' OR ";
    }
    $searchQuery = rtrim($searchQuery, " OR ") . ")";
}

// Get the total records count
$sqlCount = "SELECT COUNT(*) AS total FROM $tableName $searchQuery";
$resultCount = $conn->query($sqlCount);
$totalRecords = ($resultCount) ? $resultCount->fetch_assoc()['total'] : 0;

// Get the filtered records count
$sqlCountFiltered = "SELECT COUNT(*) AS total FROM $tableName $searchQuery";
$resultCountFiltered = $conn->query($sqlCountFiltered);
$totalRecordsFiltered = ($resultCountFiltered) ? $resultCountFiltered->fetch_assoc()['total'] : 0;

// Get the records data
$sqlData = "SELECT " . implode(", ", $columns) . " FROM $tableName $searchQuery $sortQuery LIMIT $start, $length";
$resultData = $conn->query($sqlData);
$data = [];

if ($resultData && $resultData->num_rows > 0) {
    while ($row = $resultData->fetch_assoc()) {
        $data[] = array_values($row);
    }
}

// Prepare the response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecordsFiltered),
    "data" => $data
];

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$conn->close();
?>
