# Atelier Field Operations & Courier Logistics Manual

## 1. Atelier Hand Curation Pricing Model

Noir & Bloom operates a tiered hand-curation assembly pricing structure:

- **Base / Small Curation**: KES 150
- **Medium Curation**: KES 350
- **Grand Luxury Curation**: KES 750

### Add-on Accessory Pricing (KES)
- **Calligraphy Greeting Card**: KES 200
- **Handcrafted Glass Vase**: KES 1,200
- **Premium Satin Ribbon**: KES 150

---

## 2. Mobile Courier Proof of Delivery (PoD) Flow

1. Access route `/courier/orders/{order}` on mobile device.
2. Confirm recipient identity and delivery address.
3. Capture Proof of Delivery photo upload.
4. Input recipient signature or courier handover notes.
5. Submit handover form to mark order status as `delivered`.

---

## 3. Spoilage & Wastage Logging

When stems or arrangements suffer wilting or transit damage:
1. Log wastage via Admin Inventory Portal.
2. Model event `WastageLog::saved` automatically triggers `StorefrontCacheService::flush()` to maintain accurate live inventory across the storefront catalog.
