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
class Editor  extends GetFields {
	use Singleton;
	/**
	 * Return input field.
	 *
	 * @return mixed
	 */
	public function get_field() {
		if ( $this->field && is_array( $this->field ) ) {
			$id        = sanitize_text_field( $this->field['id'] );
			$class     = sanitize_text_field( $this->field['class'] );
			$title     = sanitize_text_field( $this->field['title'] );
			$desc      = sanitize_text_field( $this->field['desc'] );
			$subtitle  = sanitize_text_field( $this->field['subtitle'] );
			$value     = isset( $this->field['prev_value'][ $id ] ) ? $this->field['prev_value'][ $id ] : '';
			$condition = $this->get_conditional_rules( $this->field['condition'] );
			$attr      = '';
			if ( $condition ) {
				$attr .= htmlspecialchars( $condition );
			}

			?>
			<div id="field-<?php echo esc_attr( $id ); ?>" class="fields-wrapper <?php echo esc_attr( $class ); ?>"  data-conditional-rules="<?php echo esc_attr( $attr ); ?>"  >
				<div class="label col">
					<label for="<?php echo esc_attr( $id ); ?>"> <?php echo esc_html( $title ); ?> </label>
					<?php if ( ! empty( $subtitle ) ) { ?>
						<p> <?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>
				</div>
				<div class="field-wrapper col">
					<?php
						// $id
						$settings = array(
							'wpautop'       => true, // use wpautop?
							'media_buttons' => true, // show insert/upload button(s)
							'textarea_name' => $id, // set the textarea name to something different, square brackets [] can be used here
							'textarea_rows' => 10, // get_option( 'default_post_edit_rows', 10 ), // rows="..." Reduce Query in this line by removing get_option function.
							'tabindex'      => '',
							'editor_css'    => '', // extra styles for both visual and HTML editors buttons,.
							'editor_class'  => '', // add extra class(es) to the editor textarea.
							'teeny'         => true, // output the minimal editor config used in Press This
							'dfw'           => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
							'tinymce'       => array(
								'toolbar1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_adv',
							), // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
							'quicktags'     => true, // load Quicktags, can be used to pass settings directly to Quicktags using an array()
						);
						wp_editor(
							$value,
							$id,
							$settings
						);
					?>
					<?php if ( ! empty( $desc ) ) { ?>
						<p> <?php echo esc_html( $desc ); ?></p>
					<?php } ?>
				</div>
			</div>
			<?php
		}
	}

}
