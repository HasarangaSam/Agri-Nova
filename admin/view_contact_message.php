<?php
// ---------------------------------------------------------
// View Contact Message - Admin Panel
// ---------------------------------------------------------
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "contacts"; 
include "sidebar.php";
require "../includes/db.php";

// Validate ID
if (!isset($_GET['id'])) {
    header("Location: manage_contact_messages.php");
    exit;
}

$message_id = intval($_GET['id']);

// 🔧 FIXED SQL — replaced a.username with CONCAT(first_name, last_name)
$query = "SELECT c.*, 
                 CONCAT(a.first_name, ' ', a.last_name) AS responder 
          FROM contact_messages c 
          LEFT JOIN admins a ON c.responded_by = a.admin_id
          WHERE c.message_id = $message_id 
          LIMIT 1";

$result = $conn->query($query);

if ($result->num_rows === 0) {
    header("Location: manage_contact_messages.php");
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Contact Message - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background:#f5f6fa; }
        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 10px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .page-header-box h2 { margin:0; font-size:1.6rem; color:#2e7d32; font-weight:700; }

        .info-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #2e7d32;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .label-title {
            font-weight: 700;
            color: #2e7d32;
        }

        .status-badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 600;
        }
        .status-pending { background:#fff3cd; color:#a67c00; }
        .status-responded { background:#d4edda; color:#1b5e20; }

        @media(max-width:768px){
            body{ margin-left:0; }
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- PAGE HEADER -->
    <div class="page-header-box">
        <h2>View Contact Message</h2>
    </div>

    <!-- DETAILS CARD -->
    <div class="info-box">

        <p><span class="label-title">Sender Name:</span><br>
            <?= htmlspecialchars($data['name']) ?>
        </p>

        <p><span class="label-title">Email:</span><br>
            <?= htmlspecialchars($data['email']) ?>
        </p>

        <p><span class="label-title">Message:</span><br>
            <?= nl2br(htmlspecialchars($data['message'])) ?>
        </p>

        <p>
            <span class="label-title">Status:</span><br>
            <?php if ($data['status'] === 'pending'): ?>
                <span class="status-badge status-pending">Pending</span>
            <?php else: ?>
                <span class="status-badge status-responded">Responded</span>
            <?php endif; ?>
        </p>

        <p><span class="label-title">Received On:</span><br>
            <?= $data['created_at'] ?>
        </p>

        <?php if ($data['status'] === 'responded'): ?>
        <hr>
        <p><span class="label-title">Response Message:</span><br>
            <?= nl2br(htmlspecialchars($data['response_message'])) ?>
        </p>

        <p><span class="label-title">Responded By:</span><br>
            <?= htmlspecialchars($data['responder'] ?: 'N/A') ?>
        </p>

        <p><span class="label-title">Responded At:</span><br>
            <?= $data['responded_at'] ?>
        </p>
        <?php endif; ?>

        <div class="mt-4 d-flex gap-2">

            <!-- Back Button -->
            <a href="manage_contact_messages.php" class="btn btn-secondary">
                Back to Messages
            </a>

            <!-- Respond Button (only if pending) -->
            <?php if ($data['status'] === 'pending'): ?>
            <a href="respond_contact_message.php?id=<?= $data['message_id'] ?>"
               class="btn btn-success">
               Respond
            </a>
            <?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>
