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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Blog Post Detail</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="post.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Heading -->
                <div class="sidebar-heading">Manage</div>
                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="post.php">
                        <i class="fas fa-fw fa-table"></i>
                        <span>View Posts</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
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
