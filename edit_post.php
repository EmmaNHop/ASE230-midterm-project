<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_handle'])) {
    header("Location: login.php");
    exit();
}

// Check if the 'id' parameter is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to dashboard if no ID is provided
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET['id'];
$csv_file = 'data/posts.csv';
$post_data = null;

// Load post data from CSV
if (file_exists($csv_file)) {
    $file = fopen($csv_file, 'r');
    while (($line = fgetcsv($file, 0, ',')) !== false) {
        if ($line[0] == $post_id && $line[1] == $_SESSION['user_handle']) {
            $post_data = $line;
            break;
        }
    }
    fclose($file);
}

if (!$post_data) {
    header("Location: dashboard.php");
    exit();
}

// Load the blog content from the content.md file
$content_file = "data/posts/$post_id/content.md";
if (file_exists($content_file)) {
    $post_content = file_get_contents($content_file);
} else {
    $post_content = "";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update post title in CSV
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];

    // Update CSV file
    $rows = array();
    if (($file = fopen($csv_file, 'r')) !== false) {
        while (($row = fgetcsv($file, 0, ',')) !== false) {
            if ($row[0] == $post_id) {
                $row[3] = $new_title; // Update the title
            }
            $rows[] = $row;
        }
        fclose($file);
    }

    // Write the updated rows back to the CSV
    $file = fopen($csv_file, 'w');
    foreach ($rows as $row) {
        fputcsv($file, $row);
    }
    fclose($file);

    // Update content.md file
    file_put_contents($content_file, $new_content);

    // Redirect back to the dashboard after editing
    header("Location: dashboard.php");
    exit();
}

?>

<?php include_once('header.php'); ?>

<div class="container">
    <h2>Edit Post: <?php echo $post_data[3]; ?></h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Post Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $post_data[3]; ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Post Content</label>
            <textarea class="form-control" name="content" rows="10" required><?php echo $post_content; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
</div>

<?php include_once('footer.php'); ?>
