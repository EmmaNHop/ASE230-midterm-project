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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Blog Home</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <style>
            /* Add fading effect to text overflow */
            .post-excerpt {
                max-height: 4.5em;
                overflow: hidden;
                text-overflow: ellipsis;
                position: relative;
                line-height: 1.5em;
                height: 4.5em;
            }
            .post-excerpt::after {
                content: '...';
                position: absolute;
                bottom: 0;
                right: 0;
                padding: 0 5px;
                background: white;
            }

            /* Ensure image retains aspect ratio of 700x350 */
            .card-img-top {
                width: 100%;
                height: auto;
                max-width: 700px;
                max-height: 350px;
                object-fit: cover; /* Ensures the image fills the space while maintaining aspect ratio */
            }
        </style>
    </head>
    <body>
        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="dashboard.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Manage
</div>

<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link" href="post.php">
        <i class="fas fa-fw fa-table"></i>
        <span>View Posts</span></a>
</li>

<!-- Nav Item - Tables -->
<li class="nav-item">
    <a class="nav-link" href="tables.php">
        <i class="fas fa-fw fa-table"></i>
        <span>Create Post</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Addons
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Pages</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Login Screens:</h6>
            <a class="collapse-item" href="login.php">Login</a>
            <a class="collapse-item" href="register.php">Register</a>
            <a class="collapse-item" href="forgot-password.php">Forgot Password</a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Other Pages:</h6>
            <a class="collapse-item" href="404.php">404 Page</a>
            <a class="collapse-item" href="blank.php">Blank Page</a>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">User Profile</span>
                                    <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Activity Log
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Page content-->
                    <div class="container">
                        <div class="row"></div>
                        <!-- Blog entries-->
                        <div class="col-lg-8">
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
                </div>

                <!-- Footer-->
                <footer class="py-5 bg-dark">
                    <div class="container">
                        <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
                    </div>
                </footer>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>
    </body>
</html>
