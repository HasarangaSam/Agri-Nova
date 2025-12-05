<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

// Handle form submit BEFORE sidebar.php (to avoid header errors)
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title_en    = trim($_POST['title_en']);
    $content_en  = trim($_POST['content_en']);
    $title_si    = trim($_POST['title_si']);
    $content_si  = trim($_POST['content_si']);
    $author_id   = $_SESSION['user_id'];  // logged admin

    $imageName = "";

    // ---- Image Upload ----
    if (!empty($_FILES['image']['name'])) {

        $targetDir = "../assets/images/blogs/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $tempName = $_FILES["image"]["tmp_name"];
        $original = basename($_FILES["image"]["name"]);
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        // Allowed formats
        $allowed = ["jpg", "jpeg", "png", "webp"];
        if (!in_array($ext, $allowed)) {
            $message = "<div class='alert alert-danger'>Only JPG, PNG, WEBP allowed.</div>";
        } else {
            // Keep original filename
            $imageName = $original;
            move_uploaded_file($tempName, $targetDir . $imageName);
        }
    }

    if ($message === "") {
        // Insert blog
        $stmt = $conn->prepare("
            INSERT INTO blogs (title_en, content_en, title_si, content_si, image, author_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssi",
            $title_en,
            $content_en,
            $title_si,
            $content_si,
            $imageName,
            $author_id
        );

        if ($stmt->execute()) {
            header("Location: manage_blogs.php?success=1");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Error adding blog.</div>";
        }
    }
}

$activePage = "blogs";
include "sidebar.php";  // Sidebar loaded AFTER processing to prevent header issues
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add New Blog - AgriNova Admin</title>
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
</style>
</head>
<body>

<div class="container mt-4">

    <!-- Header -->
    <div class="page-header-box">
        <h2>Add New Blog</h2>
    </div>

    <?= $message ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <!-- English Title -->
                <div class="mb-3">
                    <label class="form-label">English Title</label>
                    <input type="text" name="title_en" class="form-control" required>
                </div>

                <!-- English Content -->
                <div class="mb-3">
                    <label class="form-label">English Content</label>
                    <textarea name="content_en" class="form-control" rows="5" required></textarea>
                </div>

                <hr>

                <!-- Sinhala Title -->
                <div class="mb-3">
                    <label class="form-label">Sinhala Title</label>
                    <input type="text" name="title_si" class="form-control" required>
                </div>

                <!-- Sinhala Content -->
                <div class="mb-3">
                    <label class="form-label">Sinhala Content</label>
                    <textarea name="content_si" class="form-control" rows="5" required></textarea>
                </div>

                <hr>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label">Blog Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="text-end">
                    <a href="manage_blogs.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Save Blog</button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
