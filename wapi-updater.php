<?php
/**
 * Plugin Name: wapi-updater
 * Version: 1.0.0
 * Plugin URI: http://www.automattic.com/
 * Description: Updates woo api plugin.
 * Author: brent sessions
 * Author URI: http://www.automattic.com
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: wapi-updater
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Brent Sessions
 * @since 1.0.0
 */

require_once ABSPATH . 'wp-admin/includes/file.php';

register_activation_hook(__FILE__, 'my_activation');

function my_activation() {
    if (! wp_next_scheduled ( 'wapi_updater_job' )) {
    wp_schedule_event(time(), 'fourtimesdaily', 'wapi_updater_job');
    }
}

function wapi_updater() {

$plugin_path= WP_CONTENT_DIR . '/plugins/';
$plugin_dir = $plugin_path . 'woocommerce-rest-api-master';
$plugin_url = 'https://github.com/woocommerce/woocommerce-rest-api/archive/master.zip';

// Download latest version from github
$plugin_zip = download_url( $plugin_url );

// Delete the existing plugin directory
rrmdir( $plugin_dir );

// Unzip the new plugin version
$zip = new ZipArchive;
$res = $zip->open($plugin_zip);
if ($res == 1) {
  $zip->extractTo( $plugin_path );
  $zip->close();
}

// Get setup to run Composer on the plugin
require_once 'vendor/autoload.php';

putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');

chdir( $plugin_dir );

// Run Composer install command
$input = new Symfony\Component\Console\Input\ArrayInput(array('command' => 'install'));
$application = new Composer\Console\Application();
$application->setAutoExit(false);
$application->setCatchExceptions(true);
$application->run($input);
}

add_action( 'wapi_updater_job', 'wapi_updater' );


// Recursively removes a directory and files in it
function rrmdir($src) {
    if (file_exists($src)) {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
?>
