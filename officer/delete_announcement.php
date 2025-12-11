<?php
session_start();

// -------------------------------------------------------------
//     CHECK LOGIN & ROLE
// -------------------------------------------------------------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

$officer_id = $_SESSION['user_id'];  // logged officer ID

require "../includes/db.php";

// Get announcement ID
$announcement_id = $_GET['id'] ?? 0;

// Check if the announcement belongs to this officer
$stmt = $conn->prepare("
    SELECT * FROM district_announcements
    WHERE announcement_id = ? AND officer_id = ?
");
$stmt->bind_param("ii", $announcement_id, $officer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Announcement not found or does not belong to officer
    header("Location: manage_announcements.php?error=1");
    exit;
}

// Delete announcement
$deleteStmt = $conn->prepare("
    DELETE FROM district_announcements
    WHERE announcement_id = ? AND officer_id = ?
");
$deleteStmt->bind_param("ii", $announcement_id, $officer_id);

if ($deleteStmt->execute()) {
    header("Location: manage_announcements.php?deleted=1");
    exit;
} else {
    header("Location: manage_announcements.php?error=1");
    exit;
}
?>
