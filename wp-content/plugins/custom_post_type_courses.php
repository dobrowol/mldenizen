<?php
/**
 * Plugin Name: ML Courses Plugin
 * Description: Registers a custom post type "Course" and taxonomy "Course Topic" for managing ML/DL and paths.
 * Version: 1.0
 * Author: Wojciech Dobrowolski
 */

// Register Custom Post Type: Course
function register_course_post_type() {
    $labels = array(
        'name'                  => _x('Courses', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Course', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Courses', 'textdomain'),
        'name_admin_bar'        => __('Course', 'textdomain'),
        'archives'              => __('Course Archives', 'textdomain'),
        'attributes'            => __('Course Attributes', 'textdomain'),
        'parent_item_colon'     => __('Parent Course:', 'textdomain'),
        'all_items'             => __('All Courses', 'textdomain'),
        'add_new_item'          => __('Add New Course', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'new_item'              => __('New Course', 'textdomain'),
        'edit_item'             => __('Edit Course', 'textdomain'),
        'update_item'           => __('Update Course', 'textdomain'),
        'view_item'             => __('View Course', 'textdomain'),
        'search_items'          => __('Search Courses', 'textdomain'),
        'not_found'             => __('Not found', 'textdomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
        'featured_image'        => __('Course Image', 'textdomain'),
        'set_featured_image'    => __('Set course image', 'textdomain'),
        'remove_featured_image' => __('Remove course image', 'textdomain'),
        'use_featured_image'    => __('Use as course image', 'textdomain'),
        'insert_into_item'      => __('Insert into course', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this course', 'textdomain'),
        'items_list'            => __('Courses list', 'textdomain'),
        'items_list_navigation' => __('Courses list navigation', 'textdomain'),
        'filter_items_list'     => __('Filter courses list', 'textdomain'),
    );

    $args = array(
        'label'                 => __('Course', 'textdomain'),
        'description'           => __('Machine Learning and Deep Learning Courses', 'textdomain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array('course_topic'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'rewrite'               => array('slug' => 'courses'),
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'capability_type'       => 'post',
    );

    register_post_type('course', $args);
}
add_action('init', 'register_course_post_type', 0);

add_action('init', function() {
    add_rewrite_tag('%set_course%', '([^&]+)');
});

add_filter('query_vars', function($vars) {
    $vars[] = 'set_course';
    return $vars;
});


// Register Custom Taxonomy: Course Topic
function register_course_topic_taxonomy() {
    $labels = array(
        'name'                       => _x('Course Topics', 'taxonomy general name', 'textdomain'),
        'singular_name'              => _x('Course Topic', 'taxonomy singular name', 'textdomain'),
        'search_items'               => __('Search Topics', 'textdomain'),
        'popular_items'              => __('Popular Topics', 'textdomain'),
        'all_items'                  => __('All Topics', 'textdomain'),
        'parent_item'                => __('Parent Topic', 'textdomain'),
        'parent_item_colon'          => __('Parent Topic:', 'textdomain'),
        'edit_item'                  => __('Edit Topic', 'textdomain'),
        'update_item'                => __('Update Topic', 'textdomain'),
        'add_new_item'               => __('Add New Topic', 'textdomain'),
        'new_item_name'              => __('New Topic Name', 'textdomain'),
        'separate_items_with_commas' => __('Separate topics with commas', 'textdomain'),
        'add_or_remove_items'        => __('Add or remove topics', 'textdomain'),
        'choose_from_most_used'      => __('Choose from the most used topics', 'textdomain'),
        'not_found'                  => __('No topics found.', 'textdomain'),
        'menu_name'                  => __('Course Topics', 'textdomain'),
    );

    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'course-topic'),
        'show_in_rest'          => true,
    );

    register_taxonomy('course_topic', array('course'), $args);
}
add_action('init', 'register_course_topic_taxonomy', 0);
