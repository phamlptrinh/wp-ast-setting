<?php
namespace GpcAstra\Includes;

/**
 * Thêm chức năng, chỉnh sửa Kadence Blocks cho phù hợp với nhu cầu
 */
class KadenceBlocks
{
    public function __construct()
    {
        add_filter('kadence_blocks_font_family_string', [ $this, 'kadence_blocks_font_family_string'], 10, 2);

        add_filter('kadence_blocks_column_render_block_attributes', [ $this, 'kadence_blocks_column_render_block_attributes' ], 10, 1);

        //$this->update_validate();
    }

    public function update_validate()
    {
        $domain = $this->get_site_domain();
        $status_option_name = "stellarwp_uplink_license_key_status_kadence-blocks-pro_{$domain}";
        $key_option_name = "stellarwp_uplink_license_key_kadence-blocks-pro";
        update_option($key_option_name, 'abc');
        update_option($status_option_name, 'valid');
        add_filter( "transient_kadence_blocks_pro_license_status_check", [ $this, 'change_transient'], 10, 2);
    }

    public function change_transient($value, $transient)
    {
        if (!$value) {
            $value = 'abc_valid'; // $key . '_valid';
        }

        return $value;
    }

    private function get_site_domain() {
		/** @var string */
		$site_url = get_option( 'siteurl', '' );

		/** @var array<string> */
		$site_url = wp_parse_url( $site_url );
		if ( ! $site_url || ! isset( $site_url['host'] ) ) {
			if ( isset( $_SERVER['SERVER_NAME'] ) ) {
				return $_SERVER['SERVER_NAME'];
			}

			return '';
		}

		return strtolower( $site_url['host'] );
	}

    /**
     * Đổi font family về inherit khi chọn System Default trong các blocks của Kadence
     * Lý do: khi chọn System Default thì mới có nhiều lựa chọn font weight,
     * nhưng System Default lại sử dụng font hệ thống của máy tính chứ không phải của website
     * nên cần đổi lại thành 'inherit' để hiện đúng font trong website
     */
    public function kadence_blocks_font_family_string($font_string, $font_name) {
        if (str_starts_with($font_string, '-apple-system')) {
            return 'inherit';
        }

        return $font_string;
    }

    /**
     * Chỉnh block Section thể hiện dạng cột đều nhau, tối đa 6 cột
     */
    public function kadence_blocks_column_render_block_attributes($attributes)
    {
        $direction = $attributes['direction'] ?? [];
        $gutter = $attributes['gutter'] ?? [];
        $gutterVariable = $attributes['gutterVariable'] ?? [];
        $gutterUnit = $attributes['gutterUnit'] ?? 'px';
        $flexBasis = $attributes['flexBasis'] ?? [];
        $flexBasisUnit = $attributes['flexBasisUnit'] ?? '';

        // không set độ rộng cột ở desktop --> bỏ qua
        if (empty($flexBasis) || empty($flexBasis[0])) {
            return $attributes;
        }

        // không set flex directon --> mặc định là flex row --> bỏ qua
        if (empty($direction)) {
            return $attributes;
        }

        // không phải dạng flex column --> bỏ qua
        $isCol = $direction[0] == 'horizontal';
        if (!$isCol) {
            return $attributes;
        }

        // unit không phải % --> bỏ qua
        // TODO: tính số cột = container width / flex basis (px, em, rem)
        if ($flexBasisUnit != '%') {
            return $attributes;
        }

        if ( empty( $gutterVariable[0] ) ) {
            $gutterVariable[0] = 'sm';
        }

        $newFlexBasis = [];
        $maxCols = 6;

        foreach ($flexBasis as $key => $value) {

            // nếu tablet không có flex basis thì bỏ trống
            if (empty($value) && $key === 1) {
                $newFlexBasis[$key] = $value;
                continue;
            }

            // nếu mobile không có flex basis thì số cột = 1
            if (empty($value) && $key === 2) {
                $newFlexBasis[$key] = "100%";
                continue;
            }

            $cols = floor(100 / floatval($value));
            $cols = $cols > $maxCols ? $maxCols : $cols;

            $gap = $this->gpc_kb_calculate_gap($gutter[$key], $gutterUnit, $gutterVariable[$key]);

            // nếu tablet không có gap thì lấy gap ở destkop
            if (!$gap && $key === 1) {
                $gap = $this->gpc_kb_calculate_gap($gutter[0], $gutterUnit, $gutterVariable[0]);
            }

            // nếu mobile không có gap thì lấy gap ở tablet hoặc desktop
            if (!$gap && $key === 2) {
                $gap = $this->gpc_kb_calculate_gap($gutter[1], $gutterUnit, $gutterVariable[1]);
                if (!$gap) {
                    $gap = $this->gpc_kb_calculate_gap($gutter[0], $gutterUnit, $gutterVariable[0]);
                }
            }

            $newFlexBasis[$key] = "calc((100% - {$gap} * ({$cols} - 1)) / {$cols})";
        }

        $attributes['flexBasis'] = $newFlexBasis;

        /**
         * do kadence add_property có gắn thêm unit ở sau value
         * và không được để trống (mặc định là 'px')
         * nên đổi unit thành ';' để khi in công thức calc() thành cacl();
         * chứ không phải thành calc()px hoặc calc()%
         */
        $attributes['flexBasisUnit'] = ';';

        return $attributes;
    }

    private function gpc_kb_calculate_gap($gutter, $gutterUnit, $gutterVariable)
    {
        if (!isset( $gutter ) || !is_numeric( $gutter )) {
            return '';
        }

        $gap_sizes = array(
            'none' => 'var(--global-kb-gap-none, 0rem )',
            'sm' => 'var(--global-kb-gap-sm, 1rem)',
            'md' => 'var(--global-kb-gap-md, 2rem)',
            'lg' => 'var(--global-kb-gap-lg, 4rem)',
        );

        $gap = '';

        if ($gutterVariable == 'custom') {
            $gap = $gutter . $gutterUnit;
        } else {
            $gap = $gap_sizes[$gutterVariable];
        }

        return $gap;
    }
}