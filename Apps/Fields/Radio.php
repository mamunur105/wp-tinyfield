<?php
/**
 * Field displayed by this function.
 *
 * @package    Tinyfield Metabox
 * @subpackage Tinyfield_Metaboxes
 */

namespace Tiny\Init\Fields;

use Tiny\Init\Abstracts\GetFields;
use Tiny\Init\Traits\Singleton;
/**
 * Display Input.
 */
class Radio extends GetFields {
	use Singleton;
	/**
	 * Create instance
	 *
	 * @param array $field is an array value.
	 * @return object
	 */
	public static function init( $field ) {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		$defaults              = array(
			'options' => array(),
		);
		$field                 = wp_parse_args( $field, $defaults );
		self::$instance->field = $field;
		return self::$instance;
	}

	/**
	 * Return input field.
	 *
	 * @return mixed
	 */
	public function get_field() {
		if ( $this->field && is_array( $this->field ) ) {
			$id        = sanitize_text_field( $this->field['id'] );
			$type      = sanitize_text_field( $this->field['type'] );
			$class     = sanitize_text_field( $this->field['class'] );
			$title     = sanitize_text_field( $this->field['title'] );
			$options   = array_map( 'esc_attr', $this->field['options'] );
			$desc      = sanitize_text_field( $this->field['desc'] );
			$subtitle  = sanitize_text_field( $this->field['subtitle'] );
			$value     = isset( $this->field['prev_value'][ $id ] ) ? $this->field['prev_value'][ $id ] : array();
			$condition = $this->get_conditional_rules( $this->field['condition'] );
			$attr      = '';
			if ( $condition ) {
				$attr .= htmlspecialchars( $condition );
			}

			?>
			<div id="field-<?php echo esc_attr( $id ); ?>" class="fields-wrapper <?php echo esc_attr( $class ); ?>" data-conditional-rules="<?php echo esc_attr( $attr ); ?>" >
				<div class="label col">
					<label><?php echo esc_html( $title ); ?> </label>
					<?php if ( ! empty( $subtitle ) ) { ?>
						<p> <?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>
				</div>
				<div class="checkboxes-wrapper col d-flex flex-wrap">
					<?php
					if ( ! empty( $options ) ) {
						foreach ( $options as $key => $option ) {
							$checked  = $key === $value ? 'checked' : '';
							$field_id = $id . '_' . $key;

							?>
							<div class="checkbox-wraper">
								<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $field_id ); ?>" type="<?php echo esc_attr( $type ); ?>" class="field" value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $checked ); ?> >
								<label for="<?php echo esc_attr( $field_id ); ?>"> <?php echo esc_attr( $option ); ?></label>
							</div>
							<?php
						}
					}
					if ( ! empty( $desc ) ) {
						?>
						<div class="metabox-description">
							<p> <?php echo esc_html( $desc ); ?></p>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

}
