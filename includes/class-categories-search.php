<?php
/**
 * Class that can be used to create search form for different post type. Terms 
 * of hierarchical taxonomies can be inserted into form. Search query of this form 
 * returns table, where post titles are in the left column and categories of 
 * selected taxonomy are in the right column.
 * @since      2.0.0
 * @package    Proverbs
 * @subpackage Proverbs/includes
 * @author     castellar120
 */

class Category_Search {
	/**
	 * Preselected categories.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      array    $categories_preselected    Array that holds preselected categories.
	 */
	protected $categories_preselected;

	/**
	 * Taxonomy of filter categories.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $category_taxonomy    Taxonomy of filter categories.
	 */
	protected $category_taxonomy;


	/**
	 * Search result post type.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $result_post_type    Search result post type.
	 */
	protected $result_post_type;

	/**
	 * Search results categories column title.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $table_title_category   Search results categories column title
	 */
	protected $table_title_category;

	/**
	 * Search results categories column title.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $table_title_taxonomy    Array that holds preselected categories.
	 */
	protected $table_title_taxonomy;

	/**
	 * Constructor.
	 *
	 * @since    2.0.0
	 * @param array $categories
	 * @param string $category_taxonomy
	 * @param string $result_post_type
	 * @param string $table_title_category
	 * @param string $table_title_taxonomy
	 */
	public function __construct( $categories=array('category'), $category_taxonomy="term_id", $result_post_type="post", $table_title_category="Posts", $table_title_taxonomy="Categories" ) {
		$this->create_shortcode();
		$this->categories_preselected = $categories;
		$this->table_title_category = $table_title_category;
		$this->table_title_taxonomy = $table_title_taxonomy;
		$this->category_taxonomy = $category_taxonomy;
		$this->result_post_type = $result_post_type;
	}

	/**
	* Create categories search shortcode.
	*
	* @since    2.0.0
	*/
	private function create_shortcode() {
		add_shortcode( 'category-search', array($this, 'shortcode_form') );
	}

	/**
	* Display posts found with search in a table, where post titles are in the 
	* left column and post categories are in the right column. If search query 
	* is not executed, then posts of preselected categories are displayed. 
	* Preselected categories are passed as a parameter, when categories search 
	* instance is created.
	*
	* @since    2.0.0
	*/
	public function search_results() {
		$this->categories = $_GET['filter-category'];
		if (!$this->categories) {
			$this->categories = $this->categories_preselected;
		}

		global $wpdb;

		$query = new WP_Query( array(
		  'post_type' => $this->result_post_type,
		  'posts_per_page' => -1,
		  'tax_query' => array(
			array(
			  'taxonomy' => $this->category_taxonomy,
			  'terms' => $this->categories,
			  'field' => 'name',
			),
		  )
		) );

		if ( $query->have_posts() ) {
	?>
			<table class="border_solid-black_table">
				<tr>
					<th>
						<?php _e($this->table_title_category); ?>
					</th>
					<th>
						<?php _e($this->table_title_taxonomy); ?>
					</th>
				</tr>
			</table>
			<table class="border_solid-black_table casia-paginated-table">
		<?php
			while ( $query->have_posts() ) {
		?>
			<tr>
				<td>
		<?php
					$query->the_post();
						the_title();
		?>
				</td>
				<td>
					<?php
						$proverb_countries = get_the_terms( get_the_ID(), $this->category_taxonomy );
						foreach ($proverb_countries as $key => $value) {
							$last_element = end($proverb_countries);
							if ($value==$last_element) {
								echo $value->name;
							} else {
								echo $value->name . ", ";
							}
						}
					?>
				</td>
			</tr>
		<?php
				}
		?>
			</table>
	<?php
		}
	}

	/**
	 * Create categories search shortcode.
	 *
	 * @since    2.0.0
	 * @return string $output
	 */
	public function shortcode_form() {
		$output = $this->shortcode_template_form();
		$output .= '<br><br>';
		$output .= $this->shortcode_template_results();
		return $output;
	}

	/**
	 * Create html string which represents search form of category search.
	 *
	 * @since    2.0.0
	 * @return string $shortcode_template
	 */
	public function shortcode_template_form() {
		$shortcode_template = '<form>';
		foreach ($this->categories_preselected as $key => $value) {
			$shortcode_template .= '<div class="display_inline-block margin-right_25px"> <input type="checkbox" name="filter-category[]" value="' . $value . '"<label for="' . $value . '" class="display_inline">'. $value .'</label></div>';
		}
		$shortcode_template .= '<br><br><input type="submit" value="search"></form>'; //to translate
		return $shortcode_template;
	}

	/**
	 * Create html string which represents search results of category search.
	 *
	 * @since    2.0.0
	 */
	public function shortcode_template_results() {
		ob_start();
		$this->search_results();
		return ob_get_clean();
	}
}