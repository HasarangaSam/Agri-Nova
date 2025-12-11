<?php
// ---------------------------------------------------------
// VIEW SINGLE BLOG PAGE
// Public view: all users can see
// Commenting allowed only for logged-in farmers
// ---------------------------------------------------------

session_start();
$farmer_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

require "includes/db.php";

// -------------------- Get Blog ID --------------------
$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($blog_id <= 0) {
    die("Invalid blog ID.");
}

// -------------------- Language Filter --------------------
$lang = $_GET['lang'] ?? 'en';
$lang = ($lang === 'si') ? 'si' : 'en';

// -------------------- Handle New Comment --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $farmer_id && isset($_POST['comment'])) {
    $commentText = trim($_POST['comment']);
    if ($commentText !== '') {
        $stmt = $conn->prepare("INSERT INTO blog_comments (blog_id, farmer_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $blog_id, $farmer_id, $commentText);
        $stmt->execute();
        // Redirect with flag to trigger alert 
        header("Location: view_blog.php?id=$blog_id&lang=$lang&comment_added=1");
        exit; 
    }
}

// -------------------- Fetch Blog --------------------
$stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
if (!$blog) die("Blog not found.");

// -------------------- Fetch Comments --------------------
$comments_stmt = $conn->prepare("
    SELECT bc.comment, bc.created_at, f.first_name, f.last_name 
    FROM blog_comments bc
    JOIN farmers f ON bc.farmer_id = f.farmer_id
    WHERE bc.blog_id = ?
    ORDER BY bc.created_at ASC
");
$comments_stmt->bind_param("i", $blog_id);
$comments_stmt->execute();
$all_comments = $comments_stmt->get_result();
$total_comments = $all_comments->num_rows;

// -------------------- Include Navbar --------------------
$activePage = 'knowledge';
$activeDropdown = 'blogs';
include "includes/navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lang === 'si' ? $blog['title_si'] : $blog['title_en']) ?> - AgriNova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; padding-top: 70px; }
        nav.navbar { position: fixed; top: 0; width: 100%; z-index: 1030; }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 30px auto 20px;
            max-width: 800px;
            text-align: center;
        }

        /* Full image display */
        .blog-img { width: 100%; border-radius:10px; margin-bottom: 20px; }

        .blog-content { padding: 15px; font-size: 1.05rem; color: #333; }
        .blog-meta { color: #555; font-size: 0.85rem; margin-bottom: 20px; }

        /* Comments Section */
        .comment-box { border-bottom: 1px solid #ddd; padding: 10px 0; display:flex; }
        .comment-avatar { font-size: 1.5rem; margin-right: 10px; color: #0d6efd; }
        .comment-text { flex: 1; }
        .comment-meta { font-size: 0.8rem; color: #888; margin-top: 4px; }

        .comment-form textarea { resize: none; }

        /* View More Comments Button */
        #viewMoreComments { cursor: pointer; color: #0d6efd; text-decoration: underline; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <!-- Page Header -->
    <div class="page-header-box">
        <h2><?= htmlspecialchars($lang === 'si' ? $blog['title_si'] : $blog['title_en']) ?></h2>
    </div>

    <!-- Language Filter -->
    <div class="d-flex justify-content-end mb-3">
        <form method="GET" class="d-flex align-items-center">
            <label class="me-2">Language:</label>
            <select name="lang" class="form-select me-2" onchange="this.form.submit()">
                <option value="en" <?= ($lang === 'en') ? 'selected' : '' ?>>English</option>
                <option value="si" <?= ($lang === 'si') ? 'selected' : '' ?>>සිංහල</option>
            </select>
            <input type="hidden" name="id" value="<?= $blog_id ?>">
            <noscript><button type="submit" class="btn btn-primary">Apply</button></noscript>
        </form>
    </div>

    <!-- Blog Image -->
    <?php if (!empty($blog['image']) && file_exists('uploads/images/blogs/'.$blog['image'])): ?>
        <img src="uploads/images/blogs/<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image" class="blog-img">
    <?php endif; ?>

    <!-- Blog Content -->
    <div class="blog-content">
        <?= nl2br(htmlspecialchars($lang === 'si' ? $blog['content_si'] : $blog['content_en'])) ?>
    </div>

    <div class="blog-meta">
        By <strong>Admin</strong> | <?= date('d M Y, h:i A', strtotime($blog['created_at'])) ?>
    </div>

    <!-- Comments Section -->
    <h4 class="mb-3">Comments (<?= $total_comments ?>)</h4>

    <div id="commentsContainer">
        <?php
        $count = 0;
        $all_comments->data_seek(0); // Reset pointer
        while ($c = $all_comments->fetch_assoc()) {
            $count++;
            $hidden = ($count > 4) ? 'style="display:none;" class="extra-comment"' : '';
            echo '<div '.$hidden.' class="comment-box">';
            echo '<div class="comment-avatar"><i class="fas fa-user-circle"></i></div>';
            echo '<div class="comment-text">';
            echo '<strong>'.htmlspecialchars($c['first_name'].' '.$c['last_name']).'</strong>';
            echo '<div>'.nl2br(htmlspecialchars($c['comment'])).'</div>';
            echo '<div class="comment-meta">'.date('d M Y, h:i A', strtotime($c['created_at'])).'</div>';
            echo '</div></div>';
        }
        ?>
    </div>

    <?php if ($total_comments > 4): ?>
        <div id="viewMoreComments">View more comments</div>
    <?php endif; ?>

    <!-- Add Comment Form -->
    <?php if ($farmer_id && $user_role === 'farmer'): ?>
        <div class="mt-4">
            <h5>Add a Comment</h5>
            <form method="POST" class="comment-form">
                <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Write your comment..." required></textarea>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>
    <?php elseif (!$farmer_id): ?>
        <p class="text-muted mt-3">You must <a href="signup.php">login</a> as a farmer to add a comment.</p>
    <?php endif; ?>

</div>

<!-- Include Footer -->
<?php include "includes/footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Show more comments in chunks of 5
    const viewMoreBtn = document.getElementById('viewMoreComments');
    if(viewMoreBtn){
        viewMoreBtn.addEventListener('click', function(){
            const hiddenComments = document.querySelectorAll('.extra-comment');
            for(let i=0; i<5 && i<hiddenComments.length; i++){
                hiddenComments[i].style.display = 'flex';
                hiddenComments[i].classList.remove('extra-comment');
            }
            if(document.querySelectorAll('.extra-comment').length === 0){
                viewMoreBtn.style.display = 'none';
            }
        });
    }

</script>

</body>
</html>
