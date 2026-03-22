<?php
/**
 * OFO Hero Banner Setup Instructions
 * ====================================
 *
 * Two hero shortcodes are registered in functions.php:
 *
 *   [ofo_hero_main]  — Primary hero: "AMERICA'S BEST PRICE ON FIREWORKS"
 *                       with proof badges + dual CTAs (Shop 500g / Build Pallet)
 *
 *   [ofo_hero_sale]  — Sale hero: "GOING FOR GOLD — 10% OFF SITEWIDE"
 *                       with single CTA (Claim Your Discount)
 *
 * HOW TO ADD TO THE HOMEPAGE:
 * ----------------------------
 * 1. Open Bricks Builder on the homepage template
 * 2. Add a "Shortcode" element as the FIRST element on the page
 * 3. Paste:  [ofo_hero_main]
 * 4. Add another "Shortcode" element directly below it
 * 5. Paste:  [ofo_hero_sale]
 * 6. Save the template
 *
 * OPTIONAL — Add the countdown below the heroes:
 *   [ofo_countdown]
 *
 * RECOMMENDED PAGE ORDER:
 *   1. Announcement Bar (auto-injected via wp_body_open)
 *   2. Header (existing)
 *   3. [ofo_hero_main]
 *   4. [ofo_hero_sale]
 *   5. [ofo_countdown]
 *   6. Rest of homepage content
 *
 * CUSTOMIZATION:
 * - Background images: Replace the CSS gradients in hero.css with
 *   background-image URLs from your media library if desired
 * - Colors: Edit CSS custom properties in hero.css
 * - CTAs: Edit shortcode functions in functions.php
 */
