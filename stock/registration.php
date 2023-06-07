<?php
session_start();

require_once 'php_action/db_connect.php';

$errors = array();
$successMessage = "";

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $permissionId = $_POST['permission_id'];

    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required";
    } else if ($password != $confirmPassword) {
        $errors[] = "Passwords do not match";
    } else {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
            $errors[] = "Username already exists";
        } else {
            $password = md5($password);

            $insertSql = "INSERT INTO users (username, password, permission_id) VALUES ('$username', '$password', '$permissionId')";

            if ($connect->query($insertSql) === true) {
                $userId = $connect->insert_id;

                $successMessage = "Account created successfully! Go back to Login.";
            } else {
                $errors[] = "Error: " . $insertSql . "<br>" . $connect->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MIMS PHARMACY - Registration</title>
    
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="custom/css/custom.css">
    
    <script src="assests/jquery/jquery.min.js"></script>
    
    <script src="assests/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        function showSuccessMessage() {
            var successMessage = "<?php echo $successMessage; ?>";
            if (successMessage) {
                var successAlert = '<div class="alert alert-success" role="alert">' + successMessage + '</div>';
                document.getElementById("successMessage").innerHTML = successAlert;
            }
        }
    </script>
</head>

<body onload="showSuccessMessage()">
    <div class="container">
        <div class="row vertical">
            <div class="col-md-5 col-md-offset-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create Account</h3>
                    </div>
                    <div class="panel-body">
                        <div class="messages">
                            <?php if ($errors) {
                                foreach ($errors as $key => $value) {
                                    echo '<div class="alert alert-warning" role="alert">
                                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                                    ' . $value . '</div>';
                                }
                            } ?>
                            <div id="successMessage"></div>
                        </div>
                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="registrationForm">
                            <fieldset>
                                <div class="form-group">
                                    <label for="username" class="col-sm-3 control-label">Username</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password" class="col-sm-3 control-label">Confirm Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="permission_id" class="col-sm-3 control-label">Account Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="permission_id" name="permission_id" <?php if (isset($_SESSION['userId']) || isset($_SESSION['userType']) && $_SESSION['userType'] == 'user') {
                                                                                                                        echo 'disabled';
                                                                                                                    } ?>>
                                            <option value="1" selected>User</option>
                                            <option value="2">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-user"></i> Create Account</button>
                                        <a href="index.php" class="btn btn-default"><i class="glyphicon glyphicon-log-in"></i> Back to Login</a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
