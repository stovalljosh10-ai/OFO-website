# OFO Social Media Content Calendar

## Weekly Posting Schedule

| Day | Content Type | Caption Style | Notes |
|-----|-------------|---------------|-------|
| Monday | New product spotlight | HYPE | Video + caption from Claude API |
| Tuesday | "Did you know?" price comparison | VALUE | Graphic showing OFO vs retail price |
| Wednesday | Customer UGC or behind-the-scenes | HOOK | Repost or raw footage |
| Thursday | Weekly deal drop | HYPE | Countdown to Sunday deal end |
| Friday | Weekend show inspiration | VALUE | Show idea combining 3-4 products |
| Saturday | Live shoot video | HOOK | Raw unedited product demo |
| Sunday | Deal reminder + next week preview | VALUE | Urgency + what's coming |

## Hashtag Rotation
- Monday/Wednesday/Friday: Set A
- Tuesday/Thursday: Set B
- Saturday/Sunday: Set C

## Make.com Scenario Flow
1. Trigger: New product published in WooCommerce (webhook)
2. Get product data (name, price, image, shot count, case pack, category)
3. Send to Claude API → generate 3 caption variants
4. Post best caption + video to TikTok via Metricool
5. Post to Instagram Reels via Metricool
6. Post to Facebook via Metricool
7. Log to Google Sheet (product name, post URLs, date)
