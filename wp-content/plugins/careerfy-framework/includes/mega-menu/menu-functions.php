<?php

if (!class_exists('careerfy_mega_custom_menu')) {

    class careerfy_mega_custom_menu {
        /* --------------------------------------------*
         * Constructor
         * -------------------------------------------- */

        /**
         * Initializes the plugin by setting localization, 
         * filters, and administration functions.
         */
        function __construct() {
            // add custom menu fields to menu
            add_filter('wp_setup_nav_menu_item', array($this, 'careerfy_mega_add_custom_nav_fields'));
            // save menu custom fields
            add_action('wp_update_nav_menu_item', array($this, 'careerfy_mega_update_custom_nav_fields'), 10, 3);
            // edit menu walker
            add_filter('wp_edit_nav_menu_walker', array($this, 'careerfy_mega_edit_walker'), 10, 2);
        }

        /**
         * Add custom fields to $item nav object
         * in order to be used in custom Walker
         * @access      public
         * @return      void
         */
        function careerfy_mega_add_custom_nav_fields($menu_item) {
            $menu_item->megamenu = get_post_meta($menu_item->ID, '_menu_item_megamenu', true);
            $menu_item->view = get_post_meta($menu_item->ID, '_menu_item_view', true);
            $menu_item->video = get_post_meta($menu_item->ID, '_menu_item_video', true);
            $menu_item->image_title = get_post_meta($menu_item->ID, '_menu_item_image_title', true);
            $menu_item->image_paragragh = get_post_meta($menu_item->ID, '_menu_item_image_paragragh', true);
            $menu_item->image_title_2 = get_post_meta($menu_item->ID, '_menu_item_image_title_2', true);
            $menu_item->image_img = get_post_meta($menu_item->ID, '_menu_item_image_img', true);
            $menu_item->visifor = get_post_meta($menu_item->ID, '_menu_item_visifor', true);
            $menu_item = apply_filters('careerfy_mega_add_custom_nav_fields_filtr', $menu_item);
            return $menu_item;
        }

        /**
         * Save menu custom fields
         * @access      public
         * @return      void
         */
        function careerfy_mega_update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {
            // Check if element is properly sent
            $megamenu_value = 'off';
            $view_value = 'image-text';

            if (isset($_POST['menu-item-megamenu'][$menu_item_db_id])) {
                $megamenu_value = $_POST['menu-item-megamenu'][$menu_item_db_id];
            } else {
                $megamenu_value = 'off';
            }

            if (isset($_POST['menu-item-view'][$menu_item_db_id])) {
                $view_value = $_POST['menu-item-view'][$menu_item_db_id];
            } else {
                $view_value = 'image-text';
            }

            if (isset($_POST['menu-item-video'][$menu_item_db_id])) {
                $video_value = $_POST['menu-item-video'][$menu_item_db_id];
            } else {
                $video_value = '';
            }

            if (isset($_POST['menu-item-image-title'][$menu_item_db_id])) {
                $image_title_value = $_POST['menu-item-image-title'][$menu_item_db_id];
            } else {
                $image_title_value = '';
            }

            if (isset($_POST['menu-item-image-paragragh'][$menu_item_db_id])) {
                $image_title_para_value = $_POST['menu-item-image-paragragh'][$menu_item_db_id];
            } else {
                $image_title_para_value = '';
            }

            if (isset($_POST['menu-item-image-title-2'][$menu_item_db_id])) {
                $image_title_2_value = $_POST['menu-item-image-title-2'][$menu_item_db_id];
            } else {
                $image_title_2_value = '';
            }

            if (isset($_POST['menu-item-image-img'][$menu_item_db_id])) {
                $image_img_value = $_POST['menu-item-image-img'][$menu_item_db_id];
            } else {
                $image_img_value = '';
            }

            if (isset($_POST['menu-item-visifor'][$menu_item_db_id])) {
                $menu_item_visifor = $_POST['menu-item-visifor'][$menu_item_db_id];
            } else {
                $menu_item_visifor = 'all';
            }

            update_post_meta($menu_item_db_id, '_menu_item_megamenu', sanitize_text_field($megamenu_value));
            update_post_meta($menu_item_db_id, '_menu_item_view', sanitize_text_field($view_value));
            update_post_meta($menu_item_db_id, '_menu_item_video', $video_value);
            update_post_meta($menu_item_db_id, '_menu_item_image_title', $image_title_value);
            update_post_meta($menu_item_db_id, '_menu_item_image_paragragh', $image_title_para_value);
            update_post_meta($menu_item_db_id, '_menu_item_image_title_2', $image_title_2_value);
            update_post_meta($menu_item_db_id, '_menu_item_image_img', $image_img_value);
            
            update_post_meta($menu_item_db_id, '_menu_item_visifor', $menu_item_visifor);
    
            do_action('careerfy_mega_menu_items_save', $menu_item_db_id);
        }

        /**
         * Define new Walker edit
         * @access      public
         * @return      void
         */
        function careerfy_mega_edit_walker($walker, $menu_id) {
            return 'Walker_Nav_Menu_Edit_Custom';
        }

    }

}

// instantiate plugin's class
$careerfy_mega_custom_menu = new careerfy_mega_custom_menu();
