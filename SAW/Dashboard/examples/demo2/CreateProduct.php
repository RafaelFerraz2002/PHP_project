<!DOCTYPE html>
<?php
// Include config file
require_once "config.php";
require("session.php"); 
 
// Define variables and initialize with empty values
$titulo = $descricao = $preco = "";
$titulo_err = $descricao_err = $preco_err = "";
 
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
    $input_titulo = trim($_POST["titulo"]);
    if(empty($input_titulo)){
        $titulo_err = "Please enter a name.";
    } elseif(!filter_var($input_titulo, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $titulo_err = "Please enter a valid name.";
    } else{
        $titulo = $input_titulo;
    }
    
    // Validate address
    $input_descricao = trim($_POST["descricao"]);
    if(empty($input_descricao)){
        $descricao_err = "Please enter a email.";     
    } else{
        $descricao = $input_descricao;
    }
    
    // Validate salary
    $input_preco = trim($_POST["preco"]);
    if(empty($input_preco)){
        $preco_err = "Please enter age.";     
    } elseif(!ctype_digit($input_preco)){
        $preco_err = "Please enter a positive integer value.";
    } else{
        $preco = $input_preco;
    }
    
    // Check input errors before inserting in database
    if(empty($titulo_err) && empty($descricao_err) && empty($preco_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO products (titulo, descricao, preco, imageData, imageType) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_titulo, $param_descricao, $param_preco, $param_imageData, $param_imageType);
            
            // Set parameters
            $param_titulo = $titulo; 
            $param_descricao = $descricao;
            $param_preco = $preco;
            $param_imageData = $imageData;
            $param_imageType = $imageProperties['mime'];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header('location:ReadProducts.php');
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
<html lang="en">

<head>
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Pelourinho</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="../assets/img/icon.ico" type="image/x-icon" />

    <!-- Sweetalert 2 CSS -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2/sweetalert2.min.css">

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

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/atlantis.min.css">

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
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"
                                aria-expanded="false">
                                <div class="avatar-sm">
                                    <?php
                                        echo '<img class="avatar-img rounded-circle" alt="..." src="data:'.$_SESSION['imageType'].';base64,'.base64_encode($_SESSION['imageData']).'"/>';
                                    ?>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                <?php
                                                    echo '<img class="img" src="data:'.$_SESSION['imageType'].';base64,'.base64_encode($_SESSION['imageData']).'"/>';
                                                ?>
                                            </div>
                                            <div class="u-text">
                                                <h4><?php echo $_SESSION['nome']; ?></h4>
                                                <p class="text-muted"><?php echo $_SESSION['email']; ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <div class="user">
                        <div class="avatar-sm float-left mr-2">
                            <?php
                                echo '<img class="avatar-img rounded-circle" alt="..." src="data:'.$_SESSION['imageType'].';base64,'.base64_encode($_SESSION['imageData']).'"/>';
                            ?>
                        </div>
                        <div class="info">
                            <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                                <span>
                                    <?php echo $_SESSION['nome']; ?>
                                    <span class="user-level"><?php echo $_SESSION['roles']; ?></span>
                                </span>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <ul class="nav nav-primary">
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#Users" class="collapsed" aria-expanded="false">
                                <i class="fas fa-user"></i>
                                <p>Utilizadores</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="Users">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../demo2/CreateUser.php">
                                            <span class="sub-item">Criar</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../demo2/ReadUser.php">
                                            <span class="sub-item">Listar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#Products" class="collapsed" aria-expanded="false">
                                <i class="fas fa-home"></i>
                                <p>Produtos</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="Products">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../demo2/CreateProduct.php">
                                            <span class="sub-item">Criar</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../demo2/ReadProducts.php">
                                            <span class="sub-item">Listar</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="collapse" href="#submenu">
                                <i class="fas fa-bars"></i>
                                <p>Menu Levels</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="submenu">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a data-toggle="collapse" href="#subnav1">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav1">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a data-toggle="collapse" href="#subnav2">
                                            <span class="sub-item">Level 1</span>
                                            <span class="caret"></span>
                                        </a>
                                        <div class="collapse" id="subnav2">
                                            <ul class="nav nav-collapse subnav">
                                                <li>
                                                    <a href="#">
                                                        <span class="sub-item">Level 2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="sub-item">Level 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Produtos</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrapper2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="mt-5">Criar Products</h2>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                    enctype="multipart/form-data" method="post">
                                    <div class="form-group">
                                        <label>Titulo</label>
                                        <input type="text" name="titulo"
                                            class="form-control <?php echo (!empty($titulo_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $titulo; ?>">
                                        <span class="invalid-feedback"><?php echo $titulo_err;?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Descrição</label>
                                        <input name="descricao"
                                            class="form-control <?php echo (!empty($descricao_err)) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo $descricao; ?>">
                                        <span class="invalid-feedback"><?php echo $descricao_err;?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Preço</label>
                                        <input type="number" name="preco"
                                            class="form-control <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>"
                                            min="0" value="<?php echo $preco; ?>">
                                        <span class="invalid-feedback"><?php echo $preco_err;?></span>
                                    </div>
                                    <div class="input-group-addon">Image / Avatar</div>
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
                </div>
                </form>
                </form>
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

    <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#pass');

    togglePassword.addEventListener('click', function(e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
    </script>

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

    <!-- Sweet Alert
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script> -->

    <!-- Sweetalert2 JS -->
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>

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