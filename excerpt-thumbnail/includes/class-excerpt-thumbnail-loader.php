<?php
/**
 * Registers and runs WordPress actions/filters for this plugin.
 *
 * @link      https://wellplanet.com
 * @since     1.0.0
 * @package   Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 * @author    Patrick Coleman
 * @license   GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lightweight hook loader used by the core plugin class.
 *
 * @since 1.0.0
 */
class Excerpt_Thumbnail_Loader {

	/**
	 * Collected actions to add at runtime.
	 *
	 * @since 1.0.0
	 * @var array<int, array{hook:string,component:object,callback:string,priority:int,accepted_args:int}>
	 */
	protected $actions = [];

	/**
	 * Collected filters to add at runtime.
	 *
	 * @since 1.0.0
	 * @var array<int, array{hook:string,component:object,callback:string,priority:int,accepted_args:int}>
	 */
	protected $filters = [];

	/**
	 * Queue an action to be registered.
	 *
	 * @since 1.0.0
	 * @param string $hook          Action name.
	 * @param object $component     Class instance.
	 * @param string $callback      Method name.
	 * @param int    $priority      Priority.
	 * @param int    $accepted_args Accepted args.
	 * @return void
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions[] = compact( 'hook', 'component', 'callback', 'priority', 'accepted_args' );
	}

	/**
	 * Queue a filter to be registered.
	 *
	 * @since 1.0.0
	 * @param string $hook          Filter name.
	 * @param object $component     Class instance.
	 * @param string $callback      Method name.
	 * @param int    $priority      Priority.
	 * @param int    $accepted_args Accepted args.
	 * @return void
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters[] = compact( 'hook', 'component', 'callback', 'priority', 'accepted_args' );
	}

	/**
	 * Register all queued actions/filters with WordPress.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function run() {
		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
		}
	}
}
