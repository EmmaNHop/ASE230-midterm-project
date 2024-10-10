<!DOCTYPE html>
<html lang="en">

<?php
require_once('lib/functions.php');

// Variable to store error or success message
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are set
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repeat_password']) && isset($_POST['handle'])) {

        // Sanitize and validate the handle
        $handle = strtolower(trim($_POST['handle']));
        if (strlen($handle) > 16 || !preg_match('/^[a-z0-9_]+$/i', $handle)) {
            $message = '<p style="color:red;text-align:center;">The handle must be at most 16 characters long and can only contain letters, numbers, and underscores.</p>';
        } else {
            // Read the file and check if the email or handle already exists
            $email_exists = false;
            $handle_exists = false;

            if (file_exists('data/users.csv.php')) {
                $file = fopen('data/users.csv.php', 'r');
                while (($line = fgetcsv($file, 0, ';')) !== false) {
                    if (strtolower($line[0]) == strtolower($_POST['email'])) {
                        $email_exists = true;
                    }
                    if (strtolower($line[4]) == $handle) { // Check handle case-insensitively
                        $handle_exists = true;
                    }
                }
                fclose($file);
            }

            if ($email_exists) {
                $message = '<p style="color:red;text-align:center;">This email is already registered. Please use another email or <a href="login.php">login</a>.</p>';
            } elseif ($handle_exists) {
                $message = '<p style="color:red;text-align:center;">This handle is already taken. Please choose another handle.</p>';
            } else {
                // Check if passwords match
                if ($_POST['password'] === $_POST['repeat_password']) {
                    // Open file to save user data
                    $fp = fopen('data/users.csv.php', 'a+');
                    // Save user data (email, password, first name, last name, handle)
                    fputs($fp, $_POST['email'] . ';' . $_POST['password'] . ';' . $_POST['first_name'] . ';' . $_POST['last_name'] . ';' . $handle . PHP_EOL);
                    fclose($fp);

                    // Display success message
                    $message = '<p style="color:green;text-align:center;">Account successfully created! <a href="login.php">Click here to login</a></p>';
                } else {
                    // Passwords do not match
                    $message = '<p style="color:red;text-align:center;">Passwords do not match. Please try again.</p>';
                }
            }
        }
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST" action="">
                                <!-- Display success or error message here -->
                                <?php echo $message; ?>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="first_name" placeholder="First Name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="last_name" placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputHandle" name="handle" placeholder="@handle" maxlength="16" required>
                                    <small class="form-text text-muted text-center">Handle can contain letters, numbers, and underscores, and must be 16 characters or less.</small>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" name="repeat_password" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>