<?php

function cdm_item_list() {
	$options = array(
		array(
			'descr' => __( 'Product Count' ),
			'key' => 'product_count',
			'type' => 'checkbox',
			'values' => 'true' 
		),
		array(
			'descr' => __( 'Hide Empty Categories' ),
			'key' => 'hide_empty_category',
			'type' => 'checkbox',
			'values' => 'true' 
		),
		array(
			'descr' => __( 'Remove Grouping' ),
			'key' => 'remove_grouping',
			'type' => 'checkbox',
			'values' => 'true' 
		),
		array(
			'descr' => __( 'Order by:' ),
			'key' => 'order_by',
			'type' => 'select',
			'values' => array( 'Name (Default)' => 'name', 'Count' => 'count', 'Category ID' => 'term_id' ),
			'default' => 'default',
		),
		array(
			'descr' => __( 'Order:' ),
			'key' => 'order',
			'type' => 'select',
			'values' => array( 'ASC (Default)' => 'ASC', 'DESC' => 'DESC' ),
			'default' => 'default',
		),
		array(
			'descr' => __( 'Submenu order by:' ),
			'key' => 'submenu_order_by',
			'type' => 'select',
			'values' => array( 'Name (Default)' => 'name', 'Count' => 'count', 'Category ID' => 'term_id' ),
			'default' => 'default',
		),
		array(
			'descr' => __( 'Submenu order:' ),
			'key' => 'submenu_order',
			'type' => 'select',
			'values' => array( 'ASC (Default)' => 'ASC', 'DESC' => 'DESC' ),
			'default' => 'default',
		),
	);
	return $options;
}

function cdm_custom_item( $item_id, $item ) {

	//print_r($item);

    wp_nonce_field( 'custom_menu_meta_nonce', '_custom_menu_meta_nonce_name' );
    $custom_menu_meta = get_post_meta( $item_id, '_custom_menu_meta', true );


	if ( $item->type == 'cdm_menu_c_item' ){

		$options = cdm_item_list();


		$out = '';

		foreach ( $options as $option ) {
			
			$saved_value = ( get_post_meta( $item_id, '_menu_item_' . $option['key'], true ) != false )
			
				? get_post_meta( $item_id, '_menu_item_' . $option['key'], true ): '';


			$option['element_id'] = 'menu-item-' . str_replace( '_', '-', $option['key'] ) . '-' . $item_id;
			$option['key'] = 'menu-item-' . str_replace( '_', '-', $option['key'] ) . '[' . $item->ID . ']';

			$out .= dcm_options_generator( $option, $saved_value );

		}

		echo $out;
		
	}

}
add_action( 'wp_nav_menu_item_custom_fields', 'cdm_custom_item', 10, 2 );


function cdm_item_update( $menu_id, $menu_item_db_id ) {

	$dosya= fopen("hata1.php","w");
	fwrite($dosya, print_r( $_POST,true ));
	fwrite($dosya, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
	fwrite($dosya, print_r( cdm_item_list(),true  ));
	fwrite($dosya, '*******************************************'. '
');

	foreach ( cdm_item_list() as $option ) {

		fwrite($dosya, '_menu_item_' . $option[ 'key' ] . '
');

		if ( isset( $_POST[ 'menu-item-' . str_replace( '_', '-', $option['key'] ) ][ $menu_item_db_id ]  ) ) {

			$sanitized_data = sanitize_text_field( $_POST[ 'menu-item-' . str_replace( '_', '-', $option['key'] ) ][$menu_item_db_id] );
			
			update_post_meta( $menu_item_db_id, '_menu_item_' . $option[ 'key' ], $sanitized_data );

		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_' . $option[ 'key' ] );
		}
	}

}
add_action( 'wp_update_nav_menu_item', 'cdm_item_update', 10, 2 );


function kia_custom_menu_title( $title, $item ) {

    if( is_object( $item ) && isset( $item->ID ) ) {

        $custom_menu_meta = get_post_meta( $item->ID, '_custom_menu_meta', true );

        if ( ! empty( $custom_menu_meta ) ) {
            $title .= ' - ' . $custom_menu_meta;
        }
    }
    return $title;
}
add_filter( 'nav_menu_item_title', 'kia_custom_menu_title', 10, 2 );





?>