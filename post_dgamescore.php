<?php

add_action('init', 'dgamescore_init');

function dgamescore_init() {

    $args = array(
        'labels' => array(
            'name' => __( 'Игровой рейтинг' ),
            'singular_name' => __( 'Игровой рейтинг' ),
            'add_new' => __( 'Добавить элемент' ),
        ),
        'public' => true,
        'has_archive' => false,
        // 'taxonomies' => array('dgamescore_category'),
		'rewrite' => array( 'slug' => 'dgamescores' ),
		// 'supports' => array('title', 'editor','thumbnail', 'excerpt', 'custom-fields'),
		'supports' => array('title', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
        'menu_icon' => 'dashicons-chart-bar',

		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_in_admin_bar' => false,
		'show_in_rest' => false,

		// 'capabilities' => array(
		// 	'create_posts' => 'do_not_allow', // Removes support for the "Add New" function, including Super Admin's
		// ),
		// 'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts
    );

    // register_taxonomy(
	// 	'dgamescore_category',
	// 	'dgamescore',
	// 	array(
	// 		'label' => __( 'Категория Игровой рейтинг' ),
    //         'hierarchical' => true,
    //         'public' => true,
    //         'show_ui' => true,
	// 	)
	// );

    // регистрируем новый тип
    register_post_type('dgamescore', $args); // первый параметр - это название нашего нового типа данных
}

if( 1 || class_exists('ACF') ) :


// http://justintadlock.com/archives/2011/06/27/custom-columns-for-custom-post-types

#Adding custom columns


add_filter( 'manage_edit-dgamescore_columns', 'my_edit_dgamescore_columns' ) ;

function my_edit_dgamescore_columns( $columns0 ) {

	$columns = array(
		'cb' => '&lt;input type="checkbox" />',
		// 'title' => __( 'Заголовок' ),
		'author' => __( 'Пользователь' ),
		'e_game' => __( 'Игра' ),
		'e_score' => __( 'Очки' ),
		'date' => __( 'Время' ),
	);

	return $columns;
	// return array_merge($columns, $columns0);
}

#Adding content to custom columns
add_action( 'manage_dgamescore_posts_custom_column', 'my_manage_dgamescore_columns', 10, 2 );

function my_manage_dgamescore_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		case 'e_datetime' :

			$duration = get_post_meta( $post_id, 'e_datetime', true );
			// $duration = get_field( $post_id, 'e_datetime' );

			if ( empty( $duration ) )
				echo __( 'Unknown' );

			else
				printf( $duration );

			break;
		case 'e_game' :

			$duration = get_post_meta( $post_id, 'e_game', true );
			// $duration = get_field( $post_id, 'e_datetime' );

			if ( empty( $duration ) )
				echo __( 'Unknown' );

			else
				printf( $duration );

			break;

		case 'e_score' :

			$duration = get_post_meta( $post_id, 'e_score', true );
			// $duration = get_field( $post_id, 'e_datetime' );

			if ( empty( $duration ) )
				echo __( 'Unknown' );

			else
				printf( $duration );

			break;


		default :
			break;
	}
}

#Making custom columns sortable
add_filter( 'manage_edit-dgamescore_sortable_columns', 'my_dgamescore_sortable_columns' );

function my_dgamescore_sortable_columns( $columns ) {

	$columns['e_datetime'] = 'e_datetime';
	$columns['e_game'] = 'e_game';
	$columns['e_score'] = 'e_score';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_dgamescore_load' );

function my_edit_dgamescore_load() {
	add_filter( 'request', 'my_sort_dgamescores' );
}

/* Sorts the dgamescores. */
function my_sort_dgamescores( $vars ) {

	/* Check if we're viewing the 'dgamescore' post type. */
	if ( isset( $vars['post_type'] ) && 'dgamescore' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'e_datetime' == $vars['e_datetime'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'e_datetime',
					'orderby' => 'meta_value_num'
				)
			);
		}
		else if ( isset( $vars['orderby'] ) && 'e_game' == $vars['e_game'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'e_game',
					'orderby' => 'meta_value_num'
				)
			);
		}
		else if ( isset( $vars['orderby'] ) && 'e_score' == $vars['e_score'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'e_score',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

endif;