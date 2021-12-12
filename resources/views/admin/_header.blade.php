<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Matrimony Admin</title>
  <noscript>
            <div style="position: fixed; top: 0px; left: 0px; z-index: 3000; 
                        height: 100%; width: 100%; background-color: #FFFFFF">
                <h2 style="top:40%; left:35%; color:red; position: absolute; font-family:algerian;">JavaScript is not enabled.</h2>
            </div>
        </noscript>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="{{ url('assets/images/RKSP_favicon.jpg') }}">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ url('assets/admin/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('assets/admin/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ url('assets/admin/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('assets/admin/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ url('assets/admin/css/_all-skins.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/admin/css/jquery-confirm.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/admin/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ url('assets/admin/css/animate.css') }}">
  {{-- <link rel="stylesheet" href="{{ url('assets/admin/plugin/datatable/css/dataTables.bootstrap.min.css') }}"> --}}

  <link href="{{ url('assets/admin/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]> 
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-custom fixed sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">

    <header class="main-header blue-gradient">
      <!-- Logo -->
      <a href="{{ url('') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>RKSP</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Matrimony</b>{{--<img src="{{ url('assets/admin/images/logo.png') }}" class=""  alt="Logo">--}}</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->



            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ url('assets/admin/images/profile/users/default.jpg') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">  {{ ucfirst(Auth::user()->name) }}</span>
            </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="{{ url('assets/admin/images/profile/users/default.jpg') }}" class="img-circle" alt="User Image">

                  <p>
                    {{ ucfirst(Auth::user()->name) }}

                  </p>
                </li>

                <li class="user-footer">
                  <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
                  <div class="pull-left">
                    <a href="{{ url('Admin_dashboard/profile') }}" class="btn btn-default">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ route('logout') }}" class="btn btn-danger" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </li>
              </ul>
            </li>


          </ul>
        </div>
      </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{ url('assets/admin/images/profile/users/default.jpg') }}" class="user-image img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ ucfirst(Auth::user()->name) }}</p>
            <a href="#"><i class="fa fa-circle text-online"></i> Online</a>
          </div>

        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">MAIN NAVIGATION</li>
          <li class="treeview {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
           </a>

          </li>
          <li class="treeview {{ Request::is('admin/users') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">
            <i class="ion ion-ios-people"></i> <span>Users</span>
           </a>

          </li>
          <li class="treeview {{ Request::is('admin/users_requests') ? 'active' : '' }}">
            <a href="{{ route('users_requests') }}">
            <i class="fa fa-exchange"></i> <span>Users Requests</span>
           </a>

          </li>
          <li class="treeview {{ Request::is('admin/news') ? 'active' : '' }}">
            <a href="{{ route('admin.news') }}">
            <i class="fa fa-newspaper-o"></i> <span>News</span>
           </a>

          </li>

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->