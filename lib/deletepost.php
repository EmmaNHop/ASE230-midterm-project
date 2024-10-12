<?php
session_start();

if (!isset($_SESSION['user_handle'])) {
    header("Location: login.php");
    exit();
}

$posts_file = 'data/posts.csv';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET['id'];
$post_found = false;

if (file_exists($posts_file)) {
    $file = fopen($posts_file, 'r');
    $updated_posts = [];
    while (($line = fgetcsv($file, 0, ';')) !== false) {
        if ($line[0] == $post_id && $line[1] == $_SESSION['user_handle']) {
            $post_found = true; 
            continue;
        }
        $updated_posts[] = $line;
    }
    fclose($file);
}

if (!$post_found) {
    header("Location: dashboard.php");
    exit();
}

$file = fopen($posts_file, 'w');
foreach ($updated_posts as $post) {
    fputcsv($file, $post, ';');
}
fclose($file);

header("Location: dashboard.php?message=Post deleted successfully!");
exit();
?>
