<?php
namespace GpcAstra\Includes\Shortcodes;

class DateShortcode
{
    public function __construct()
    {
        add_shortcode( 'date', array( $this, 'date_shortcode' ) );
    }

    public function date_shortcode( $atts ) {

        /**
         * Shortcode attributes.
         *
         * @since 1.0
         */
        $atts = shortcode_atts(
            array(
                'format' => '',
            ), $atts
        );

        if ( ! empty( $atts['format'] ) ) {
            $dateFormat = sanitize_text_field( $atts['format'] );
        } else {
            $dateFormat = 'd/m/Y';
        }

        return date_i18n( $dateFormat );
    }
}