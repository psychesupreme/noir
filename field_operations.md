# Noir & Bloom: Field Operations & Logistics Manual

This manual details the standard operating procedures (SOPs) for the Noir & Bloom concierge service, ensuring premium quality from farm to vase, logistics optimization, customer satisfaction tracking, and regulatory compliance.

---

## 1. Cold-Chain Procurement & Hub Inventory Management

To preserve the elite grade of our volcanic and highland flowers, strict temperature and hydration protocols are enforced from the moment of harvest.

### Farm-Level Sourcing
- **Naivasha Volcanic Roses**: Sourced from farms situated at 2,000m+ altitude around Lake Naivasha to ensure large heads and vibrant colors.
- **Limuru Lilies & Foliage**: Grown in the cool climates of Limuru, ensuring strong stems and long vase life.
- **Grade Standards**: Only Grade A stems are procurement-eligible (long, sturdy stems, uniform bud size, free from blemishes).

### Cold-Chain Protocol
| Stage | Target Temperature | Humidity | Maximum Duration | Key Actions |
| :--- | :--- | :--- | :--- | :--- |
| **Harvest Transit** | 2°C – 4°C | 85% – 90% | 4 Hours | Stems transported in refrigerated vans immediately after sorting. |
| **Hub Cold Room** | 3.5°C | 90% | 5 Days | Unpacked, stems recut at 45°, and placed in clean buckets. |
| **Atelier Assembly** | 18°C (Max) | Ambient | 2 Hours | Flower arrangement and packaging completed in climate-controlled room. |

### Hydration & Nutrition
- **Chrysal Professional 2**: Applied to all cold room buckets to maintain bud size and prevent premature opening.
- **Chrysal Clear Packet**: Provided with every finished bouquet for customer home care.

### Floral Waste & Spoilage Log (ERP Integration)
All damaged, opened, or wilted stems must be logged in the ERP Wastage module.
- **Triggers**: Stems failing quality control upon arrival or during storage.
- **Accounting**: Wastage must deduct raw material stock automatically to sync inventory levels and calculate exact gross margins.

---

## 2. Atelier Hamper Assembly & Courier Dispatch

Every order is custom-assembled and handled as a high-value luxury shipment.

### Curation Packaging Assembly
1. **Stem Trimming**: Stems are cut to match the selected layout profile (standard, deluxe, or grand).
2. **Accents Application**: 
   - **Glitter**: Applied lightly using specialized adhesive.
   - **Fragrance Mist**: Spritzed 3 times from a 15cm distance on the wrapping (never directly on flower petals).
3. **Wrapping**: Secured with selected Kraft Paper, French Mesh, or Glass Vase container, and tied with the chosen ribbon (e.g., Purple Satin) at the neck.
4. **Greeting Card**: Handwritten calligraphy message exactly matching the client's input (e.g., "happy birthday"), enclosed in a sealed wax-stamped envelope.

### Logistics & Routing
- **Branch Allocation**: Orders are routed to the branch nearest to the delivery address with active product inventory.
- **Courier Selection**: High-end motorcycle couriers equipped with insulated, shock-absorbent delivery boxes.
- **Real-Time Tracking**: Drivers use the driver mobile portal to confirm location, route optimization, and delivery status.

### White-Glove Handover
- **Presentation**: Couriers must wear branding attire and hand over the gift package upright.
- **Proof of Delivery (PoD)**: Couriers must upload a photo of the receipt/package at handover to the driver dashboard to trigger status change to `delivered`.

---

## 3. Customer Feedback & Service Ratings

Post-delivery feedback loops ensure service excellence is maintained.

### Automated Survey Trigger
- Within 30 minutes of delivery confirmation (PoD upload), an automated email/SMS survey is sent to the client.
- **Key Metrics**:
  - **Overall Experience**: 1 to 5 stars.
  - **Freshness Score**: 1 to 5 rating.
  - **Delivery Quality**: 1 to 5 rating.
  - **Open Feedback**: Text input.

### Low-Rating Alert escalation
- Any feedback rating **under 3.5 stars** triggers an immediate Slack/Email alert to the Atelier Manager.
- Compensation options (replacement bouquet, voucher) are automatically generated for customer relations outreach.

---

## 4. KRA eTIMS Tax Compliance (Kenya)

Under Kenya Revenue Authority (KRA) guidelines, all sales invoices must be validated through the electronic Tax Invoice Management System (eTIMS) API in real time.

### API Integration Architecture
- **Trigger**: An order is marked as `paid` or `approved`.
- **Payload Structure**:
  - Customer PIN (if corporate invoice is requested).
  - HS Codes (Harmonized System) mapped to each product line.
  - Unique Transaction Identifier (UTI).
- **Tax Classification**:
  - Stems/Flowers: Exempt / 0% VAT / 16% VAT as per current Finance Act guidelines.
  - Giftings (Wine, Chocolates, Perfume): 16% VAT.
  - Services (Atelier Hand Curation Service): 16% VAT.

### eTIMS HS Mapping
| SKU Prefix | Product Category | HS Code | VAT Rate |
| :--- | :--- | :--- | :--- |
| `NB-STM-` | Fresh Cut Flowers | `0603.11.00` | 16% / 0% |
| `NB-DEC-` | Packaging & Accent | `4819.20.00` | 16% |
| `NB-HMP-` | Wine & Foodstuff | `2204.21.00` | 16% |
| `NB-SRV-` | Professional Service | `9987.14.00` | 16% |

### Error & Offline Fallback
- If the KRA eTIMS server is unreachable, the system queues the transaction for sync.
- A background worker retries synchronization every 10 minutes.
- Once sync succeeds, a tax invoice with the official QR code and KRA Signature is sent to the client.
