<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AdminLTE 2 | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
     <?php view::tagCss('bootstrap')  ?>
    <!-- FontAwesome 4.3.0 -->    
    <?php view::tagCss('font/font-awesome-4.5.0/css/font-awesome.min')  ?>
    <!-- Ionicons 2.0.0 -->
    
    <?php view::tagCssPublic('jquery-ui.theme')  ?>
    
    
    
    <?php //view::tagCss('font/ionicons/ionicons.min')  ?>
    <!-- Theme style -->
    <?php view::tagCss('dist/css/AdminLTE.min')  ?>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <?php view::tagCss('dist/css/skins/_all-skins')  ?>
    <!-- iCheck -->
     <?php view::tagCss('plugins/iCheck/flat/blue')  ?>
    <!-- Morris chart -->
    <?php view::tagCss('plugins/morris/morris')  ?>
    <!-- jvectormap -->
    <?php view::tagCss('plugins/jvectormap/jquery-jvectormap-1.2.2')  ?>
    <!-- Date Picker -->
    <?php //view::tagCss('plugins/datepicker/datepicker3')  ?>
    <!-- Daterange picker -->
    <?php //view::tagCss('plugins/daterangepicker/daterangepicker-bs3')  ?>
    <!-- bootstrap wysihtml5 - text editor -->
    
    <?php // view::tagCss('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min')  ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php if(isset($_layoutParams['css']) && count($_layoutParams['css'])):?>
    <?php for($i = 0; $i < count($_layoutParams['css']);$i++): ?>
           <link href="<?php echo $_layoutParams['css'][$i] ?>" rel="stylesheet" type="text/css">
    <?php endfor ?>
    <?php endif ?>
                
            
  </head>
  <body class="skin-red sidebar-mini">
    <div class="wrapper">
        <?php view::partial('barra', $_layoutParams) ?>
      
      <!-- Left side column. contains the logo and sidebar -->
      <?php view::partial('menu', $_layoutParams) ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
      <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php if(isset($this->title))echo ucfirst($this->title) ?>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php if(isset($this->error)): ?>
                <li class="alert alert-danger alert-dismissible " role="alert">
					 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-exclamation"></i> <?php echo $this->error; ?>
                </li>
            <?php endif; ?>
			<?php if(isset($this->mensaje)): ?>
                 <li class="alert alert-success alert-dismissible " role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-comments"></i> <?php echo $this->mensaje; ?>
                </li>
            <?php endif; ?>
			<?php if(isset($this->info)): ?>
                 <li class="alert alert-warning alert-dismissible " role="alert">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-info"></i> <?php echo $this->info; ?>
                </li>
            <?php endif; ?>	