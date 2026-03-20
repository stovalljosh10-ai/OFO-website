<?php
function custom_slider_shortcode_footer()
{
    ob_start();
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap');

        .ofo-footer {
            background: linear-gradient(135deg, #0a0d1a 0%, #151825 25%, #1a1e2e 50%, #151825 75%, #0a0d1a 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite;
            color: #ffffff;
            padding: 50px 0 40px;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            width: 100%;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Diagonal gradient overlay effect */
        /* Diagonal gradient overlay effect */
        .ofo-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -30%;
            width: 150%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.0) 0%,
                    rgba(255, 255, 255, 0.0) 30%,
                    rgba(255, 255, 255, 0.15) 45%,
                    rgba(255, 255, 255, 0.16) 50%,
                    rgba(255, 255, 255, 0.15) 55%,
                    rgba(255, 255, 255, 0.0) 70%,
                    rgba(255, 255, 255, 0.0) 100%);
            animation: diagonalShift 20s ease-in-out infinite;
            pointer-events: none;
        }

        /* Top glowing border */
        .ofo-footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--bt-brand-9, #ff6b35), transparent);
            box-shadow: 0 0 30px var(--bt-brand-9, #ff6b35), 0 0 60px rgba(255, 107, 53, 0.5);
            z-index: 2;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes diagonalShift {

            0%,
            100% {
                transform: translateX(0) translateY(0);
                opacity: 1;
            }

            25% {
                transform: translateX(10%) translateY(-5%);
                opacity: 0.8;
            }

            50% {
                transform: translateX(5%) translateY(5%);
                opacity: 0.9;
            }

            75% {
                transform: translateX(-5%) translateY(-3%);
                opacity: 0.85;
            }
        }

        .ofo-footer * {
            box-sizing: border-box;
        }

        .ofo-footer-container {
            max-width: 100%;
            width: 100%;
            margin: 0;
            padding: 0 60px;
            position: relative;
            z-index: 1;
        }

        .ofo-footer-top {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 50px;
            margin-bottom: 40px;
        }

        .ofo-footer-column h3 {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'Montserrat', sans-serif;
        }

        .ofo-footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .ofo-footer-column ul li {
            margin-bottom: 12px;
        }

        .ofo-footer-column ul li a {
            color: #c0c0c0;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.5;
            position: relative;
        }

        .ofo-footer-column ul li a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--bt-brand-9, #ff6b35);
            transition: width 0.3s ease;
        }

        .ofo-footer-column ul li a:hover {
            color: #ffffff;
            transform: translateX(5px);
        }

        .ofo-footer-column ul li a:hover::after {
            width: 100%;
        }

        .ofo-contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .ofo-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: #c0c0c0;
            font-size: 15px;
            line-height: 1.5;
        }

        .ofo-contact-item svg {
            width: 20px;
            height: 20px;
            fill: var(--bt-brand-9, #ff6b35);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .ofo-contact-item a {
            color: #c0c0c0;
            text-decoration: none;
            transition: color 0.3s ease;
            word-break: break-word;
        }

        .ofo-contact-item a:hover {
            color: #ffffff;
        }

        .ofo-footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--bt-brand-9, #ff6b35) 20%, var(--bt-brand-9, #ff6b35) 80%, transparent);
            margin: 30px 0 25px;
            opacity: 0.7;
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.4);
        }

        .ofo-footer-bottom {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 30px;
            padding: 0 60px;
        }

        .ofo-copyright {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 400;
            justify-self: start;
        }

        .ofo-payment-methods {
            justify-self: center;
        }

        .ofo-payment-methods h3 {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
        }

        .ofo-payment-icons {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .ofo-payment-icons img {
            height: 32px;
            width: auto;
            background: white;
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        .ofo-payment-icons img:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 4px 16px rgba(255, 107, 53, 0.4);
        }

        .ofo-social-links {
            display: flex;
            gap: 12px;
            justify-self: end;
        }

        .ofo-social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ofo-social-link:hover {
            background: var(--bt-brand-9, #ff6b35);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.5);
            border-color: var(--bt-brand-9, #ff6b35);
        }

        .ofo-social-link svg {
            width: 20px;
            height: 20px;
            fill: #cbd5e1;
            transition: fill 0.3s ease;
        }

        .ofo-social-link:hover svg {
            fill: white;
        }

        /* Tablet Styles */
        @media (max-width: 1024px) {
            .ofo-footer {
                padding: 40px 0 35px;
            }

            .ofo-footer-container {
                padding: 0 40px;
            }

            .ofo-footer-top {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px 35px;
            }

            .ofo-footer-bottom {
                padding: 0 40px;
                gap: 25px;
            }
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .ofo-footer {
                padding: 35px 0 30px;
            }

            .ofo-footer-container {
                padding: 0 25px;
            }

            .ofo-footer-top {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .ofo-footer-column h3 {
                font-size: 15px;
                margin-bottom: 16px;
            }

            .ofo-footer-column ul li {
                margin-bottom: 10px;
            }

            .ofo-footer-column ul li a {
                font-size: 14px;
            }

            .ofo-contact-info {
                gap: 12px;
            }

            .ofo-contact-item {
                font-size: 14px;
                gap: 10px;
            }

            .ofo-footer-divider {
                margin: 25px 0 20px;
            }

            .ofo-footer-bottom {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 25px;
                text-align: center;
            }

            .ofo-copyright {
                font-size: 13px;
                justify-self: center;
                order: 3;
            }

            .ofo-payment-methods {
                order: 2;
                justify-self: center;
            }

            .ofo-payment-methods h3 {
                font-size: 13px;
                margin-bottom: 10px;
            }

            .ofo-payment-icons {
                gap: 10px;
            }

            .ofo-payment-icons img {
                height: 28px;
                padding: 5px 10px;
            }

            .ofo-social-links {
                order: 1;
                justify-self: center;
            }

            .ofo-social-link {
                width: 38px;
                height: 38px;
            }

            .ofo-social-link svg {
                width: 18px;
                height: 18px;
            }
        }

        /* Small Mobile Styles */
        @media (max-width: 480px) {
            .ofo-footer {
                padding: 30px 0 25px;
            }

            .ofo-footer-container {
                padding: 0 20px;
            }

            .ofo-footer-top {
                gap: 25px;
            }

            .ofo-footer-bottom {
                padding: 0 20px;
                gap: 18px;
            }

            .ofo-contact-item a {
                font-size: 13px;
            }

            .ofo-payment-icons img {
                height: 26px;
                padding: 4px 8px;
            }
        }
    </style>

    <footer class="ofo-footer">
        <div class="ofo-footer-container">
            <div class="ofo-footer-top">
                <!-- Contact Information Column -->
                <div class="ofo-footer-column">
                    <h3>Contact Us</h3>
                    <div class="ofo-contact-info">
                        <div class="ofo-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z" />
                            </svg>
                            <a href="tel:803-849-0221">803-849-0221</a>
                        </div>
                        <div class="ofo-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10h5v-2h-5c-4.34 0-8-3.66-8-8s3.66-8 8-8 8 3.66 8 8v1.43c0 .79-.71 1.57-1.5 1.57s-1.5-.78-1.5-1.57V12c0-2.76-2.24-5-5-5s-5 2.24-5 5 2.24 5 5 5c1.38 0 2.64-.56 3.54-1.47.65.89 1.77 1.47 2.96 1.47 1.97 0 3.5-1.6 3.5-3.57V12c0-5.52-4.48-10-10-10zm0 13c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z" />
                            </svg>
                            <a href="mailto:webmaster@orderfireworksonline.com">webmaster@orderfireworksonline.com</a>
                        </div>
                        <div class="ofo-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                            <a href="https://www.google.com/maps/place/Order+Fireworks+Online/@33.2294765,-84.2843096,823m/data=!3m2!1e3!4b1!4m6!3m5!1s0x88f48b3004d87d1f:0x1a3e69093e3781d4!8m2!3d33.229472!4d-84.2817347!16s%2Fg%2F11xkp5pm6s" target="_blank" rel="noopener">Get Directions</a>
                        </div>
                        <div class="ofo-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                            </svg>
                            <span>Weekdays 8am - 4pm EST</span>
                        </div>
                    </div>
                </div>

                <!-- For Customers Column -->
                <div class="ofo-footer-column">
                    <h3>For Customers</h3>
                    <ul>
                        <li><a href="/my-account/">My Account</a></li>
                        <li><a href="/my-wishlist/">My Wishlist</a></li>
                        <li><a href="/loyalty-rewards/">Loyalty Rewards</a></li>
                        <li><a href="/contact/">Support</a></li>
                        <li><a href="/privacy-policy/">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Shop Column -->
                <div class="ofo-footer-column">
                    <h3>Shop</h3>
                    <ul>
                        <li><a href="/cart/">Cart</a></li>
                        <li><a href="/refund-and-returns-policy/">Returns</a></li>
                    </ul>
                </div>

                <!-- Popular Categories Column -->
                <div class="ofo-footer-column">
                    <h3>Popular Categories</h3>
                    <ul>
                        <li><a href="/product-category/ground-fireworks/firecrackers/">Firecrackers</a></li>
                        <li><a href="/product-category/ground-fireworks/fountains/">Fountains</a></li>
                        <li><a href="/product-category/pallet-packs/">Pallet Packs</a></li>
                        <li><a href="/product-category/aerial-fireworks/roman-candles/">Roman Candles</a></li>
                        <li><a href="/product-category/ground-fireworks/smoke/">Smoke</a></li>
                    </ul>
                </div>
            </div>

            <div class="ofo-footer-divider"></div>

            <!-- Footer Bottom - 3 Column Layout -->
            <div class="ofo-footer-bottom">
                <!-- Left: Copyright -->
                <div class="ofo-copyright">
                    © 2025 Order Fireworks Online. All Rights Reserved.
                </div>

                <!-- Center: Payment Methods -->
                <div class="ofo-payment-methods">
                    <h3>Payment Methods</h3>
                    <div class="ofo-payment-icons">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIHZpZXdCb3g9IjAgMCA2MCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIGZpbGw9IiMwMDY2QzAiIHJ4PSI0Ii8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+VklTQTwvdGV4dD48L3N2Zz4=" alt="Visa">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIHZpZXdCb3g9IjAgMCA2MCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIGZpbGw9IndoaXRlIiByeD0iNCIvPjxjaXJjbGUgY3g9IjIyIiBjeT0iMTkiIHI9IjEwIiBmaWxsPSIjRUI0NzRFIi8+PGNpcmNsZSBjeD0iMzgiIGN5PSIxOSIgcj0iMTAiIGZpbGw9IiNGNzkxMDUiLz48L3N2Zz4=" alt="Mastercard">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIHZpZXdCb3g9IjAgMCA2MCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iMzgiIGZpbGw9ImJsYWNrIiByeD0iNCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZvbnQtd2VpZ2h0PSJib2xkIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkFQUExFIFBBWTwvdGV4dD48L3N2Zz4=" alt="Apple Pay">
                    </div>
                </div>

                <!-- Right: Social Links -->
                <div class="ofo-social-links">
                    <a href="https://www.facebook.com/ofopage" class="ofo-social-link" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

<?php
    return ob_get_clean();
}
add_shortcode('custom_footer', 'custom_slider_shortcode_footer');
?>