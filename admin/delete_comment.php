<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['blog_id'])) {
    header("Location: manage_blogs.php?error=1");
    exit;
}

$comment_id = (int)$_GET['id'];
$blog_id = (int)$_GET['blog_id'];

require "../includes/db.php";

$stmt = $conn->prepare("DELETE FROM blog_comments WHERE comment_id = ?");
$stmt->bind_param("i", $comment_id);

if ($stmt->execute()) {
    header("Location: manage_blog_comments.php?id=$blog_id&deleted=1");
} else {
    header("Location: manage_blog_comments.php?id=$blog_id&error=1");
}
exit;
