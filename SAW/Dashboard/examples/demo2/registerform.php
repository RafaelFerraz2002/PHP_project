<!DOCTYPE html>
<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $email = $idade = $roles = $pass = "";
$nome_err = $email_err = $idade_err = $role_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if (count($_FILES) > 0) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $imageData = file_get_contents($_FILES['file']['tmp_name']);
            $imageProperties = getimageSize($_FILES['file']['tmp_name']);
        }
        else {
            echo "nao fez upload";
        }
    }
    else {
        echo "problema de FILES";
    }

    // Validate name
    $input_name = trim($_POST["nome"]);
    if(empty($input_name)){
        $nome_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nome_err = "Please enter a valid name.";
    } else{
        $nome = $input_name;
    }
    
    // Validate address
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter a email.";     
    } else{
        $email = $input_email;
    }
    
    // Validate salary
    $input_idade = trim($_POST["idade"]);
    if(empty($input_idade)){
        $idade_err = "Please enter age.";     
    } elseif(!ctype_digit($input_idade)){
        $idade_err = "Please enter a positive integer value.";
    } else{
        $idade = $input_idade;
    }

    // Validate role
    $input_role = trim($_POST["role"]);
    if(empty($input_role)){
        $role_err = "Please enter role";     
    } else{
        $roles = $input_role;
    }

    // Validate pass
    $input_pass = trim($_POST["pass"]);
    if(empty($input_pass)){
        $email_err = "Please enter an pass";     
    } else{
        $pass = $input_pass;
    }
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($email_err) && empty($idade_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO user (nome, email, idade, roles, pass, imageData, imageType) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_name, $param_address, $param_salary, $param_roles, $param_pass, $param_imageData, $param_imageType);
            
            // Set parameters
            $param_name = $nome; 
            $param_address = $email;
            $param_salary = $idade;
            $param_roles = $roles;
            $param_pass = md5($pass);
            $param_imageData = $imageData;
            $param_imageType = $imageProperties['mime'];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header('location:ReadUser.php');
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Pelourinho</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="../assets/img/icon.ico" type="image/x-icon" />

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
    WebFont.load({
        google: {
            "families": ["Lato:300,400,700,900"]
        },
        custom: {
            "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                "simple-line-icons"
            ],
            urls: ['../assets/css/fonts.min.css']
        },
        active: function() {
            sessionStorage.fonts = true;
        }
    });
    </script>

    <style>
    .wrapper2 {
        width: 600px;
        margin: 0 auto;
    }

    .icon-rtl {
        padding-right: 25px;
        background: url("https://static.thenounproject.com/png/101791-200.png") no-repeat right;
        background-size: 20px;
    }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/atlantis.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>

<body data-background-color="dark">
    <div class="wrapper sidebar_minimize">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark2">

                <a href="index.html" class="logo">
                    <img src="../assets/img/logo.svg" alt="navbar brand" class="navbar-brand">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="dark">
                <div class="container-fluid">
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Registo</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="login">
                    <div class="container">
                        <div id="login-row" class="row justify-content-center align-items-center">
                            <div id="login-column" class="col-md-6">
                                <div id="login-box" class="col-md-12">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                        enctype="multipart/form-data" method="post">
                                        <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome"
                                                class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>"
                                                value="<?php echo $nome; ?>">
                                            <span class="invalid-feedback"><?php echo $nome_err;?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input name="email"
                                                class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                                                value="<?php echo $email; ?>">
                                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Idade</label>
                                            <input type="number" name="idade"
                                                class="form-control <?php echo (!empty($idade_err)) ? 'is-invalid' : ''; ?>"
                                                min="18" max="100" value="<?php echo $idade; ?>">
                                            <span class="invalid-feedback"><?php echo $idade_err;?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" id="pass" name="pass"
                                                class="form-control <?php echo (!empty($pass_err)) ? 'is-invalid' : ''; ?>"
                                                Required>
                                            <i class="far fa-eye"
                                                style="float:right; margin-top:-25px; margin-right:10px; cursor: pointer;"
                                                id="togglePassword"></i>
                                            <span class="invalid-feedback"><?php echo $pass_err;?></span>
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role" id="select"
                                                class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>"
                                                Required>
                                                <option value="">Choose the User Role</option>
                                                <option value="Public">Public</option>
                                            </select>
                                            <span class="invalid-feedback"><?php echo $role_err;?></span>
                                        </div>
                                        <div class="input-group-addon">Image / Avatar</div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="file" id="file-multiple-input" name="file" multiple=""
                                        class="form-control-file">
                                </div>
                                <div class="input-group-addon">
                                    <i class="fa fa-images"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.html" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <nav class="pull-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.themekita.com">
                            ThemeKita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Help
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Licenses
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright ml-auto">
                2018, made with <i class="fa fa-heart heart text-danger"></i> by <a
                    href="https://www.themekita.com">ThemeKita</a>
            </div>
        </div>
    </footer>
    </div>
    </div>
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="../assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="../assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert -->
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="../assets/js/atlantis.min.js"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <!-- <script src="../assets/js/setting-demo.js"></script>
	<script src="../assets/js/demo.js"></script> -->
    <script>
    $('#lineChart').sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#177dff',
        fillColor: 'rgba(23, 125, 255, 0.14)'
    });

    $('#lineChart2').sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#f3545d',
        fillColor: 'rgba(243, 84, 93, .14)'
    });

    $('#lineChart3').sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: '#ffa534',
        fillColor: 'rgba(255, 165, 52, .14)'
    });
    </script>
</body>

</html>