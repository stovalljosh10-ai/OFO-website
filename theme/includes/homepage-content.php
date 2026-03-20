<?php
function custom_slider_shortcode_content() {
ob_start();
?>
<style>
/* About Section Styles */
.ofw-about-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 80px 20px;
    position: relative;
    overflow: hidden;
}

.ofw-about-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 30%, rgba(106, 17, 203, 0.3) 0%, transparent 40%),
        radial-gradient(circle at 80% 70%, rgba(37, 117, 252, 0.3) 0%, transparent 40%),
        radial-gradient(circle at 50% 50%, rgba(190, 75, 219, 0.2) 0%, transparent 50%);
    animation: backgroundPulse 8s ease-in-out infinite;
    pointer-events: none;
}

.ofw-about-section::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(2px 2px at 20% 30%, rgba(255, 255, 255, 0.3), transparent),
        radial-gradient(2px 2px at 60% 70%, rgba(255, 255, 255, 0.3), transparent),
        radial-gradient(1px 1px at 50% 50%, rgba(255, 255, 255, 0.3), transparent),
        radial-gradient(1px 1px at 80% 10%, rgba(255, 255, 255, 0.3), transparent),
        radial-gradient(2px 2px at 90% 60%, rgba(255, 255, 255, 0.3), transparent),
        radial-gradient(1px 1px at 33% 80%, rgba(255, 255, 255, 0.3), transparent);
    background-size: 200% 200%;
    animation: stars 60s linear infinite;
    pointer-events: none;
}

@keyframes backgroundPulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
}

@keyframes stars {
    from {
        background-position: 0 0;
    }
    to {
        background-position: 100% 100%;
    }
}

/* Fireworks Canvas */
.ofw-fireworks-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
}

.ofw-container {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.ofw-header {
    text-align: center;
    margin-bottom: 60px;
    animation: fadeInDown 1s ease-out;
}

.ofw-label {
    display: inline-block;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
    opacity: 0.9;
}

.ofw-title {
    font-size: 48px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 30px 0;
    line-height: 1.2;
}

.ofw-title-highlight {
    color: #ffd700;
}

.ofw-content {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 20px;
    padding: 50px;
    margin-bottom: 50px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    animation: fadeInUp 1s ease-out 0.3s both;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.ofw-description {
    font-size: 18px;
    line-height: 1.8;
    color: #333;
    margin: 0 0 20px 0;
}

.ofw-description:last-child {
    margin-bottom: 0;
}

.ofw-highlight {
    color: #5e72e4;
    font-weight: 600;
}

.ofw-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.ofw-feature-card {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 15px;
    padding: 35px 30px; /* Increased horizontal padding */
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 1s ease-out both;
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex; /* Added */
    flex-direction: column; /* Added */
    align-items: center; /* Added */
    justify-content: flex-start; /* Added */
    min-height: 280px; /* Added for consistent height */
}


.ofw-feature-card:nth-child(1) {
    animation-delay: 0.5s;
}

.ofw-feature-card:nth-child(2) {
    animation-delay: 0.7s;
}

.ofw-feature-card:nth-child(3) {
    animation-delay: 0.9s;
}

.ofw-feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(106, 17, 203, 0.4);
}

.ofw-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 25px; /* Increased bottom margin */
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.4s ease;
    box-shadow: 0 5px 15px rgba(106, 17, 203, 0.4);
    flex-shrink: 0; /* Prevent icon from shrinking */
}

.ofw-feature-card:hover .ofw-icon {
    transform: scale(1.1) rotate(5deg);
}

.ofw-icon svg {
    width: 35px;
    height: 35px;
    stroke: #fff;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.ofw-feature-title {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif; /* Better font stack */
    font-size: 16px; /* Slightly smaller for better fit */
    font-weight: 600;
    color: #2d3748; /* Darker, more readable color */
    margin: 0 0 12px 0; /* Added bottom margin for multiple titles */
    line-height: 1.5; /* Improved line height */
    letter-spacing: 0.3px; /* Added for readability */
}

.ofw-feature-title:last-child {
    margin-bottom: 0;
}


/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .ofw-about-section {
        padding: 60px 15px;
    }
    
    .ofw-title {
        font-size: 36px;
    }
    
    .ofw-content {
        padding: 35px 25px;
    }
    
    .ofw-description {
        font-size: 16px;
    }
    
    .ofw-features {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .ofw-title {
        font-size: 28px;
    }
    
    .ofw-content {
        padding: 25px 20px;
    }
    
    .ofw-feature-card {
        padding: 25px 20px;
    }
    
    .ofw-icon {
        width: 60px;
        height: 60px;
    }
    
    .ofw-icon svg {
        width: 30px;
        height: 30px;
    }
}

.ofw-feature-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px; /* Space between multiple titles */
}

@media (max-width: 768px) {
    .ofw-feature-card {
        padding: 30px 25px;
        min-height: 260px;
    }
    
    .ofw-feature-title {
        font-size: 15px;
    }
}

@media (max-width: 480px) {
    .ofw-feature-card {
        padding: 25px 20px;
        min-height: auto;
    }
    
    .ofw-feature-title {
        font-size: 14px;
        line-height: 1.4;
    }
}
</style>

<section class="ofw-about-section">
    <canvas class="ofw-fireworks-canvas" id="fireworksCanvas"></canvas>
    <div class="ofw-container">
        <!-- Header -->
        <div class="ofw-header">
            <span class="ofw-label">About us</span>
            <h1 class="ofw-title">
                Why Order <span class="ofw-title-highlight">Fireworks</span> Online?
            </h1>
        </div>

        <!-- Content -->
        <div class="ofw-content">
            <p class="ofw-description">
                At <span class="ofw-highlight">Order Fireworks Online</span>, we bring the boom to your doorstep! 
                We're a trusted, top-rated fireworks store based in Griffin, Georgia, offering a massive selection 
                of high-quality products at unbeatable prices.
            </p>
            <p class="ofw-description">
                Our website makes it easy to shop for everything from cakes and artillery to novelties and 
                sparklers – all with no minimum order requirement.
            </p>
        </div>

        <!-- Features -->
        <div class="ofw-features">
            <!-- Feature 1 -->
<div class="ofw-feature-card">
    <div class="ofw-icon">
        <svg viewBox="0 0 24 24">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
            <line x1="12" y1="22.08" x2="12" y2="12"></line>
        </svg>
    </div>
    <div class="ofw-feature-content">
    <h3 class="ofw-feature-title">$99 Shipping on Orders above $1500</h3>
        <h3 class="ofw-feature-title">Free Shipping on Orders above $2000</h3>
        
    </div>
</div>

            <!-- Feature 2 -->
            <div class="ofw-feature-card">
                <div class="ofw-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <h3 class="ofw-feature-title">No Order Minimums</h3>
            </div>

            <!-- Feature 3 -->
            <div class="ofw-feature-card">
                <div class="ofw-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </div>
                <h3 class="ofw-feature-title">Shop Premium Fireworks Brands - World Class, Cutting Edge and Brothers</h3>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const canvas = document.getElementById('fireworksCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    let particles = [];
    let rockets = [];
    
    // Set canvas size
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Particle class for firework explosion
    class Particle {
        constructor(x, y, color) {
            this.x = x;
            this.y = y;
            this.color = color;
            this.velocity = {
                x: (Math.random() - 0.5) * 8,
                y: (Math.random() - 0.5) * 8
            };
            this.alpha = 1;
            this.decay = Math.random() * 0.015 + 0.015;
            this.gravity = 0.08;
            this.size = Math.random() * 3 + 1;
        }
        
        update() {
            this.velocity.y += this.gravity;
            this.x += this.velocity.x;
            this.y += this.velocity.y;
            this.alpha -= this.decay;
        }
        
        draw() {
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
            
            // Glow effect
            ctx.shadowBlur = 15;
            ctx.shadowColor = this.color;
            ctx.fill();
            ctx.restore();
        }
    }
    
    // Rocket class for launching effect
    class Rocket {
        constructor(x, targetY) {
            this.x = x;
            this.y = canvas.height;
            this.targetY = targetY;
            this.velocity = -8;
            this.exploded = false;
            this.color = `hsl(${Math.random() * 360}, 100%, 60%)`;
            this.trail = [];
        }
        
        update() {
            if (!this.exploded) {
                this.trail.push({x: this.x, y: this.y, alpha: 1});
                if (this.trail.length > 10) this.trail.shift();
                
                this.y += this.velocity;
                
                if (this.y <= this.targetY) {
                    this.explode();
                }
            }
        }
        
        draw() {
            if (!this.exploded) {
                // Draw trail
                this.trail.forEach((point, index) => {
                    ctx.save();
                    ctx.globalAlpha = (index / this.trail.length) * point.alpha;
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(point.x, point.y, 2, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                });
                
                // Draw rocket
                ctx.save();
                ctx.fillStyle = this.color;
                ctx.shadowBlur = 10;
                ctx.shadowColor = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, 3, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            }
        }
        
        explode() {
            this.exploded = true;
            const particleCount = Math.random() * 50 + 80;
            
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle(this.x, this.y, this.color));
            }
        }
    }
    
    // Launch firework
    function launchFirework() {
        const x = Math.random() * canvas.width;
        const targetY = Math.random() * (canvas.height * 0.4) + canvas.height * 0.1;
        rockets.push(new Rocket(x, targetY));
    }
    
    // Animation loop
    function animate() {
        ctx.fillStyle = 'rgba(26, 26, 46, 0.1)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Update and draw rockets
        rockets = rockets.filter(rocket => {
            rocket.update();
            rocket.draw();
            return !rocket.exploded;
        });
        
        // Update and draw particles
        particles = particles.filter(particle => {
            particle.update();
            particle.draw();
            return particle.alpha > 0;
        });
        
        requestAnimationFrame(animate);
    }
    
    // Launch fireworks at intervals
    setInterval(() => {
        if (Math.random() > 0.2) {
            launchFirework();
        }
    }, 800);
    
    // Start animation
    animate();
})();
</script>

<?php 
return ob_get_clean();
}
add_shortcode('custom_content', 'custom_slider_shortcode_content');
?>