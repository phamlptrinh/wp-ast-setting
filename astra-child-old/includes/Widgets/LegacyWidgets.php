<?php
namespace GpcAstra\Includes\Widgets;

class LegacyWidgets
{
    public function __construct()
    {
        add_filter('widget_types_to_hide_from_legacy_widget_block', [$this, 'unhide_widgets']);
    }

    /**
     * Cho hiện các widget cũ trong trang quản lý widget khi ở chế độ block editor
     */
    public function unhide_widgets($widget_types)
    {
        $widget_types = array_values(array_diff($widget_types, [
            'text',
            'categories',
            'custom_html',
        ]));
        return $widget_types;
    }
}