<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      29/08/2018
 *
 * @package Typography.php
 */

namespace Neve\Views\Inline;

/**
 * Class Container_Sidebar
 *
 * @package Neve\Views\Inline
 */
class Container_Sidebar extends Base_Inline {
	/**
	 * Do all actions necessary.
	 *
	 * @return void
	 */
	public function init() {
		$this->container_style();
		$this->sidebar_style();
	}

	/**
	 * Container styles.
	 */
	private function container_style() {
		$container_width = get_theme_mod( 'neve_container_width' );
		$container_width = json_decode( $container_width, true );
		if ( empty( $container_width ) ) {
			return;
		}

		$settings  = array(
			array(
				'css_prop' => 'max-width',
				'value'    => $container_width,
				'suffix'   => 'px',
			),
		);
		$selectors = '.container, 
		.nv-single-post-wrap > *:not(.nv-content-wrap), 
		.nv-single-post-wrap .nv-content-wrap > *:not(.alignwide):not(.alignfull), 
		.nv-single-page-wrap > *:not(.nv-content-wrap), 
		.nv-single-page-wrap .nv-content-wrap > *:not(.alignwide):not(.alignfull)';

		$this->add_responsive_style( $settings, $selectors );

		$alignwide_width = $container_width; // +100px
		array_walk(
			$alignwide_width,
			function ( &$item1 ) {
				$item1 += 100;
			}
		);

		$settings = array(
			array(
				'css_prop' => 'max-width',
				'value'    => $alignwide_width,
				'suffix'   => 'px',
			),
		);

		$this->add_responsive_style( $settings, '.alignwide' );

	}

	/**
	 * Sidebar style.
	 */
	private function sidebar_style() {
		$sidebar_width = get_theme_mod( 'neve_sidebar_width' );
		$sidebar_width = json_decode( $sidebar_width, true );
		$content_width = 100 - $sidebar_width;
		$settings      = array(
			'content' => array(
				'css_prop' => 'max - width',
				'value'    => $content_width,
				'suffix'   => ' % ',
			),
			'sidebar' => array(
				array(
					'css_prop' => 'max - width',
					'value'    => $sidebar_width,
					'suffix'   => ' % ',
				),
			),
		);

		$this->add_style( $settings['content'], '#primary .container .col:not(:only-child)', 'desktop' );
		$this->add_style( $settings['sidebar'], '.nv-sidebar-wrap, .nv-sidebar-wrap.shop-sidebar', 'desktop' );
	}
}
