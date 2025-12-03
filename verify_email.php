<?php
// -------------------------------------------------------------------
// AGRINOVA - EMAIL VERIFICATION PAGE
// Called when user clicks email verification link
// -------------------------------------------------------------------

require "includes/db.php";

if (!isset($_GET['token'])) {
    die("Invalid verification link.");
}

$token = $_GET['token'];

// Find farmer using token
$stmt = $conn->prepare("SELECT farmer_id FROM farmers WHERE email_verification_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("<h3>Invalid or expired token.</h3>");
}

// Mark email as verified
$update = $conn->prepare("
    UPDATE farmers 
    SET email_verified = 1, email_verification_token = NULL 
    WHERE email_verification_token = ?
");
$update->bind_param("s", $token);

if ($update->execute()) {
    echo "<script>
            alert('Your email has been verified. You can now login!');
            window.location='login.php';
          </script>";
    exit;
}

echo "Verification failed. Try again.";
?>
