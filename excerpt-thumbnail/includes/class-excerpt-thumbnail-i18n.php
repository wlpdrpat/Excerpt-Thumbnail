<?php
class Excerpt_Thumbnail_i18n {
    private $domain;

    public function set_domain( $domain ) {
        $this->domain = $domain;
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            dirname( plugin_basename( EXCERPT_THUMBNAIL_FILE ) ) . '/languages/'
        );
    }
}
