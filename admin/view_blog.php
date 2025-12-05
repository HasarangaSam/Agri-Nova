<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_blogs.php");
    exit;
}

$blog_id = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT b.*, a.first_name, a.last_name 
    FROM blogs b 
    JOIN admins a ON b.author_id = a.admin_id
    WHERE b.blog_id = ?
");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_blogs.php?notfound=1");
    exit;
}

$blog = $result->fetch_assoc();

$activePage = "blogs";
include "sidebar.php";  // Keep sidebar style consistent
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Blog - AgriNova Admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { margin-left:250px; background:#f8f9fa; }

    .page-header-box {
        border-left: 6px solid #2e7d32;
        background: #e5f5e5;
        padding: 12px 18px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .page-header-box h2 { 
        margin: 0; 
        font-size: 1.6rem; 
        color: #2e7d32; 
        font-weight: 700; 
    }

    .card { border-radius:10px; }
    .blog-image { max-height:400px; object-fit:cover; margin-bottom:20px; border-radius:6px; }
</style>
</head>
<body>

<div class="container mt-4">

    <!-- Header -->
    <div class="page-header-box">
        <h2>View Blog</h2>
    </div>

    <div class="card shadow-sm p-4">

        <!-- Blog Image -->
        <?php if (!empty($blog['image'])): ?>
            <img src="../assets/images/blogs/<?= htmlspecialchars($blog['image']); ?>" class="img-fluid blog-image">
        <?php endif; ?>

        <!-- English Content -->
        <h4 class="mb-2"><?= htmlspecialchars($blog['title_en']); ?> (English)</h4>
        <p><?= nl2br(htmlspecialchars($blog['content_en'])); ?></p>

        <hr>

        <!-- Sinhala Content -->
        <h4 class="mb-2"><?= htmlspecialchars($blog['title_si']); ?> (සිංහල)</h4>
        <p style="font-family:'Noto Sans Sinhala', sans-serif;">
            <?= nl2br(htmlspecialchars($blog['content_si'])); ?>
        </p>

        <hr>

        <!-- Blog Meta Info -->
        <p><strong>Author:</strong> <?= htmlspecialchars($blog['first_name'] . " " . $blog['last_name']); ?></p>
        <p><strong>Created At:</strong> <?= $blog['created_at']; ?></p>
        <p><strong>Last Updated:</strong> <?= $blog['updated_at']; ?></p>

        <!-- Back Button -->
        <a href="manage_blogs.php" class="btn btn-secondary mt-3">Back to Blogs</a>

    </div>
</div>

</body>
</html>
