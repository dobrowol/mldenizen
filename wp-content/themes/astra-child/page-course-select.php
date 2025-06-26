<?php
/*
Template Name: Course Select Handler
*/

get_header();

error_log('Course Select Handler loaded.');
if (is_user_logged_in() && isset($_GET['id'], $_GET['redirect'])) {
    error_log('User is logged in and redirecting to: ' . esc_url_raw($_GET['redirect']));
    error_log('Selected course ID: ' . intval($_GET['id']));
    update_user_meta(get_current_user_id(), 'selected_course_id', intval($_GET['id']));
    wp_redirect(esc_url_raw($_GET['redirect']));
    exit;
}

echo '<p>Something went wrong. Please try again.</p>';
get_footer();
