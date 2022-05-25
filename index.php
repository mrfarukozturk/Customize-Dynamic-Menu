<?php
/*
Plugin Name: Customize Dynamic Menu
Plugin URI: 
Description: 
Version: 1.0
Author: Faruk ÖZTÜRK
Author URI: 
License: GNU
*/

if ( ! defined( 'ABSPATH' ) ) exit;
class Customize_Dynamic_Menu{

        public function __construct() {
            add_action( 'admin_head-nav-menus.php', array( $this, 'CDM_add_nav_menu_meta_box') );
        }

        public function CDM_add_nav_menu_meta_box() {
            add_meta_box( 'cdm_nav_links', 'Customize Dynamic Menu', array( $this, 'cdm_nav_menu_links' ), 'nav-menus', 'side', 'low' );
        }
        public function cdm_nav_menu_links() {
    
            ?>
        <div id="termtype_cdm" class="posttypediv">
            <div id="tabs-panel-termtype-cdm" class="tabs-panel tabs-panel-active">
                <ul id="termtype-cdm-checklist" class="categorychecklist form-no-clear">
                    <li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="0" />
                            Customize Dinamic Product Category Menu
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom" />
                        <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="cdpcm" />
                        <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#" />
                    </li>
                    <li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="0" />
                            Customize Dinamic Product Tag Menu
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom" />
                        <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="cdptm" />
                        <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="#" />
                    </li>
                </ul>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
                    <button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'woocommerce' ); ?>" name="add-post-type-menu-item" id="submit-termtype_cdm"><?php esc_html_e( 'Add to menu', 'woocommerce' ); ?></button>
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
            <?php
        }

}
new Customize_Dynamic_Menu();

include_once( 'custom_menu_field_hook.php' );
include_once( 'options.php' );
include_once( 'cdm_frontend.php' );


add_action( 'wp_update_nav_menu_item', function( $menu_id, $menu_item_id, $args ) {

    if ( $args['menu-item-title'] == 'cdpcm' ){

        update_post_meta($menu_item_id, '_menu_item_type', 'cdm_menu_c_item' );
        update_post_meta($menu_item_id, '_menu_item_object', 'cdm_menu_c_item' );
		$post['ID']          = $menu_item_id;
		$post['post_title'] = 'Customize Dinamic Product Category Menu';
		wp_update_post( $post );
    }

    if ( $args['menu-item-title'] == 'cdptm' ){

        update_post_meta($menu_item_id, '_menu_item_type', 'cdm_menu_t_item' );
        update_post_meta($menu_item_id, '_menu_item_object', 'cdm_menu_t_item' );
		$post['ID']          = $menu_item_id;
		$post['post_title'] = 'Customize Dinamic Product Tag Menu';
		wp_update_post( $post );
    }

    return $menu_id;
}, 1, 3 );



?>