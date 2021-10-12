<?php
/**
 * Field displayed by this function.
 *
 * @package    PS Metabox
 * @subpackage PS_Metaboxes
 */

namespace PS\INIT\Fields;

use PS\INIT\GetFields;
/**
 * Display Input.
 */
class ToggleSwitch extends GetFields {
	/**
	 * Get instance;
	 *
	 * @var obaject
	 */
	protected static $instance;

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

	/**
	 * Return input field.
	 *
	 * @return mixed
	 */
	public function get_field() {
		if ( $this->field && is_array( $this->field ) ) {
			global $post;
			$id       = sanitize_text_field( $this->field['id'] );
			$type     = sanitize_text_field( $this->field['type'] );
			$class    = sanitize_text_field( $this->field['class'] );
			$title    = sanitize_text_field( $this->field['title'] );
			$desc     = sanitize_text_field( $this->field['desc'] );
			$subtitle = sanitize_text_field( $this->field['subtitle'] );
			$value    = boolval( get_post_meta( $post->ID, $id, true ) );
			if ( ! metadata_exists( 'post', $post->ID, $this->field['id'] ) ) {
				$value = boolval( $this->field['default'] );
			}
			?>
			<div id="field-<?php echo esc_attr( $id ); ?>" class="fields-wrapper flex-wrap <?php echo esc_attr( $class ); ?>">
				<div class="label col">
					<label><?php echo esc_html( $title ); ?> </label>
					<?php if ( ! empty( $subtitle ) ) { ?>
						<p> <?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>
				</div>
				<div class="field-wrapper col d-flex flex-wrap">
					<div class="toggle-button">
						<label class="switch" style="--true:'<?php echo esc_attr( $this->field['true'] ); ?>'; --false:'<?php echo esc_attr( $this->field['false'] ); ?>';">
							<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type="checkbox"  <?php echo $value ? esc_attr( 'checked' ) : ''; ?> value="<?php echo $value ? 1 : 0; ?>" >
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