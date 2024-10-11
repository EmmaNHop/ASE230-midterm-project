<?php
// Load the most recent posts from /data/posts.csv
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
    }
}

// Reverse the posts array to get the most recent posts first
$posts = array_reverse($posts);
$recent_posts = array_slice($posts, 0, 10); // Get the 10 most recent posts

?>

<?php include_once('header.php'); ?>

                    <!-- Page content-->
                    <div class="container">
                        <!-- Blog entries-->
                        
                            <div class="row" id="post-container">
                                <?php
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
                                        <div class='col-lg-6'>
                                            <div class='card mb-4'>
                                                <a href='detail.php?id=$post_id'><img class='card-img-top' src='$image_path' alt='...' /></a>
                                                <div class='card-body'>
                                                    <div class='small text-muted'>$date</div>
                                                    <h2 class='card-title h4'>$post_title</h2>
                                                    <p class='post-excerpt'>$post_excerpt</p>
                                                    <a class='btn btn-primary' href='detail.php?id=$post_id'>Read more →</a>
                                                </div>
                                            </div>
                                        </div>";
                                    }
                                ?>
                            </div>
                            <!-- Pagination-->
                            <nav aria-label="Pagination">
                                <hr class="my-0" />
                                <ul class="pagination justify-content-center my-4">
                                    <li class="page-item"><a class="page-link" href="post.php?page=<?php echo isset($_GET['page']) ? ($_GET['page'] + 1) : 2; ?>" id="load-more">Load more</a></li>
                                </ul>
                            </nav>
                        
                    </div>
                </div>

<?php include_once('footer.php'); ?>
