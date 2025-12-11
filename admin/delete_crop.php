<?php
// ================================
// Delete Crop - AgriNova
// ================================

require "../includes/db.php";

if (isset($_GET['id'])) {
    $crop_id = intval($_GET['id']);

    // 1. First fetch the image so we can delete it from the folder
    $query_img = "SELECT image FROM crop_info WHERE crop_id = ?";
    $stmt_img = $conn->prepare($query_img);
    $stmt_img->bind_param("i", $crop_id);
    $stmt_img->execute();
    $result_img = $stmt_img->get_result();

    if ($result_img->num_rows > 0) {
        $row = $result_img->fetch_assoc();
        $image_path = "uploads/crops/" . $row['image'];

        // Delete image file if exists
        if (!empty($row['image']) && file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // 2. Delete DB record
    $query = "DELETE FROM crop_info WHERE crop_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $crop_id);

        if ($stmt->execute()) {
        header("Location: manage_crop_info.php?deleted");
        exit();
    } else {
        header("Location: manage_crop_info.php?error");
        exit();
    }
} else {
    // If no ID found
    header("Location: manage_crop_info.php");
    exit();
}
?>
