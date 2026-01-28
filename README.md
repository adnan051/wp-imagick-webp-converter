# WordPress WebP Converter with Imagick

This script automatically converts uploaded JPEG and PNG images to WebP format using the **Imagick PHP extension**. It includes features for auto-resizing and EXIF orientation fixing.

## Features
- **Auto-Conversion:** Converts uploads to `.webp`.
- **Resize:** Set max-width and max-height via Settings > Media.
- **Orientation Fix:** Uses Imagick to fix rotated mobile uploads.
- **Cleanup:** Option to delete the original file after conversion.

## Installation
1. Ensure the **Imagick PHP Extension** is enabled on your server.
2. Copy the code into your child theme's `functions.php`.
3. Go to **Settings > Media** in WordPress to enable and configure settings.

## Requirements
- PHP 7.4+
- Imagick Extension
