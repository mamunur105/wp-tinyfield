<?php
/**
 * SIngleton.
 *
 * @package    Tinyfield Metabox
 * @subpackage Tinyfield_Metaboxes
 */

namespace Tiny\Init\Traits;

trait Nonce {

	/**
	 * An array inside container.
	 *
	 * @var array
	 */
	protected $nonce = 'tiny_nonce';
	/**
	 * Recursive sanitation for text or array
	 *
	 * @since  0.1
	 * @return mixed
	 */
	public function create_nonce() {
		echo '<input type="hidden" name="' . esc_html( $this->nonce ) . '" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
	}
	/**
	 * Undocumented function
	 *
	 * @return bool
	 */
	public function varify_nonce() {
		if ( isset( $_POST[ $this->nonce ] ) ) {
			$nonce_check = sanitize_text_field( wp_unslash( $_POST[ $this->nonce ] ) );
			if ( wp_verify_nonce( $nonce_check, basename( __FILE__ ) ) ) {
				return true;
			}
		}
		return false;
	}

}
