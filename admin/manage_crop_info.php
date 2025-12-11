<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "crop_info";  // highlight in sidebar

$alertMessage = "";
$alertType = "success";

if (isset($_GET['success'])) {
    $alertMessage = "Crop information added successfully!";
} elseif (isset($_GET['updated'])) {
    $alertMessage = "Crop information updated successfully!";
} elseif (isset($_GET['deleted'])) {
    $alertMessage = "Crop information deleted successfully!";
} elseif (isset($_GET['error'])) {
    $alertMessage = "Something went wrong!";
    $alertType = "danger";
} elseif (isset($_GET['notfound'])) {
    $alertMessage = "Record not found!";
    $alertType = "warning";
}

include "sidebar.php";
require "../includes/db.php";

// Search filter
$search = $_GET['search'] ?? '';

if ($search !== '') {
    $like = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM crop_info WHERE crop_name LIKE ? ORDER BY crop_id DESC");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM crop_info ORDER BY crop_id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Crop Info - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background: #f5f6fa; }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .page-header-box h2 { margin: 0; font-size: 1.6rem; color: #2e7d32; font-weight: 700; }

        .card { border-radius: 10px; border: 1px solid #dcdde1; }

        thead.table-success {
            background: #dbeafe !important;
            color: #63aae8ff !important;
        }

        .thumb-img { width: 90px; height: 60px; object-fit:cover; border-radius:6px; }
        .truncate { max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; }

        .table-hover tbody tr:hover { background: #f1f2f6; }

        .actions-cell .btn { white-space: nowrap; margin-right: 4px; }

        @media (max-width: 768px) {
            body { margin-left: 0; }
            .thumb-img { width: 70px; height: 50px; }
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">

<?php if ($alertMessage !== ""): ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert" id="flash-alert">
        <?= htmlspecialchars($alertMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <!-- Page header -->
    <div class="page-header-box">
        <h2 class="mb-0">Manage Crop Information</h2>
    </div>

    <!-- Search + Add -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form class="d-flex flex-grow-1 me-3" method="GET" action="manage_crop_info.php" style="max-width:800px;">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by crop name"
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <a href="manage_crop_info.php" class="btn btn-secondary">Clear</a>
        </form>

        <a href="add_crop_info.php" class="btn btn-success mt-2 mt-md-0">Add Crop Info</a>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th style="width:70px">#</th>
                            <th style="width:110px">Image</th>
                            <th>Crop Name</th>
                            <th style="width:240px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php 
                                $imgHtml = $row['image']
                                    ? "<img src=\"../uploads/images/crops/".htmlspecialchars($row['image'])."\" class=\"thumb-img\">"
                                    : "<div class='text-muted'>No Image</div>";
                            ?>
                        <tr>
                            <td><?= (int)$row['crop_id'] ?></td>
                            <td><?= $imgHtml ?></td>
                            <td class="truncate"><?= htmlspecialchars($row['crop_name']) ?></td>

                            <td class="actions-cell">
                                <a href="view_crop.php?id=<?= (int)$row['crop_id'] ?>" class="btn btn-info btn-sm">View</a>

                                <a href="edit_crop.php?id=<?= (int)$row['crop_id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                                <a href="delete_crop.php?id=<?= (int)$row['crop_id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this record?');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No crop information found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(() => {
        const alertEl = document.getElementById('flash-alert');
        if(alertEl) new bootstrap.Alert(alertEl).close();
    }, 5000);
</script>

</body>
</html>
