<?php

namespace GpcAstra\Site;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use GpcAstra\Includes\Traits\Singleton;

class SiteSettings
{
    use Singleton;

    public function __construct()
    {
        add_action('carbon_fields_register_fields', [$this, 'add_settings']);
    }

    public function add_settings()
    {
        Container::make('theme_options', 'GPC Settings')
            ->set_page_parent('options-general.php')
            ->add_tab(__('Hệ thống'), array(
                Field::make('checkbox', 'gpc_hide_update_notices', __('Ẩn thông báo cập nhật'))
                    ->set_option_value('yes'),
                Field::make('checkbox', 'gpc_disable_theme_file_editor', __('Disable theme file editor'))
                    ->set_option_value('yes'),
            ))
            ->add_tab(__('Nút liên hệ'), array(
                Field::make('html', 'floating_buttons_information_text')
                    ->set_html('<h2>Lưu ý:</h2>
                <ul>
                    <li>Các nút liên hệ ở đây chỉ là nút bấm dẫn sang zalo messenger hoặc FB messenger...</li>
                    <li><span style="color:red;">Nếu muốn sử dụng chatbox của zalo, facebook... thì đặt code ở bên <b>Header & Footer Scripts</b></span></li>
                    <li>
                        Màu sắc tham khảo:
                        <ul>
                            <li>Facebook Messenger: #0084ff</li>
                            <li>Zalo: #1F7FC2</li>
                            <li>Viber: #665cac</li>
                            <li>Whatsapp: #00bfa5</li>
                            <li>Instagram: #9b6954</li>
                            <li>Youtube: #FF0000</li>
                        </ul>
                    </li>
                </ul> '),
                Field::make('complex', 'gpc_floating_buttons', __('Các nút liên hệ'))
                    ->add_fields(array(
                        Field::make('text', 'title', 'Title'),
                        Field::make('text', 'link', 'Link'),
                        Field::make('image', 'icon_image', __('Icon image')),
                        Field::make('color', 'color', 'Màu nền')
                            ->set_alpha_enabled(true),
                        Field::make('text', 'css_class', 'Extra CSS Classes'),
                        Field::make('checkbox', 'is_in_another_side', 'Hiển thị khác bên mặc định'),
                    ))
                    ->set_header_template('
                        <% if (title) { %>
                            <%- title %>
                        <% } %>
                    ')
                    ->set_collapsed(true),
                Field::make('checkbox', 'gpc_run_zalo_error_fix', __('Chạy code sửa lỗi zalo'))
                    ->set_conditional_logic( array(
                        'relation' => 'AND', // Optional, defaults to "AND"
                        array(
                            'field' => 'gpc_floating_buttons',
                            'value' => 'Zalo', // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                            'compare' => 'IN', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                        )
                    ) )
                    ->set_option_value('yes'),
                Field::make('text', 'gpc_zalo_phone', __('Số điện thoại Zalo'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'gpc_run_zalo_error_fix',
                            'value' => true,
                        )
                    )),
                Field::make('text', 'gpc_zalo_code', __('Mã QR Zalo'))
                    ->set_conditional_logic(array(
                        array(
                            'field' => 'gpc_run_zalo_error_fix',
                            'value' => true,
                        )
                    )),

                Field::make('select', 'gpc_default_side_floating_buttons', __('Nút liên hệ hiển thị bên?'))
                    ->set_options(array(
                        'left' => __('Trái'),
                        'right' => __('Phải'),
                    ))
                    ->set_default_value('right'),
                Field::make('set', 'gpc_shake_btn', __('Bật hiệu ứng rung cho nút liên hệ'))
                    ->set_options(array(
                        'desktop' => 'Desktop',
                        'tablet' => 'Tablet',
                        'mobile' => 'Mobile',
                    )),
                Field::make('checkbox', 'gpc_mobile_fixed_floating_buttons', __('Cố định nút liên hệ trên mobile'))
                    ->set_option_value('yes'),
            ))
            ->add_tab(__('Header - Footer Script'), array(
                Field::make('footer_scripts', 'gpc_footer_scripts', __('Footer Scripts'))
                    ->set_hook('wp_footer', 100),
                Field::make('header_scripts', 'gpc_header_scripts', __('Header Scripts'))
                    ->set_hook('wp_head', 100),
                Field::make('text', 'gpc_sample_site', __('Sample Text On Site')),
            ));
    }
}
