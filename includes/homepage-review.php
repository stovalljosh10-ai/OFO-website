<?php
function custom_slider_shortcode_review() {
    ob_start();
    
    // Generate unique ID for this instance to avoid conflicts
    $unique_id = 'reviews_' . uniqid();
    ?>
    <!-- Hero Banner Section -->
    <div class="fireworks-hero-banner-<?php echo $unique_id; ?>">
        <div class="hero-banner-content-<?php echo $unique_id; ?>">
            <div class="hero-text-overlay-<?php echo $unique_id; ?>">
                <h1 class="hero-title-<?php echo $unique_id; ?>">
                    Maximum<br>
                    Charge 60g<br>
                    Canister<br>
                    Artillery Kits
                </h1>
                <a href="/product-category/artillery/" class="hero-cta-button-<?php echo $unique_id; ?>">
                    SHOP ARTILLERY KITS
                </a>
            </div>
            <img src="/wp-content/uploads/2025/08/736362858-exclusive-offer-full-scaled-1.webp" 
                 alt="Maximum Charge 60g Canister Artillery Kits - #1 Selling Artillery in the U.S." 
                 class="web-banner hero-banner-image-<?php echo $unique_id; ?>">
                 <img src="/wp-content/uploads/2025/11/Frame-2055248362.png" 
                 alt="Maximum Charge 60g Canister Artillery Kits - #1 Selling Artillery in the U.S." 
                 class="mobile-banner hero-banner-image-<?php echo $unique_id; ?>">
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="custom-reviews-wrapper-<?php echo $unique_id; ?>">
        <!-- Fireworks Canvas - Isolated for reviews section only -->
        <canvas id="reviewsFireworksCanvas_<?php echo $unique_id; ?>" class="reviews-fireworks-canvas-<?php echo $unique_id; ?>"></canvas>
        
        <div class="reviews-container-<?php echo $unique_id; ?>">
            <div class="reviews-header-<?php echo $unique_id; ?>">
                <h2 class="reviews-title-<?php echo $unique_id; ?>">What Our Customers Say</h2>
                <p class="reviews-subtitle-<?php echo $unique_id; ?>">Real reviews from real people</p>
            </div>
            
            <div class="reviews-content-<?php echo $unique_id; ?>">
                <?php echo do_shortcode('[trustindex no-registration=google]'); ?>
            </div>
        </div>
    </div>

    <style>
        /* Hero Banner Section - Isolated with unique ID */
        .fireworks-hero-banner-<?php echo $unique_id; ?> {
            width: 100%;
            position: relative;
            overflow: hidden;
            background: #c60000;
        }

        .hero-banner-content-<?php echo $unique_id; ?> {
            position: relative;
            width: 100%;
        }

        .hero-text-overlay-<?php echo $unique_id; ?> {
            position: absolute;
            top: 50%;
            left: 15%;
            transform: translateY(-50%);
            z-index: 10;
            max-width: 600px;
        }

        .hero-title-<?php echo $unique_id; ?> {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.1;
            margin: 0 0 30px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-cta-button-<?php echo $unique_id; ?> {
            display: inline-block;
            padding: 18px 40px;
            background: #ffffff;
            color: #dc0000;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .hero-cta-button-<?php echo $unique_id; ?>:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-banner-image-<?php echo $unique_id; ?> {
            width: 100%;
            height: 400px;
            display: block;
            transition: transform 0.5s ease;
        }

        .fireworks-hero-banner-<?php echo $unique_id; ?>:hover .hero-banner-image-<?php echo $unique_id; ?> {
            transform: scale(1.02);
        }

        /* Reviews Wrapper - Isolated */
        .custom-reviews-wrapper-<?php echo $unique_id; ?> {
            width: 100%;
            padding: 80px 20px;
            background: 
                radial-gradient(circle at 20% 50%, rgba(220, 38, 38, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(239, 68, 68, 0.15) 0%, transparent 50%),
                linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            position: relative;
            overflow: hidden;
        }

        .custom-reviews-wrapper-<?php echo $unique_id; ?>::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 100px,
                    rgba(220, 38, 38, 0.03) 100px,
                    rgba(220, 38, 38, 0.03) 200px
                );
            pointer-events: none;
        }

        /* Fireworks Canvas - Scoped to reviews section only */
        .reviews-fireworks-canvas-<?php echo $unique_id; ?> {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .reviews-container-<?php echo $unique_id; ?> {
            max-width: 75%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                0 0 100px rgba(220, 38, 38, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            padding: 50px 40px;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(220, 38, 38, 0.1);
        }

        .reviews-header-<?php echo $unique_id; ?> {
            text-align: center;
            margin-bottom: 40px;
        }

        .reviews-title-<?php echo $unique_id; ?> {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 10px 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .reviews-subtitle-<?php echo $unique_id; ?> {
            font-size: 1.1rem;
            color: #666;
            margin: 0;
            font-weight: 400;
        }

        .reviews-content-<?php echo $unique_id; ?> {
            margin-top: 30px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .reviews-container-<?php echo $unique_id; ?> {
                max-width: 85%;
            }
        }

        @media (max-width: 1024px) {
            .hero-title-<?php echo $unique_id; ?> {
                font-size: 3rem;
            }

            .hero-text-overlay-<?php echo $unique_id; ?> {
                left: 4%;
            }

            .reviews-container-<?php echo $unique_id; ?> {
                max-width: 90%;
                padding: 40px 30px;
            }

            .reviews-title-<?php echo $unique_id; ?> {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-banner-image-<?php echo $unique_id; ?> {
                height: 400px;
            }

            .hero-title-<?php echo $unique_id; ?> {
                font-size: 3rem;
                margin-bottom: 20px;
            }

            .hero-cta-button-<?php echo $unique_id; ?> {
                padding: 15px 30px;
                font-size: 1rem;
            }

            .hero-text-overlay-<?php echo $unique_id; ?> {
                left: 3%;
            }

            .custom-reviews-wrapper-<?php echo $unique_id; ?> {
                padding: 50px 15px;
            }

            .reviews-container-<?php echo $unique_id; ?> {
                max-width: 95%;
                padding: 35px 20px;
                border-radius: 15px;
            }

            .reviews-title-<?php echo $unique_id; ?> {
                font-size: 1.8rem;
            }

            .reviews-subtitle-<?php echo $unique_id; ?> {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title-<?php echo $unique_id; ?> {
                font-size: 2rem;
                margin-bottom: 15px;
            }

            .hero-cta-button-<?php echo $unique_id; ?> {
                padding: 12px 24px;
                font-size: 0.9rem;
            }

            .hero-text-overlay-<?php echo $unique_id; ?> {
                left: 5%;
                max-width: 90%;
            }

            .custom-reviews-wrapper-<?php echo $unique_id; ?> {
                padding: 40px 10px;
            }

            .reviews-title-<?php echo $unique_id; ?> {
                font-size: 1.5rem;
            }

            .reviews-subtitle-<?php echo $unique_id; ?> {
                font-size: 0.9rem;
            }

            .reviews-container-<?php echo $unique_id; ?> {
                max-width: 98%;
                padding: 25px 15px;
            }
        }

        @media (max-width: 360px) {
            .hero-title-<?php echo $unique_id; ?> {
                font-size: 1.6rem;
            }

            .hero-cta-button-<?php echo $unique_id; ?> {
                padding: 10px 20px;
                font-size: 0.85rem;
            }

            .reviews-title-<?php echo $unique_id; ?> {
                font-size: 1.3rem;
            }

            .reviews-subtitle-<?php echo $unique_id; ?> {
                font-size: 0.85rem;
            }

            .reviews-container-<?php echo $unique_id; ?> {
                padding: 20px 12px;
            }
        }

        /* Additional styling for TrustIndex widget */
        .reviews-content-<?php echo $unique_id; ?> .trustindex-widget {
            margin: 0 auto;
        }
    </style>

    <script>
        // Isolated fireworks animation - Wrapped in its own namespace
        (function() {
            'use strict';
            
            // Use unique ID to avoid conflicts with other canvases
            const canvasId = 'reviewsFireworksCanvas_<?php echo $unique_id; ?>';
            const canvas = document.getElementById(canvasId);
            if (!canvas) return; // Exit if canvas doesn't exist
            
            const ctx = canvas.getContext('2d');
            
            function resizeCanvas() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }
            
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
            
            // Flower Pot Firework class - Realistic cone-shaped flower pot
            class FlowerPotReview {
                constructor(x, y) {
                    this.x = x;
                    this.y = y;
                    this.particles = [];
                    this.life = 300;
                    this.maxLife = 300;
                    this.baseWidth = 80;
                    this.height = 70;
                    this.cycleTime = 0;
                }
                
                emit() {
                    this.cycleTime += 0.1;
                    const burstIntensity = Math.sin(this.cycleTime) * 0.3 + 0.7;
                    const particleCount = Math.floor(burstIntensity * 10 + 6);
                    
                    for (let i = 0; i < particleCount; i++) {
                        const emitX = this.x + (Math.random() - 0.5) * 8;
                        const emitY = this.y - this.height;
                        const angle = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI / 2.5;
                        const speed = Math.random() * 6 + 4;
                        
                        const colorChoice = Math.random();
                        let color;
                        if (colorChoice < 0.25) color = '#FFD700';
                        else if (colorChoice < 0.45) color = '#FF4500';
                        else if (colorChoice < 0.6) color = '#00FF00';
                        else if (colorChoice < 0.75) color = '#FF1493';
                        else if (colorChoice < 0.9) color = '#00CED1';
                        else color = '#FFFFFF';
                        
                        this.particles.push({
                            x: emitX,
                            y: emitY,
                            vx: Math.cos(angle) * speed,
                            vy: Math.sin(angle) * speed,
                            life: Math.random() * 45 + 35,
                            maxLife: 60,
                            color: color,
                            size: Math.random() * 3 + 1.5,
                            twinkle: Math.random() * Math.PI * 2
                        });
                    }
                }
                
                update() {
                    this.emit();
                    this.life--;
                    
                    this.particles.forEach((p, index) => {
                        p.x += p.vx;
                        p.y += p.vy;
                        p.vy += 0.2;
                        p.vx *= 0.98;
                        p.life--;
                        p.twinkle += 0.5;
                        
                        if (p.life <= 0 || p.y > this.y) {
                            this.particles.splice(index, 1);
                        }
                    });
                }
                
                draw() {
                    ctx.save();
                    
                    // Draw decorative base
                    const baseRadius = this.baseWidth / 2;
                    const segments = 12;
                    
                    for (let i = 0; i < segments; i++) {
                        const angle1 = (i / segments) * Math.PI * 2;
                        const angle2 = ((i + 0.8) / segments) * Math.PI * 2;
                        ctx.fillStyle = i % 2 === 0 ? '#FF0000' : '#FFD700';
                        ctx.beginPath();
                        ctx.moveTo(this.x, this.y);
                        ctx.arc(this.x, this.y, baseRadius, angle1, angle2);
                        ctx.closePath();
                        ctx.fill();
                    }
                    
                    ctx.strokeStyle = '#8B4513';
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, baseRadius, 0, Math.PI * 2);
                    ctx.stroke();
                    
                    // Draw cone
                    const gradient = ctx.createLinearGradient(
                        this.x, this.y - this.height,
                        this.x, this.y
                    );
                    gradient.addColorStop(0, '#FF6347');
                    gradient.addColorStop(0.5, '#DC143C');
                    gradient.addColorStop(1, '#8B0000');
                    
                    ctx.fillStyle = gradient;
                    ctx.strokeStyle = '#8B4513';
                    ctx.lineWidth = 2;
                    ctx.beginPath();
                    ctx.moveTo(this.x, this.y - this.height);
                    ctx.lineTo(this.x - this.baseWidth / 2, this.y);
                    ctx.lineTo(this.x + this.baseWidth / 2, this.y);
                    ctx.closePath();
                    ctx.fill();
                    ctx.stroke();
                    
                    // Draw stars
                    ctx.fillStyle = '#FFD700';
                    const starPositions = [
                        { x: this.x, y: this.y - this.height * 0.7 },
                        { x: this.x - 15, y: this.y - this.height * 0.4 },
                        { x: this.x + 15, y: this.y - this.height * 0.4 },
                        { x: this.x - 25, y: this.y - this.height * 0.15 },
                        { x: this.x + 25, y: this.y - this.height * 0.15 }
                    ];
                    
                    starPositions.forEach(pos => {
                        this.drawStar(pos.x, pos.y, 4, 3, 6);
                    });
                    
                    // Badge
                    const badgeGradient = ctx.createRadialGradient(
                        this.x, this.y - this.height * 0.5, 0,
                        this.x, this.y - this.height * 0.5, 12
                    );
                    badgeGradient.addColorStop(0, '#FFFF00');
                    badgeGradient.addColorStop(0.6, '#FFD700');
                    badgeGradient.addColorStop(1, '#FFA500');
                    
                    ctx.fillStyle = badgeGradient;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y - this.height * 0.5, 12, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.strokeStyle = '#8B4513';
                    ctx.lineWidth = 1.5;
                    ctx.stroke();
                    
                    // Green base
                    ctx.fillStyle = '#228B22';
                    ctx.beginPath();
                    ctx.ellipse(this.x, this.y, this.baseWidth / 2 + 5, 8, 0, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.strokeStyle = '#006400';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                    
                    ctx.restore();
                    
                    // Draw particles
                    this.particles.forEach(p => {
                        const alpha = (p.life / p.maxLife) * (Math.sin(p.twinkle) * 0.4 + 0.6);
                        ctx.save();
                        ctx.globalAlpha = Math.max(0, alpha);
                        ctx.fillStyle = p.color;
                        ctx.shadowBlur = 20;
                        ctx.shadowColor = p.color;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                        ctx.fill();
                        
                        ctx.globalAlpha = Math.max(0, alpha * 1.5);
                        ctx.fillStyle = '#FFFFFF';
                        ctx.shadowBlur = 10;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.size * 0.5, 0, Math.PI * 2);
                        ctx.fill();
                        
                        if (Math.abs(p.vx) > 3 || Math.abs(p.vy) > 3) {
                            ctx.globalAlpha = Math.max(0, alpha * 0.4);
                            ctx.fillStyle = p.color;
                            ctx.beginPath();
                            ctx.arc(p.x - p.vx * 2, p.y - p.vy * 2, p.size * 0.6, 0, Math.PI * 2);
                            ctx.fill();
                        }
                        
                        ctx.restore();
                    });
                }
                
                drawStar(cx, cy, spikes, outerRadius, innerRadius) {
                    let rot = Math.PI / 2 * 3;
                    const step = Math.PI / spikes;
                    
                    ctx.beginPath();
                    ctx.moveTo(cx, cy - outerRadius);
                    
                    for (let i = 0; i < spikes; i++) {
                        let x = cx + Math.cos(rot) * outerRadius;
                        let y = cy + Math.sin(rot) * outerRadius;
                        ctx.lineTo(x, y);
                        rot += step;
                        
                        x = cx + Math.cos(rot) * innerRadius;
                        y = cy + Math.sin(rot) * innerRadius;
                        ctx.lineTo(x, y);
                        rot += step;
                    }
                    
                    ctx.lineTo(cx, cy - outerRadius);
                    ctx.closePath();
                    ctx.fill();
                }
                
                isDead() {
                    return this.life <= 0 && this.particles.length === 0;
                }
            }
            
            // Moving Star class
            class MovingStarReview {
                constructor() {
                    this.reset();
                }
                
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height * 0.5;
                    this.vx = Math.random() * 0.5 + 0.2;
                    this.vy = Math.random() * 0.3 - 0.15;
                    this.size = Math.random() * 2 + 1;
                    this.opacity = Math.random() * 0.5 + 0.5;
                    this.twinkleSpeed = Math.random() * 0.05 + 0.02;
                    this.twinkle = Math.random() * Math.PI * 2;
                    this.trail = [];
                    this.maxTrailLength = 8;
                }
                
                update() {
                    this.trail.push({x: this.x, y: this.y});
                    if (this.trail.length > this.maxTrailLength) {
                        this.trail.shift();
                    }
                    
                    this.x += this.vx;
                    this.y += this.vy;
                    this.twinkle += this.twinkleSpeed;
                    
                    if (this.x > canvas.width + 20 || this.y > canvas.height || this.y < -20) {
                        this.reset();
                        this.x = -20;
                    }
                }
                
                draw() {
                    this.trail.forEach((pos, index) => {
                        const trailAlpha = (index / this.trail.length) * this.opacity * 0.3;
                        ctx.save();
                        ctx.globalAlpha = trailAlpha;
                        ctx.fillStyle = '#FFFFFF';
                        ctx.shadowBlur = 4;
                        ctx.shadowColor = '#FFFFFF';
                        ctx.beginPath();
                        ctx.arc(pos.x, pos.y, this.size * 0.5, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.restore();
                    });
                    
                    const twinkleAlpha = this.opacity * (Math.sin(this.twinkle) * 0.3 + 0.7);
                    ctx.save();
                    ctx.globalAlpha = twinkleAlpha;
                    ctx.fillStyle = '#FFFFFF';
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = '#FFFFFF';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                    
                    ctx.globalAlpha = twinkleAlpha * 1.5;
                    ctx.shadowBlur = 8;
                    ctx.fillStyle = '#FFFFAA';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size * 0.6, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }
            }
            
            const flowerPots = [];
            const movingStars = [];
            let animationFrameId = null;
            
            for (let i = 0; i < 15; i++) {
                movingStars.push(new MovingStarReview());
            }
            
            function createFlowerPot() {
                const x = 60;
                const y = canvas.height;
                flowerPots.push(new FlowerPotReview(x, y));
            }
            
            function initializeFlowerPots() {
                createFlowerPot();
            }
            
            function animate() {
                ctx.fillStyle = 'rgba(26, 26, 26, 0.15)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                if (flowerPots.length < 1) {
                    createFlowerPot();
                }
                
                movingStars.forEach(star => {
                    star.update();
                    star.draw();
                });
                
                for (let i = flowerPots.length - 1; i >= 0; i--) {
                    flowerPots[i].update();
                    flowerPots[i].draw();
                    
                    if (flowerPots[i].isDead()) {
                        flowerPots.splice(i, 1);
                    }
                }
                
                animationFrameId = requestAnimationFrame(animate);
            }
            
            setTimeout(() => {
                initializeFlowerPots();
                animate();
            }, 500);
            
            window.addEventListener('beforeunload', () => {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
            });
        })();
    </script>
    <?php 
    return ob_get_clean();
}
add_shortcode('custom_review', 'custom_slider_shortcode_review');
?>