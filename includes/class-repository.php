<?php
/**
 * Class that can be used to manage posts and taxonomies.
 * @since      2.0.0
 * @package    Proverbs
 * @subpackage Proverbs/includes
 */
class Repository
{
	/**
	 * WordPress query object.
	 *
	 * @since    2.0.0
	 * @var WP_Query
	 */
	private $query;

	/**
	 * Constructor.
	 *
	 * @since    2.0.0
	 * @param WP_Query $query
	 */
	public function __construct(WP_Query $query) {
		$this->query = $query;
	}

	/**
	 * Attach categories. Parameter $posts is an array of post titles. 
	 * Parameter $taxonomy has to be category, post_tag, or the name of another taxonomy.
	 *
	 * @since 2.0.0
	 * @param array $posts
	 * @param string $post_type
	 * @param string $taxonomy
	 */
	public function attach_categories($posts, $post_type='post', $taxonomy="category") {
		foreach ($posts as $category => $post) {
			foreach ($post as $index => $single_post) {
				$my_post = get_page_by_title( $single_post, "OBJECT", $post_type );
				wp_set_object_terms( $my_post->ID, array($category), $taxonomy, true);
			}
		}
	}

	/**
	 * Find all post objects for the given query.
	 *
	 * @since    2.0.0
	 * @param array $query
	 *
	 * @return WP_Post[]
	 */
	public function find(array $query)
	{
		$query = array_merge(array(
			'no_found_rows' => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		), $query);

		return $this->query->query($query);
	}

	/**
	 * Find posts written by the given author.
	 *
	 * @since    2.0.0
	 * @param WP_User $author
	 * @param int     $limit
	 *
	 * @return WP_Post[]
	 */
	public function find_by_author(WP_User $author, $limit = 10)
	{
		return $this->find(array(
			'author' => $author->ID,
			'posts_per_page' => $limit,
		));
	}

	/**
	 * Find a post using the given post ID.
	 *
	 * @since    2.0.0
	 * @param int $id
	 *
	 * @return WP_Post|null
	 */
	public function find_by_id($id)
	{
		return $this->find_one(array('p' => $id));
	}

	/**
	 * Find a post using the given post title.
	 *
	 * @since    2.0.0
	 * @param string $title
	 *
	 * @return WP_Post|null
	 */
	public function find_by_title($title)
	{
		return get_page_by_title($title);
	}

	/**
	 * Find a category.
	 *
	 * @since    2.0.0
	 * @param array $args
	 *
	 * @return Term Row (object or array) from database|false
	 */
	public function find_category($field, $value, $taxonomy)
	{
		return get_term_by($field, $value, $taxonomy);
	}

	/**
	 * Find a single post object for the given query. Returns null
	 * if it doesn't find one.
	 *
	 * @since    2.0.0
	 * @param array $query
	 *
	 * @return WP_Post|null
	 */
	private function find_one(array $query)
	{
		$query = array_merge($query, array(
			'posts_per_page' => 1,
		));

		$posts = $this->find($query);

		return !empty($posts[0]) ? $posts[0] : null;
	}

	/**
	 * Initialize the repository.
	 *
	 * @since    2.0.0
	 * @uses PHP 5.3
	 *
	 * @return self
	 */
	public static function init()
	{
		include_once(ABSPATH . 'wp-includes/pluggable.php');

		if ( !function_exists('is_user_logged_in') ) :
		/**
		 * Checks if the current visitor is a logged in user.
		 *
		 * @since 2.0.0
		 *
		 * @return bool True if user is logged in, false if not logged in.
		 */
		function is_user_logged_in() {
			$user = wp_get_current_user();

			if ( empty( $user->ID ) )
				return false;

			return true;
		}
		endif;

		return new self(new WP_Query());
	}

	/**
	 * Save a array of posts of selected post type into the repository.
	 *
	 * @since    2.0.0
	 * @param string $post_type
	 * @param array $postarr
	 */
	public function wp_insert_post_1_pt($post_type, $postarr) {
		foreach ($postarr as $key => $value) {
			if (!get_page_by_title( $value, "OBJECT", $post_type )) {
				$my_post = array(
					'post_title'    => $value,
					'post_status'   => 'publish',
					'post_type'   => $post_type
				);
			wp_insert_post( $my_post );
			}
		}
	}

	/**
	 * Save a category into the repository. Returns the category ID or a WP_Error.
	 *
	 * @param array $category
	 *
	 * @return int|WP_Error
	 */
	public function save_category(string $name, string $taxonomy, array $params)
	{
		return wp_insert_term($name, $taxonomy, $params);
	}

	/**
	 * Save a post into the repository. Returns the post ID or a WP_Error.
	 *
	 * @since    2.0.0
	 * @param array $post
	 *
	 * @return int|WP_Error
	 */
	public function save(array $post)
	{
		if (!empty($post['ID'])) {
			return wp_update_post($post, true);
		}
 
		return wp_insert_post($post, true);
	}

	/**
	 * Save a published page.
	 *
	 * @since    2.0.0
	 * @param string $post_name
	 * @param string $content
	 */
	public function save_published_page($post_name, $content) {
		if (!$this->find_by_title($post_name)){
			$this->save(array(
				'post_title' => $post_name,
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_content' => $content,
			));
		}
	}
}