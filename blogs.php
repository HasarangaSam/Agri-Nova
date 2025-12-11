<?php
// ---------------------------------------------------------
// BLOGS PAGE - PUBLIC VIEW
// Both logged-in and guest users can access this page
// Features:
// 1. Language filter (English/Sinhala)
// 2. Pagination (latest 4 blogs per page)
// 3. Display blog image, title, snippet, admin, created date
// ---------------------------------------------------------

session_start();  // Start session to store user info if logged in

// Store user info if logged in
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$activePage = "knowledge";       // For navbar active highlight
$activeDropdown = "blogs";       // For navbar dropdown highlight

include "includes/navbar.php";   // Include main site navbar
require "includes/db.php";       // Include database connection

// -------------------- Language Filter --------------------
$lang = $_GET['lang'] ?? 'en';   // Default English
$lang = ($lang === 'si') ? 'si' : 'en';  // Only allow 'en' or 'si'

// -------------------- Pagination Setup --------------------
$perPage = 4;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Count total blogs
$totalBlogs = $conn->query("SELECT COUNT(*) AS total FROM blogs")->fetch_assoc()['total'];
$totalPages = ceil($totalBlogs / $perPage);

// -------------------- Fetch Blogs --------------------
$stmt = $conn->prepare("
    SELECT * FROM blogs
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest Blogs - AgriNova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 30px auto 20px;
            max-width: 800px;
            text-align: center;
        }
        .blog-card { border-radius: 10px; margin-bottom: 25px; }
        .blog-img { width: 100%; max-height: 220px; object-fit: cover; border-top-left-radius:10px; border-top-right-radius:10px; }
        .blog-content { padding: 15px; }
        .blog-title { font-weight: 600; font-size: 1.3rem; }
        .blog-snippet { color: #555; margin-top: 8px; }
        .blog-meta { font-size: 0.85rem; color: #888; margin-top: 10px; }
        .pagination { justify-content: center; margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">

    <!-- Page Header -->
    <div class="page-header-box">
        <h2>Latest Blogs</h2>
    </div>

    <!-- Language Filter -->
    <div class="d-flex justify-content-end mb-3">
        <form method="GET" class="d-flex align-items-center">
            <label class="me-2">Language:</label>
            <select name="lang" class="form-select me-2" onchange="this.form.submit()">
                <option value="en" <?= ($lang === 'en') ? 'selected' : '' ?>>English</option>
                <option value="si" <?= ($lang === 'si') ? 'selected' : '' ?>>සිංහල</option>
            </select>
            <noscript><button type="submit" class="btn btn-primary">Apply</button></noscript>
        </form>
    </div>

    <!-- Blog Cards -->
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm blog-card">
                        <?php if (!empty($row['image']) && file_exists('uploads/images/blogs/'.$row['image'])): ?>
                            <img src="uploads/images/blogs/<?= htmlspecialchars($row['image']) ?>" alt="Blog Image" class="blog-img">
                        <?php endif; ?>
                        <div class="blog-content">
                            <div class="blog-title">
                                <?= htmlspecialchars($lang === 'si' ? $row['title_si'] : $row['title_en']) ?>
                            </div>
                            <div class="blog-snippet">
                                <?= htmlspecialchars(mb_substr($lang === 'si' ? strip_tags($row['content_si']) : strip_tags($row['content_en']), 0, 150, 'UTF-8')) ?>
                                <?= (mb_strlen($lang === 'si' ? strip_tags($row['content_si']) : strip_tags($row['content_en']), 'UTF-8') > 150) ? '…' : '' ?>
                            </div>
                            <div class="blog-meta">
                                By <strong>Admin</strong> | <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?>
                                <a href="view_blog.php?id=<?= (int)$row['blog_id'] ?>&lang=<?= $lang ?>" class="btn btn-sm btn-primary float-end">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">No blogs found.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>&lang=<?= $lang ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($p=1; $p<=$totalPages; $p++): ?>
                <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $p ?>&lang=<?= $lang ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>&lang=<?= $lang ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include "includes/footer.php"; ?>
