<?php 
include_once('header.php'); 

if (count($_POST) > 0) {
    // Path to the CSV file that stores post metadata
    $csv_file = 'data/posts.csv';
    
    // Initialize the post_id as 1 if the file is empty or doesn't exist
    $post_id = 1;

    // Check if the CSV file exists and is not empty
    if (file_exists($csv_file) && filesize($csv_file) > 0) {
        // Read the CSV file to find the highest existing post ID
        $file_handle = fopen($csv_file, 'r');
        $highest_id = 0;

        // Loop through the CSV to find the highest post ID
        while (($data = fgetcsv($file_handle)) !== false) {
            $current_id = (int)$data[0]; // Assuming the first column contains the post ID
            if ($current_id > $highest_id) {
                $highest_id = $current_id;
            }
        }
        fclose($file_handle);

        // Increment the highest ID found
        $post_id = $highest_id + 1;
    }

    // Set up the folder path for the new post (using the post ID)
    $post_folder = 'data/posts/' . $post_id;

    // Create the folder if it doesn't exist
    if (!file_exists($post_folder)) {
        mkdir($post_folder, 0777, true);
    }

    // Prepare the post data
    $user_name = $_POST['user_name'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s'); // Current date and time

    // Handle image upload (if any)
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $image_file = $_FILES['image']['name'];
        $target_file = $post_folder . '/image.jpg'; // You can change the extension based on the uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file; // Save the path to the image
        }
    }

    // Save the post content to a markdown file in the post folder
    file_put_contents("$post_folder/content.md", $content);

    // Update the posts.csv file with the new post entry
    $new_post = [$post_id, $user_name, $date, $title];
    $file_handle = fopen($csv_file, 'a');
    fputcsv($file_handle, $new_post);
    fclose($file_handle);

    // Use JavaScript for redirection to the detail page
    echo "<script>window.location.href='detail.php?id=$post_id';</script>";
    exit();
} 
?>

<!-- Page content for form -->
<div class="container">
    <div class="row">
        <!-- Blog entry-->
        <div class="col-lg-8">
            <div class="card mb-4 container">
                <div class="m-3">
                    <h2 class="card-title">Create New Post</h2>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                        <div>
                            <label>Username</label><br/>
                            <input type="text" name="user_name" required/>
                        </div>
                        <div>
                            <label>Title</label><br/>
                            <input type="text" name="title" required/>
                        </div>
                        <div>
                            <label>Image (optional)</label><br/>
                            <input type="file" name="image"/>
                        </div>
                        <div>
                            <label>Content</label><br/>
                            <textarea name="content" required></textarea>
                        </div>
                        <button type="submit">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('footer.php'); ?>
