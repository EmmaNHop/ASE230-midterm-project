<?php
session_start();

if (!isset($_SESSION['user_handle'])) {
    header("Location: login.php");
    exit();
}

$posts_file = 'data/posts.csv';
$user_posts = [];

// Ensure the correct index is used to match the user handle
if (file_exists($posts_file)) {
    $file = fopen($posts_file, 'r');
    // Skip the header line in the CSV file
    fgetcsv($file);
    
    // Loop through each line in the CSV
    while (($line = fgetcsv($file, 1000, ',')) !== false) {
        // Ensure that the line has the expected columns and check the user handle match
        if (isset($line[1]) && trim($line[1]) === trim($_SESSION['user_handle'])) {
            $user_posts[] = $line;
        }
    }
    fclose($file);
}

// Sort posts by post ID in descending order (newest first)
usort($user_posts, function($a, $b) {
    return (int)$b[0] - (int)$a[0]; // Compare post IDs numerically in descending order
});

// Get all the user's posts
$recent_posts = $user_posts;

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
        <!-- Display each post for the logged-in user -->
        <?php
        if (empty($recent_posts)) {
            echo "<p>No posts available for this user.</p>";
        } else {
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
                    // Extract the first 300 characters or 3 sentences
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
                                <p class='small text-muted'>$date</p>
                                <p class='small'>$post_excerpt</p>
                            </div>
                            <div class='row no-gutters align-items-center'>
                                <a href='edit_post.php?id=$post_id' class='btn btn-info btn-icon-split'>
                                    <span class='icon text-white-50'></span>
                                    <span class='text'>Edit</span>
                                </a>
                                <a href='delete_post.php?id=$post_id' class='btn btn-danger btn-icon-split'>
                                    <span class='icon text-white-50'></span>
                                    <span class='text'>Delete</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        }
        ?>
    </div>
</div>
<!-- /.container-fluid -->

<!--External JavaScript file-->
<script src="home.js"></script>

<?php include_once('footer.php'); ?>
