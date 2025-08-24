<?php
class Excerpt_Thumbnail_Admin {
    private $slug;
    private $version;

    public function __construct( $slug, $version ) {
        $this->slug    = $slug;
        $this->version = $version;
    }

    public function enqueue_styles() {}
    public function enqueue_scripts() {}
}
