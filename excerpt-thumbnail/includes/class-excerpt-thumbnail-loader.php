<?php
/**
 * Registers and runs WordPress actions/filters for this plugin.
 *
 * @link       https://wellplanet.com
 * @since      3.0.0
 * @package    Excerpt_Thumbnail
 * @subpackage Excerpt_Thumbnail/includes
 * @author     Patrick Coleman <pat@wellplanet.com>
 * @license    GPL-2.0-or-later
 */

/**
 * Lightweight hook loader used by the core plugin class.
 *
 * @since 3.0.0
 */
class Excerpt_Thumbnail_Loader {

    /**
     * Collected actions to add at runtime.
     *
     * @since 3.0.0
     * @var array<int, array{hook:string,component:object,callback:string,priority:int,accepted_args:int}>
     */
    protected $actions = [];

    /**
     * Queue an action to be registered.
     *
     * @since 3.0.0
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
     * Register all queued actions with WordPress.
     *
     * @since 3.0.0
     * @return void
     */
    public function run() {
        foreach ( $this->actions as $hook ) {
            add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
        }
    }
}
