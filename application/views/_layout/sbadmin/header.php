<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo APP_NAME ?></title>
    <?php view::tagCss('bootstrap')  ?>
    <?php view::tagCss('sb-admin')  ?>
    <?php view::tagCss('plugins/morris')  ?>
    <?php view::tagCss('font-awesome/css/font-awesome.min')  ?>
    <?php if(isset($_layoutParams['css']) && count($_layoutParams['css'])):?>
    <?php for($i = 0; $i < count($_layoutParams['css']);$i++): ?>
           <link href="<?php echo $_layoutParams['css'][$i] ?>" rel="stylesheet" type="text/css">
    <?php endfor ?>
    <?php endif ?>
    
        
    <?php view::tagJs('jquery') ?>
    <?php view::tagJs('bootstrap.min') ?>
    <?php view::tagJs('jquery',$_layoutParams['template']) ?>
    <?php if(isset($_layoutParams['js']) && count($_layoutParams['js'])):?>
    <?php for($i = 0; $i < count($_layoutParams['js']);$i++): ?>
            <script src="<?php echo $_layoutParams['js'][$i] ?>" type="text/javascript"></script>
    <?php endfor ?>
    <?php endif ?>       
           
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><?php echo APP_NAME ?></a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <?php if(isset($_mensajes) && count($_mensajes)):  ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>      
                    <ul class="dropdown-menu message-dropdown">
                        <li class="message-preview">
                            <a href="#">
                                <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object" src="http://placehold.it/50x50" alt="">
                                    </span>
                                    <div class="media-body">
                                        <h5 class="media-heading"><strong>John Smith</strong>
                                        </h5>
                                        <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur...</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                       
                        <li class="message-footer">
                            <a href="#">Read All New Messages</a>
                        </li>
                    
                    </ul>
                   
                </li>
                 <?php endif; ?>
               <?php if(isset($_alerta) && count($_alerta)):  ?> 
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li>
                 <?php endif; ?>
                <?php if(session::has('id_usuario') && session::has('autenticado')): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucfirst(session::get('alias')) ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        
                        <li>
                            <a href="<?php echo BASE_URL ?>index/salir/"><i class="fa fa-sign-out"></i> Salir</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
        </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
        <?php if(isset($lista_menu)) : ?>
	<?php   for($i = 0; $i < count($lista_menu);$i++):  ?>
        <?php       $submenu = array();
                    if($_layoutParams['item'] && $lista_menu[$i]['id'] == $_layoutParams['item'] )
                    { 
                    	$_item_style = 'active'; 
                    } else {
                        $_item_style = '';
                    }
                    
                    $submenu = $menu->getSubmenu($lista_menu[$i]['id']);
                                     
	?>
        <?php if(count($submenu)):  ?>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo<?php echo $i ?>"><i class="<?php echo $lista_menu[$i]['imagen']  ?>"></i> <?php echo $lista_menu[$i]['titulo']  ?><i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo<?php echo $i ?>" class="collapse">
            <?php   for($j = 0; $j < count($submenu);$j++):  ?>
                    <li>
                        <a href="<?php echo $submenu[$j]['enlace']  ?>"><i class="<?php echo $submenu[$j]['imagen']  ?>"></i> <?php echo $submenu[$j]['titulo']  ?></a>
                    </li>
            <?php   endfor; ?>        
                </ul>
            </li>
        <?php else: ?>
            <li>
            <a class="<?php echo $_item_style; ?>" href=""><i class="<?php echo $lista_menu[$i]['imagen']  ?>"></i> <?php echo $lista_menu[$i]['titulo']  ?></a>
            </li>
        <?php endif; ?>
        <?php endfor; ?>
	<?php else : ?>    
            <li class="active">
                <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="charts.html"><i class="fa fa-fw fa-bar-chart-o"></i> Charts</a>
            </li>
            <li>
                <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
            </li>
            <li>
                <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
            </li>
            <li>
                <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
            </li>
            <li>
                <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
            </li>
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="#">Dropdown Item</a>
                    </li>
                    <li>
                        <a href="#">Dropdown Item</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
            </li>
            <li>
                <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
            </li>
        <?php endif ?>    
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
 <!-- Page Content -->
<div class="container-fluid">
    <div id="page-wrapper">

        <div id="page-wrapper">
            <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <?php if(isset($this->titulo))echo ucfirst ($this->titulo) ?> 
                </h1>
                <?php if(isset($this->error)): ?>
                <li class="alert alert-danger alert-dismissible" role="alert">
                    <i class="fa fa-exclamation"></i> <?php echo $this->error; ?>
                </li>
                <?php endif; ?>        
                
            </div>
        </div>
        <!-- /.row -->
</div>    
        </div>