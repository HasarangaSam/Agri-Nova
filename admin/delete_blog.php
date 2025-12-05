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

// Fetch blog to get image
$stmt = $conn->prepare("SELECT image FROM blogs WHERE blog_id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_blogs.php?notfound=1");
    exit;
}

$blog = $result->fetch_assoc();
$imagePath = "../assets/images/blogs/" . $blog['image'];

// Delete image file if exists
if (!empty($blog['image']) && file_exists($imagePath)) {
    unlink($imagePath);
}

// Delete blog from DB
$stmt = $conn->prepare("DELETE FROM blogs WHERE blog_id = ?");
$stmt->bind_param("i", $blog_id);

if ($stmt->execute()) {
    header("Location: manage_blogs.php?deleted=1");
    exit;
} else {
    header("Location: manage_blogs.php?error=1");
    exit;
}
?>
