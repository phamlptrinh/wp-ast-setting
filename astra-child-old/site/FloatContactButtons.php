<?php

namespace GpcAstra\Site;

class FloatContactButtons
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts'], 999);

        add_action('wp_footer', [$this, 'gpc_contact_buttons'], 5);
    }

    public function wp_enqueue_scripts()
    {
        wp_enqueue_style('gpc-fcb', get_stylesheet_directory_uri() . '/assets/css/float-contact-buttons.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/float-contact-buttons.css'));
    }

    public function gpc_contact_buttons()
    {
        /**
         * TODO:
         * - option bật/tắt
         * - các options cho các nút
         */

        /* $floating_buttons = carbon_get_theme_option('gpc_floating_buttons');
        if ( empty($floating_buttons) ) {
            return;
        } */

        $contact_buttons = carbon_get_theme_option('gpc_floating_buttons');

        if (empty($contact_buttons)) {
            return;
        }

        $float_btn_container_class = "gpc-floating-buttons";

        $shaking_btn_arr = carbon_get_theme_option('gpc_shake_btn');
        foreach ($shaking_btn_arr as $shaking_btn) {
            $float_btn_container_class .= " has-effect-shake-" . $shaking_btn;
        }

        $is_mobile_fixed = carbon_get_theme_option('gpc_mobile_fixed_floating_buttons');
        if (!empty($is_mobile_fixed)) {
            $float_btn_container_class .= " mobile-fixed";
        }

        $default_side = carbon_get_theme_option('gpc_default_side_floating_buttons');
        $another_side = 'left';
        if (!empty($default_side)) {
            $float_btn_container_class .= ' button-' . $default_side;
            if ($default_side == 'left')
                $another_side = 'right';
        }
        $has_another_side = 0;

        echo '<div class="' . $float_btn_container_class . '">';

        foreach ($contact_buttons as $button) {
            $this->render_button($button, $has_another_side);
        }

        if ($has_another_side > 0) {
            echo '<style>
            @media only screen and (max-width:588px) {
                .gpc-floating-buttons.mobile-fixed .another-side-container {
                    flex-grow: ' . $has_another_side. ';
                }
            </style>';
            echo '<section class="another-side-container button-' . $another_side . '">';
            foreach ($contact_buttons as $button) {
                $this->render_button($button, $has_another_side, false);
            }
            echo '</section>';
        }

        echo '</div>';
    }

    private function render_button($button, &$has_another_side, $is_main_side = true)
    {
        $is_in_another_side = $button['is_in_another_side'];
        if (!empty($is_in_another_side) == $is_main_side) {
            if ($is_main_side) {$has_another_side++;}
            return;
        }

        //$show_label = isset($button['show_label']) ? $button['show_label'] : false;
        //$show_label_on_hover = isset($button['show_label_on_hover']) ? $button['show_label_on_hover'] : false;
        $title = $button['title'];
        $link = $button['link'];
        $color = $button['color'];
        $icon_image = $button['icon_image'];
        $css_class = $button['css_class'];

        //$icon_html = $this->render_icon($button);

        $button_class = 'class="gpc-floating-buttons__item ' . $css_class . '" ';

        /* if ($show_label) {
                $button_class .= $show_label_on_hover ? ' has-label-on-hover ' : ' has-label';
            } */

        $button_style = ' style="';
        if (!empty($color)) {
            $button_style .= 'background-color:' . $color . ';';
        }
        if (!empty($icon_image)) {
            $image_url = wp_get_attachment_image_src($icon_image);
            $button_style .= 'background-image: url(' . $image_url[0] . ');';
        }
        $button_style .= '" ';

        echo '<div ' . $button_class . '>';
        if (!empty($link)) {
            echo '<a ' . $button_style . 'href="' . $link . '" title="' . $title . '" rel="nofollow" target="_blank">';
        }

        //echo $icon_html;

        /* if ($show_label && !empty($title)) {
                echo '<span class="button-label">' . $title . '</span>';
            } */

        if (!empty($link)) {
            echo '.</a>';
        }

        echo '</div>';
    }

    private function render_icon($button)
    {
        $icon_type = $button['icon_type'];

        $icon_html = '';
        switch ($icon_type) {
            case 'icon':
                $icon_html = $this->render_icon_type_icon($button);
                break;
            case 'image':
                $icon_html = $this->render_icon_type_image($button);
                break;
            default:
                $icon_html = $this->render_icon_type_custom($button);
                break;
        }

        return $icon_html;
    }

    private function render_icon_type_icon($button)
    {
        $icon = $button['icon'];
        if (empty($icon)) {
            return '';
        }

        $html = '';

        if ($icon['provider'] === 'custom') {
            $html = '<span class="floating-contact-icon">';
            $html .= '<img alt="' . $button['title'] . '" src="' . $icon['icon'] . '" />';
            $html .= '</span>';
        } else {
            $html = '<span class="floating-contact-icon"><i class="' . $icon['class'] . '"></i></span>';
        }

        return $html;
    }

    private function render_icon_type_image($button)
    {
        $image_id = $button['icon_image'];
        if (empty($image_id)) {
            return '';
        }

        $icon_html = '';

        $image_url = wp_get_attachment_image_src($image_id);
        $icon_html = '<span class="floating-contact-icon">';
        $icon_html .= '<img alt="' . $button['title'] . '" src="' . $image_url[0] . '" />';
        $icon_html .= '</span>';

        return $icon_html;
    }

    private function render_icon_type_custom($button)
    {
        if (empty($button['icon_custom'])) {
            return '';
        }
        return '<span class="floating-contact-icon"><i class="' . $button['icon_custom'] . '"></i></span>';
    }
}
