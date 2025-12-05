<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "blogs";  // highlight blogs in sidebar
include "sidebar.php";
require "../includes/db.php";

$alertMessage = "";
$alertType = "success";

if (isset($_GET['deleted'])) {
    $alertMessage = "Comment deleted successfully!";
    $alertType = "success";
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_blogs.php?notfound=1");
    exit;
}

$blog_id = (int)$_GET['id'];

// Fetch blog title
$stmtBlog = $conn->prepare("SELECT title_en, title_si FROM blogs WHERE blog_id = ?");
$stmtBlog->bind_param("i", $blog_id);
$stmtBlog->execute();
$blogResult = $stmtBlog->get_result();

if ($blogResult->num_rows === 0) {
    header("Location: manage_blogs.php?notfound=1");
    exit;
}

$blog = $blogResult->fetch_assoc();

// Fetch all comments for this blog
$stmt = $conn->prepare("
    SELECT c.comment_id, c.comment, c.created_at,
           f.first_name, f.last_name
    FROM blog_comments c
    JOIN farmers f ON c.farmer_id = f.farmer_id
    WHERE c.blog_id = ?
    ORDER BY c.comment_id DESC
");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$comments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Blog Comments</title>
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
        .page-header-box h2 { margin: 0; color: #2e7d32; }
        thead.table-primary {
            background: #dbeafe !important;
            color: #63aae8ff !important;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">

<?php if ($alertMessage !== ""): ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" id="flash-alert">
        <?= htmlspecialchars($alertMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

    <div class="page-header-box">
        <h2>Comments for:  
            <span class="text-dark"><?= htmlspecialchars($blog['title_en']) ?> / <?= htmlspecialchars($blog['title_si']) ?></span>
        </h2>
    </div>

    <a href="manage_blogs.php" class="btn btn-secondary mb-3">← Back to Blogs</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:200px;">Farmer Name</th>
                            <th>Comment</th>
                            <th style="width:170px;">Date</th>
                            <th style="width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if ($comments->num_rows > 0): ?>
                        <?php while ($row = $comments->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$row['comment_id'] ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td>
                                    <a href="delete_comment.php?id=<?= (int)$row['comment_id'] ?>&blog_id=<?= $blog_id ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Delete this comment?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No comments found.</td>
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
    let alertBox = document.getElementById("flash-alert");
    if (alertBox) {
        let alert = new bootstrap.Alert(alertBox);
        alert.close();
    }
}, 5000);
</script>

</body>
</html>
