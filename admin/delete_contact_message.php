<?php
// ---------------------------------------------------------
// Delete Contact Message - Admin Panel
// ---------------------------------------------------------

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_contact_messages.php?error=1");
    exit;
}

$message_id = intval($_GET['id']);

// Delete query
$query = "DELETE FROM contact_messages WHERE message_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $message_id);

if ($stmt->execute()) {
    header("Location: manage_contact_messages.php?deleted=1");
    exit;
} else {
    header("Location: manage_contact_messages.php?error=1");
    exit;
}
