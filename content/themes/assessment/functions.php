<?php
/**
 * Theme functions.
 */

/**
 * Enqueue scripts/styles.
 *
 * @return void
 */
function headless_scripts() {
    wp_enqueue_style( 'headless-style', get_template_directory_uri() . '/style.css', array(), rand() );
    wp_enqueue_style( 'fa-styles', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), '4.2.0' );

    $asset_uri   = trailingslashit(get_template_directory_uri()) . 'assets/build/';
    $asset_root  = trailingslashit(get_template_directory()) . 'assets/build/';
    $asset_files = glob($asset_root . '*.asset.php');

    // Enqueue webpack loader.js, if it exists.
    if (true === is_readable($asset_root . 'loader.js')) {
        wp_enqueue_script(
            'assessment/runtime',
            $asset_uri . 'loader.js',
            array(),
            filemtime($asset_root . 'loader.js')
        );
    }

    foreach ($asset_files as $asset_file) {
        $asset_script = require($asset_file);

        $asset_filename = basename($asset_file);

        $asset_slug_parts = explode('.asset.php', $asset_filename);
        $asset_slug       = array_shift($asset_slug_parts);

        $asset_handle = sprintf('assessment/%s', $asset_slug);

        $stylesheet_path = $asset_root . $asset_slug . '.css';
        $stylesheet_uri  = $asset_uri . $asset_slug . '.css';

        $javascript_path = $asset_root . $asset_slug . '.js';
        $javascript_uri  = $asset_uri . $asset_slug . '.js';

        if (true === is_readable($stylesheet_path)) {
            $style_dependencies = [];

            wp_enqueue_style(
                $asset_handle,
                $stylesheet_uri,
                array(),
                $asset_script['version']
            );
        }

        if (true === is_readable($javascript_path)) {
            wp_enqueue_script(
                $asset_handle,
                $javascript_uri,
                $asset_script['dependencies'],
                $asset_script['version']
            );
        }
    }
}

add_action( 'wp_enqueue_scripts', 'headless_scripts' );

// set permalink
function set_permalink(){
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
}
add_action('init', 'set_permalink');

// Add image support and custom sizes
add_theme_support( 'post-thumbnails' );
add_image_size( 'movie_card', 412, 575 );
add_image_size( 'front_post', 412, 274 );

/**
 * Setup Movies Custom Post Type.
 * Initialize the custom post type and taxonomy.
 */
function setup_movies_cpt(){
    register_movie_post_type();
    register_movie_taxonomy();
}
function register_movie_post_type() {
    $labels = array(
        'name'               => _x( 'Movies', 'post type general name', 'movies-cpt' ),
        'singular_name'      => _x( 'Movie', 'post type singular name', 'movies-cpt' ),
        'menu_name'          => _x( 'Movies', 'admin menu', 'movies-cpt' ),
        'name_admin_bar'     => _x( 'Movie', 'add new on admin bar', 'movies-cpt' ),
        'add_new'            => _x( 'Add New', 'movie', 'movies-cpt' ),
        'add_new_item'       => __( 'Add New Movie', 'movies-cpt' ),
        'new_item'           => __( 'New Movie', 'movies-cpt' ),
        'edit_item'          => __( 'Edit Movie', 'movies-cpt' ),
        'view_item'          => __( 'View Movie', 'movies-cpt' ),
        'all_items'          => __( 'All Movies', 'movies-cpt' ),
        'search_items'       => __( 'Search Movies', 'movies-cpt' ),
        'parent_item_colon'  => __( 'Parent Movies:', 'movies-cpt' ),
        'not_found'          => __( 'No movies found.', 'movies-cpt' ),
        'not_found_in_trash' => __( 'No movies found in Trash.', 'movies-cpt' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'movies-cpt' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true, 
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'movie' ),
        'capability_type'    => 'post',
        'has_archive'        => true, 
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'movie', $args );
}
function register_movie_taxonomy() {
    $labels = array(
        'name'              => _x( 'Genres', 'taxonomy general name', 'movies-cpt' ),
        'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'movies-cpt' ),
        'search_items'      => __( 'Search Genres', 'movies-cpt' ),
        'all_items'         => __( 'All Genres', 'movies-cpt' ),
        'parent_item'       => __( 'Parent Genre', 'movies-cpt' ),
        'parent_item_colon' => __( 'Parent Genre:', 'movies-cpt' ),
        'edit_item'         => __( 'Edit Genre', 'movies-cpt' ),
        'update_item'       => __( 'Update Genre', 'movies-cpt' ),
        'add_new_item'      => __( 'Add New Genre', 'movies-cpt' ),
        'new_item_name'     => __( 'New Genre Name', 'movies-cpt' ),
        'menu_name'         => __( 'Genre', 'movies-cpt' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );

    register_taxonomy( 'genre', array( 'movie' ), $args );
}
/**
 * Create new pages, movie posts and regular posts. 
 */
function create_new_pages_and_posts() {
    create_new_pages();
    create_new_movie_posts();
    create_five_posts();
}
function create_new_pages() {
    $template = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_author' => 1
    );
    $pages = array(
        array(
            'title'    => 'Homepage',
            'content'  => 'This is the home page.',
            'page_template' => 'archive-movie.php',
        ),
        array(
            'title'         => 'Single Movie',
            'content'       => 'This is the single Movie page.',
            'page_template' => 'single-movie.php',
        ),
        array(
            'title'         => 'Single Post',
            'content'       => 'This is the single post page.',
            'page_template' => 'single.php',
        ),
        array(
            'title'         => 'Single Page',
            'content'       => 'This is the single page.',
            'page_template' => 'page.php',
        ),
        array(
            'title'         => 'About',
            'content'       => 'This is the about page.',
            'page_template' => 'page.php',
        ),
    );
    foreach ($pages as $page) {
        $exists = get_page_by_title($page['title']);

        if (!$exists) {
            $my_page = array(
                'post_title'    => $page['title'],
                'post_content'  => $page['content'],
                'page_template' =>$page['page_template'],
            );
            $my_page = array_merge($my_page, $template);
            wp_insert_post($my_page);
        }
    }
}
function create_new_movie_posts() {
    $movie_posts = array(
        array(
            'title'   => 'The Shawshank Redemption',
            'content' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
            'image'   => 'https://live.staticflickr.com/21/25725273_cd47f38434_b.jpg',
            'term'    => 'Drama',
        ),
        array(
            'title'   => 'The Godfather',
            'content' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
            'image'   => 'https://live.staticflickr.com/1054/1096144644_96faeaa43c_k.jpg',
            'term'    => 'Crime',
        ),
        array(
            'title'   => 'The Dark Knight',
            'content' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
            'image'   => 'https://live.staticflickr.com/7091/7290572796_db2b73ea3f_c.jpg',
            'term'    => 'Action',
        ),
        array(
            'title'   => 'The Godfather: Part II',
            'content' => 'The early life and career of Vito Corleone in 1920s New York City is portrayed, while his son, Michael, expands and tightens his grip on the family crime syndicate.',
            'image'   => 'https://live.staticflickr.com/4146/4845693386_3b12358f7c_c.jpg',
            'term'    => 'Crime',
        ),
        array(
            'title'   => '12 Angry Men',
            'content' => 'A jury holdout attempts to prevent a miscarriage of justice by forcing his colleagues to reconsider the evidence.',
            'image'   => 'https://m.media-amazon.com/images/I/917ckvQ1LbL._SL1500_.jpg',
            'term'    => 'Drama',
        ),
        array(
            'title' => 'Schindler\'s List',
            'content' => 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.',
            'image' => 'https://live.staticflickr.com/3077/2666977523_e8d700b205_b.jpg',
            'term' => 'Biography',
        ),
        array(
            'title' => 'The Lord of the Rings: The Return of the King',
            'content' => 'Gandalf and Aragorn lead the World of Men against Sauron\'s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.',
            'image' => 'https://m.media-amazon.com/images/I/71Jazc-7zWL._AC_SL1000_.jpg',
            'term' => 'Action',
        ),
        array(
            'title'   => 'Pulp Fiction',
            'content' => 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.',
            'image'   => 'https://m.media-amazon.com/images/I/61w0wMweB2L._AC_SL1000_.jpg',
            'term'    => 'Crime',
        ),
        array(
            'title'   => 'The Good, the Bad and the Ugly',
            'content' => 'A bounty hunting scam joins two men in an uneasy alliance against a third in a race to find a fortune in gold buried in a remote cemetery.',
            'image'   => 'https://m.media-amazon.com/images/I/61Gt0pfhtUL._SL1010_.jpg',
            'term'    => 'Western',
        ),
        array(
            'title'   => 'Fight Club',
            'content' => 'An insomniac office worker and a devil-may-care soapmaker form an underground fight club that evolves into something much, much more.',
            'image'   => 'https://m.media-amazon.com/images/I/61IUdN-J-7L._AC_SL1500_.jpg',
            'term'    => 'Drama',
        ),

    );
    foreach ($movie_posts as $movie => $movie_post) {
        create_movie_post($movie, $movie_post['term'], $movie_post['title'], $movie_post['content'], $movie_post['image']);
    }
}
function create_movie_post($movie, $movie_term, $title, $content, $image_name){
    global $typenow, $pagenow;
    if ( $pagenow === 'edit.php' && $typenow === 'movie' ) {
        if ( isset( $_GET['deleted'] ) ) {
            return;
        }
    }
    if (!post_exists( $title, $content, '', 'movie', '' )){
        $post_id = wp_insert_post(
            array(
                'post_title' => $title,
                'post_type' => 'movie',
                'post_status' => 'publish',
                'post_content' => $content,
                'post_excerpt' => $content,
                'post_name' => sanitize_title($title),
                'post_author' => 1,
                'post_date' => date('Y-m-d H:i:s'),
                'post_date_gmt' => date('Y-m-d H:i:s'),
            )
        );
        if ($post_id) {
            $attachment_id = get_attachment_id_from_url($post_id, $image_name);
            // Set image as featured image
            set_post_thumbnail( $post_id, $attachment_id );
            // Finally, set the terms for the movie
            wp_set_object_terms( $post_id, $movie_term, 'genre' );
        }
    }
}
add_action('after_setup_theme', 'setup_movies_cpt');
add_action( 'admin_init', 'create_new_pages_and_posts' );
/**
 * Add custom image size to rest api.
 */
function add_movie_image_sizes() {
    register_rest_field( 'movie', 'movie_card', array(
        'get_callback'    => 'get_movie_card_image',
        'update_callback' => null,
        'schema'          => null,
    ));
    register_rest_field( 'post', 'front_post', array(
        'get_callback'    => 'get_front_post_image',
        'update_callback' => null,
        'schema'          => null,
    ));
}
function get_movie_card_image( $object, $field_name, $request ) {
    $image_id = get_post_thumbnail_id( $object['id'] );
    $image = wp_get_attachment_image_src( $image_id, 'movie_card' );
    return $image[0];
}
function get_front_post_image( $object, $field_name, $request ) {
    $image_id = get_post_thumbnail_id( $object['id'] );
    $image = wp_get_attachment_image_src( $image_id, 'front_post' );
    return $image[0];
}
/** 
 * Attachment ID helper function.
 */
function get_attachment_id_from_url( $post_id, $attachment_url = '' ) {
    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();
    // $filename should be the path to the files that you stored in the uploads base directory.
    $file_url = $attachment_url;
    // Getting the name
    $fname = pathinfo(parse_url($file_url)['path'], PATHINFO_FILENAME);
    // Getting the extension
    $fext = pathinfo(parse_url($file_url)['path'], PATHINFO_EXTENSION);
    // Get the contents of the image, metadata, and the image size.            
    $image_data = file_get_contents( $file_url ); 
    // Generate unique name
    $unique_file_name = wp_unique_filename( $wp_upload_dir['path'], $fname . '.' . $fext);
    // Create image file name
    $filename = basename( $unique_file_name );
    // The ID of the post this attachment is for.
    $attach_to_post_id = $post_id;

    if ( wp_mkdir_p( $wp_upload_dir['path'] ) ) {
        $file = $wp_upload_dir['path'] . '/' . $fname . '.' . $fext;
    }
    else {
        $file = $wp_upload_dir['basedir'] . '/' . $filename;
    }
    // Add image to uploads directory
    file_put_contents( $file, $image_data );
    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( $filename, null );
    // Prepare an array of post data for the attachment.
    $attachment = array(
        // 'guid'           => $wp_upload_dir['path'] . '/' . basename( $filename ), 
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $file, $attach_to_post_id );
    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    // return the attachment id
    return $attach_id;
}
function get_bacon_ipsum() {
    $request = wp_remote_post('https://baconipsum.com/api/?type=all-meat&paras=2&start-with-lorem=1');
    $post_content = wp_remote_retrieve_body($request);
    $post_content = json_decode($post_content);
    return $post_content[0];
}
function create_five_posts() {
    $images_url_response = wp_remote_get('https://shibe.online/api/shibes?count=5');
    $images_array = wp_remote_retrieve_body( $images_url_response );
    $images_array = json_decode($images_array);
    for ($i = 1; $i <= 5; $i++) {
        if ( !post_exists( 'Post Title No.' . $i, '', '', 'post', 'publish' ) ) {
            $post_title = 'Post Title No.' . $i;
            $post_content = get_bacon_ipsum();
            $post_id = wp_insert_post(
                array(
                    'post_title' => $post_title,
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'post_content' => $post_content,
                    'post_excerpt' => $post_content,
                    'post_name' => 'post-' . $i,
                    'post_author' => 1,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                )
            );
            // set featured image
            $image_url = $images_array[$i - 1];
            $attachment_id = get_attachment_id_from_url($post_id, $image_url);
            // Set image as featured image
            set_post_thumbnail( $post_id, $attachment_id );
        }
    }
}
 