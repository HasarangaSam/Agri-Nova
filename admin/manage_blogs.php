<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "blogs";   // highlight Manage Blogs in sidebar

$alertMessage = "";
$alertType = "success";

if (isset($_GET['success'])) {
    $alertMessage = "Blog added successfully!";
    $alertType = "success";
} elseif (isset($_GET['updated'])) {
    $alertMessage = "Blog updated successfully!";
    $alertType = "success";
} elseif (isset($_GET['deleted'])) {
    $alertMessage = "Blog deleted successfully!";
    $alertType = "success";
} elseif (isset($_GET['error'])) {
    $alertMessage = "Something went wrong!";
    $alertType = "danger";
} elseif (isset($_GET['notfound'])) {
    $alertMessage = "Blog not found!";
    $alertType = "warning";
}

include "sidebar.php";
require "../includes/db.php";

// Handle search (use prepared statement to avoid injection)
$search = $_GET['search'] ?? '';
$blogs = [];

if ($search !== '') {
    $like = "%{$search}%";
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE title_en LIKE ? OR title_si LIKE ? ORDER BY blog_id DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM blogs ORDER BY blog_id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Blogs - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background: #f5f6fa; }

        /* Green header only */
        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .page-header-box h2 { margin: 0; font-size: 1.6rem; color: #2e7d32; font-weight: 700; }

        /* Neutral theme for rest */
        .card { border-radius: 10px; border: 1px solid #dcdde1; }
        .table-hover tbody tr:hover { background: #f1f2f6; }

        thead.table-success {
            background: #dbeafe !important;
            color: #63aae8ff !important;
        }

        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
        .btn-success { background-color: #0d6efd; border-color: #0d6efd; } 
        .btn-secondary { background-color: #6c757d; }
        .btn-warning { background-color: #f0ad4e; border: none; }
        .btn-danger { background-color: #dc3545; border: none; }
        .btn-info { background-color: #17a2b8; border: none; }
        .btn-dark { background-color: #343a40; border: none; }

        .thumb-img { width: 90px; height: 60px; object-fit:cover; border-radius:6px; }
        .truncate { max-width: 240px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; }

        @media (max-width: 768px) {
            body { margin-left: 0; }
            .thumb-img { width: 70px; height: 50px; }
        }
        /* Keep action buttons on one row */
.table .btn {
    white-space: nowrap;
    margin-right: 4px;
    margin-bottom: 4px; /* remove bottom margin if you want perfectly straight row */
}

td.actions-cell {
    white-space: nowrap;
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
        <h2 class="mb-0">Manage Blogs</h2>
    </div>

    <!-- Search + Add New Blog -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <form class="d-flex flex-grow-1 me-3" method="GET" action="manage_blogs.php" style="max-width: 800px;">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by English or Sinhala title"
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <a href="manage_blogs.php" class="btn btn-secondary">Clear</a>
        </form>

        <a href="add_blog.php" class="btn btn-success mt-2 mt-md-0">Add New Blog</a>
    </div>

    <!-- Blogs table -->
    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-success">
                        <tr>
                            <th style="width:60px">#</th>
                            <th style="width:110px">Image</th>
                            <th>Topic (English)</th>
                            <th>Topic (Sinhala)</th>
                            <th style="width:240px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                                $imgHtml = $row['image'] 
                                    ? "<img src=\"../assets/images/blogs/".htmlspecialchars($row['image'])."\" class=\"thumb-img\">"
                                    : "<div class='text-muted'>No Image</div>";
                            ?>
                        <tr>
                            <td><?= (int)$row['blog_id'] ?></td>
                            <td><?= $imgHtml ?></td>
                            <td class="truncate"><?= htmlspecialchars($row['title_en']) ?></td>
                            <td class="truncate"><?= htmlspecialchars($row['title_si']) ?></td>

                            <td class="actions-cell">
                                <a href="view_blog.php?id=<?= (int)$row['blog_id'] ?>" class="btn btn-info btn-sm">View</a>

                                <a href="edit_blog.php?id=<?= (int)$row['blog_id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                                <a href="delete_blog.php?id=<?= (int)$row['blog_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>

                                <!-- NEW BUTTON -->
                                <a href="manage_blog_comments.php?id=<?= (int)$row['blog_id'] ?>" 
                                   class="btn btn-dark btn-sm">Comments</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No blogs found.</td>
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
