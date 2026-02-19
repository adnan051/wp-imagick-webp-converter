# WP Imagick WebP Converter

![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL--2.0-green)
![Imagick](https://img.shields.io/badge/Requires-Imagick-orange)
![Version](https://img.shields.io/badge/Version-2.0.0-blue)

Automatically convert uploaded JPEG and PNG images to **WebP format** using the **Imagick PHP extension** — with built-in EXIF orientation fixing, smart resizing, and configurable compression quality. All settings are managed directly from **Settings → Media** in the WordPress admin — no code changes required after setup.

---

## ✨ Features

| Feature | Details |
|---|---|
| 🔄 **Auto WebP Conversion** | Converts JPEG/PNG uploads to `.webp` on the fly |
| 📐 **Smart Resizing** | Caps images at configurable max width & height |
| 🧭 **EXIF Orientation Fix** | Corrects rotated mobile/camera photos automatically |
| 🗜️ **Quality Control** | Set compression quality (1–100) to balance size vs. clarity |
| 🗑️ **Original Cleanup** | Optionally delete the original file after conversion |
| ⚙️ **Admin UI** | All settings live under **Settings → Media** — no code editing needed |
| 🛡️ **Safe by Default** | Conversion is disabled until you explicitly enable it |
| 🔔 **Missing Imagick Warning** | Admin notice shown immediately if Imagick is not available |

---

## 📋 Requirements

- **WordPress** 6.0 or higher
- **PHP** 7.4 or higher
- **Imagick PHP Extension** enabled on your server

> **How to check if Imagick is available:**  
> Go to **Tools → Site Health → Info → Server** in your WordPress admin and look for `imagick` in the PHP extensions list. Alternatively, ask your hosting provider.

---

## 🚀 Installation

### Option A — Child Theme (Quick Setup)

1. Copy the contents of `Functions.php` into your **child theme's** `functions.php`.
2. Save the file.
3. Go to **Settings → Media** in WordPress and configure the options.

> ⚠️ **Note:** Adding code to `functions.php` is quick but ties the feature to your theme. If you switch themes, the converter will stop working. Consider Option B for a more robust setup.

### Option B — Standalone Plugin (Recommended)

1. Create a new folder: `wp-content/plugins/webp-converter/`
2. Inside it, create `webp-converter.php` with this header, then paste the code below it:

```php
<?php
/**
 * Plugin Name: WP Imagick WebP Converter
 * Description: Converts uploaded images to WebP using Imagick.
 * Version:     2.0.0
 * Author:      Your Name
 * License:     GPL-2.0+
 */
```

3. Activate the plugin from **Plugins → Installed Plugins**.
4. Go to **Settings → Media** to configure.

---

## ⚙️ Configuration

All settings are available under **Settings → Media**:

| Setting | Default | Description |
|---|---|---|
| Enable Conversion | `Off` | Master switch — enables WebP conversion on upload |
| Max Width | `1920 px` | Images wider than this are resized down proportionally |
| Max Height | `1080 px` | Images taller than this are resized down proportionally |
| Quality | `80` | WebP compression quality (1 = smallest, 100 = best quality) |
| Keep Original | `Off` | When off, the original JPEG/PNG is deleted after conversion |

---

## 🔄 How It Works

```
User uploads JPEG/PNG
        ↓
Imagick loads the file
        ↓
EXIF orientation is read & corrected
        ↓
Image resized to fit max width/height (aspect ratio preserved)
        ↓
Format set to WebP, compression quality applied
        ↓
New .webp file written to disk
        ↓
(Optional) Original file deleted
        ↓
WordPress Media Library uses the .webp URL
```

---

## ❓ FAQ

**Q: Will this convert my existing images in the Media Library?**  
A: No. This only applies to **newly uploaded** images. For bulk conversion of existing images, a separate migration script would be needed.

**Q: What happens if Imagick is not installed?**  
A: An admin notice will appear in your dashboard and conversion will be silently skipped — no errors shown to visitors.

**Q: Is WebP supported by all browsers?**  
A: Yes — WebP is supported by all modern browsers including Chrome, Firefox, Safari (14+), and Edge. Global support is above 95%.

**Q: Can I keep the original JPEG/PNG alongside the WebP?**  
A: Yes. Enable **Keep original files** in **Settings → Media** and both files will be stored.

**Q: What image types are supported?**  
A: JPEG (`.jpg`, `.jpeg`) and PNG (`.png`). GIF and SVG are not converted.

**Q: Will this slow down uploads?**  
A: Very slightly, as conversion happens server-side. On typical hosting, the overhead is under 1 second per image.

---

## 🔐 Security

- Input values are cast to `int` before use — no injection risk.
- File operations are guarded with `file_exists()` checks.
- Conversion errors are caught in a `try/catch` and logged to `debug.log` — never exposed to visitors.
- The master switch must be explicitly enabled in admin — off by default.

---

## 📁 File Structure

```
wp-imagick-webp-converter/
├── Functions.php     ← Main converter & settings code
└── README.md
```

> If using as a standalone plugin, you may also want to add:
> ```
> ├── css/
> │   └── mbwpc-styles.css    ← Optional admin styles (enqueued automatically)
> └── webp-converter.php      ← Plugin entry point with plugin header
> ```

---

## 🤝 Contributing

Pull requests and issues are welcome!

1. Fork this repository
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Commit your changes: `git commit -m "Add: my feature"`
4. Push: `git push origin feature/my-feature`
5. Open a Pull Request

---

## 📄 License

Licensed under the [GPL-2.0 License](https://www.gnu.org/licenses/gpl-2.0.html) — the same license as WordPress.

---

## 👤 Author

**Adnan** · [github.com/adnan051](https://github.com/adnan051)
