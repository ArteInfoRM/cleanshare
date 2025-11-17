# Changelog
All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-10-17
### Added
- Initial release of the **CleanShare** module for PrestaShop.
- Provides integration and cleanup features for shared content.

## [1.0.1] - 2025-11-16
### Added
- Option to enable a floating share button across all pages.
- Configurable button **position** and **color**.
- Basic configuration options for enabling/disabling sharing features.

### Improved
- Better i18n support for UI messages.

---

## [1.0.2] - 2025-11-17
### Improved
- Refactored front-end JavaScript to support **multiple share buttons** on the same page (inline + floating) without conflicts.
- Introduced new CSS class-based structure (`.cleanshare-btn`, `.cleanshare-toast`, `.cleanshare-wrapper`) replacing the previous ID-based logic.
- Enhanced toast handling with per-button scope and safer fallback copy behavior.
- Refined Web Share API usage to prevent Android/WhatsApp from adding unwanted prefix text (e.g. "Condividi").

### Changed
- Updated `cleanshare_button.tpl` and `cleanshare_float.tpl` to support the new class-based share button system.
- Cleaned and reorganized `cleanshare.js` for improved compatibility across Windows, macOS, Android and fallback support on Linux systems.

### Fixed
- Resolved conflict where enabling both inline and floating buttons caused the inline button to stop working.
- Fixed issue where Android shared messages included undesired prefix text.
- Ensured consistent share behavior across various OS/browser combinations.

