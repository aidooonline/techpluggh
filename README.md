# TechPlug GH - WordPress Theme (Handover)

Custom WooCommerce theme. Tagline: **Plug Into Quality**. Black + electric green, Tailwind-powered. **All media is Customizer-driven or pulled from WooCommerce/featured images. Nothing is hardcoded**, so you can swap every image from the dashboard later.

Repo: `https://github.com/aidooonline/techpluggh.git`

---

## 1. Requirements
- WordPress 6.2+
- WooCommerce 7.0+
- PHP 7.4+

## 2. Install
1. Zip the `techpluggh` folder (or use the provided `techpluggh.zip`).
2. WordPress admin > Appearance > Themes > Add New > Upload Theme > activate.
3. Install and activate **WooCommerce**, run its setup wizard.
4. **WooCommerce > Settings > General:** set Currency to **Ghana cedi (GHS)**.

## 3. Set up content
**Menus:** Appearance > Menus > create a menu, assign to **Primary Menu** (and optionally Footer Menu). If you skip this, a sensible default menu shows automatically.

**Pages to create** (Pages > Add New). Slugs must match so footer links work:
- Front page: set a static homepage under Settings > Reading (the theme's `front-page.php` renders the full homepage automatically on whatever page is set as Front Page, or on the site root).
- `Deals` -> page template **Deals & Offers** -> slug `deals`
- `How to Order` -> page template **How to Order** -> slug `how-to-order`
- `About` -> slug `about`
- `Contact` -> slug `contact`
- `Warranty Policy` -> slug `warranty-policy`
- `Return Policy` -> slug `return-policy`
- `Delivery Policy` -> slug `delivery-policy`
- `Privacy Policy` -> slug `privacy-policy`
- `Terms & Conditions` -> slug `terms`

(Policy text drafts are in `techpluggh-POLICIES.md`.)

**Product categories** (Products > Categories). Create these and set a **category image** on each (this image is what shows on the homepage category tiles, fully editable):
- UK Used Laptops
- Business Laptops
- Student Laptops
- MacBooks
- HP Laptops
- Dell Laptops
- Lenovo Laptops
- Laptop Accessories

## 4. Customizer (Appearance > Customize > TechPlug GH Settings)
Everything below is editable here, no code needed:
- **Contact & Social:** phone, WhatsApp number (intl format e.g. `233XXXXXXXXX`), email, location, pickup note, Facebook/Instagram/TikTok/X URLs.
- **Homepage Hero:** eyebrow, headline, subtext, and **hero image**.
- **Promo Banner:** on/off, promo bar text, and **deals section image**.
- **Logo:** Customize > Site Identity > Logo. Until you upload one, a clean "TechPlugGH" text wordmark shows.

> The WhatsApp number drives the floating WhatsApp button, the "Order on WhatsApp" button on each product, and the footer link. Leave it empty and those simply do not appear.

## 5. Import products
1. **WooCommerce > Products > Import.**
2. Upload `techpluggh-products-import.csv` (UTF-8 with BOM, standard WooCommerce format).
3. Map columns (they match WooCommerce defaults). Run the import.
4. Result: **31 products, 186 units in stock, 11 marked Featured** (the featured ones populate the homepage "Featured laptops" row). Prices are in GHS exactly as your inventory sheet.
5. **Images column is intentionally blank** - add each product's real photo via the product's Featured Image / Gallery (your rule: you select the images).

## 6. Media = never hardcoded (how to change images later)
| Slot | Where to change it |
|------|--------------------|
| Logo | Customize > Site Identity |
| Hero image | Customize > TechPlug GH > Homepage Hero |
| Deals image | Customize > TechPlug GH > Promo Banner |
| Category tiles | Products > Categories > (each) > Thumbnail |
| Product photos | Each product > Featured image + Gallery |
| Blog images | Each post > Featured image |

If a slot is empty, a branded placeholder shows so nothing ever looks broken.

## 7. Rebuilding CSS (only if you edit styles/markup)
The compiled stylesheet is `assets/css/main.css` (already built). Source is `assets/css/tailwind.css` + `tailwind.config.js`.
```bash
npm install        # first time
npm run build      # rebuild minified main.css
npm run dev        # watch mode while developing
```

## 8. Structure
```
techpluggh/
  style.css              theme header
  functions.php          setup, enqueues, menus, WooCommerce support
  front-page.php         homepage (assembles the sections below)
  header.php / footer.php
  index.php page.php single.php archive.php 404.php searchform.php
  inc/
    customizer.php        all editable settings + media
    template-tags.php     helpers (logo, WhatsApp, image fallbacks, nav walker)
    woocommerce.php       layout, columns, WhatsApp order button, tweaks
  template-parts/home/    hero, marquee, categories, featured, why, deals, how, cta
  woocommerce/
    content-product.php   product card override
  page-templates/
    page-how-to-order.php
    page-deals.php
  assets/css|js|images
```

## 9. Deploy (your usual SSH/cPanel flow)
Upload the theme folder to `wp-content/themes/techpluggh/` (or install the zip from admin). Push source to the GitHub repo as you prefer.

---
*Built for TechPlug GH. Brand: black + electric green, fonts Sora / Manrope / DM Mono.*
