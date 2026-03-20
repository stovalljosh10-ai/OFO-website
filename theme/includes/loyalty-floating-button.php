<?php
/**
 * Floating Loyalty Rewards Button
 * 
 * Displays a floating button on the homepage that links to the loyalty rewards page
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only show on homepage
if (!is_front_page()) {
    return;
}

// Get loyalty rewards page URL
$loyalty_page = get_page_by_path('loyalty-rewards');
$loyalty_url = $loyalty_page ? get_permalink($loyalty_page->ID) : home_url('/loyalty-rewards/');
?>

<style>
    .loyalty-floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        animation: loyaltyPulse 2s infinite;
    }
    
    .loyalty-floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(255, 107, 53, 0.7);
        text-decoration: none;
    }
    
    .loyalty-floating-btn:active {
        transform: scale(0.95);
    }
    
    .loyalty-floating-btn svg {
        width: 35px;
        height: 35px;
        fill: #ffffff;
    }
    
    .loyalty-floating-btn .btn-text {
        position: absolute;
        right: 85px;
        background: #1f2937;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 30px;
        white-space: nowrap;
        font-size: 16px;
        font-weight: 700;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        pointer-events: none;
    }
    
    .loyalty-floating-btn .btn-text::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 50%;
        transform: translateY(-50%);
        border: 8px solid transparent;
        border-left-color: #1f2937;
    }
    
    .loyalty-floating-btn:hover .btn-text {
        opacity: 1;
        visibility: visible;
        right: 90px;
    }
    
    @keyframes loyaltyPulse {
        0%, 100% {
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.5);
        }
        50% {
            box-shadow: 0 8px 35px rgba(255, 107, 53, 0.8), 0 0 0 10px rgba(255, 107, 53, 0.1);
        }
    }
    
    @media (max-width: 768px) {
        .loyalty-floating-btn {
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
        }
        
        .loyalty-floating-btn svg {
            width: 30px;
            height: 30px;
        }
        
        .loyalty-floating-btn .btn-text {
            display: none;
        }
    }
    
    @media (max-width: 480px) {
        .loyalty-floating-btn {
            width: 55px;
            height: 55px;
            bottom: 15px;
            right: 15px;
        }
        
        .loyalty-floating-btn svg {
            width: 28px;
            height: 28px;
        }
    }
</style>

<a href="<?php echo esc_url($loyalty_url); ?>" class="loyalty-floating-btn" aria-label="Loyalty Rewards">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z"/>
        <path d="M19 12L20.09 18.26L27 19L20.09 19.74L19 26L17.91 19.74L11 19L17.91 18.26L19 12Z"/>
    </svg>
    <span class="btn-text">Loyalty Rewards</span>
</a>