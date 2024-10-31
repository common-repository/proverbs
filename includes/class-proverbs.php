<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       proverbs
 * @since      2.0.0
 *
 * @package    Proverbs
 * @subpackage Proverbs/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Proverbs
 * @subpackage Proverbs/includes
 * @author     castellar120
 */
class Proverbs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Proverbs_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Proverbs custom post type.
	 *
	 * @since    2.0.0
	 * @access   protected
	 */
	protected $proverb;

	/**
	 * Displayed proverbs.
	 *
	 * @since    2.0.0
	 * @access   protected
	 */
	protected $proverbs;

	/**
	 * Initial proverbs.
	 *
	 * @since    2.0.0
	 * @access   protected
	 */
	protected $proverbs_initial;

	/**
	* Repository instance.
	*
	* @since    2.0.0
	* @access   protected
	*/
	protected $repository_instance;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	* Selected proverb.
	*
	* @since    2.0.0
	* @access   private
	*/
	private $selected_proverb;

	/**
	 * Textdomain used for translation. Use the set_textdomain() method to set a custom textdomain.
	 * @var string $textdomain Used for internationalising.
	 */
	public $textdomain;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'proverbs';
		$this->textdomain = 'proverbs';

		$this->load_dependencies();
		$this->set_locale();

		// Hooks
		$this->define_general_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// Initial proverbs
		$this->proverbs_initial = array(
				'england' => array(
								"Absence makes the heart grow fonder.",
								"Actions speak louder than words.",
								"A journey of a thousand miles begins with a single step.",
								"All good things must come to an end",
								"A picture is worth a thousand words.",
								"A watched pot never boils",
								"Beggars can’t be choosers",
								"Beauty is in the eye of the beholder",
								"Better late than never",
								"Birds of a feather flock together",
								"Cleanliness is next to godliness"
							),
				'estonia' => array(
								"He who seeks shall find",
								"An old dog barks not in vain.",
								"A wolf will not break a wolf.",
								"Such mother, such daughter.",
								"The apple never falls far form the tree.",
								"No dreaming cat catches mice.",
								"A little fish is supper for a large fish.",
								"It is good fishing in streamy water",
								"Old love does not rust.",
								"Old horse, foal thinking.",
								"A lie has short legs."
							)
			);
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Proverbs_Loader. Orchestrates the hooks of the plugin.
	 * - Proverbs_i18n. Defines internationalization functionality.
	 * - Proverbs_Admin. Defines all hooks for the admin area.
	 * - Proverbs_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-proverbs-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-proverbs-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-proverbs-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-proverbs-public.php';

		/**
		 * The class responsible for managing custom post types.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-custom-post-type-class.php';

		/**
		 * The class responsible for managing posts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-repository.php';

		/**
		 * The class responsible for categories search.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-categories-search.php';

		$this->loader = new Proverbs_Loader();

		$this->proverb = new CPT('proverb', array(
			'supports' => array('title', 'thumbnail')
		));

		$this->proverb->register_taxonomy(array(
			'hierarchical'      => false,
			'taxonomy_name' => 'proverb_category',
			'singular' => 'Proverb category',
			'plural' => 'Proverb categories',
			'slug' => 'proverb_category'
		));

		$this->repository_instance = Repository::init();
	}

	/**
	 * Create proverb categories.
	 *
	 * @since    2.0.0
	 */
	public function add_categories() {
		$this->repository_instance->save_category('Estonian proverbs', 'proverb_category',array(
				'slug' 		=> 'estonia'
			));

		$this->repository_instance->save_category('English proverbs', 'proverb_category',array(
			'slug' 		=> 'england'
		));
	}

	/**
	 * Create necessary pages.
	 *
	 * @since    2.0.0
	 */
	public function add_pages() {
		$this->repository_instance->save_published_page('Proverbs', '[category-search]');
	}

	/**
	 * Insert initial proverbs into the database.
	 *
	 * @since    2.0.0
	 */
	public function add_proverbs() {
		$result = [];
		foreach ($this->proverbs_initial as $key => $value) {
			$result  = array_merge($value, $result);
		}
		$this->repository_instance->wp_insert_post_1_pt('proverb', $result);
	}

	/**
	 * Attach country categories to proverbs. Which proverb originates from 
	 * which country is defined in $this->proverbs_initial.
	 *
	 * @since    2.0.0
	 */
	public function attach_categories() {
		$this->repository_instance->attach_categories($this->proverbs_initial, 'proverb', 'proverb_category');
	}

	/**
	 * Create category search instance.
	 *
	 * @since    2.0.0
	 */
	public function create_search_form() {
		$preselected_categories = wp_list_pluck( get_terms( array(
		'taxonomy' => 'proverb_category'
		)), 'name' );

		$this->category_search = new Category_Search($preselected_categories, 'proverb_category', 'proverb', 'Proverbs', 'Categories');
	}

	public function create_shortcodes() {
		add_shortcode( 'proverb', array($this, 'shortcode_template') );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Proverbs_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Proverbs_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $this, 'create_search_form' );
	}

	/**
	 * Register all of the hooks related to the both areas functionality
	 * of the plugin - admin and public.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_general_hooks() {
		$this->loader->add_action( 'init', $this, 'add_categories' );
		$this->loader->add_action( 'wp_loaded', $this, 'add_proverbs', 11 );
		$this->loader->add_action( 'wp_loaded', $this, 'attach_categories', 12 );
		$this->loader->add_action( 'init', $this, 'create_shortcodes' );
		$this->loader->add_action( 'wp_loaded', $this, 'add_pages', 14 );
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    Proverbs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Change the value of $this->selected_proverb.
	 *
	 * @since     2.0.0
	 */
	public function get_proverb() {
		global $wpdb;

		$this->selected_proverb = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'proverb' ORDER BY RAND() LIMIT 1");
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Proverbs_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Proverbs_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Create template for shortcode which displays random proverb in html 
	 * blockquote tag.
	 *
	 * @since    2.0.0
	 */
	public function shortcode_template( $atts ) {
		$this->get_proverb();
		ob_start(); ?>
		<blockquote>
			<?php echo $this->selected_proverb[0]->post_title ?>
		</blockquote>
		<?php
		return ob_get_clean();
	}
}
