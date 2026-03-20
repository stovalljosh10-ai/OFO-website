<?php
function custom_shortcode_header()
{
    ob_start();
?>
    <style>
        /* Header background default for all pages */
        #customHeader .header {
            background: black;
        }

        /* Homepage transparent header */
        #customHeader.home-header .header {
            background: transparent !important;
        }



        /* Scoped styles for the header only */
        #customHeader * {
            box-sizing: border-box;
        }

        #customHeader * {
            margin: 0;
            padding: 0;
        }

        #customHeader body {
            /* unlikely to match but kept blank to avoid global body override */
        }

        #customHeader .top-banner {
            background: #e60000;
            color: #fff;
            text-align: center;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: 600;
        }

        #customHeader.home-header .header {
            background: transparent !important;
        }

        #customHeader .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        #customHeader .logo {
            flex-shrink: 0;
        }

        #customHeader .logo img {
            height: 70px;
            width: auto;
            display: block;
        }

        #customHeader .nav-menu {
            display: flex;
            list-style: none;
            gap: 0;
            flex: 1;
            justify-content: center;
            align-items: center;
        }

        #customHeader .nav-menu>li {
            position: relative;
        }

        #customHeader .nav-menu>li>a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            padding: 20px 25px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s, color 0.3s;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        #customHeader .nav-menu>li>a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ff6b35;
        }

        #customHeader .submenu {
            position: absolute;
            top: 100%;
            left: 0;
            background: transparent;
            min-width: 220px;
            list-style: none;
            padding: 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000 !important;
        }

        #customHeader .nav-menu>li:hover .submenu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #customHeader .submenu li a {
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            display: block;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        #customHeader .submenu li a:hover {
            background: rgba(255, 107, 53, 0.5);
            backdrop-filter: blur(10px);
            color: #fff;
            border-left-color: #ff6b35;
            padding-left: 30px;
        }

        /* ========== SEARCH CONTAINER ========== */
        #customHeader .search-container {
            position: relative;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 46px;
        }

        #customHeader .search-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1450;
        }

        #customHeader .search-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ff6b35;
        }

        #customHeader .search-toggle.hidden {
            display: none;
        }

        /* ========== SEARCH DROPDOWN (DESKTOP) ========== */
        #customHeader .search-dropdown-wrapper {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            display: none;
        }

        #customHeader .search-dropdown-wrapper.active {
            display: block;
        }

        #customHeader .search-input-box {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 12px;
            padding: 12px 18px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
            width: 420px;
            border: 2px solid #ff6b35;
        }

        #customHeader .search-dropdown-wrapper.has-results .search-input-box {
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid #eee;
        }

        #customHeader .search-input-box input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            padding: 8px 10px;
            color: #333;
            background: transparent;
        }

        #customHeader .search-input-box input::placeholder {
            color: #999;
        }

        #customHeader .search-input-box .search-submit-btn {
            background: #ff6b35;
            border: none;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        #customHeader .search-input-box .search-submit-btn:hover {
            background: #e55a2b;
        }

        #customHeader .search-input-box .search-close-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #999;
            cursor: pointer;
            margin-left: 8px;
            padding: 5px;
            line-height: 1;
        }

        #customHeader .search-input-box .search-close-btn:hover {
            color: #333;
        }

        /* ========== SEARCH RESULTS DROPDOWN ========== */
        #customHeader .search-results-dropdown {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
            max-height: 450px;
            overflow-y: auto;
            display: none;
            width: 420px;
            border: 2px solid #ff6b35;
            border-top: none;
        }

        #customHeader .search-results-dropdown.active {
            display: block;
        }

        /* Custom scrollbar */
        #customHeader .search-results-dropdown::-webkit-scrollbar {
            width: 6px;
        }

        #customHeader .search-results-dropdown::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 0 0 12px 0;
        }

        #customHeader .search-results-dropdown::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        #customHeader .search-results-dropdown::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        #customHeader .search-results-dropdown .results-header {
            padding: 10px 18px;
            background: #f8f8f8;
            border-bottom: 1px solid #eee;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #customHeader .search-results-dropdown .search-result-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }

        #customHeader .search-results-dropdown .search-result-item:hover {
            background: linear-gradient(90deg, #fff5f2 0%, #fff 100%);
            padding-left: 22px;
        }

        #customHeader .search-results-dropdown .search-result-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            border: 1px solid #eee;
            background: #f9f9f9;
        }

        #customHeader .search-results-dropdown .search-result-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        #customHeader .search-results-dropdown .search-result-title {
            font-weight: 600;
            color: #222;
            font-size: 14px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        #customHeader .search-results-dropdown .search-result-price {
            color: #e60000;
            font-weight: 700;
            font-size: 15px;
        }

        #customHeader .search-results-dropdown .search-result-price del {
            color: #999;
            font-weight: 400;
            font-size: 13px;
            margin-right: 6px;
        }

        #customHeader .search-results-dropdown .search-no-results,
        #customHeader .search-results-dropdown .search-loading {
            padding: 35px 20px;
            text-align: center;
            color: #888;
            font-size: 14px;
        }

        #customHeader .search-results-dropdown .search-loading {
            color: #ff6b35;
        }

        #customHeader .search-results-dropdown .search-view-all {
            display: block;
            text-align: center;
            padding: 14px 18px;
            background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border-radius: 0 0 10px 10px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #customHeader .search-results-dropdown .search-view-all:hover {
            background: linear-gradient(135deg, #e55a2b 0%, #d44a1b 100%);
        }

        /* ========== USER ACTIONS ========== */
        #customHeader .user-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        #customHeader .user-action {
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            transition: all 0.3s;
            position: relative;
            padding: 5px;
            border-radius: 8px;
            white-space: nowrap;
        }

        #customHeader .user-action:hover {
            color: #ff6b35;
            background: rgba(255, 255, 255, 0.1);
        }

        #customHeader .user-action-icon {
            font-size: 20px;
        }

        #customHeader .badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #e60000;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid transparent;
        }

        /* ========== DROPDOWN ARROW ========== */
        #customHeader .dropdown-arrow {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-left: 8px;
            vertical-align: middle;
            transform: rotate(45deg);
            border-right: 2px solid rgba(255, 255, 255, 0.95);
            border-bottom: 2px solid rgba(255, 255, 255, 0.95);
            transition: transform 0.25s ease;
        }

        #customHeader .nav-menu>li.submenu-open>a .dropdown-arrow {
            transform: rotate(-135deg);
        }

        @media (min-width: 769px) {
            #customHeader .nav-menu>li:hover>a .dropdown-arrow {
                transform: rotate(-135deg);
            }
        }

        #customHeader .nav-menu>li>a:hover .dropdown-arrow {
            border-color: #ff6b35;
        }

        /* ========== MENU TOGGLE ========== */
        #customHeader .menu-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid #fff;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s;
            z-index: 2000;
        }

        #customHeader .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* ========== MOBILE SEARCH MODAL ========== */
        #customHeader .search-modal {
            position: fixed;
            inset: 0;
            display: none;
            align-items: flex-start;
            justify-content: center;
            z-index: 9999;
            padding-top: 80px;
        }

        #customHeader .search-modal.active {
            display: flex;
        }

        #customHeader .search-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
        }

        #customHeader .search-modal-inner {
            position: relative;
            width: calc(100% - 30px);
            max-width: 500px;
            background: #fff;
            border-radius: 12px;
            padding: 0;
            z-index: 10;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4);
            max-height: 70vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #customHeader .search-modal .modal-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border-bottom: 1px solid #eee;
            background: #fff;
        }

        #customHeader .search-modal .modal-header input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
        }

        #customHeader .search-modal .modal-header input[type="text"]:focus {
            border-color: #ff6b35;
        }

        #customHeader .search-modal .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 5px;
            line-height: 1;
        }

        #customHeader .search-modal .modal-search-btn {
            background: #ff6b35;
            border: none;
            color: #fff;
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        #customHeader .search-results-modal {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        #customHeader .search-results-modal .search-result-item-modal {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }

        #customHeader .search-results-modal .search-result-item-modal:hover {
            background: #f8f8f8;
        }

        #customHeader .search-results-modal .search-result-item-modal img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }

        #customHeader .search-results-modal .search-result-item-modal .result-info {
            flex: 1;
        }

        #customHeader .search-results-modal .search-result-item-modal .result-title {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 3px;
        }

        #customHeader .search-results-modal .search-result-item-modal .result-price {
            color: #e60000;
            font-weight: 700;
            font-size: 13px;
        }

        #customHeader .search-results-modal .search-no-results,
        #customHeader .search-results-modal .search-loading {
            padding: 25px;
            text-align: center;
            color: #666;
        }

        #customHeader .search-results-modal .search-view-all-modal {
            display: block;
            text-align: center;
            padding: 15px;
            background: #f5f5f5;
            color: #ff6b35;
            font-weight: 600;
            text-decoration: none;
        }

        /* ========== RESPONSIVE: TABLET ========== */
        @media (max-width: 1024px) {
            #customHeader .nav-menu>li>a {
                padding: 20px 15px;
                font-size: 15px;
            }

            #customHeader .search-dropdown-wrapper {
                right: 15px;
                top: 65px;
            }

            #customHeader .search-input-box {
                width: 350px;
            }

            #customHeader .search-results-dropdown {
                width: 350px;
            }

            #customHeader .user-actions {
                gap: 15px;
            }
        }

        /* ========== RESPONSIVE: MOBILE ========== */
        @media (max-width: 768px) {

            /* Make non-home pages stay black on mobile */
            #customHeader:not(.home-header) .header {
                background: black !important;
            }

            /* Keep homepage transparent on mobile */
            #customHeader.home-header .header {
                background: transparent !important;
            }

            #customHeader .top-banner {
                font-size: 12px;
                padding: 6px 10px;
            }

            #customHeader .header {
                background: transparent;
            }

            #customHeader .nav-container {
                flex-wrap: wrap;
                padding: 8px 12px;
                row-gap: 10px;
                align-items: center;
            }

            #customHeader .logo img {
                height: 55px !important;
            }

            #customHeader .search-dropdown-wrapper {
                display: none !important;
            }

            #customHeader .search-container {
                order: 1;
                width: auto;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                padding: 0;
                margin: 0;
            }

            #customHeader .search-toggle {
                order: 1;
                display: flex !important;
            }

            #customHeader .user-actions {
                order: 2;
                gap: 14px;
                margin-left: auto;
            }

            #customHeader .menu-toggle {
                display: block;
                order: 3;
            }

            #customHeader .nav-menu {
                position: absolute;
                top: calc(100% + 8px);
                right: 12px;
                width: 280px;
                display: none;
                flex-direction: column;
                background: #fff;
                color: #111;
                border-radius: 8px;
                padding: 8px 0;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
                z-index: 2000;
            }

            #customHeader .nav-menu.active {
                display: flex;
            }

            #customHeader .nav-menu>li {
                width: 100%;
                border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            }

            #customHeader .nav-menu>li>a {
                padding: 12px 16px;
                color: #111 !important;
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-shadow: none;
            }

            #customHeader .nav-menu>li>a .dropdown-arrow {
                border-right: 2px solid rgba(0, 0, 0, 0.45);
                border-bottom: 2px solid rgba(0, 0, 0, 0.45);
                transform: rotate(45deg);
                margin-left: 8px;
            }

            #customHeader .nav-menu>li>a:hover {
                background: rgba(0, 0, 0, 0.02);
            }

            #customHeader .submenu {
                position: static;
                display: none;
                background: transparent;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
            }

            #customHeader .nav-menu>li.submenu-open .submenu {
                display: block;
            }

            #customHeader .submenu li a {
                color: #333;
                padding: 10px 22px;
                background: transparent;
                border-left: 3px solid transparent;
                text-shadow: none;
            }

            #customHeader .submenu li a:hover {
                background: rgba(0, 0, 0, 0.03);
                color: #000;
                padding-left: 22px;
                border-left-color: #ff6b35;
            }
        }

        @media (max-width: 480px) {
            #customHeader .logo img {
                height: 48px !important;
            }
        }

        /* Improved spacing for devices under 400px - matches desktop spacing */
@media (max-width: 400px) {
    #customHeader .nav-container {
        padding: 12px 15px;
        gap: 0; /* Remove gap, we'll use margins instead */
        justify-content: space-between;
    }

    #customHeader .logo {
        flex: 0 0 auto;
        min-width: 0;
        margin-right: auto; /* Push everything else to the right */
    }

    #customHeader .logo img {
        height: 50px !important;
        width: auto;
    }

    /* Group search + user actions + menu together on the right */
    #customHeader .search-container {
        flex: 0 0 auto;
        margin-right: 15px;
        order: 1;
    }

    #customHeader .search-toggle {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }

    #customHeader .user-actions {
        flex: 0 0 auto;
        gap: 15px;
        margin-right: 15px;
        order: 2;
        margin-left: 0;
    }

    #customHeader .user-action span:not(.user-action-icon):not(.badge) {
        display: none; /* Hide text labels */
    }

    #customHeader .user-action {
        padding: 5px;
        min-width: 0;
    }

    #customHeader .user-action-icon {
        font-size: 24px;
    }

    #customHeader .user-action-icon svg {
        width: 24px;
        height: 24px;
    }

    #customHeader .menu-toggle {
        flex: 0 0 auto;
        padding: 8px 12px;
        font-size: 22px;
        min-width: auto;
        order: 3;
    }

    #customHeader .badge {
        width: 18px;
        height: 18px;
        font-size: 10px;
        top: -2px;
        right: -4px;
        font-weight: 700;
    }
}

/* Extra small devices - under 360px */
@media (max-width: 360px) {
    #customHeader .nav-container {
        padding: 10px 12px;
    }

    #customHeader .logo img {
        height: 46px !important;
    }

    #customHeader .search-container {
        margin-right: 12px;
    }

    #customHeader .user-actions {
        gap: 12px;
        margin-right: 12px;
    }

    #customHeader .user-action-icon {
        font-size: 22px;
    }

    #customHeader .user-action-icon svg {
        width: 22px;
        height: 22px;
    }

    #customHeader .search-toggle {
        width: 38px;
        height: 38px;
        font-size: 19px;
    }

    #customHeader .menu-toggle {
        font-size: 20px;
        padding: 7px 11px;
    }
}

/* Tiny devices - under 340px */
@media (max-width: 340px) {
    #customHeader .nav-container {
        padding: 8px 10px;
    }

    #customHeader .logo img {
        height: 42px !important;
    }

    #customHeader .user-actions {
        gap: 10px;
        margin-right: 10px;
    }

    #customHeader .search-container {
        margin-right: 10px;
    }
}

        /* Banner Slider - CSS Animation Only */
        .ep-banner-slider {
            position: relative;
            background: red;
            color: white;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            overflow: hidden;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ep-banner-track {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .ep-banner-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            animation: bannerFade 8s infinite;
        }

        .ep-banner-slide:nth-child(1) {
            animation-delay: 0s;
        }

        .ep-banner-slide:nth-child(2) {
            animation-delay: 4s;
        }

        @keyframes bannerFade {
            0% {
                opacity: 0;
            }

            8% {
                opacity: 1;
            }

            42% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 0;
            }
        }

        .ep-banner-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            animation: bannerFade 6s infinite;
            /* Changed from 8s to 6s */
        }

        .ep-banner-slide:nth-child(1) {
            animation-delay: 0s;
        }

        .ep-banner-slide:nth-child(2) {
            animation-delay: 3.7s;
            /* Changed from 4s to 3s */
        }
    </style>

    <!-- Scoped wrapper -->
    <div id="customHeader" class="<?php echo is_front_page() ? 'home-header' : 'inner-header'; ?>">

        <!-- Rotating Banner Slider -->
        <div class="ep-banner-slider">
            <div class="ep-banner-track">
                <div class="ep-banner-slide">
                    $99 Shipping On All Orders Over $1500
                </div>
                <div class="ep-banner-slide">
                    Free Shipping on All Orders above $2000
                </div>
            </div>
        </div>

        <header class="header">
            <nav class="nav-container">
                <div class="logo">
                    <a href="/" class="logo">
                        <img src="/wp-content/uploads/2025/11/Order-Fireworks-Online-Logo.png" alt="OFO Logo">
                    </a>
                </div>

                <!-- Menu Toggle (Mobile) -->
                <button class="menu-toggle" id="menuToggle">☰</button>

                <!-- Navigation Menu -->
                <ul class="nav-menu" id="navMenu">
                    <li>
                        <a href="/product-category/aerial-fireworks/">
                            Aerial Fireworks
                            <span class="dropdown-arrow"></span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/product-category/aerial-fireworks/200g-cakes/">200g Cakes</a></li>
                            <li><a href="/product-category/aerial-fireworks/500g-cakes/">500g Cakes</a></li>
                            <li><a href="/product-category/aerial-fireworks/artillery/">Artillery</a></li>
                            <li><a href="/product-category/aerial-fireworks/parachutes/">Parachutes</a></li>
                            <li><a href="/product-category/aerial-fireworks/rockets/">Rockets</a></li>
                            <li><a href="/product-category/aerial-fireworks/roman-candles/">Roman Candles</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/product-category/ground-fireworks/">
                            Ground Fireworks
                            <span class="dropdown-arrow"></span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/product-category/ground-fireworks/firecrackers/">Firecrackers</a></li>
                            <li><a href="/product-category/ground-fireworks/fountains/">Fountains</a></li>
                            <li><a href="/product-category/ground-fireworks/novelties/">Novelties</a></li>
                            <li><a href="/product-category/ground-fireworks/smoke/">Smoke</a></li>
                            <li><a href="/product-category/ground-fireworks/sparklers/">Sparklers</a></li>
                        </ul>
                    </li>
                    <li><a href="/product-category/pallet-packs/">Pallet Packs</a></li>
                </ul>

                <!-- Search Container -->
                <div class="search-container">
                    <button class="search-toggle" id="searchToggle" aria-label="Open search">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>

                    <!-- Desktop Search Dropdown -->
                    <div class="search-dropdown-wrapper" id="searchDropdownWrapper">
                        <div class="search-input-box">
                            <input type="text" id="desktopSearchInput" placeholder="Search products..." autocomplete="off">
                            <button type="button" class="search-submit-btn" id="desktopSearchSubmit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                            <button type="button" class="search-close-btn" id="desktopSearchClose">✕</button>
                        </div>
                        <div class="search-results-dropdown" id="desktopSearchResults">
                            <!-- Results will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="user-actions">
                    <a href="/my-account/" class="user-action">
                        <span class="user-action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <span>Login</span>
                    </a>
                    <a href="/my-wishlist/" class="user-action">
                        <span class="user-action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </span>
                        <span>Wishlist</span>
                        <span class="badge" id="wishlistCount">0</span>
                    </a>
                    <a href="/cart/" class="user-action" id="cartAction">
                        <span class="user-action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </span>
                        <span>Cart</span>
                        <span class="badge" id="cartCount">0</span>
                    </a>
                </div>
            </nav>
        </header>

        <!-- Mobile Search Modal -->
        <div class="search-modal" id="searchModal" aria-hidden="true" role="dialog" aria-modal="true">
            <div class="search-modal-backdrop" id="searchModalBackdrop"></div>
            <div class="search-modal-inner" role="document">
                <div class="modal-header">
                    <button class="close-modal" id="closeSearchModal" aria-label="Close search">✕</button>
                    <input type="text" id="modalSearchInput" placeholder="Search products..." autocomplete="off">
                    <button class="modal-search-btn" id="modalSearchSubmit" aria-label="Search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
                <div class="search-results-modal" id="searchResultsModal"></div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // ==================== CONFIGURATION ====================
            var ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
            var homeUrl = '<?php echo esc_url(home_url("/")); ?>';
            var searchTimeout = null;
            var minSearchLength = 2;

            // Scoped container
            var container = document.getElementById('customHeader');
            if (!container) return;

            // ==================== DOM ELEMENTS (scoped) ====================
            var menuToggle = container.querySelector('#menuToggle');
            var navMenu = container.querySelector('#navMenu');
            var searchToggle = container.querySelector('#searchToggle');
            var searchDropdownWrapper = container.querySelector('#searchDropdownWrapper');
            var desktopSearchInput = container.querySelector('#desktopSearchInput');
            var desktopSearchResults = container.querySelector('#desktopSearchResults');
            var desktopSearchSubmit = container.querySelector('#desktopSearchSubmit');
            var desktopSearchClose = container.querySelector('#desktopSearchClose');
            var searchModal = container.querySelector('#searchModal');
            var searchModalBackdrop = container.querySelector('#searchModalBackdrop');
            var modalSearchInput = container.querySelector('#modalSearchInput');
            var searchResultsModal = container.querySelector('#searchResultsModal');
            var closeSearchModalBtn = container.querySelector('#closeSearchModal');
            var modalSearchSubmit = container.querySelector('#modalSearchSubmit');

            // ==================== MENU TOGGLE ====================
            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    navMenu.classList.toggle('active');
                    menuToggle.classList.toggle('open');
                });
            }

            // ==================== SUBMENU HANDLING ====================
            var menuItems = container.querySelectorAll('.nav-menu > li');
            menuItems.forEach(function(item) {
                var link = item.querySelector('a');
                var submenu = item.querySelector('.submenu');

                if (submenu && link) {
                    link.addEventListener('click', function(e) {
                        if (window.innerWidth <= 768) {
                            e.preventDefault();
                            item.classList.toggle('submenu-open');
                        }
                    });
                }
            });

            // Close submenus on resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    menuItems.forEach(function(item) {
                        item.classList.remove('submenu-open');
                    });
                    if (navMenu) navMenu.classList.remove('active');
                    closeSearchModal();
                }
            });

            // ==================== UTILITY FUNCTIONS ====================
            function isMobile() {
                return window.innerWidth <= 768;
            }

            function escapeHtml(text) {
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ==================== DESKTOP SEARCH FUNCTIONS ====================
            function openDesktopSearch() {
                if (searchDropdownWrapper) {
                    searchDropdownWrapper.classList.add('active');
                    if (searchToggle) searchToggle.classList.add('hidden');
                    if (desktopSearchInput) {
                        desktopSearchInput.value = '';
                        desktopSearchInput.focus();
                    }
                    hideDesktopResults();
                }
            }

            function closeDesktopSearch() {
                if (searchDropdownWrapper) {
                    searchDropdownWrapper.classList.remove('active');
                    if (searchToggle) searchToggle.classList.remove('hidden');
                    hideDesktopResults();
                    if (desktopSearchInput) desktopSearchInput.value = '';
                }
            }

            function showDesktopResults() {
                if (desktopSearchResults) {
                    desktopSearchResults.classList.add('active');
                    if (searchDropdownWrapper) searchDropdownWrapper.classList.add('has-results');
                }
            }

            function hideDesktopResults() {
                if (desktopSearchResults) {
                    desktopSearchResults.classList.remove('active');
                    desktopSearchResults.innerHTML = '';
                    if (searchDropdownWrapper) searchDropdownWrapper.classList.remove('has-results');
                }
            }

            function showDesktopLoading() {
                if (desktopSearchResults) {
                    desktopSearchResults.innerHTML = '<div class="search-loading">Searching products...</div>';
                    showDesktopResults();
                }
            }

            function displayDesktopResults(data, query) {
                if (!desktopSearchResults) return;

                if (!data.success || !data.data || data.data.products.length === 0) {
                    desktopSearchResults.innerHTML = '<div class="search-no-results">No products found for "' + escapeHtml(query) + '"</div>';
                    showDesktopResults();
                    return;
                }

                var html = '';
                html += '<div class="results-header">Products (' + data.data.products.length + ')</div>';

                data.data.products.forEach(function(product) {
                    html += '<a href="' + escapeHtml(product.url) + '" class="search-result-item">';
                    html += '<img src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.title) + '" class="search-result-image" loading="lazy">';
                    html += '<div class="search-result-info">';
                    html += '<div class="search-result-title">' + escapeHtml(product.title) + '</div>';
                    html += '<div class="search-result-price">' + product.price + '</div>';
                    html += '</div>';
                    html += '</a>';
                });

                html += '<a href="' + homeUrl + '?s=' + encodeURIComponent(query) + '&post_type=product" class="search-view-all">';
                html += 'View All Results for "' + escapeHtml(query) + '"';
                html += '</a>';

                desktopSearchResults.innerHTML = html;
                showDesktopResults();
            }

            // ==================== MOBILE SEARCH FUNCTIONS ====================
            function openSearchModal() {
                if (searchModal) {
                    searchModal.classList.add('active');
                    searchModal.setAttribute('aria-hidden', 'false');
                    if (modalSearchInput) {
                        modalSearchInput.value = '';
                        setTimeout(function() {
                            modalSearchInput.focus();
                        }, 100);
                    }
                    if (searchResultsModal) searchResultsModal.innerHTML = '';
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeSearchModal() {
                if (searchModal) {
                    searchModal.classList.remove('active');
                    searchModal.setAttribute('aria-hidden', 'true');
                    if (modalSearchInput) modalSearchInput.value = '';
                    if (searchResultsModal) searchResultsModal.innerHTML = '';
                    document.body.style.overflow = '';
                }
            }

            function showMobileLoading() {
                if (searchResultsModal) {
                    searchResultsModal.innerHTML = '<div class="search-loading">Searching products...</div>';
                }
            }

            function displayMobileResults(data, query) {
                if (!searchResultsModal) return;

                if (!data.success || !data.data || data.data.products.length === 0) {
                    searchResultsModal.innerHTML = '<div class="search-no-results">No products found for "' + escapeHtml(query) + '"</div>';
                    return;
                }

                var html = '';
                data.data.products.forEach(function(product) {
                    html += '<a href="' + escapeHtml(product.url) + '" class="search-result-item-modal">';
                    html += '<img src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.title) + '">';
                    html += '<div class="result-info">';
                    html += '<div class="result-title">' + escapeHtml(product.title) + '</div>';
                    html += '<div class="result-price">' + product.price + '</div>';
                    html += '</div>';
                    html += '</a>';
                });

                // Add "View All Results" link
                html += '<a href="' + homeUrl + '?s=' + encodeURIComponent(query) + '&post_type=product" class="search-view-all-modal">';
                html += 'View All Results →';
                html += '</a>';

                searchResultsModal.innerHTML = html;
            }

            // ==================== AJAX SEARCH FUNCTION ====================
            function performAjaxSearch(query, callback) {
                var url = ajaxUrl + '?action=ajax_product_search&s=' + encodeURIComponent(query);

                fetch(url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Cache-Control': 'no-cache'
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (typeof callback === 'function') {
                            callback(data, query);
                        }
                    })
                    .catch(function(error) {
                        console.error('Search error:', error);
                        if (typeof callback === 'function') {
                            callback({
                                success: false,
                                data: {
                                    products: []
                                }
                            }, query);
                        }
                    });
            }

            function goToSearchResults(query) {
                if (query && query.trim().length > 0) {
                    window.location.href = homeUrl + '?s=' + encodeURIComponent(query.trim()) + '&post_type=product';
                }
            }

            // ==================== DESKTOP SEARCH EVENT HANDLERS ====================
            if (searchToggle) {
                searchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (isMobile()) {
                        openSearchModal();
                    } else {
                        openDesktopSearch();
                    }
                });
            }

            if (desktopSearchClose) {
                desktopSearchClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeDesktopSearch();
                });
            }

            if (desktopSearchInput) {
                desktopSearchInput.addEventListener('input', function(e) {
                    var query = e.target.value.trim();
                    clearTimeout(searchTimeout);

                    if (query.length < minSearchLength) {
                        hideDesktopResults();
                        return;
                    }

                    showDesktopLoading();

                    searchTimeout = setTimeout(function() {
                        performAjaxSearch(query, displayDesktopResults);
                    }, 300);
                });

                desktopSearchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        goToSearchResults(desktopSearchInput.value);
                    }
                });
            }

            if (desktopSearchSubmit) {
                desktopSearchSubmit.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (desktopSearchInput) {
                        goToSearchResults(desktopSearchInput.value);
                    }
                });
            }

            // Close desktop search when clicking outside (scoped)
            document.addEventListener('click', function(e) {
                if (searchDropdownWrapper && searchDropdownWrapper.classList.contains('active')) {
                    var clickedInside = searchDropdownWrapper.contains(e.target);
                    var clickedToggle = searchToggle && searchToggle.contains(e.target);

                    if (!clickedInside && !clickedToggle) {
                        closeDesktopSearch();
                    }
                }
            });

            // Prevent clicks inside dropdown from closing it
            if (searchDropdownWrapper) {
                searchDropdownWrapper.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // ==================== MOBILE SEARCH EVENT HANDLERS ====================
            if (searchModalBackdrop) {
                searchModalBackdrop.addEventListener('click', function() {
                    closeSearchModal();
                });
            }

            if (closeSearchModalBtn) {
                closeSearchModalBtn.addEventListener('click', function() {
                    closeSearchModal();
                });
            }

            if (modalSearchInput) {
                modalSearchInput.addEventListener('input', function(e) {
                    var query = e.target.value.trim();
                    clearTimeout(searchTimeout);

                    if (query.length < minSearchLength) {
                        if (searchResultsModal) searchResultsModal.innerHTML = '';
                        return;
                    }

                    showMobileLoading();

                    searchTimeout = setTimeout(function() {
                        performAjaxSearch(query, displayMobileResults);
                    }, 300);
                });

                modalSearchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        goToSearchResults(modalSearchInput.value);
                    }
                });
            }

            if (modalSearchSubmit) {
                modalSearchSubmit.addEventListener('click', function() {
                    if (modalSearchInput) {
                        goToSearchResults(modalSearchInput.value);
                    }
                });
            }

            // ==================== ESCAPE KEY HANDLER ====================
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeDesktopSearch();
                    closeSearchModal();
                }
            });

            // ==================== CART & WISHLIST FUNCTIONALITY ====================
            function updateCart() {
                var cartCountElement = container.querySelector('#cartCount');
                if (!cartCountElement) return;

                fetch(ajaxUrl + '?action=get_cart_count&_=' + Date.now(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Cache-Control': 'no-cache'
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success && typeof data.data.count !== 'undefined') {
                            var count = parseInt(data.data.count) || 0;
                            cartCountElement.textContent = count;
                            cartCountElement.style.display = 'flex';
                        }
                    })
                    .catch(function(error) {
                        console.error('Cart count error:', error);
                    });
            }

            function updateWishlist() {
                var wishlistCountElement = container.querySelector('#wishlistCount');
                if (!wishlistCountElement) return;

                fetch(ajaxUrl + '?action=get_wishlist_count&_=' + Date.now(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Cache-Control': 'no-cache'
                        }
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success && typeof data.data.count !== 'undefined') {
                            var count = parseInt(data.data.count) || 0;
                            wishlistCountElement.textContent = count;
                            wishlistCountElement.style.display = 'flex';
                        }
                    })
                    .catch(function(error) {
                        console.error('Wishlist count error:', error);
                    });
            }

            function initializeCounts() {
                setTimeout(function() {
                    updateCart();
                    updateWishlist();
                }, 100);
            }

            // Initialize on DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeCounts);
            } else {
                initializeCounts();
            }

            // Also update on window load
            window.addEventListener('load', function() {
                setTimeout(function() {
                    updateCart();
                    updateWishlist();
                }, 200);
            });

            // WooCommerce event listeners (scoped selectors remain fine)
            if (window.jQuery) {
                jQuery(document).ready(function($) {
                    $(document.body).on('wc_fragments_refreshed wc_fragments_loaded updated_wc_div added_to_cart removed_from_cart updated_cart_totals', function() {
                        setTimeout(updateCart, 300);
                    });

                    $(document).on('added_to_wishlist removed_from_wishlist', function() {
                        setTimeout(updateWishlist, 300);
                    });
                });
            }

            // Periodic refresh as backup
            setInterval(function() {
                updateCart();
                updateWishlist();
            }, 5000);

        })();
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('custom_header', 'custom_shortcode_header');

/* Keep your AJAX handlers unchanged */
add_action('wp_ajax_ajax_product_search', 'ajax_product_search_handler');
add_action('wp_ajax_nopriv_ajax_product_search', 'ajax_product_search_handler');

function ajax_product_search_handler()
{
    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    if (empty($search_query)) {
        wp_send_json_error(array('message' => 'Empty search query'));
        return;
    }

    $args = array(
        'post_type'      => 'product',
        's'              => $search_query,
        'posts_per_page' => 8,
        'post_status'    => 'publish'
    );

    $products_query = new WP_Query($args);
    $products = array();

    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $product = wc_get_product(get_the_ID());

            if ($product) {
                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                if (!$image_url) {
                    $image_url = wc_placeholder_img_src('thumbnail');
                }

                $products[] = array(
                    'id'    => get_the_ID(),
                    'title' => get_the_title(),
                    'url'   => get_permalink(),
                    'image' => $image_url,
                    'price' => $product->get_price_html()
                );
            }
        }
        wp_reset_postdata();
    }

    wp_send_json_success(array(
        'products' => $products,
        'count'    => count($products)
    ));
}
?>