<?php 
// ---------------------------------------------------------
// Respond to Contact Message - Admin Panel
// ---------------------------------------------------------
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";
$activePage = "contacts";

// Use your existing mailer wrapper
require "../includes/mailer.php";

// Validate ID
if (!isset($_GET['id'])) {
    header("Location: manage_contact_messages.php");
    exit;
}

$message_id = intval($_GET['id']);

// Fetch message details
$query = "SELECT * FROM contact_messages WHERE message_id = $message_id LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    header("Location: manage_contact_messages.php");
    exit;
}

$data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Raw response from form
    $response_raw = $_POST['response_message'] ?? '';
    // Safe version for DB
    $response_db = $conn->real_escape_string($response_raw);
    $admin_id = (int)$_SESSION['user_id'];

    // Update DB
    $update = "UPDATE contact_messages 
               SET status='responded',
                   response_message='$response_db',
                   responded_at=NOW(),
                   responded_by=$admin_id
               WHERE message_id=$message_id";

    if ($conn->query($update)) {

        // Prepare HTML email body (escape user input for safety)
        $safe_html_response = nl2br(htmlspecialchars($response_raw, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        $emailBody = "
            <p>Hi <strong>" . htmlspecialchars($data['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</strong>,</p>
            <p>Thank you for contacting AgriNova. We have reviewed your message and provided the following response:</p>
            <div style='padding:12px;border-left:4px solid #2e7d32;background:#f7fff7;margin:12px 0;color:#0b3b12'>
                {$safe_html_response}
            </div>
            <p>If you need further help, reply to this email.</p>
            <p>Regards,<br>AgriNova Support Team</p>
        ";

        // Send email using your sendMail() helper
        $sent = sendMail($data['email'], "Reply from AgriNova Support", $emailBody);

        // Redirect back to view page with flags so view page or manage list can show alerts
        if ($sent) {
            header("Location: view_contact_message.php?id={$message_id}&responded=1");
            exit;
        } else {
            // Email failed but DB updated — still consider responded but notify via flag
            header("Location: view_contact_message.php?id={$message_id}&responded=1&email_failed=1");
            exit;
        }
    } else {
        // DB update failed
        $errorAlert = "<div class='alert alert-danger'>Error updating message. Please try again.</div>";
    }
}

include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Respond to Message - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background:#f5f6fa; }
        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .page-header-box h2 { margin:0; font-size:1.5rem; color:#2e7d32; font-weight:700; }
        .info-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #2e7d32;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .label-title { font-weight: 700; color: #2e7d32; }
        @media(max-width:768px){ body{ margin-left:0; } }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- PAGE HEADER -->
    <div class="page-header-box">
        <h2>Respond to Contact Message</h2>
    </div>

    <?php if (!empty($errorAlert)): ?>
        <div class="mb-3"><?= $errorAlert ?></div>
    <?php endif; ?>

    <div class="info-box">

        <p><span class="label-title">Sender:</span><br>
            <?= htmlspecialchars($data['name'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> 
            (<?= htmlspecialchars($data['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>)
        </p>

        <p><span class="label-title">Original Message:</span><br>
            <?= nl2br(htmlspecialchars($data['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>
        </p>

        <hr>

        <form method="POST">
            <div class="mb-3">
                <label class="label-title">Your Response:</label>
                <textarea name="response_message" class="form-control" rows="6" required></textarea>
            </div>

            <div class="d-flex gap-2">
                <a href="view_contact_message.php?id=<?= $message_id ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Send Response & Email</button>
            </div>
        </form>

    </div>
</div>

</body>
</html>
