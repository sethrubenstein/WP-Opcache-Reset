<?php
/*
Plugin Name: WP Opcache Reset
Plugin URI: https://github.com/sethrubenstein/WP-Opcache-Reset
Description: Add's a reset OPCache menu item to WP Admin Bar.
Version: 1.0
Author: Seth Rubenstein
Author URI: http://sethrubenstein.info
*/

class WPOpcacheReset {

    public function __construct() {
        add_action('admin_bar_menu', array( $this, 'menu_item' ), 100);
        add_action( 'wp_ajax_reset_call', array( $this, 'reset_call' ) );
        add_action('admin_head', array( $this, 'script' ));
    }

    public function reset_call() {
        if( isset( $_POST['opcache_reset'] ) ) {
            opcache_reset();
            error_log('OP Cache Reset call made');
        }
        die();
    }

    public function script() {
        ?>
        <script>
        jQuery(document).ready(function(){
            function resetOpCache() {
                jQuery.post(ajaxurl, {
                    action:         'wp_op_rst_call',
                    opcache_reset:	true
                });
            }
            jQuery('#wp-admin-bar-reset-opcache-menu').click(function(){
                console.log("Clearing OP Cache");
                resetOpCache();
                return false;
            });
        });
        </script>
        <?php
    }

    public function menu_item($admin_bar){
        $admin_bar->add_menu(
            array(
                'id'    => 'reset-opcache-menu',
                'title' => 'Reset OPCache',
                'href'  => '#',
                'meta'  => array('title' => __('Reset OPCache'),),
            )
        );
    }

}

$WPOpcacheReset = new WPOpcacheReset();
