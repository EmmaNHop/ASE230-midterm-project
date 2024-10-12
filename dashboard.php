<?php
// Load the most recent posts from /data/posts.csv
$file_path = 'data/data.json';
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

                        <!-- Pending Requests Card Example -->
                        <?php
                            foreach ($recent_posts as $post) {
                                $post_id = $post[0];
                                $username = $post[1];
                                $date = $post[2];
                                $post_title = $post[3];
                                // Load the blog content from the content.md file
                                $content_file = "data/posts/$post_id/content.md";
                                $post_excerpt = "No content available."; // Default if the file doesn't exis
                                if (file_exists($content_file)) {
                                    $content = file_get_contents($content_file);
                                    // Extract the first 300 characters or 3 sentences
                                    $post_excerpt = substr(strip_tags($content), 0, 300); 
                                }
                                // Check if the post folder contains an image
                                $image_path = "data/images/$image"; // or other image types like .png, etc.
                                if (!file_exists($image_path)) {
                                    // If no image is found, use a default "no image" placeholder
                                    $image_path = "assets/no-photo.jpg";
                                
                                    echo "
                                    <div class='col-xl-3 col-md-6 mb-4'>
                                        <div class='card border-left-warning shadow h-100 py-2'>
                                             <div class='card-body'>
                                                <div class='row no-gutters align-items-center'>
                                                    <div class='h5 font-weight-bold text-success text-uppercase mb-1'>$post_title</div>
                                                </div>
                                                <div class = 'row no-gutters align-items-center'>    
                                                    <a href='#' class='btn btn-info btn-icon-split'>
                                                        <span class='icon text-white-50'></span>
                                                        <span class='text'>Edit</span>
                                                    </a>
                                                    <a href='#' class='btn btn-info btn-icon-split'>
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
                         

                <div>
            
                <br> 
                <br> 

                <!--External JavaScript file-->
                <script src="home.js"></script> 
                </div>
            </div>
            <!-- End of Main Content -->
<?php include_once('footer.php'); ?>