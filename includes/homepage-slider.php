<?php
function custom_slider_shortcode() {
    ob_start();
    ?>

    <style>
        .header {
            background: transparent;
            position: absolute;
            top: 40;
            left: 0;
            right: 0;
            z-index: 100;
        }

        /* slider */
        .slider-container { position: relative; width: 100%; height: 700px; overflow: hidden; }
        .slide { position: absolute; width: 100%; height: 100%; opacity: 0; visibility: hidden; transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out; }
        .slide.active { opacity: 1; visibility: visible; z-index: 1; }
        .slide-bg { width: 100%; height: 100%;  object-position: center; display:block; }
        .slide-content { position: absolute; top: 50%; left: 10%; transform: translateY(-50%); text-align:left; color:#fff; max-width:600px; z-index:2; }
        .slide-content, .slide-content h1, .slide-content p, .slide-content-small, .cta-button { color: #ffffff !important; }
        .slide-content-small { font-size: 16px; font-weight: 600; letter-spacing: 1px; margin-bottom: 15px; text-transform: uppercase; }
        .slide-content h1 { font-size: 64px; margin-bottom: 20px; font-weight: 900; line-height: 1.1; text-shadow: 2px 2px 8px rgba(0,0,0,0.3); text-transform: uppercase; }
        .slide-content p { font-size: 18px; margin-bottom: 35px; font-weight: 400; line-height: 1.5; color: #fff !important; }

        .cta-button { display: inline-block; padding: 16px 45px; background: #ff6b35; color: #fff; text-decoration: none; border-radius: 30px; font-size: 16px; font-weight: 700; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4); }
        .cta-button:hover { text-decoration:none;background: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,53,0.6); color: #e55a2b !important; }

        .slider-dots { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 12px; z-index: 10; }
        .dot { width: 14px; height: 14px; border-radius: 50%; background: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s; border: 2px solid transparent; }
        .dot.active { background: #fff; border-color: #fff; transform: scale(1.2); }
        .slider-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: #fff; border: none; font-size: 30px; padding: 15px 20px; cursor: pointer; z-index: 10; transition: all 0.3s; backdrop-filter: blur(5px); }
        .slider-arrow-left { left: 20px; }
        .slider-arrow-right { right: 20px; }
        .slider-arrow:hover { background: rgba(0,0,0,0.8); }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .slider-container { height: 600px; }
            .slide-content h1 { font-size: 48px; }
            .slide-content p { font-size: 16px; }
        }
        @media (min-width: 769px) {
            .mobile-banner {display: none !important;}
        }
      

        @media (max-width: 768px) {
            .slider-container { height: 750px; }
            .slide-content { left: 6%; max-width: 80%; }
            .slide-content h1 { font-size: 36px; }
            .slide-content p { font-size: 15px; }
            .slider-arrow { font-size: 24px; padding: 10px 15px; }
            .slider-arrow-left { left: 10px; }
            .slider-arrow-right { right: 10px; }
            
        }
        @media (max-width: 768px) {
            .web-banner {display: none !important;}
            .slider-container { height: 750px; }
            .slide-content {top:35% !important;}
            .slider-arrow {top:55% !important;}
            .slide-content h1 { font-size: 28px; }
        }
    </style>

    <!-- Slider -->
    <?php
    // Get slider data from functions.php
    $slider_slides = function_exists('get_homepage_slider_data') ? get_homepage_slider_data() : array();
    
    if (empty($slider_slides)) {
        echo '<p>No slider slides configured. Please add slides in functions.php using get_homepage_slider_data().</p>';
        return ob_get_clean();
    }
    ?>
    <div class="slider-container">
        <button class="slider-arrow slider-arrow-left" id="prevSlide">‹</button>
        <button class="slider-arrow slider-arrow-right" id="nextSlide">›</button>

        <?php foreach ($slider_slides as $index => $slide) : 
            $is_first = ($index === 0);
            $slide_number = $index + 1;
        ?>
            <div class="slide <?php echo $is_first ? 'active' : ''; ?>">
                <?php if (!empty($slide['desktop_image'])) : ?>
                    <img src="<?php echo esc_url($slide['desktop_image']); ?>" alt="Slide <?php echo esc_attr($slide_number); ?>" class="slide-bg web-banner">
                <?php endif; ?>
                <?php if (!empty($slide['mobile_image'])) : ?>
                    <img src="<?php echo esc_url($slide['mobile_image']); ?>" alt="Slide <?php echo esc_attr($slide_number); ?>" class="slide-bg mobile-banner">
                <?php endif; ?>
                <div class="slide-content">
                    <?php if (!empty($slide['small_text'])) : ?>
                        <div class="slide-content-small"><?php echo esc_html($slide['small_text']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($slide['heading'])) : ?>
                        <h1><?php echo wp_kses_post($slide['heading']); ?></h1>
                    <?php endif; ?>
                    <?php if (!empty($slide['description'])) : ?>
                        <p><?php echo esc_html($slide['description']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($slide['button_text']) && !empty($slide['button_url'])) : ?>
                        <a href="<?php echo esc_url($slide['button_url']); ?>" class="cta-button">
                            <?php echo esc_html($slide['button_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="slider-dots" id="sliderDots">
            <?php foreach ($slider_slides as $index => $slide) : ?>
                <span class="dot <?php echo ($index === 0) ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($index); ?>"></span>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            // Slider functionality
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');
            let autoSlideInterval;
            let isTransitioning = false;

            function showSlide(index) {
                if (isTransitioning) return;
                isTransitioning = true;

                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));

                currentSlide = index;
                if (slides[currentSlide]) slides[currentSlide].classList.add('active');
                if (dots[currentSlide]) dots[currentSlide].classList.add('active');

                setTimeout(function() {
                    isTransitioning = false;
                }, 800);
            }

            function nextSlide() {
                const next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }

            function previousSlide() {
                const prev = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prev);
            }

            function goToSlide(index) {
                clearInterval(autoSlideInterval);
                showSlide(index);
                startAutoSlide();
            }

            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 6200);
            }

            // Event listeners for slider
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    clearInterval(autoSlideInterval);
                    previousSlide();
                    startAutoSlide();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    clearInterval(autoSlideInterval);
                    nextSlide();
                    startAutoSlide();
                });
            }

            // Dot navigation
            dots.forEach(function(dot, index) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    goToSlide(index);
                });
            });

            // Start auto-sliding
            startAutoSlide();
            
        })();
    </script>

<?php
    return ob_get_clean();
}
add_shortcode('custom_slider', 'custom_slider_shortcode');
?>