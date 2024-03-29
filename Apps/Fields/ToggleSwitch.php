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
class ToggleSwitch extends GetFields {
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
			'true'    => 'true',
			'false'   => 'false',
			'default' => true,
		);
		$field                 = wp_parse_args( $field, $defaults );
		self::$instance->field = $field;
		return self::$instance;
	}

	public function get_settings_value( $id ) {
		$get_values = maybe_unserialize( $this->field['prev_value'] );
		$value      = maybe_unserialize( $get_values[ $id ][0] );
		return $value;
	}

	/**
	 * Return input field.
	 *
	 * @return mixed
	 */
	public function get_field() {
		if ( $this->field && is_array( $this->field ) ) {
			$id       = sanitize_text_field( $this->field['id'] );
			$class    = sanitize_text_field( $this->field['class'] );
			$title    = sanitize_text_field( $this->field['title'] );
			$desc     = sanitize_text_field( $this->field['desc'] );
			$subtitle = sanitize_text_field( $this->field['subtitle'] );
			$value    = isset( $this->field['prev_value'][ $id ] ) ? $this->field['prev_value'][ $id ] : array();
			if ( 'yes' !== $value ) {
				$value = false;
			}
			$condition = $this->get_conditional_rules( $this->field['condition'] );
			$attr      = '';
			if ( $condition ) {
				$attr .= htmlspecialchars( $condition );
			}

			?>
			<div id="field-<?php echo esc_attr( $id ); ?>" class="fields-wrapper flex-wrap <?php echo esc_attr( $class ); ?>" >
				<div class="label col">
					<label><?php echo esc_html( $title ); ?> </label>
					<?php if ( ! empty( $subtitle ) ) { ?>
						<p> <?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>
				</div>
				<div class="field-wrapper col d-flex flex-wrap">
					<div class="toggle-button">
						<label class="switch" style="--true:'<?php echo esc_attr( $this->field['true'] ); ?>'; --false:'<?php echo esc_attr( $this->field['false'] ); ?>';">
							<input  data-conditional-rules="<?php echo esc_attr( $attr ); ?>" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="checkbox"  <?php echo $value ? esc_attr( 'checked="checked"' ) : ''; ?> value="yes" >
							<span class="slider round" ></span>
						</label>
						<?php if ( ! empty( $desc ) ) { ?>
							<p> <?php echo esc_html( $desc ); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
