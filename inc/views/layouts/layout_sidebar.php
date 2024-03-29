<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      27/08/2018
 *
 * @package Neve\Views\Layouts
 */

namespace Neve\Views\Layouts;

use Neve\Views\Base_View;

/**
 * Class Layout_Container
 *
 * @package Neve\Views\Layouts
 */
class Layout_Sidebar extends Base_View {

	/**
	 * Function that is run after instantiation.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'neve_do_sidebar', array( $this, 'sidebar' ), 10, 2 );
	}

	/**
	 * Render the sidebar.
	 *
	 * @param string $context  context passed into do_action.
	 * @param string $position position passed into do_action.
	 */
	public function sidebar( $context, $position ) {
		$sidebar_setup = $this->get_sidebar_setup( $context );
		$theme_mod     = $sidebar_setup['theme_mod'];
		$theme_mod     = apply_filters( 'neve_sidebar_position', get_theme_mod( $theme_mod, 'right' ) );
		if ( $theme_mod !== $position ) {
			return;
		} ?>


		<div class="nv-sidebar-wrap col-sm-12 <?php echo esc_attr( $position ); ?>">
			<aside id="secondary" class="<?php echo esc_attr( $sidebar_setup['sidebar_slug'] ); ?>"
					role="complementary">
				<?php dynamic_sidebar( $sidebar_setup['sidebar_slug'] ); ?>
			</aside>
		</div>
		<?php
	}

	/**
	 * Get the sidebar setup. Returns array (`theme_mod`, `sidebar_slug`) based on context.
	 *
	 * @param string $context the provided context.
	 *
	 * @return array
	 */
	private function get_sidebar_setup( $context ) {
		$sidebar_setup = array(
			'theme_mod'    => 'neve_default_sidebar_layout',
			'sidebar_slug' => 'blog-sidebar',
		);

		if ( $context === 'blog-archive' ) {
			$sidebar_setup['theme_mod'] = 'neve_blog_archive_sidebar_layout';

			return $sidebar_setup;
		}

		if ( $context === 'single-post' ) {
			/*
			Commented for now
			if ( class_exists( 'WooCommerce' ) && is_product() ) {
				$sidebar_setup['theme_mod']    = 'neve_single_product_sidebar_layout';
				$sidebar_setup['sidebar_slug'] = 'shop-sidebar';

				return $sidebar_setup;
			}
			*/

			$sidebar_setup['theme_mod'] = 'neve_single_post_sidebar_layout';

			return $sidebar_setup;

		}

		if ( $context === 'single-page' ) {
			/*
			Commented for now
			if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() ) ) {
				$sidebar_setup['theme_mod']    = 'neve_shop_archive_sidebar_layout';
				$sidebar_setup['sidebar_slug'] = 'shop-sidebar';

				return $sidebar_setup;
			}
			*/
		}

		return $sidebar_setup;
	}
}
