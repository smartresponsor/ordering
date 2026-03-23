# Payment Flows (Stripe primary)

- **Authorize** → **Capture** → **Refund**
- Webhook verification: HMAC-SHA256 over `t.payload`, compare with header `Stripe-Signature: t=..., v1=...` (constant-time compare).
- Canonical status mapping: `succeeded|failed|refunded` → `payment.succeeded|payment.failed|payment.refunded`.
