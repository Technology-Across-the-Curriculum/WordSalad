<?php
/**
 * Created by Nathan Healea.
 * Project: WordSalad
 * File: dashboard/index.php 
 * Date: 2/4/16 
 * Time: 2:32 PM
 */
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo APP_NAME; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo URL; ?>libs/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo URL; ?>libs/AdminLTE/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="<?php echo URL; ?>libs/AdminLTE/dist/css/skins/skin-green-light.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

    <!-- Main Header !-->
    <?php require TEMP . 'main-header.php' ?>

    <!-- Main Sidebar
    <?php require TEMP . 'main-sidebar.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Page Header !-->
        <?php require TEMP . 'page-header.php' ?>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Main Footer !-->
    <?php require TEMP . 'main-footer.php' ?>

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo URL; ?>libs/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo URL; ?>libs/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo URL; ?>libs/AdminLTE/dist/js/app.min.js"></script>
</body>
</html>
