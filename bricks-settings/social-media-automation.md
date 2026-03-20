# OFO Social Media Automation
# Make.com scenario configuration

## Claude API Caption Prompt
Use this exact prompt in Make.com -> Claude API module:

---
You are a social media manager for Order Fireworks Online (OFO), a wholesale
fireworks website at orderfireworksonline.com.

Given a fireworks product, write 3 caption variants for TikTok/Instagram Reels.
Each under 150 characters. Include relevant hashtags. Tone: high energy, patriotic, fun.

Product: {{product_name}}
Price: {{product_price}} (case of {{case_pack}} units)
Shot count: {{shot_count}}
Category: {{category}}

Write 3 variants:
HYPE: [energy-focused, all caps moments, fire emojis]
VALUE: [price/savings focused, emphasize wholesale]
HOOK: [curiosity-driven, question or surprising fact]

Format as JSON: {"hype": "...", "value": "...", "hook": "..."}
---

## Content Calendar
Monday:    New product spotlight (video + hype caption)
Tuesday:   "Did you know?" educational post (price comparison graphic)
Wednesday: Customer UGC repost or behind-the-scenes
Thursday:  Weekly deal drop (countdown to Sunday)
Friday:    "Weekend show inspo" — show idea for the weekend
Saturday:  Live shoot video (raw, unedited product demo)
Sunday:    Deal reminder + next week preview

## Hashtag Sets (rotate weekly)
Set A: #fireworks #4thofjuly #fireworksshow #backyard #USA #pyro #fireworkslovers #OFO
Set B: #fireworks2026 #wholesalefireworks #500gramcakes #buyfireworks #fireworksonline
Set C: #4thofjuly2026 #independenceday #americanfireworks #fireworksdisplay #pyrotechnics

## Make.com Scenario Steps
1. Trigger: New product published in WooCommerce (webhook)
2. Get product data (name, price, image URL, shot count, case pack)
3. Send to Claude API -> generate 3 caption variants
4. Post video + best caption to TikTok via Metricool API
5. Post to Instagram Reels via Metricool API
6. Post to Facebook via Metricool API
7. Log to Google Sheet (product name, post URLs, date)

## WooCommerce Webhook Setup
In WP Admin -> WooCommerce -> Settings -> Advanced -> Webhooks
- Name: New Product Published
- Status: Active
- Topic: Product created
- Delivery URL: [Your Make.com webhook URL]
- Secret: [Generate a random string]
