<?php
include('../Config/Database.php');

?>

<!DOCTYPE html>

<html lang="en" class="theme-<?=$CaiDatChung['theme'];?>">
<!-- BEGIN: Head -->

<head>
    <meta charset="utf-8">
    <link href="/assets/media/logos/favicon.ico" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ADMIN CPANEL - THANHVU.NET V4">
    <meta name="keywords" content="ADMIN CPANEL - THANHVU.NET V4">
    <meta name="author" content="ADMIN CPANEL - THANHVU.NET V4">

    <title> ADMIN CPANEL - THANHVU.NET V4 </title>

    <!-- BEGIN: CSS Assets-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>  
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./dist/css/app.css" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->

<body class="py-5">
    <!-- BEGIN: Mobile Menu -->
    <div class="mobile-menu md:hidden">
        <div class="mobile-menu-bar">
            <a href="./" class="flex mr-auto">
                <img alt="Admin Logo" class="w-6" src="/assets/media/logos/favicon.ico">
            </a>
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
        </div>
        <div class="scrollable">
            <a href="javascript:;" class="mobile-menu-toggler">
                <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i>
            </a>
            <ul class="scrollable__content py-2">
                <li>
                    <a href="./" class="menu menu--active">
                        <div class="menu__icon">
                            <i data-lucide="home"></i>
                        </div>
                        <div class="menu__title">
                            Trang Chủ
                        </div>
                    </a>
                </li>
                
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="box"></i>
                        </div>
                        <div class="menu__title">
                            Quản Lý Sản Phẩm
                            <i data-lucide="chevron-down" class="menu__sub-icon"></i>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="/them-san-pham.php" class="menu menu--active">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Thêm Sản Phẩm
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./danh-sach-san-pham.php" class="menu menu--active">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Danh Sách Sản Phẩm
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./duyet-don-hang.php" class="menu menu--active">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Đơn Chờ Xử Lí
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="./DNS.php" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="inbox"></i>
                        </div>
                        <div class="menu__title">
                            Cập Nhật DNS 
                        </div>
                    </a>
                </li>
                
                <li>
                    <a href="./don-nap-vi.php" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="credit-card"></i>
                        </div>
                        <div class="menu__title">
                            Đơn Nạp Ví 
                        </div>
                    </a>
                </li>
                
                <li class="menu__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="edit"></i>
                        </div>
                        <div class="menu__title">
                            Cài Đặt Chung
                            <i data-lucide="chevron-down" class="menu__sub-icon"></i>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="./cai-dat-web.php" class="menu">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Cài Đặt Website
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./setting-gach-card.php" class="menu">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Cài Đặt Gạch Thẻ
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="javascript:;" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="users"></i>
                        </div>
                        <div class="menu__title">
                            Quản Lí Thành Viên
                            <i data-lucide="chevron-down" class="menu__sub-icon"></i>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="./quan-ly-thanh-vien.php" class="menu">
                                <div class="menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="menu__title">
                                    Quản Lí Thành Viên
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="./Gach-Cards.php" class="menu">
                        <div class="menu__icon">
                            <i data-lucide="trello"></i>
                        </div>
                        <div class="menu__title">
                            Đơn Gạch Thẻ
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex mt-[4.7rem] md:mt-0">
        <nav class="side-nav">
            <a href="./" class="intro-x flex items-center pl-5 pt-4">
                <img alt="Midone - HTML Admin Template" class="w-6" src="../images/logo.jpg">
                <span class="hidden xl:block text-white text-lg ml-3">
                    THANHVU.NET V4
                </span>
            </a>
            <div class="side-nav__devider my-6"></div>
            <ul>
                <li>
                    <a href="./" class="side-menu side-menu--active">
                        <div class="side-menu__icon">
                            <i data-lucide="home"></i>
                        </div>
                        <div class="side-menu__title">
                            Trang Chủ
                        </div>
                    </a>
                </li>
                
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="box"></i>
                        </div>
                        <div class="side-menu__title">
                            Quản Lý Sản Phẩm
                            <div class="side-menu__sub-icon">
                                <i data-lucide="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="./them-san-pham.php" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="side-menu__title">
                                    Thêm Sản Phẩm
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./danh-sach-san-pham.php" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="side-menu__title">
                                    Danh Sách Sản Phẩm
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./duyet-don-hang.php" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="side-menu__title">
                                    Đơn Chờ Xử Lí
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="./DNS.php" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="shopping-bag"></i>
                        </div>
                        <div class="side-menu__title">
                            Cập Nhật DNS 
                        </div>
                    </a>
                </li>
                
                <li>
                    <a href="./don-nap-vi.php" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="credit-card"></i>
                        </div>
                        <div class="side-menu__title">
                            Đơn Nạp Ví
                        </div>
                    </a>
                </li>
                
                <li class="side-nav__devider my-6"></li>
                <li>
                    <a href="javascript:;" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="edit"></i>
                        </div>
                        <div class="side-menu__title">
                            Cài Đặt Chung
                            <div class="side-menu__sub-icon">
                                <i data-lucide="chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="">
                        <li>
                            <a href="./cai-dat-web.php" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="side-menu__title">
                                    Cài Đặt Website
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./setting-gach-card.php" class="side-menu">
                                <div class="side-menu__icon">
                                    <i data-lucide="activity"></i>
                                </div>
                                <div class="side-menu__title">
                                    Cài Đặt Gạch Thẻ
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a href="./quan-ly-thanh-vien.php" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="users"></i>
                        </div>
                        <div class="side-menu__title">
                            Quản Lí Thành Viên
                        </div>
                    </a>
                </li>
                
                <li>
                    <a href="./Gach-Cards.php" class="side-menu">
                        <div class="side-menu__icon">
                            <i data-lucide="trello"></i>
                        </div>
                        <div class="side-menu__title">
                            Đơn Gạch Thẻ
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="content">
            <div class="top-bar">
                <!-- BEGIN: Breadcrumb -->
                <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"> Admin </a></li>
                        <li class="breadcrumb-item active" aria-current="page"> THANHVU.NET V4 </li>
                    </ol>
                </nav>
                <!-- END: Breadcrumb -->
                
                <!-- BEGIN: Search -->
                <div class="intro-x relative mr-3 sm:mr-6">
                    <div class="search hidden sm:block">
                        <input type="text" class="search__input form-control border-transparent" placeholder="Search...">
                        <i data-lucide="search" class="search__icon dark:text-slate-500"></i>
                    </div>
                    <a class="notification sm:hidden" href="#">
                        <i data-lucide="search" class="notification__icon dark:text-slate-500"></i>
                    </a>
                </div>
                
                <!-- BEGIN: Avatar Dropdown -->
                <div class="intro-x dropdown w-8 h-8">
                    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button" aria-expanded="false" data-tw-toggle="dropdown">
                        <img alt="Midone - HTML Admin Template" src="../images/thanhvu.jpg">
                    </div>
                    <div class="dropdown-menu w-56">
                        <ul class="dropdown-content bg-primary text-white">
                            <li class="p-2">
                                <div class="font-medium"> Đàm Thanh Vũ </div>
                                <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500"> Developer & Desinger </div>
                            </li>
                            <li>
                                <a href="/" target="_blank" class="dropdown-item hover:bg-white/5">
                                    <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Về Trang Web
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Avatar Dropdown -->
            </div>
