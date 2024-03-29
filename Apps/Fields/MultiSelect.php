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
class MultiSelect extends GetFields {
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
			'multiselect' => false,
			'default'     => array( '' ),
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
			$id          = sanitize_text_field( $this->field['id'] );
			$class       = sanitize_text_field( $this->field['class'] );
			$title       = sanitize_text_field( $this->field['title'] );
			$options     = array_map( 'esc_attr', $this->field['options'] );
			$multiselect = boolval( $this->field['multiselect'] );
			$desc        = sanitize_text_field( $this->field['desc'] );
			$subtitle    = sanitize_text_field( $this->field['subtitle'] );
			$value       = isset( $this->field['prev_value'][ $id ] ) ? $this->field['prev_value'][ $id ] : array();
			// $value = maybe_unserialize( $value );
			wp_enqueue_style( 'select2' );
			wp_enqueue_script( 'select2' );
			$condition = $this->get_conditional_rules( $this->field['condition'] );
			$attr      = '';
			if ( $condition ) {
				$attr .= htmlspecialchars( $condition );
			}

			?>
			<div id="field-<?php echo esc_attr( $id ); ?>" class="fields-wrapper <?php echo esc_attr( $class ); ?>"  data-conditional-rules="<?php echo esc_attr( $attr ); ?>" >
				<div class="label col">
					<label for="<?php echo esc_attr( $id ); ?>" > <?php echo esc_html( $title ); ?> </label>
					<?php if ( ! empty( $subtitle ) ) { ?>
						<p> <?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>
				</div>
					<?php
					if ( ! empty( $options ) ) {
						?>
						<div class="selectbox-wraper" data-multiselect="<?php echo esc_attr( $multiselect ); ?>">
							<select name="<?php echo esc_attr( $id ); ?>[]" id="<?php echo esc_attr( $id ); ?>" multiple aria-hidden="true">
							<?php
							if ( ! $multiselect ) {
								?>
							<option value="" > <?php esc_html_e( '--Select one--', 'psnmetabox' ); ?> </option>
								<?php
							}
							foreach ( $options as $key => $option ) {
								$selected = is_array( $value ) && in_array( $key, $value, true ) ? 'selected' : '';
								?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>> <?php echo esc_attr( $option ); ?> </option>
								<?php
							}
							?>
							</select>
							<?php if ( ! empty( $desc ) ) { ?>
								<p> <?php echo esc_html( $desc ); ?></p>
							<?php } ?>
						</div>
						<?php
					}
					?>
			</div>
			<?php
		}
	}

}
