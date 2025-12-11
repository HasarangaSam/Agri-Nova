<?php
session_start();
include('../includes/db.php');

// Check if officer_id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_officers.php?error=Invalid Officer ID");
    exit();
}

$officer_id = intval($_GET['id']);

// First check if the officer exists
$check = $conn->prepare("SELECT officer_id FROM officers WHERE officer_id = ?");
$check->bind_param("i", $officer_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_officers.php?error=Officer not found");
    exit();
}

// Delete officer
$stmt = $conn->prepare("DELETE FROM officers WHERE officer_id = ?");
$stmt->bind_param("i", $officer_id);

if ($stmt->execute()) {
    header("Location: manage_officers.php?deleted=Officer deleted successfully");
    exit();
} else {
    header("Location: manage_officers.php?error=Failed to delete officer");
    exit();
}
?>
