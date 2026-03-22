# Make.com Scenario Setup Instructions

## Tools Required
- Make.com account (free tier works)
- Metricool account (free tier — connects TikTok, Instagram, Facebook)
- Claude API key (get from console.anthropic.com)
- Google account (for logging sheet)

## Step 1: WooCommerce Webhook
- In WP Admin → WooCommerce → Settings → Advanced → Webhooks
- Create new webhook:
  - Name: New Product Published
  - Status: Active
  - Topic: Product created
  - Delivery URL: [your Make.com webhook URL — generated in Step 2]
  - Secret: [any random string]

## Step 2: Make.com Scenario
1. Create new scenario
2. Add trigger: Webhooks → Custom webhook
3. Copy the webhook URL → paste into WooCommerce above
4. Add module: HTTP → Make a request
   - URL: https://api.anthropic.com/v1/messages
   - Method: POST
   - Headers:
     - x-api-key: [your Claude API key]
     - anthropic-version: 2023-06-01
     - content-type: application/json
   - Body (raw JSON):
     {
       "model": "claude-sonnet-4-20250514",
       "max_tokens": 500,
       "messages": [{
         "role": "user",
         "content": "You are a social media manager for Order Fireworks Online (OFO) at orderfireworksonline.com. Write 3 caption variants for TikTok/Instagram under 150 characters each with hashtags. Tone: high energy, patriotic, fun.\n\nProduct: {{product_name}}\nPrice: ${{price}} (case of {{case_pack}} units)\nShot count: {{shot_count}}\nCategory: {{category}}\n\nWrite variants:\nHYPE: energy-focused, fire emojis\nVALUE: price/savings focused\nHOOK: curiosity-driven question\n\nFormat as JSON: {\"hype\": \"...\", \"value\": \"...\", \"hook\": \"...\"}"
       }]
     }
5. Add module: Metricool → Schedule post
   - Connect your Metricool account
   - Use the HYPE caption from Claude response
   - Schedule for next available slot
   - Add product image URL from webhook data
6. Add module: Google Sheets → Add row
   - Log: date, product name, caption used, post URL

## Step 3: Test
- Publish a test product in WooCommerce
- Watch Make.com scenario run
- Check Metricool for scheduled post
- Check Google Sheet for log entry

## Notes
- Claude API costs roughly $0.01 per caption generation
- Metricool free tier allows 1 social profile per platform
- Run scenario manually first to verify before setting to auto-run
