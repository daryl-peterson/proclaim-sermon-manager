<?php
/**
 * Widget
 *
 * @package     DRPPSM\FooWidget
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use WP_Widget;

/**
 * Widget
 *
 * @package     DRPPSM\FooWidget
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class FooWidget extends WP_Widget implements Executable, Registrable {

	use ExecutableTrait;

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'foo_widget', // Base ID
			'Foo_Widget', // Name
			array( 'description' => __( 'A Foo Widget', 'text_domain' ) ) // Args
		);
	}

	public function register(): ?bool {

		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		Logger::debug( 'Registering Foo Widget' );

		return true;
	}

	public function register_widget() {
		register_widget( 'DRPPSM\FooWidget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		echo __( 'Hello, World!', 'text_domain' );
		echo $after_widget;
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // class Foo_Widget