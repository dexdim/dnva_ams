<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon" />
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        <!-- Navbar -->
        {{-- <nav class="main-header navbar navbar-expand bg-light navbar-light border-bottom">
            <!-- Left navbar links -->


            <!-- SEARCH FORM -->
            <div class="input-group">
                <input class="form-control" @keyup.enter="searchit" v-model="search" type="text" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" @click="searchit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

        </nav> --}}
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary">
            <!-- Brand Logo -->
            <div class="top-sidebar">
                <router-link to="/inventory" class="brand-link">
                    <img src="{{ asset('/images/logo-2.png') }}" alt="The Logo" class="brand-image">
                    <span class="brand-text">{{ config('app.name', 'Laravel') }}</span>
                </router-link>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <router-link to="/profile">
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="info">
                            {{ Auth::user()->name }}
                            <span class="d-block text-muted">
                                {{ Ucfirst(Auth::user()->type) }}
                            </span>
                        </div>
                    </div>
                </router-link>

                <!-- Sidebar Menu -->
                @include('layouts.sidebar-menu')
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        {{-- Content Wrapper. Contains page content --}}
        <div class="content-wrapper">
            {{-- Main content --}}

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    {{-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Starter Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row --> --}}
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <router-view></router-view>

            <vue-progress-bar></vue-progress-bar>

            {{-- /.content --}}
        </div>
        {{-- /.content-wrapper --}}

        {{-- Main Footer --}}
        <footer class="main-footer">
            {{-- To the right --}}
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 2.0.1
            </div>
            {{-- Default to the left --}}
            <strong>Copyright &copy; 2024 <a href="https://domainesia.com">Deneva</a>.</strong> All rights
            reserved.
        </footer>
    </div>
    {{-- ./wrapper --}}

    @auth
        <script>
            window.user = @json(auth()->user())
        </script>
    @endauth
    <script src="{{ mix('/js/app.js') }}"></script>
</body>


</html>
