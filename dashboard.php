<?php
session_start();

if (!isset($_SESSION['user_handle'])) {
    header("Location: login.php");
    exit();
}

$posts_file = 'data/posts.csv';
$user_posts = [];

// Handle delete request
if (isset($_POST['delete_post_id'])) {
    $delete_post_id = $_POST['delete_post_id'];

    // Read the posts CSV
    $all_posts = [];
    if (file_exists($posts_file)) {
        $file = fopen($posts_file, 'r');
        while (($line = fgetcsv($file, 0, ',')) !== false) {
            $all_posts[] = $line;
        }
        fclose($file);
    }

    // Filter out the post with the given ID
    $updated_posts = array_filter($all_posts, function ($post) use ($delete_post_id) {
        return $post[0] != $delete_post_id; // Remove post by ID
    });

    // Rewrite the CSV without the deleted post
    $file = fopen($posts_file, 'w');
    foreach ($updated_posts as $post) {
        fputcsv($file, $post);
    }
    fclose($file);

    // Delete the post folder and its content (e.g., content.md, images)
    $post_folder = "data/posts/$delete_post_id";
    if (is_dir($post_folder)) {
        array_map('unlink', glob("$post_folder/*.*"));
        rmdir($post_folder); // Remove the directory itself
    }

    // Redirect to refresh the page after deleting
    header("Location: dashboard.php");
    exit();
}

// Retrieve user's posts
if (file_exists($posts_file)) {
    $file = fopen($posts_file, 'r');
    while (($line = fgetcsv($file, 0, ',')) !== false) {
        // Ensure the line has at least 4 columns (post_id, user_handle, date, title)
        if (count($line) >= 4 && $line[1] == $_SESSION['user_handle']) {
            $user_posts[] = $line;
        }
    }
    fclose($file);
}

$posts = array_reverse($user_posts); // Sort by newest to oldest
$recent_posts = array_slice($posts, 0, 10); // Limit to 10 posts
?>

<?php include_once('header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href='form.php' class='btn btn-info btn-icon-split'>
            <span class='icon text-white-50'></span>
            <span class='text'>Create New Post</span>
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <?php
        if (!empty($recent_posts)) {
            foreach ($recent_posts as $post) {
                $post_id = $post[0];
                $user_handle = $post[1];
                $date = $post[2];
                $post_title = $post[3];

                // Load the blog content from the content.md file
                $content_file = "data/posts/$post_id/content.md";
                $post_excerpt = "No content available."; // Default if the file doesn't exist
                if (file_exists($content_file)) {
                    $content = file_get_contents($content_file);
                    // Extract the first 300 characters or sentences
                    $post_excerpt = substr(strip_tags($content), 0, 300);
                }

                // Check if the post folder contains an image
                $image_path = "data/posts/$post_id/image.jpg"; // or other image types like .png, etc.
                if (!file_exists($image_path)) {
                    // If no image is found, use a default "no image" placeholder
                    $image_path = "assets/no-photo.jpg";
                }

                echo "
                <div class='col-xl-3 col-md-6 mb-4'>
                    <div class='card border-left-warning shadow h-100 py-2'>
                        <div class='card-body'>
                            <div class='row no-gutters align-items-center'>
                                <div class='h5 font-weight-bold text-success text-uppercase mb-1'>$post_title</div>
                                <div class='small text-muted mb-1'>$date</div>
                                <p>$post_excerpt</p>
                            </div>
                            <div class='row no-gutters align-items-center'>
                                <a href='edit_post.php?id=$post_id' class='btn btn-info btn-icon-split'>
                                    <span class='icon text-white-50'></span>
                                    <span class='text'>Edit</span>
                                </a>
                                <form method='post' class='d-inline'>
                                    <input type='hidden' name='delete_post_id' value='$post_id'>
                                    <button type='submit' class='btn btn-danger btn-icon-split' onclick='return confirm(\"Are you sure you want to delete this post?\")'>
                                        <span class='icon text-white-50'></span>
                                        <span class='text'>Delete</span>
                                    </button>
                                </form>
                                <a href='detail.php?id=$post_id' class='btn btn-primary btn-icon-split'>
                                    <span class='icon text-white-50'></span>
                                    <span class='text'>View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No posts available for this user.</p>";
        }
        ?>
    </div>

</div>
<!-- /.container-fluid -->

<?php include_once('footer.php'); ?>
