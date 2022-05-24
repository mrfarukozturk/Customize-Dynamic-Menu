<?php

/**
 **
 *asdasd
 * @param $object örnek menü elemanı modeli.
 * @param $item_metas Menu elemanı ayarları
 * @param $menu_order otomatik eklenecek elemanların sıralaması
 */

function cdm_get_sub_category( $object, $item_meta, $menu_order, $parent_cat_id, $menu_item_parent_id, $sub_cat_arg = null){


    //*Kategori sorgu parametreleri
    $get_cat_args = array();
    $get_cat_args[ "parent" ] = $parent_cat_id;
    $get_cat_args[ "taxonomy" ] = 'product_cat';


    $get_cat_args[ "hide_empty" ] = ( key_exists( '_menu_item_hide_empty_category', $item_meta) and $item_meta[ '_menu_item_hide_empty_category' ][ 0 ] == 'true' )? true : false ;



    if ( $sub_cat_arg == null ) {
        $get_cat_args[ "order" ] = ( key_exists( '_menu_item_submenu_order', $item_meta) and $item_meta[ '_menu_item_submenu_order' ][ 0 ] == 'ASC' )? 'ASC' : 'DESC' ;
        $get_cat_args[ "orderby" ] = ( key_exists( '_menu_item_submenu_order_by', $item_meta) )? $item_meta[ '_menu_item_submenu_order_by' ][ 0 ] : 'name';
        //echo "aaaaaaaaaaaaaa";

        //print_r($item_meta);
        //print_r($get_cat_args);
    }else {
        $get_cat_args = array_merge( $get_cat_args, $sub_cat_arg);
    }

    $get_categories = get_categories( $get_cat_args );
    //print_r($get_categories);
    if ( count( $get_categories ) > 0 ){

        $item_id = $menu_item_parent_id . $parent_cat_id;

        $menu_order++;
        $new_items = array();

        $total_pro_count = 0;

        $id_pre = 'c';
        foreach ($get_categories as $cat_item => $cat_object) {

            $item = clone $object;
            $item->ID = $id_pre . $cat_object->term_id; //* Önemli
            $item->post_name = $cat_object->term_id;
            $item->post_title = $cat_object->name;
            $item->db_id =$id_pre . $cat_object->term_id; //* Önemli

            $item->menu_item_parent = $menu_item_parent_id; //* Önemli

            $item->menu_order = $menu_order;
            $item->object = 'category';
            $item->object_id = $cat_object->term_id;
            $item->type = 'taxonomy';
            $item->count = $cat_object->count;

            $item->url = esc_url( get_category_link( $cat_object->term_id ) );

            if ( ( key_exists( '_menu_item_product_count', $item_meta) and $item_meta[ '_menu_item_product_count' ][ 0 ] = 'true' ) ){
                $item->title = $cat_object->name. ' (' . $cat_object->count . ')';    
            }else {
                $item->title = $cat_object->name;    
            }

            $item->classes = Array( 
                '', 
                'menu-item', 
                'menu-item-type-taxonomy', 
                'menu-item-object-category',
            );

            $menu_order++;
            $items_subs = cdm_get_sub_category( $object, $item_meta, $menu_order, $cat_object->term_id, $id_pre . $cat_object->term_id);

            if ( count(  $items_subs[ 'items' ] ) > 0 ) {

                $item->classes[] = 'menu-item-has-children';
                $new_items[] = $item ;
                $new_items = array_merge( $new_items, $items_subs[ 'items' ] );
                $item->total_count =  $items_subs[ 'pro_count' ] + $cat_object->count;
                $total_pro_count = $total_pro_count + $cat_object->count; //*parent Pro Count

            }else {
                $item->total_count = $cat_object->count;
                $total_pro_count = $total_pro_count + $cat_object->count; //*parent Pro Count
                $new_items[] = $item ;
            }

        }

        return Array( 'items' => $new_items, 'pro_count' => $total_pro_count );

    }else {
        return Array( 'items' => array(), 'pro_count' => 0 );
        
    }

}


function cdm_frontend( $items, $args ){

    $new_items;
    $menu_order = 1;
    foreach ( $items as $item => $object ) {

        if ( $object->type == 'cdm_menu_c_item' ) {

            $sample_object = clone $object;
            $item_metas = get_post_meta( $object->ID );

            if ( ( key_exists( '_menu_item_remove_grouping', $item_metas) and $item_metas[ '_menu_item_remove_grouping' ][ 0 ] = 'true' ) ){
                $menu_item_parent_id = $object->menu_item_parent; 
            }else {
                $new_items[] = $object;
                $menu_item_parent_id = $object->ID;  
            }

            $get_cat_argsa[ "order" ] = ( key_exists( '_menu_item_order', $item_metas) and $item_metas[ '_menu_item_order' ][ 0 ] == 'ASC' )? 'ASC' : 'DESC' ;
            $get_cat_argsa[ "orderby" ] = ( key_exists( '_menu_item_order_by', $item_metas) )? $item_metas[ '_menu_item_order_by' ][ 0 ] : 'name';

            $new_items_subs = cdm_get_sub_category( $sample_object, $item_metas, $menu_order, 0, $menu_item_parent_id, $get_cat_argsa);

            if ( count( $new_items_subs[ 'items' ] ) > 0 ){
                $object->classes[] = 'menu-item-has-children';
            }

            $new_items = array_merge( $new_items, $new_items_subs[ 'items' ]);

        }else {
            $object->menu_order = $menu_order;
            $new_items[] = $object;

            $menu_order++;
        }
    }
    //print_r($new_items);
    return $new_items;
    
}
add_filter( 'wp_nav_menu_objects', 'cdm_frontend', 10, 2 );










?>