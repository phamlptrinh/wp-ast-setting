<?php
namespace GpcAstra\Site\Shortcodes;

use GpcAstra\Includes\Traits\Singleton;

class DanhSachLopShortcode
{
    use Singleton;

    public function __construct()
    {
        add_shortcode( 'danh_sach_lop', array( $this, 'danh_sach_lop_shortcode' ) );
    }

    public function danh_sach_lop_shortcode( $atts )
    {
        $atts = shortcode_atts(
            array(
                'cols' => '3',
                'count' => '6',
            ), $atts
        );

        $html = '';

        $query = new \WP_Query(
            array(
                'post_type' => 'gpc_lop',
                'posts_per_page' => $atts['count'],
                'orderby' => 'date',
                'order' => 'DESC',
            )
        );

        ob_start(); ?>

        <div class="grid grid-cols-<?php echo $atts['cols']; ?> gpc-lop-list">
        <?php
			if ( $query->have_posts() ) :

				while ( $query->have_posts() ) :
					$query->the_post();

					get_template_part( 'template-parts/content', 'gpc_lop' );

				endwhile;
			else :
				do_action( 'astra_template_parts_content_none' );
			endif;
            wp_reset_postdata();
        ?>
        </div>

        <?php
        $html = ob_get_clean();

        return $html;
    }
}