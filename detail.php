<?php
// Check if the 'id' parameter is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to post.php if no ID is provided
    header("Location: post.php");
    exit();
}

// Load the post data from the CSV
$post_id = $_GET['id'];
$file_path = 'data/posts.csv';
$posts = [];
if (file_exists($file_path)) {
    if (($file = fopen($file_path, 'r')) !== false) {
        // Skip the header row
        fgetcsv($file);
        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            $posts[] = $data; // Store each post
        }
        fclose($file);
    } else {
        // Redirect if the file cannot be opened
        header("Location: post.php");
        exit();
    }
} else {
    // Redirect if the CSV file does not exist
    header("Location: post.php");
    exit();
}

// Find the post data by ID
$post_data = null;
foreach ($posts as $post) {
    // Ensure that the post ID matches correctly
    if (trim($post[0]) === $post_id) {
        $post_data = $post;
        break;
    }
}

// If post not found, redirect to post.php
if (!$post_data) {
    header("Location: post.php");
    exit();
}

// Extract post details
$user_handle = $post_data[1];
$date = $post_data[2];
$post_title = $post_data[3];

// Load the blog content from the content.md file
$content_file = "data/posts/$post_id/content.md";
if (file_exists($content_file)) {
    $post_content = file_get_contents($content_file);
} else {
    $post_content = "Content not available for this post.";
}

// Check if the post folder contains an image
$image_path = "data/posts/$post_id/image.jpg"; // or other image types like .png, etc.
if (!file_exists($image_path)) {
    // If no image is found, use a default "no image" placeholder
    $image_path = "assets/no-photo.jpg";
}
?>

<?php include_once('header.php'); ?>

                    <!-- Page content-->
                    <div class="container">
                        <div class="row">
                            <!-- Blog entry-->
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <img class="card-img-top" src="<?php echo $image_path; ?>" alt="Blog image">
                                    <div class="card-body">
                                        <div class="small text-muted"><?php echo $date; ?></div>
                                        <h2 class="card-title"><?php echo $post_title; ?></h2>
                                        <p><?php echo nl2br($post_content); ?></p>
                                    </div>
                                    <!-- Go back button -->
                                    <div class="card-body">
                                        <button class="btn btn-secondary" onclick="window.history.back();">Go Back</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php include_once('footer.php'); ?>