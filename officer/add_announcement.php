<?php
session_start();

// -------------------------------------------------------------
// CHECK LOGIN & ROLE
// -------------------------------------------------------------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

$officer_id = $_SESSION['user_id'];  // logged officer ID
$activePage = "announcements";

require "../includes/db.php";

$alertMessage = "";
$alertType = "danger";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($title === '' || $message === '') {
        $alertMessage = "All fields are required!";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO district_announcements (officer_id, title, message)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $officer_id, $title, $message);

        if ($stmt->execute()) {
            header("Location: manage_announcements.php?added=1");
            exit;
        } else {
            $alertMessage = "Failed to add announcement. Try again.";
        }
    }
}
?>

<?php include "sidebar_officer.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Announcement - Agri Officer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin-left: 250px; background: #f4f8ff; }
        .page-header-box {
            border-left: 6px solid #1e40af;
            background: #e0e7ff;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .page-header-box h2 { 
            margin: 0; 
            font-size: 1.6rem; 
            color: #1e40af; 
            font-weight: 700; 
        }
        .card { border-radius: 10px; }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="page-header-box">
        <h2>Add Announcement</h2>
        <p class="text-muted mb-0">Create a new announcement for farmers in your district.</p>
    </div>

    <?php if ($alertMessage): ?>
        <div class="alert alert-<?= $alertType ?>"><?= htmlspecialchars($alertMessage) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="POST" action="add_announcement.php">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter announcement title" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your announcement" required></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="manage_announcements.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Add Announcement</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>
