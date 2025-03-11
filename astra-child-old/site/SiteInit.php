<?php
namespace GpcAstra\Site;

use GpcAstra\Includes\AstraTheme;
use GpcAstra\Includes\Blocks\CoreGroup;
use GpcAstra\Includes\Blocks\GpcDummy;
use GpcAstra\Includes\KadenceBlocks;
use GpcAstra\Includes\Shortcodes\DateShortcode;
use GpcAstra\Includes\TinyEditor;
use GpcAstra\Includes\Traits\Singleton;
use GpcAstra\Includes\Widgets\LegacyWidgets;

class SiteInit
{
    use Singleton;

	public function __construct()
	{
		add_action( 'after_setup_theme', array( $this, 'load_carbon_fields' ) );

		$this->theme_setup();
		$this->register_post_types();
		$this->register_blocks();
		$this->load_admin();
		$this->load_public();
	}

	public function load_carbon_fields()
	{
		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function theme_setup()
	{
		new AstraTheme();
	}

	public function register_post_types()
	{

	}

	public function register_blocks()
	{
		new KadenceBlocks();
		new CoreGroup();
		new BlockStyles();

		new GpcDummy();
	}

	public function load_admin()
	{
		// Load admin JS & CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load css, js in block editor
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 11 );

		// create setting page
		SiteSettings::instance();

		// Chỉnh button trên TinyMCE
		new TinyEditor();

		new LegacyWidgets();
	}

	public function load_public()
	{
		add_action( 'wp_enqueue_scripts', [ $this, 'add_fontawesome' ]);
		add_action( 'wp_enqueue_scripts', [ $this, 'child_enqueue_styles' ], 99 );

		// Thêm css, js cho từng page, dùng khi làm landing page hoặc khi cần gắn css, js riêng cho 1 page cụ thể
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_page_assets' ], 98 );

		new DateShortcode();
		new FloatContactButtons();
		new HideUpdateNotices();
		new FixZaloError();
		new HideThemeFileEditor();
	}

	public function add_fontawesome() {
		wp_enqueue_style( 'gpc-fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', array(), '5.9.0' );
	}

	public function enqueue_page_assets() {

		$homeId = get_option('page_on_front');

		$pages = [
			'home' => $homeId,
		];

		if ( is_page( $pages['home'] ) ) {

		}
	}

	public function child_enqueue_styles() {
		wp_enqueue_style( 'gpc-astra-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), filemtime(get_stylesheet_directory(). '/style.css') );

		wp_enqueue_script('gpc-astra-theme-js', get_stylesheet_directory_uri() . '/assets/js/frontend.js', array('jquery'), filemtime(get_stylesheet_directory(). '/assets/js/frontend.js'), true );
	}

	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( 'gpc-astra-admin', esc_url( GPC_ASTRA_DIR_URL ) . '/assets/css/admin.css', array(), GPC_ASTRA_VERSION );
		wp_enqueue_style( 'gpc-astra-admin' );
	}

	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( 'gpc-astra-admin', esc_url( GPC_ASTRA_DIR_URL ) . '/assets/js/admin.js', array( 'jquery' ), GPC_ASTRA_VERSION, true );
		wp_enqueue_script( 'gpc-astra-admin' );
	}

	public function enqueue_block_editor_assets() {
		wp_enqueue_style( 'gpc-editor-css', get_stylesheet_directory_uri() . '/editor-style.css', array(), filemtime(get_stylesheet_directory(). '/editor-style.css') );

		$deps = ['react', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-core-data', 'wp-data', 'wp-i18n', 'wp-primitives', 'wp-server-side-render'];

		foreach ($deps as $dep) {
			echo "<script>console.log('{$dep}')</script>";
			wp_enqueue_script($dep);
		}
	}
}