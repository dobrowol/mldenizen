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
function enable_mathjax_selection() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll(".mjx-assistive-mml").forEach(el => {
        el.removeAttribute("unselectable");
        el.style.userSelect = "text";
        el.style.pointerEvents = "auto";
      });
    });
    </script>
    <?php
}
add_action('wp_footer', 'enable_mathjax_selection');

function add_mathjax_copy_button_with_internal_source() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        if (!window.MathJax || !MathJax.startup) {
            console.warn("MathJax not ready");
            return;
        }

        MathJax.startup.promise.then(() => {
            const mathItems = MathJax.startup.document.math;

            mathItems.forEach((math) => {
                const tex = math.inputJax.format === "TeX" ? math.start.data.originalText : null;
                const container = math.typesetRoot.parentElement;

                if (tex && container && !container.classList.contains("latex-copied")) {
                    const btn = document.createElement("button");
                    btn.textContent = "📋";
                    btn.title = "Copy LaTeX";
                    btn.style.marginLeft = "6px";
                    btn.style.fontSize = "0.8em";
                    btn.style.cursor = "pointer";

                    btn.addEventListener("click", (e) => {
                        e.preventDefault();
                        navigator.clipboard.writeText(tex).then(() => {
                            btn.textContent = "✅";
                            setTimeout(() => btn.textContent = "📋", 1000);
                        });
                    });

                    container.appendChild(btn);
                    container.classList.add("latex-copied");
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'add_mathjax_copy_button_with_internal_source');


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
    if (!isset($_SESSION['clicked_modules']) || !is_array($_SESSION['clicked_modules'])) {
        $_SESSION['clicked_modules'] = [];
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

    <div class="ml-course-visual">
    <?php
    $module_index = 0;
    error_log('Modules found: ' . count($modules));
    $active_module = null;
    $last_completed_lesson = end($completed_lessons);
    $active_module_term_id = null;

    for ($i = 0; $i < count($modules); $i++) {
        $module = $modules[$i];
        $lessons = get_posts([
            'post_type' => 'lesson',
            'tax_query' => [[
                'taxonomy' => 'course_topic',
                'field' => 'term_id',
                'terms' => $module->term_id,
            ]],
            'fields' => 'ids',
            'posts_per_page' => -1,
        ]);

        if (in_array($last_completed_lesson, $lessons)) {
            // Check if all lessons from this module are completed
            $all_completed = !array_diff($lessons, $completed_lessons);
            if ($all_completed && isset($modules[$i + 1])) {
                // Move to next module if exists
                $active_module_term_id = $modules[$i + 1]->term_id;
            } else {
                $active_module_term_id = $module->term_id;
            }
            break;
        }
    }
    // Fallback: if all modules are completed, show the last module
    if (!$active_module_term_id && !empty($modules)) {
        $active_module_term_id = end($modules)->term_id;
    }

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
        $is_next_module = !$all_lessons_completed && isset($modules[$module_index - 1]) && (
            // previous module is fully completed
            !array_diff(
                get_posts([
                    'post_type' => 'lesson',
                    'fields' => 'ids',
                    'tax_query' => [[
                        'taxonomy' => 'course_topic',
                        'field' => 'term_id',
                        'terms' => $modules[$module_index - 1]->term_id,
                    ]]
                ]),
                $completed_lessons
            )
        );

        $module_enabled = $module_index === 0 || in_array($modules[$module_index - 1]->term_id, $unlocked_modules);
        $module_clickable = $module_index === 0 || $module_enabled;
        $module_clicked = isset($_SESSION['clicked_modules']) && in_array($active_module_term_id, $_SESSION['clicked_modules']);
        $should_glow = $is_next_module && !$module_clicked;
        $module_url = add_query_arg([
            'term_id' => $module->term_id,
            'stage' => 'module_intro'
        ], get_permalink($_SESSION['selected_course_id']));

        ?>
        <div class="module-separator">
            <span class="module-separator-text"><?php echo esc_html($module->name); ?></span>
        </div>
        <div class="neuron-wrapper" <?php if ($module->term_id == $active_module_term_id) echo 'id="active-module"'; ?>>
            <div class="ml-module">
                <div class="module-node-wrapper">
                    <?php if ($module_clickable): ?>
                        <a href="<?php echo esc_url($module_url); ?>"
                            class="module-icon-link<?php echo $all_lessons_completed ? ' completed' : ''; ?>
                                                    <?php echo $should_glow ? ' glowing-icon' : ''; ?>">
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
        $first_incomplete_lesson_id = null;
        foreach ($lessons as $l) {
            if (!in_array($l->ID, $completed_lessons)) {
                $first_incomplete_lesson_id = $l->ID;
                break;
            }
        }

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
                <a href="<?php echo esc_url($lesson_url); ?>"
                    class="lesson-image-link
                            <?php echo $is_completed ? ' completed' : ($is_enabled ? ' enabled' : ' disabled'); ?>
                            <?php echo $lesson_id === $first_incomplete_lesson_id ? ' glowing-icon' : ''; ?>">

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
function enqueue_modules_styles() {
    if ( is_singular() ) { // Load only on individual post/page
        wp_enqueue_style(
            'modules-styles',
            get_stylesheet_directory_uri() . '/css/modules-styles.css',
            [],
            filemtime( get_stylesheet_directory() . '/css/modules-styles.css' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_modules_styles' );

function handle_submit_answer($exercise_id, $term_id, $exercise) {
    global $wpdb;
    error_log('handle_submit_answer Exercise type: ' . $exercise->question_type);
    error_log('user_answer set: ' . (isset($_POST['user_answer']) ? 'yes' : 'no'));

    error_log('POST user_answer: ' . print_r($_POST['user_answer'], true));
    // Get answer
    if ( 'array_type' === $exercise->question_type ) {
        $raw = $_POST['t'] ?? [];
        $clean = [];
        foreach ($raw as $r => $cols) {
            foreach ($cols as $c => $v) {
                $clean[$r][$c] = trim(wp_strip_all_tags($v));
            }
        }
        $user_answer = wp_json_encode($clean);
    } // ✅ labeled_input expects associative array of floats (not imploded string)
    elseif ( 'labeled_inputs' === $exercise->question_type && isset($_POST['user_answer']) && is_array($_POST['user_answer']) ) {
        $user_answer = [];
        foreach ($_POST['user_answer'] as $label => $value) {
            $user_answer[ sanitize_text_field($label) ] = floatval($value);
        }
        error_log("user answer is ".print_r($user_answer, true));
    } elseif ( 'match_boxes' === $exercise->question_type && isset($_POST['user_answer']) ) {
        error_log('✅ Entered match_boxes handler');

        // We assume: $_POST['user_answer'] is a 1-indexed array of selected definition indices
        error_log('POST user_answer: ' . print_r($_POST['user_answer'], true));

        $user_answer = [];

        foreach ($_POST['user_answer'] as $i => $selected_index) {
            $user_answer[] = ($selected_index === '') ? null : intval($selected_index);
        }

        $user_answer_json = wp_json_encode($user_answer);
        error_log('✅ Final JSON user_answer: ' . $user_answer_json);

        $user_answer = $user_answer_json;
    } elseif (isset($_POST['user_answer']) && is_array($_POST['user_answer'])) {
        $user_answer = implode(',', array_map('sanitize_text_field', $_POST['user_answer']));
    }  else {
        $user_answer = sanitize_text_field($_POST['user_answer']);
    }

    // Verify
    $result = verify_answer($exercise_id, $user_answer);

    error_log("result of verify_answer is ".print_r($result, true));
    $_SESSION['last_answer_feedback'] = [
        'exercise_id' => $exercise_id,
        'submitted' => $result['user_keys'],
        'correct' => $result['correct'],
        'correct_keys' => $result['correct_keys'] ?? [], // you must return this from `verify_answer`
    ];
    // Log attempt
    $attempts_table = $wpdb->prefix . 'exercise_attempts';
    $wpdb->insert($attempts_table, [
        'exercise_id'   => $exercise_id,
        'user_id'       => get_current_user_id(),
        'user_answer'   => $user_answer,
        'is_correct'    => $result['correct'],
        'attempt_time'  => current_time('mysql'),
        'points_awarded'=> $result['points'],
    ]);



    

    // echo '<form method="post">';
    // echo '<input type="hidden" name="continue_next" value="1">';
    // echo '<input type="hidden" name="exercise_id" value="' . esc_attr($exercise_id) . '">';
    // echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    // echo '<div class="submit-button-container">';
    // echo '<input type="submit" name="submit_continue" value="Continue">';
    // echo '</div></form>';

    return $_SESSION['last_answer_feedback'];
}
add_action('wp_ajax_ml_submit_exercise', 'ml_submit_exercise_ajax');
add_action('wp_ajax_nopriv_ml_submit_exercise', 'ml_submit_exercise_ajax');

function ml_submit_exercise_ajax() {
    error_log("ml submit exercise ajax");

    $exercise_id = intval($_POST['exercise_id'] ?? 0);
    $term_id = intval($_POST['term_id'] ?? 0);

    global $wpdb;
    $table = $wpdb->prefix . 'exercises';
    $exercise = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $exercise_id)
    );

    if (!$exercise) {
        wp_send_json_error(['message' => 'Exercise not found']);
    }

    // Update session and verify answer
    handle_submit_answer($exercise_id, $term_id, $exercise);

    // Now render the updated part of the form only (options/feedback)
    ob_start();
    render_exercise($exercise, $term_id, $_SESSION['last_answer_feedback']);
    $markup = ob_get_clean();

    wp_send_json_success([
        'html' => $markup
    ]);

    wp_die();
}
add_action('wp_enqueue_scripts', function () {
    error_log("wp_enque_scripts ".get_stylesheet_directory_uri() . '/js/exercise.js');
    wp_enqueue_script(
        'ml-exercise-script',
        get_stylesheet_directory_uri() .  '/js/exercise.js',
        [],
        null,
        true
    );

    wp_localize_script('ml-exercise-script', 'ajaxobject', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
});

