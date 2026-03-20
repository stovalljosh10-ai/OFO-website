<?php
/**
 * Loyalty Rewards Page Content
 * 
 * Contains all HTML, CSS, and styling for the loyalty rewards page
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get the account loyalty rewards endpoint URL
$points_url = wc_get_account_endpoint_url('loyalty-rewards');
if (!$points_url) {
    // Fallback to my account page
    $points_url = wc_get_page_permalink('myaccount') . 'loyalty-rewards/';
}
?>

<style>
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

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .loyalty-rewards-content {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 80px 20px;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    .loyalty-rewards-content::before {
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

    .loyalty-rewards-content::after {
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
            radial-gradient(1px 1px at 80% 10%, rgba(255, 255, 255, 0.3), transparent);
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

    .loyalty-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .loyalty-header {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeInDown 1s ease-out;
    }

    .loyalty-header .label {
        display: inline-block;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .loyalty-header h1 {
        font-size: 56px;
        font-weight: 900;
        color: #ffffff;
        margin: 0 0 15px 0;
        line-height: 1.2;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .loyalty-header h1 .highlight {
        color: #ffd700;
    }

    .loyalty-header .subtitle {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 400;
    }

    .loyalty-content {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        padding: 60px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        margin-bottom: 40px;
        animation: fadeInUp 1s ease-out 0.2s both;
        backdrop-filter: blur(10px);
    }

    .loyalty-intro {
        font-size: 22px;
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 50px;
        text-align: center;
        line-height: 1.8;
    }

    .loyalty-points-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 50px;
    }

    .loyalty-point-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        animation: fadeInUp 1s ease-out both;
    }

    .loyalty-point-card:nth-child(1) { animation-delay: 0.3s; }
    .loyalty-point-card:nth-child(2) { animation-delay: 0.4s; }
    .loyalty-point-card:nth-child(3) { animation-delay: 0.5s; }
    .loyalty-point-card:nth-child(4) { animation-delay: 0.6s; }

    .loyalty-point-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
        border-color: #ff6b35;
    }

    .loyalty-point-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 28px;
        box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    .loyalty-point-card strong {
        display: block;
        font-size: 18px;
        color: #1f2937;
        font-weight: 700;
        line-height: 1.5;
    }

    .loyalty-example {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(255, 107, 53, 0.05) 100%);
        border: 2px solid rgba(255, 107, 53, 0.3);
        border-radius: 20px;
        padding: 40px;
        margin: 40px 0;
        animation: fadeInUp 1s ease-out 0.7s both;
        position: relative;
        overflow: hidden;
    }

    .loyalty-example::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    .loyalty-example h3 {
        color: #ff6b35;
        font-size: 24px;
        font-weight: 800;
        margin: 0 0 20px 0;
        position: relative;
        z-index: 1;
    }

    .loyalty-example p {
        font-size: 18px;
        color: #1f2937;
        margin: 0;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }

    .loyalty-example strong {
        color: #ff6b35;
        font-weight: 700;
    }

    .loyalty-cta {
        text-align: center;
        margin-top: 60px;
        padding: 50px 40px;
        background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(255, 107, 53, 0.4);
        animation: fadeInUp 1s ease-out 0.8s both;
        position: relative;
        overflow: hidden;
    }

    .loyalty-cta::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .loyalty-cta h2 {
        color: #ffffff;
        font-size: 36px;
        font-weight: 800;
        margin: 0 0 30px 0;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .loyalty-cta-button {
        display: inline-block;
        padding: 20px 60px;
        background: #ffffff;
        color: #ff6b35;
        text-decoration: none;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 1;
    }

    .loyalty-cta-button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
        color: #e55a2b;
        text-decoration: none;
    }

    @media (max-width: 968px) {
        .loyalty-points-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .loyalty-rewards-content {
            padding: 60px 15px;
        }
        
        .loyalty-header h1 {
            font-size: 40px;
        }
        
        .loyalty-header .subtitle {
            font-size: 18px;
        }
        
        .loyalty-content {
            padding: 40px 25px;
        }
        
        .loyalty-intro {
            font-size: 20px;
        }
        
        .loyalty-points-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .loyalty-point-card {
            padding: 25px;
        }
        
        .loyalty-example {
            padding: 30px 20px;
        }
        
        .loyalty-example h3 {
            font-size: 20px;
        }
        
        .loyalty-example p {
            font-size: 16px;
        }
        
        .loyalty-cta {
            padding: 40px 25px;
        }
        
        .loyalty-cta h2 {
            font-size: 28px;
        }
        
        .loyalty-cta-button {
            padding: 16px 40px;
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .loyalty-points-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .loyalty-point-card {
            padding: 20px;
        }
        
        .loyalty-point-icon {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }
</style>

<div class="loyalty-rewards-content">
    <div class="loyalty-container">
        <div class="loyalty-header">
            <span class="label">Loyalty Program</span>
            <h1>Earn <span class="highlight">FREE</span> Fireworks</h1>
            <p class="subtitle">With Every Purchase at Order Fireworks Online</p>
        </div>

        <div class="loyalty-content">
            <p class="loyalty-intro">
                Earn FREE Fireworks with every purchase with Order Fireworks Online
            </p>

            <div class="loyalty-points-grid">
                <div class="loyalty-point-card">
                    <div class="loyalty-point-icon">💰</div>
                    <strong>Every dollar spent earns 1 point</strong>
                </div>
                <div class="loyalty-point-card">
                    <div class="loyalty-point-icon">💵</div>
                    <strong>1 point equals $0.10</strong>
                </div>
                <div class="loyalty-point-card">
                    <div class="loyalty-point-icon">🛒</div>
                    <strong>Redeem points on your next purchase on the Checkout review page</strong>
                </div>
                <div class="loyalty-point-card">
                    <div class="loyalty-point-icon">⏰</div>
                    <strong>Points expire after 15 months</strong>
                </div>
            </div>

            <div class="loyalty-example">
                <h3>Example:</h3>
                <p>
                    If you order <strong>$1500 subtotal</strong> you would earn <strong>1500 points</strong>. 
                    This would put a <strong>$150 credit</strong> on your account for your next purchase.
                </p>
            </div>

            <div class="loyalty-cta">
                <h2>Ready to Check Your Points?</h2>
                <a href="<?php echo esc_url($points_url); ?>" class="loyalty-cta-button">Check Your Points</a>
            </div>
        </div>
    </div>
</div>


