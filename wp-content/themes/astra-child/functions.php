<?php
require_once get_stylesheet_directory() . '/include/course_functions.php';

add_filter('show_admin_bar', function($show) {
    return current_user_can('administrator') ? $show : false;
});

// Enqueue parent and child theme styles
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );
function astra_child_enqueue_styles() {
    wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );
}

function ml_denizen_login_logout_shortcode() {
    if ( is_user_logged_in() ) {
        return '<a href="' . esc_url( wp_logout_url( home_url() ) ) . '" class="ast-custom-button">Log Out</a>';
    } else {
        return '<a href="' . esc_url( site_url( '/login/' ) ) . '" class="ast-custom-button">Log In</a>';
    }
}
add_shortcode( 'login_logout_button', 'ml_denizen_login_logout_shortcode' );


add_action('wp_footer', 'ml_selected_course_button');

function ml_selected_course_button() {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $course_id = get_user_meta($user_id, 'selected_course_id', true);

    if (!$course_id) return;

    $course_title = get_the_title($course_id);
    $flag_url = get_the_post_thumbnail_url($course_id, 'thumbnail');
    $short = stripos($course_title, 'deep') !== false ? 'DL' : 'ML';
    $img = $flag_url ? '<img src="' . esc_url($flag_url) . '" style="height: 20px; margin-right: 6px;">' : '';
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let loginArea = document.querySelector('.ast-builder-grid-row');

            let exactLocation = document.querySelector('.site-header-primary-section-right-center');

            if (!loginArea) return;
            const btn = document.createElement('a');
            btn.href = "<?php echo esc_url(home_url('/courses/')); ?>";
            // btn.innerHTML = `<?php echo $img; ?><strong><?php echo esc_html($short); ?></strong>`;
            btn.innerHTML = `<strong><?php echo esc_html($short); ?></strong>`;
            btn.className = "ml-course-switch-button";

            btn.style.cssText = `
                display: inline-flex;
                align-items: center;
                padding: 2px 6px;
                font-size: 13px;
                line-height: 1;
                border-radius: 4px;
                height: auto;
                white-space: nowrap;
                margin-left: 6px;
                background: #f5f5f5;
                border: 1px solid #ccc;
                color: #333;
                text-decoration: none;
                vertical-align: middle;
                `;


            btn.onmouseover = () => btn.style.background = "#eaeaea";
            btn.onmouseout = () => btn.style.background = "#f5f5f5";

            if (loginArea) {
                exactLocation.appendChild(btn); // or use appendChild(btn)
            } else {
                console.log('⚠️ Login area not found, falling back to header');
                let fallbackHeader = document.querySelector('header');
                if (fallbackHeader) fallbackHeader.appendChild(btn);
            }
        });
    </script>
    <?php
}



function ml_courses_grid_shortcode() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!is_user_logged_in()) {
        return '<p>Please <a href="' . wp_login_url() . '">log in</a> to select a course.</p>';
    }

    $user_id = get_current_user_id();

    // Save course selection if present
    if (isset($_GET['set_course'])) {
        $selected_course_id = intval($_GET['set_course']);
        update_user_meta($user_id, 'selected_course_id', $selected_course_id);
    }

    // Retrieve user's selected course
    $selected_id = get_user_meta($user_id, 'selected_course_id', true);


    $args = array(
        'post_type'      => 'course',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );
    $courses = get_posts($args); // ← use get_posts (not WP_Query loop)

    ob_start();
    ?>
    <style>
        .ml-course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }
        .ml-course-card {
            position: relative;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            transition: 0.2s;
            text-decoration: none;
            color: inherit;
        }
        .ml-course-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .ml-course-card.selected {
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40,167,69,0.2);
        }
        .ml-course-flag img {
            width: 48px;
            height: auto;
            margin-bottom: 12px;
        }
        .ml-course-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .checkmark {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #28a745;
        }
    </style>

    <div class="ml-course-grid">
    <?php foreach ($courses as $course): 
        $is_selected = $course->ID == $selected_id;
        $flag = get_field('flag', $course->ID);
        // $link = add_query_arg('set_course', $course->ID, get_permalink($course->ID));
        $link = add_query_arg([
            'id' => $course->ID,
            'redirect' => urlencode(get_permalink($course->ID))
        ], home_url('/course-select/'));
    ?>
        <a href="<?php echo esc_url($link); ?>" class="ml-course-card<?php echo $is_selected ? ' selected' : ''; ?>">
            <?php if ($is_selected): ?>
                <div class="checkmark">✅</div>
            <?php endif; ?>
            <div class="ml-course-flag">
                <?php if ($flag): ?>
                    <img src="<?php echo esc_url($flag['url']); ?>" alt="Flag">
                <?php elseif (has_post_thumbnail($course->ID)): ?>
                    <?php echo get_the_post_thumbnail($course->ID, 'thumbnail'); ?>
                <?php endif; ?>
            </div>
            <div class="ml-course-title"><?php echo esc_html(get_the_title($course->ID)); ?></div>
        </a>
    <?php endforeach; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('ml_course_cards', 'ml_courses_grid_shortcode');

function handle_submit_continue() {
    unset($_SESSION['last_answer_feedback']);
    global $wpdb;
    unset($_SESSION['lesson_description']);
    // If this came from BKT, and they’ve now hit 4, force summary
    if ( ! empty($_POST['via_bkt']) && ! empty($_SESSION['bkt_streak_count']) 
        && $_SESSION['bkt_streak_count'] >= 4 ) 
    {
        error_log("BKT streak count is ".$_SESSION['bkt_streak_count']);
        // clear and redirect summary
        $redirect_url = add_query_arg( array(
            'bkt_streak_count'   => $_SESSION['bkt_streak_count']
        ), get_permalink( get_page_by_path('summary') ) );
        unset($_SESSION['bkt_streak_count']);
        wp_redirect( $redirect_url );
        exit;
    }

    // Otherwise—if via_bkt—just reload clean to grab next BKT
    if ( ! empty($_POST['via_bkt']) ) {
        $term_id = intval( $_POST['term_id'] );
        error_log("Continue BKT streak count is ".$_SESSION['bkt_streak_count']);
        $clean = remove_query_arg( ['lesson_id','exercise_number'], get_permalink() );
        $redirect_url = add_query_arg( array(
            'term_id'   => $term_id
        ), $clean );
        wp_redirect( $redirect_url );
        exit;
    }
    // Get the current exercise ID (passed via hidden field 'exercise_id')
    $current_exercise_id = intval($_POST['exercise_id']);
    error_log("continue from exercise id ".$current_exercise_id);
    // Retrieve the current exercise from the database.
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';
    $current_exercise = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $current_exercise_id)
    );
    
    if ( $current_exercise ) {
        error_log("found current exercise");
        // Get the lesson_id and the current exercise_number for the exercise.
        $lesson_id = $current_exercise->lesson_id;
        $current_number = $current_exercise->exercise_number+1;
        error_log("lesson id".$lesson_id);
        error_log("current number".$current_number);
        
        // Query for the next exercise in the same lesson, with a higher exercise_number.
        $next_exercise = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE lesson_id = %d AND exercise_number = %d",
                $lesson_id, $current_number
            )
        );
        
        if ( $next_exercise ) {
            // Build the URL so that it contains the lesson_id and (optionally) the new exercise_number.
            // For example, you might want to pass the next exercise_number as well.
            $redirect_url = add_query_arg( array(
                'lesson_id'   => $lesson_id,
                'exercise_number' => $current_number,
                'term_id'         => $term_id,   // optional but good
                'stage'           => 'exercise', // 👈 this is essential
            ), get_permalink() );
            wp_redirect( $redirect_url );
            exit;
        } else {
            // No more exercises in this lesson; redirect to the summary page.
            // Make sure to change the page slug if needed.
            $course_id = $_SESSION['selected_course_id'] ?? 0;
            $term_id = intval($_POST['term_id'] ?? 0);

            $summary_url = add_query_arg([
                'lesson_id' => $lesson_id,
                'term_id'   => $term_id,
                'stage'     => 'lesson_summary'
            ], get_permalink($course_id));

            wp_redirect( $summary_url );
            exit;
        }
    } else{
        error_log("could not find current exercise");
    }
    
    // Fallback redirection:
    wp_redirect( add_query_arg( 'new_exercise', '1', get_permalink() ) );
    exit;
}

function ml_allow_custom_query_vars($vars) {
    $vars[] = 'stage';
    $vars[] = 'lesson_id';
    $vars[] = 'term_id';
    return $vars;
}
add_filter('query_vars', 'ml_allow_custom_query_vars');

function get_image_url_by_name($filename) {
    $args = [
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'     => '_wp_attached_file',
                'value'   => $filename . '.png',
                'compare' => 'LIKE',
            ],
        ],
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        return wp_get_attachment_url($query->posts[0]->ID);
    }
    return null;
}

function ml_duolingo_learning_path_shortcode() {
    if (!defined('M_PI')) {
        define('M_PI', 3.141592653589793);
    }
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ( isset($_POST['submit_continue']) ) {
        handle_submit_continue();
    }
    $course_id = null;

    $course_id = $_SESSION['selected_course_id'] ?? null;
    if (!$course_id) return '<p>Please select a course first.</p>';

    $stage = $_GET['stage'] ?? null;
    error_log('ml_duolingo_learning_path_shortcode::Stage: ' . $stage);

    if ($stage === 'module_intro') {
        $term_id = intval($_GET['term_id'] ?? 0);
        return show_module_intro($term_id);
    }

    if ($stage === 'lesson_intro') {
        $term_id = intval($_GET['term_id'] ?? 0);
        $lesson_id = intval($_GET['lesson_id'] ?? 0);
        return show_lesson_intro($lesson_id, $term_id);
    }

    if ($stage === 'exercise') {
        error_log('Stage: exercise');
        $term_id = intval($_GET['term_id'] ?? 0);
        $lesson_id = intval($_GET['lesson_id'] ?? 0);
        $exercise_number = intval($_GET['exercise_number'] ?? 1);
        return show_exercise($lesson_id, $exercise_number, $term_id);
    }

    if ($stage === 'lesson_summary') {
        $term_id = intval($_GET['term_id'] ?? 0);
        $lesson_id = intval($_GET['lesson_id'] ?? 0);
        return show_lesson_summary($lesson_id, $term_id);
    }

    if ($stage === 'module_summary') {
        $term_id = intval($_GET['term_id'] ?? 0);
        return show_module_summary($term_id);
    }

    if ($stage === 'mind_map') {
        error_log('Stage: mind_map');
        $term_id = intval($_GET['term_id'] ?? 0);
        $lesson_id = intval($_GET['lesson_id'] ?? 0);
        return show_mind_map_stage($lesson_id, $term_id);
    }
    error_log('Is taxonomy: ' . is_tax('course_topic'));
    // Try to infer from course_topic term
    if (!$course_id) {
        $term = get_queried_object();
        error_log('Term ID: ' . $term->term_id);
        if ($term instanceof WP_Term) {
            error_log('Term Name: ' . $term->name.' is instance of WP_Term');
            $parent_course = get_field('parent_course', 'course_topic_' . $term->term_id);
            if ($parent_course instanceof WP_Post) {
                $course_id = $parent_course->ID;
                $_SESSION['selected_course_id'] = $course_id; // optionally cache it
            }
        }
    }

    if (!$course_id) {
        return '<p>Please select a course first.</p>';
    }


    $course_id = intval($_SESSION['selected_course_id']);
    $current_user = get_current_user_id();
    $completed_lessons = get_user_meta($current_user, 'completed_lessons', true) ?: [];
    $unlocked_modules = [];

    $terms = get_terms([
        'taxonomy' => 'course_topic',
        'hide_empty' => false,
    ]);

    $modules = [];

    foreach ($terms as $term) {
        $linked_course = get_field('parent_course', 'course_topic_' . $term->term_id);
        if ($linked_course && intval($linked_course->ID) === $course_id) {
            // Get the custom module order field (set via ACF)
            $order = get_field('module_order', 'course_topic_' . $term->term_id);
            $modules[] = [
                'term'  => $term,
                'order' => is_numeric($order) ? intval($order) : PHP_INT_MAX, // fallback for missing field
            ];
        }
    }

    // Sort by the ACF module order
    usort($modules, fn($a, $b) => $a['order'] <=> $b['order']);

    // Keep only the terms
    $modules = array_column($modules, 'term');

    if (empty($modules)) return '<p>No modules found for this course.</p>';

    ob_start();
    ?>
    <style>
        .neuron-wrapper {
            background-image: url('https://mldenizen.com/wp-content/uploads/2025/05/neuron_no_background.png');
            background-repeat: no-repeat;
            background-position: center ; /* shift up if needed */
            background-size: 600px auto;       /* or exact pixel width */
            overflow: visible;
            position: relative;
            z-index: 0;
            min-height: 600px; /* ensure it can fit the whole neuron image */
        }
        .ml-module {
            position: relative;
            width: 420px;
            height: 380px;
            margin: 60px auto;
            overflow: visible; /* allow background to overflow */
        }

        .module-node-wrapper {
            position: absolute;
            top: 330px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }

        .lesson-circle {
            position: absolute;
            top: 400px;
            left: 0;
            width: 100%;
            height: 220px;
        }

        .lesson-positioned {
            position: absolute;
            transform: translate(-50%, -50%);
        }

        .ml-module .lesson-cluster:not(:first-child) {
            margin-top: 20px;           /* reduce spacing between lessons */
        }
        .lesson-cluster {
            position: relative;
            width: 100px;       /* optional: slightly tighter width */
            height: 100px;      /* reduced height */
            margin: 30px auto;  /* reduced top/bottom margin */
        }

        .lesson-image-link {
            display: block;
            width: 80px;
            height: 80px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease;
        }

        .lesson-image-link:hover {
            transform: scale(1.05);
        }

        .lesson-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .lesson-image-link.completed {
            box-shadow: 0 0 8px #4CAF50;
        }

        .lesson-image-link.enabled {
            box-shadow: 0 0 6px #87CEEB;
        }

        .lesson-image-link.disabled {
            opacity: 0.4;
            pointer-events: none;
        }


        .module-node {
            width: 80px;
            height: 80px;
            background: #6a1b9a;
            color: white;
            font-size: 14px;
            line-height: 80px;
        }

        .exercise-group {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }

        .exercise-circle {
            position: absolute;
            width: 16px;
            height: 16px;
            background: #aaa;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        .exercise-circle.completed {
            background: #4CAF50; /* Green for completed lessons */
        }
        .module-icon-link {
            display: block;
            width: 80px;
            height: 80px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
            
            border-radius: 50%; /* make it round */
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease;
        }

        .module-icon-link.disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        .module-icon {
            width: 100%;
            height: 100%;
            object-fit: cover; /* ensures image fills the circular container */
            border-radius: 50%;
            display: block;
            background: transparent !important;
            box-shadow: none;
            transition: transform 0.2s ease;
        }

        .module-icon-link:hover .module-icon {
            transform: scale(1.05);
        }

        /* ✅ Purple glow when completed */
        .module-icon-link.completed .module-icon {
            box-shadow: 0 0 10px 3px #a020f0;
        }

        .connection-line {
            position: absolute;
            height: 2px;
            background: rgba(160, 160, 255, 0.5); /* soft blue line */
            transform-origin: left center;
            z-index: 1;
        }
        .ml-course-visual,
        .ml-learning-path-content,
        .ml-course-inner,
        .ml-course-single {
            overflow: visible !important;
            position: relative;
        }
    </style>

    <div class="ml-course-visual">
    <?php
    $module_index = 0;
    error_log('Modules found: ' . count($modules));
    foreach ($modules as $module):
        $lessons = get_posts([
            'post_type' => 'lesson',
            'tax_query' => [[
                'taxonomy' => 'course_topic',
                'field' => 'term_id',
                'terms' => $module->term_id,
            ]],
            'orderby' => 'meta_value_num',
            'meta_key' => 'lesson_number',
            'order' => 'ASC',
            'posts_per_page' => -1,
        ]);

        $first_lesson_id = $lessons[0]->ID ?? 0;
        $all_lessons_completed = true;
        foreach ($lessons as $lesson) {
            if (!in_array($lesson->ID, $completed_lessons)) {
                $all_lessons_completed = false;
                break;
            }
        }

        $module_enabled = $module_index === 0 || in_array($modules[$module_index - 1]->term_id, $unlocked_modules);
        $module_clickable = $module_index === 0 || $module_enabled;

        $module_url = add_query_arg([
            'term_id' => $module->term_id,
            'stage' => 'module_intro'
        ], get_permalink($_SESSION['selected_course_id']));

        ?>
        <div class="neuron-wrapper">
            <div class="ml-module">
                <div class="module-node-wrapper">
                    <?php if ($module_clickable): ?>
                        <a href="<?php echo esc_url($module_url); ?>" class="module-icon-link<?php echo $all_lessons_completed ? ' completed' : ''; ?>">
                        <img src="https://mldenizen.com/wp-content/uploads/2025/05/module-3.png" alt="<?php echo esc_attr($module->name); ?>" class="module-icon">
                        </a>
                    <?php else: ?>
            <div class="module-icon-link disabled">
                <img src="https://mldenizen.com/wp-content/uploads/2025/05/module-3.png" alt="<?php echo esc_attr($module->name); ?>" class="module-icon">
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="lesson-circle">
        <?php
        $lesson_count = count($lessons);
        foreach ($lessons as $index => $lesson):
            $lesson_id = $lesson->ID;
            $is_completed = in_array($lesson_id, $completed_lessons);
            $is_enabled = $module_clickable && ($index === 0 || in_array($lessons[$index - 1]->ID, $completed_lessons));

            $lesson_url = add_query_arg([
                'term_id' => $module->term_id,
                'lesson_id' => $lesson_id,
                'exercise_number' => 1,
                'stage' => 'lesson_intro'
            ], get_permalink(get_page_by_path('course-topic')));

            global $wpdb;
            $table = $wpdb->prefix . 'exercises';
            $exercises = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table WHERE lesson_id = %d ORDER BY exercise_number ASC", $lesson_id)
            );
            $num_ex = count($exercises);

            $angle_span = 150;
            $angle_start = 90 + ($angle_span / 2); // 150°
            $angle_end = 90 - ($angle_span / 2);   // 30°
            $angle_step = $angle_span / max($lesson_count - 1, 1);

            $angle_deg = $angle_start - ($index * $angle_step);
            $angle_rad = deg2rad($angle_deg);

            $radius = 180;
            $x = $radius * cos($angle_rad);
            $y = $radius * sin($angle_rad);

            $style = "left: calc(50% + {$x}px); top: {$y}px;";

            // Draw line to module (top center)
            $line_length = sqrt($x * $x + $y * $y);
            $rotate_angle = atan2($y, $x) * (180 / M_PI);
            $line_style = sprintf(
                'left: calc(50%%); top: 0%%; width: %.2fpx; transform: rotate(%.2fdeg);',
                $line_length,
                $rotate_angle
            );
            $image_url = get_image_url_by_name('ex-' . min($num_ex, 10));
            error_log("Image URL: " . $image_url);
            ?>
            <div class="lesson-positioned" style="<?php echo $style; ?>">
                <a href="<?php echo esc_url($lesson_url); ?>" class="lesson-image-link<?php echo $is_completed ? ' completed' : ($is_enabled ? ' enabled' : ' disabled'); ?>">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" class="lesson-image" alt="Lesson Icon">
                    <?php endif; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
        <?php if ($all_lessons_completed) $unlocked_modules[] = $module->term_id; ?>
    <?php $module_index++; endforeach; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('ml_lesson_clusters', 'ml_duolingo_learning_path_shortcode');

function notify_admin_on_new_user_registration($user_id) {
    $user_info = get_userdata($user_id);
    $admin_email = get_option('admin_email');

    $subject = 'New user registration on your website';
    $message = sprintf(
        "A new user has registered.\n\nUsername: %s\nEmail: %s\nUser ID: %d",
        $user_info->user_login,
        $user_info->user_email,
        $user_id
    );

    wp_mail($admin_email, $subject, $message);
}
add_action('user_register', 'notify_admin_on_new_user_registration', 10, 1);


add_action('wp_ajax_run_code_runner', 'run_code_runner');
add_action('wp_ajax_nopriv_run_code_runner', 'run_code_runner');

function run_code_runner() {
    error_log('Code Runner AJAX called');
    error_log('RAW $_POST: ' . print_r($_POST, true));
    $json_data = stripslashes($_POST['data'] ?? '{}');
    $input = json_decode($json_data, true);
    error_log('Received data: ' . print_r($input, true));

    $code = $input['code'] ?? '';
    error_log('Received code: ' . print_r($code, true));
    if (empty($code)) {
        wp_send_json_error(['error' => 'No code received']);
        wp_die();
    }

    // Prepend numpy import if not already in code
    if (strpos($code, 'import numpy') === false) {
        $code = "import numpy as np\n\n" . $code;
    }

    $response = wp_remote_post('https://emkc.org/api/v2/piston/execute', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode([
            'language' => 'python3',
            'version' => '3.10.0',
            'files' => [['name' => 'main.py', 'content' => $code]],
        ])
    ]);

    if (is_wp_error($response)) {
        wp_send_json_success(['error' => 'Connection error']);
    } else {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $output = $body['run']['output'] ?? 'No output';
        wp_send_json_success(['output' => $output]);
    }

    wp_die(); // Always needed for admin-ajax
}

function enqueue_exercise_styles() {
    if ( is_singular() ) { // Load only on individual post/page
        wp_enqueue_style(
            'exercise-styles',
            get_stylesheet_directory_uri() . '/css/exercise-styles.css',
            [],
            filemtime( get_stylesheet_directory() . '/css/exercise-styles.css' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_exercise_styles' );
