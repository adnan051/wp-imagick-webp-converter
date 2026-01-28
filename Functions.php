<?php
//Script to change images in Webp format

/**
 * WebP Image Converter - Child Theme Version
 * Add this to your child theme's functions.php
 * 
 * WARNING: This is not the recommended approach. Consider creating a plugin instead.
 */
/**
 * WebP Image Converter - Fully Debugged Version
 */

if (!defined('ABSPATH')) exit;

if (!function_exists('mbwpc_plugin_init')) {

    add_action('init', 'mbwpc_plugin_init');

    function mbwpc_plugin_init() {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', 'mbwpc_admin_enqueue_styles');
            add_action('admin_notices', 'mbwpc_admin_notices');
        }
        add_action('admin_init', 'mbwpc_register_settings');
        add_filter('wp_handle_upload', 'mbwpc_handle_upload_convert_to_webp');
    }

    function mbwpc_admin_enqueue_styles() {
        wp_enqueue_style('mbwpc-admin-styles', get_stylesheet_directory_uri() . '/css/mbwpc-styles.css', array(), '2.0.0');
    }

    /**
     * MAIN CONVERTER FUNCTION
     */
    function mbwpc_handle_upload_convert_to_webp($upload) {
        // 1. Check if Imagick exists and feature is enabled
        if (!extension_loaded('imagick') || !get_option('mbwpc_convert_to_webp')) {
            return $upload;
        }

        $file_path = $upload['file'];
        $file_type = $upload['type'];

        // 2. Only process JPEG and PNG
        if (in_array($file_type, ['image/jpeg', 'image/png'])) {
            try {
                $image = new \Imagick($file_path);

                // 3. Fix Orientation (EXIF)
                $orientation = $image->getImageOrientation();
                switch ($orientation) {
                    case \Imagick::ORIENTATION_BOTTOMRIGHT: $image->rotateImage('#000', 180); break;
                    case \Imagick::ORIENTATION_RIGHTTOP:    $image->rotateImage('#000', 90);  break;
                    case \Imagick::ORIENTATION_LEFTBOTTOM:  $image->rotateImage('#000', -90); break;
                }
                $image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

                // 4. Resize if larger than settings
                $max_w = (int) get_option('mbwpc_max_width', 1920);
                $max_h = (int) get_option('mbwpc_max_height', 1080);
                $image->resizeImage($max_w, $max_h, \Imagick::FILTER_LANCZOS, 1, true);

                // 5. Convert to WebP
                $image->setImageFormat('webp');
                $image->setCompressionQuality((int) get_option('mbwpc_compression_quality', 80));
                
                $new_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file_path);
                
                // 6. Save and Update WordPress Upload Array
                if ($image->writeImage($new_path)) {
                    if (!get_option('mbwpc_keep_original')) {
                        if (file_exists($file_path)) unlink($file_path);
                        $upload['file'] = $new_path;
                        $upload['url']  = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $upload['url']);
                        $upload['type'] = 'image/webp';
                    }
                }
                
                $image->clear();
                $image->destroy();

            } catch (Exception $e) {
                error_log("WebP Conversion Error: " . $e->getMessage());
            }
        }
        return $upload;
    }

    /**
     * SETTINGS & UI
     */
    function mbwpc_register_settings() {
        register_setting('media', 'mbwpc_convert_to_webp', ['type' => 'boolean', 'default' => false]);
        register_setting('media', 'mbwpc_max_width', ['type' => 'integer', 'default' => 1920]);
        register_setting('media', 'mbwpc_max_height', ['type' => 'integer', 'default' => 1080]);
        register_setting('media', 'mbwpc_compression_quality', ['type' => 'integer', 'default' => 80]);
        register_setting('media', 'mbwpc_keep_original', ['type' => 'boolean', 'default' => false]);

        add_settings_field('mbwpc_convert_to_webp', 'Convert Uploaded Images to WebP', 'mbwpc_field_callback', 'media', 'default');
        add_settings_field('mbwpc_image_settings', 'Image Processing Settings', 'mbwpc_image_settings_callback', 'media', 'default');
    }

    function mbwpc_field_callback() {
        $val = get_option('mbwpc_convert_to_webp');
        echo '<input type="checkbox" name="mbwpc_convert_to_webp" value="1" ' . checked(1, $val, false) . '> Enable auto-conversion.';
    }

    function mbwpc_image_settings_callback() {
        ?>
        <p>Max Width: <input type="number" name="mbwpc_max_width" value="<?php echo esc_attr(get_option('mbwpc_max_width', 1920)); ?>" /></p>
        <p>Max Height: <input type="number" name="mbwpc_max_height" value="<?php echo esc_attr(get_option('mbwpc_max_height', 1080)); ?>" /></p>
        <p>Quality (1-100): <input type="number" name="mbwpc_compression_quality" value="<?php echo esc_attr(get_option('mbwpc_compression_quality', 80)); ?>" /></p>
        <p><input type="checkbox" name="mbwpc_keep_original" value="1" <?php checked(1, get_option('mbwpc_keep_original')); ?>> Keep original files.</p>
        <?php
    }

    function mbwpc_admin_notices() {
        if (!extension_loaded('imagick')) {
            echo "<div class='notice notice-error'><p><strong>Imagick extension is missing!</strong> WebP conversion will not function.</p></div>";
        }
    }
}
