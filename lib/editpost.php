<?php
session_start();

if (!isset($_SESSION['user_handle'])) {
    header("Location: login.php");
    exit();
}

$message = '';

$posts_file = 'data/posts.csv.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET['id'];
$current_post = null;

if (file_exists($posts_file)) {
    $file = fopen($posts_file, 'r');
    while (($line = fgetcsv($file, 0, ';')) !== false) {
        if ($line[0] == $post_id && $line[5] == $_SESSION['user_handle']) {
            $current_post = $line;
            break;
        }
    }
    fclose($file);
}

if (!$current_post) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['title'])) {
        $new_title = trim($_POST['title']);
        
        // Update the post in the CSV file
        $updated_posts = [];
        if (file_exists($posts_file)) {
            $file = fopen($posts_file, 'r');
            while (($line = fgetcsv($file, 0, ';')) !== false) {
                if ($line[0] == $post_id && $line[5] == $_SESSION['user_handle']) {
                    $line[3] = $new_title;
                }
                $updated_posts[] = $line;
            }
            fclose($file);
        }

        $file = fopen($posts_file, 'w');
        foreach ($updated_posts as $post) {
            fputcsv($file, $post, ';');
        }
        fclose($file);

        $message = '<p style="color:green;text-align:center;">Post updated successfully!</p>';
    } else {
        $message = '<p style="color:red;text-align:center;">The title field is required.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <!-- Include any styles you need here -->
</head>
<body>
    <h1>Edit Post</h1>
    
    <!-- Display success or error message -->
    <?php echo $message; ?>

    <!-- Post Edit Form -->
    <form method="POST" action="">
        <label for="title">Post Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($current_post[3]); ?>" required><br><br>
        
        <button type="submit">Update Post</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

