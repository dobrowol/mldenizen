<?php
/* Template: course-topic.php */
get_header();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$term_id         = isset($_GET['term_id']) ? intval($_GET['term_id']) : 0;
$lesson_id       = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
$exercise_number = isset($_GET['exercise_number']) ? intval($_GET['exercise_number']) : 1;
$stage           = isset($_GET['stage']) ? sanitize_text_field($_GET['stage']) : 'exercise';
error_log("Stage: $stage");
error_log("Term ID: $term_id");
error_log("Lesson ID: $lesson_id");
error_log("Exercise Number: $exercise_number");
switch ($stage) {
    case 'module_intro':
        show_module_intro($term_id);
        break;
    case 'lesson_intro':
        show_lesson_intro($lesson_id, $term_id);
        break;
    case 'exercise':
        show_exercise($lesson_id, $exercise_number, $term_id);
        break;
    case 'lesson_summary':
        show_lesson_summary($lesson_id, $term_id);
        break;
    case 'module_summary':
        show_module_summary($term_id);
        break;
    case 'next_lesson_or_module_summary':
        route_to_next_lesson_or_module_summary($lesson_id, $term_id);
        break;
    default:
        echo '<p>Invalid stage.</p>';
        break;
}

get_footer();

function show_module_intro($term_id) {
    $module_intro = get_field('module_intro', 'course_topic_' . $term_id);
    echo '<div class="module-intro">' . wpautop(wp_kses_post($module_intro)) . '</div>';
    echo '<form method="get" action="' . esc_url(get_term_link((int) $term_id, 'course_topic')) . '">';
    echo '<input type="submit" value="Continue">';
    echo '</form>';
}

function show_lesson_intro($lesson_id, $term_id) {
    $intro = get_field('lesson_intro', $lesson_id);
    echo '<div class="lesson-intro">' . wpautop(wp_kses_post($intro)) . '</div>';
    echo '<form method="get">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
    echo '<input type="hidden" name="exercise_number" value="1">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    echo '<input type="hidden" name="stage" value="exercise">';
    echo '<input type="submit" value="Start Lesson">';
    echo '</form>';
}

function show_exercise($lesson_id, $exercise_number, $term_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'exercises';
    $exercise = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE lesson_id = %d AND exercise_number = %d",
        $lesson_id, $exercise_number
    ));

    if (!$exercise) {
        wp_redirect(add_query_arg([
            'stage' => 'lesson_summary',
            'lesson_id' => $lesson_id,
            'term_id' => $term_id
        ], get_permalink()));
        exit;
    }

    echo '<div class="exercise">';
    echo '<h2>' . esc_html($exercise->exercise_title) . '</h2>';
    echo wpautop(wp_kses_post($exercise->exercise_content));
    echo '<form method="post" action="">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
    echo '<input type="hidden" name="exercise_number" value="' . esc_attr($exercise_number) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    echo '<input type="submit" name="submit_answer" value="Submit">';
    echo '</form>';
    echo '</div>';

    // In your submit handler, redirect to next exercise or lesson_summary
}

function show_lesson_summary($lesson_id, $term_id) {
    $summary = get_field('lesson_summary', $lesson_id);
    echo '<div class="lesson-summary">' . wpautop(wp_kses_post($summary)) . '</div>';
    echo '<form method="get">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    echo '<input type="hidden" name="stage" value="next_lesson_or_module_summary">';
    echo '<input type="submit" value="Continue">';
    echo '</form>';
}

function show_module_summary($term_id) {
    $summary = get_field('module_summary', 'course_topic_' . $term_id);
    echo '<div class="module-summary">' . wpautop(wp_kses_post($summary)) . '</div>';
    echo '<a href="' . esc_url(home_url('/courses')) . '">Back to Courses</a>';
}

function route_to_next_lesson_or_module_summary($lesson_id, $term_id) {
    $next = get_next_lesson_id($lesson_id, $term_id);
    if ($next) {
        wp_redirect(add_query_arg([
            'lesson_id' => $next,
            'term_id' => $term_id,
            'exercise_number' => 1,
            'stage' => 'lesson_intro',
        ], get_permalink()));
    } else {
        wp_redirect(add_query_arg([
            'term_id' => $term_id,
            'stage' => 'module_summary',
        ], get_permalink()));
    }
    exit;
}

function get_first_lesson_id($term_id) {
    $lessons = get_posts([
        'post_type' => 'lesson',
        'posts_per_page' => 1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'lesson_number',
        'tax_query' => [[
            'taxonomy' => 'course_topic',
            'field' => 'term_id',
            'terms' => $term_id,
        ]]
    ]);
    return $lessons ? $lessons[0]->ID : 0;
}

function get_next_lesson_id($current_lesson_id, $term_id) {
    $all_lessons = get_posts([
        'post_type' => 'lesson',
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'lesson_number',
        'order' => 'ASC',
        'tax_query' => [[
            'taxonomy' => 'course_topic',
            'field' => 'term_id',
            'terms' => $term_id,
        ]]
    ]);

    foreach ($all_lessons as $i => $lesson) {
        if ($lesson->ID == $current_lesson_id && isset($all_lessons[$i + 1])) {
            return $all_lessons[$i + 1]->ID;
        }
    }
    return 0;
}
