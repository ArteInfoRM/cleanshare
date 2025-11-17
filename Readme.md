# 📦 CleanShare – Simple & Modern URL Sharing Button for PrestaShop

![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)  
![Built for PrestaShop](https://img.shields.io/badge/Built%20for-PrestaShop-DF0067?logo=prestashop&logoColor=white)

CleanShare is a lightweight, modern, and fully responsive sharing module for PrestaShop.  
It allows customers to quickly share product URLs using either the native sharing interface of their device (Android/iOS/Windows) or a clean copy-to-clipboard fallback.

You can display the share button inline on product pages, as a floating button, or both.

---

## 🚀 Features

### ✔ Floating Share Button
A customizable floating button that stays visible on product pages. Options include:
- Left / Centered / Right position
- Bottom offset in pixels
- Custom z-index
- Background and text colors (normal + hover)

### ✔ Inline Share Button
Insert a share button directly into product details using:
- `displayProductAdditionalInfo`
- `showProductCustomized`
- or disable it entirely

### ✔ Web Share API + Fallback
- **Mobile / Windows:** Uses the native share popup when available
- **Desktop:** Copies the clean URL to the clipboard
- **Toast notification** confirms the action

### ✔ Clean & Lightweight
- No external libraries
- Minimal CSS/JS footprint
- No overrides
- Fast and safe

### ✔ Full Back Office Integration
- Modern configuration page
- Input validation & color sanitization
- Custom hints and descriptions for each option
- Translation-ready (EN, IT included)

---

## 🛡️ Technical Highlights

- PrestaShop 1.7, 8.x and 9 compatible
- Works with all themes (Classic, Warehouse, Transformer, Panda…)
- Fully compliant with the PrestaShop validator
- Safe output escaping and clipboard logic
- Supports multiple share buttons on the same page

---

## 📁 Installation

1. Download the ZIP file
2. Upload it through *Modules > Module Manager > Upload a module*
3. Configure it from the module settings panel
4. Enable inline and/or floating button as desired

No overrides, database changes, or core modifications.

---

## 🎯 Theme Integration (showProductCustomized Hook)

CleanShare can render the inline share button in two hooks:

- `displayProductAdditionalInfo` (default)
- `showProductCustomized`

Some themes do not implement `showProductCustomized` by default.  
If you want to use this hook, verify that it exists in your product template.

### 1. Locate your product template

Common paths are:

- `themes/your-theme/templates/catalog/product.tpl`
- or a partial such as:  
  `themes/your-theme/templates/catalog/_partials/product-additional-info.tpl`

### 2. Insert the hook call manually

Add this line where you want the inline share button to appear:

```smarty
{hook h='showProductCustomized' product=$product}
```
## 🌍 Translations

- English (en)  
- Italian (it)

Additional translations can be added via:  
**International → Translations → Installed modules**

---

## 📝 Changelog

See the complete changelog here:  
**[CHANGELOG.md](CHANGELOG.md)**

---

## 📄 License

Released under the **MIT License**.  
You are free to use, modify, and distribute this module.

---

## 🤝 Author

Developed by **Tecnoacquisti.com® – Arte e Informatica**  
https://www.tecnoacquisti.com
