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
$message = "";

// Fetch existing blog
$stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_blogs.php?notfound=1");
    exit;
}

$blog = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title_en    = trim($_POST['title_en']);
    $content_en  = trim($_POST['content_en']);
    $title_si    = trim($_POST['title_si']);
    $content_si  = trim($_POST['content_si']);
    $author_id   = $_SESSION['user_id'];

    $imageName = $blog['image']; // default: keep existing image
    $targetDir = "../uploads/images/blogs/";

    // ---- Image Upload ----
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $tempName = $_FILES["image"]["tmp_name"];
        $original = basename($_FILES["image"]["name"]);
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $allowed)) {
            $message = "<div class='alert alert-danger'>Only JPG, JPEG, PNG, WEBP allowed.</div>";
        } elseif (!is_uploaded_file($tempName)) {
            $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
        } else {
            $destination = $targetDir . $original;

            // Delete old image if a new file with different name
            if (!empty($blog['image']) && $blog['image'] !== $original && file_exists($targetDir . $blog['image'])) {
                unlink($targetDir . $blog['image']);
            }

            if (move_uploaded_file($tempName, $destination)) {
                $imageName = $original; // store original filename in DB
            } else {
                $message = "<div class='alert alert-danger'>Failed to move uploaded image.</div>";
            }
        }
    }

    // Update DB if no errors
    if ($message === "") {
        $stmt = $conn->prepare("
            UPDATE blogs SET title_en = ?, content_en = ?, title_si = ?, content_si = ?, image = ?, author_id = ? 
            WHERE blog_id = ?
        ");
        // Fix: image column is string ('s'), not int ('i')
        $stmt->bind_param(
            "ssssssi",
            $title_en,
            $content_en,
            $title_si,
            $content_si,
            $imageName,
            $author_id,
            $blog_id
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Blog updated successfully!</div>";
            // Refresh blog data
            $stmt2 = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
            $stmt2->bind_param("i", $blog_id);
            $stmt2->execute();
            $blog = $stmt2->get_result()->fetch_assoc();
        } else {
            $message = "<div class='alert alert-danger'>Error updating blog.</div>";
        }
    }
}

$activePage = "blogs";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Blog - AgriNova Admin</title>
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
.current-image { max-height:200px; margin-bottom:15px; border-radius:6px; }
</style>
</head>
<body>

<div class="container mt-4">

    <!-- Header -->
    <div class="page-header-box">
        <h2>Edit Blog</h2>
    </div>

    <?= $message ?>

    <div class="card shadow-sm p-4">

        <form method="POST" enctype="multipart/form-data">

            <!-- English Title -->
            <div class="mb-3">
                <label class="form-label">English Title</label>
                <input type="text" name="title_en" class="form-control" value="<?= htmlspecialchars($blog['title_en']); ?>" required>
            </div>

            <!-- English Content -->
            <div class="mb-3">
                <label class="form-label">English Content</label>
                <textarea name="content_en" class="form-control" rows="5" required><?= htmlspecialchars($blog['content_en']); ?></textarea>
            </div>

            <hr>

            <!-- Sinhala Title -->
            <div class="mb-3">
                <label class="form-label">Sinhala Title</label>
                <input type="text" name="title_si" class="form-control" value="<?= htmlspecialchars($blog['title_si']); ?>" required>
            </div>

            <!-- Sinhala Content -->
            <div class="mb-3">
                <label class="form-label">Sinhala Content</label>
                <textarea name="content_si" class="form-control" rows="5" required><?= htmlspecialchars($blog['content_si']); ?></textarea>
            </div>

            <hr>

            <!-- Current Image -->
            <?php if (!empty($blog['image'])): ?>
                <label class="form-label">Current Image</label><br>
                <img src="../uploads/images/blogs/<?= htmlspecialchars($blog['image']); ?>" class="current-image"><br>
            <?php endif; ?>

            <!-- Upload New Image -->
            <div class="mb-3">
                <label class="form-label">Replace Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="text-end">
                <a href="manage_blogs.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Blog</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
