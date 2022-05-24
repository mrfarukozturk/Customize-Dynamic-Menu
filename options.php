<?php
function dcm_options_generator( $option, $value ){
	

	$option['name'] = isset( $option['name'] ) ? $option['name'] : '';
	$option['descr'] = isset( $option['descr'] ) ? $option['descr'] : '';
	$option['key'] = isset( $option['key'] ) ? $option['key'] : 'key_no_set';
	$option['type'] = isset( $option['type'] ) ? $option['type'] : '';
	$option['values'] = isset( $option['values'] ) ? $option['values'] : '';


	$out = '';
	switch ( $option['type'] ) {
		case 'textarea':
			$col_width = ( isset( $option['col_width'] ) && !empty( $option['col_width'] ) ) ? $option['col_width'] : 6;
			$out .= '<textarea class="textarea wpb_vc_param_value" name="' . $option['key'] . '" rows="' . $col_width . '">' . ( ( isset( $value ) && $value !== false ) 
				? esc_textarea( $value ) 
				: ( isset( $option['default'] ) 
					? esc_textarea( $option['default'] )  
					: ( isset( $option['values'] ) 
						? esc_textarea( $option['values'] ) 
						: ''
					)
				) 
			) . '</textarea>';
			break;
		case 'checkbox':



				$out .= '<p class="field-link-target description">';
				$out .= '	<label for="' . $option['element_id'] . '">';
				$out .= '		<input type="checkbox" name="' . $option['key'] . '" id="' . $option['element_id'] . '" value="true" '.checked( $value, $option['values'] , false).' />';
				$out .= '		' . $option['descr'] . '<br>';
				$out .= '	</label>';
				$out .= '</p>';



			break;
		case 'select':

			$out .= '<p class="description description-wide">';
			$out .= '	<label for="' . $option['element_id'] . '">';
			$out .= '		' . $option['descr'] . '<br>';
			$out .= '<select class="widefat" name="' . $option['key'] . '" id="' . $option['element_id'] . '">';

			if ( is_array( $option['values'] ) ) {
				//print_r( $option );
				foreach ( $option['values'] as $key => $option_value ) {

					$out .= '<option value="' . $option_value .'" ' . ( ( isset( $value ) && $value !== false ) 
						? ( $option_value == $value 
							? 'selected="selected" ' 
							: ''
						)
						: ( (  isset( $option['default'] ) && ( ( is_array( $option['default'] ) && in_array( $option_value, $option['default'] ) ) || $option_value == $option['default'] ) ) 
							? 'selected="selected" ' 
							: ''
						)
					) . '>' . ( is_string( $key ) ? $key : $option_value ) .'</option>';
				}
			}
			$out .= '</select>';
			$out .= '	</label>';
			$out .= '</p>';

			break;
		case 'radio':
			$col_width = ( isset( $option['col_width'] ) && !empty( $option['col_width'] ) ) ? $option['col_width'] : 6;
			$out .= '<div class="row">';
			if ( is_array( $option['values'] ) ) {
				foreach ( $option['values'] as $key => $value ) {
					$out .= '<div class="mm_radio col-lg-' . $col_width . ' col-md-' . $col_width . ' col-sm-12 col-xs-12">';
					$out .= '<label><input type="radio" class="wpb_vc_param_value" name="' . $option['key'] . '" value="' . $value .'" ' . ( ( isset( $mm_saved_value ) && $mm_saved_value !== false ) 
						? ( $value == $mm_saved_value 
							? 'checked="checked" ' 
							: ''
						)
						: ( ( isset( $option['default'] ) && ( ( is_array( $option['default'] ) && in_array( $value, $option['default'] ) ) || $value == $option['default'] ) ) 
							? 'checked="checked" ' 
							: ''
						)
					) . '/>' . ( is_string( $key ) ? $key : $value ) .'</label>';
					$out .= '</div>';
				}
			}
			$out .= '</div>';
			break;
	}
/*
	if ( $option['type'] != 'collapse_start' && $option['type'] != 'collapse_end' && $option['type'] != 'skin_options_generator' && $option['type'] != 'caption' ) {
		$section = '';
		$section .= '<div class="bootstrap">';
		$section .= '<div id=""  class="option row ">';
		$section .= '<div class="col-xs-12">';
		$section .= '<div class="h_separator">';
		$section .= '</div><!-- class="h_separator" -->';
		$section .= '</div><!-- class="col-xs-12" -->';
		$section .= '<div class="option_header col-md-3 col-sm-12">';
		$section .= '<div class="caption">';
		$section .= $option['name'];
		$section .= '</div><!-- class="caption" -->';
		$section .= '<div class="descr">';
		$section .= $option['descr'];
		$section .= '</div><!-- class="descr" -->';
		$section .= '</div><!-- class="option_header col-3" -->';
		$section .= '<div class="option_field col-md-9 col-sm-12">';
		$section .= $out;
		$section .= '</div><!-- class="option_field col-9" -->';
		$section .= '</div><!-- class="option row " -->';
		$section .= '</div><!-- class="bootstrap" -->';
		$out = $section;
	}
*/




	return $out;


}
?>