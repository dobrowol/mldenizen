<style>

</style>

<?php

$allowed_tags = array(
    'div' => array(
        'class' => true,
        'style' => true,
        'draggable' => true,
        'data-value' => true
    ),
    'input' => array(
        'type' => true,
        'name' => true,
        'value' => true,
        'style' => true,
        'placeholder' => true,
        'readonly' => true,
    ),
    'span' => array(
        'class' => true,
        'style' => true
    ),
    'table' => array(
        'style' => true
    ),
    'thead' => array(),
    'tbody' => array(),
    'tr' => array(),
    'th' => array(
        'style' => true
    ),
    'td' => array(
        'style' => true
    ),
    'p' => array(),
    'br' => array(),
    'b' => array(),
    'strong' => array(),
    'i' => array(),
    'em' => array(),
    'u' => array(),
    'script' => array(
        'type' => true
    ),
);

/* Template for displaying course_topic taxonomy term archives */
get_header();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process form submission
if ( isset($_POST['submit_answer']) ) {
    //check if this was via BKT
    if ( ! empty($_POST['via_bkt']) ) {
        error_log("submit BKT streak count is ".$_SESSION['bkt_streak_count']);
        // mark the form so continue-handler knows this was BKT
        // $via_bkt = true;
        echo '<input type="hidden" name="via_bkt" value="1">';
    }
    else {
        error_log("not via BKT");
    }
    error_log("BKT streak count is ".$_SESSION['bkt_streak_count']);
    $exercise_id = intval( $_POST['exercise_id'] );
    $term_id = intval( $_POST['term_id'] );
    
    // Retrieve the current exercise from the database so we can display the solution.
    $table_name = $wpdb->prefix . 'exercises';
    $exercise = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $exercise_id ) );
    // now capture the answer according to type
    if ( 'array_type' === $exercise->question_type ) {

        // user filled a table t[row][col]
        $raw = $_POST['t'] ?? [];
        $clean = [];
        foreach ( $raw as $r => $cols ) {
            if ( ! is_array( $cols ) ) continue;
            foreach ( $cols as $c => $v ) {
                // sanitize each cell
                $clean[$r][$c] = trim( wp_strip_all_tags( $v ) );
            }
        }
        // if your verify_answer expects a string, you can JSON‑encode it:
        $user_answer = wp_json_encode( $clean );

    } elseif ( isset($_POST['user_answer']) && is_array( $_POST['user_answer'] ) ) {
        $user_answer = implode( ',', array_map( 'sanitize_text_field', $_POST['user_answer'] ) );
    } else {
        $user_answer = sanitize_text_field( $_POST['user_answer'] );
    }
    
    // Verify the answer using your verify_answer() function.
    $result = verify_answer( $exercise_id, $user_answer );
    
    // Determine points for this attempt.
    

    // Store the attempt in the custom attempts table.
    global $wpdb;
    $attempts_table = $wpdb->prefix . 'exercise_attempts';
    $data = array(
        'exercise_id'   => $exercise_id,
        'user_id'       => get_current_user_id(),
        'user_answer'   => $user_answer,
        'is_correct'    => $result['correct'],
        'attempt_time'  => current_time('mysql'),
        'points_awarded'=> $result['points'],
    );
    $format = array('%d', '%d', '%s', '%d', '%s', '%d');
    $wpdb->insert( $attempts_table, $data, $format );
    
    
    
    
    if ( $result['correct'] ) {
        echo '<p style="color:green;">Correct Answer!</p>';
    } else {
        echo '<p style="color:red;">Incorrect Answer.</p>';
        echo '<div class="exercise-solution">';
        echo '<h3>Solution:</h3>';
        echo wp_kses_post( $exercise->exercise_solution );
        echo '</div>';
    }
    
    // Display the "Continue" form with the current exercise id passed as a hidden field.
    echo '<form method="post" action="">';
    echo '<input type="hidden" name="continue_next" value="1">';
    echo '<input type="hidden" name="exercise_id" value="' . esc_attr( $exercise_id ) . '">'; // Pass the exercise ID here
    echo '<input type="hidden" name="term_id" value="' . esc_attr( $term_id ) . '">'; // Pass the exercise ID here
    if ( ! empty($_POST['via_bkt']) ) {
        error_log("checking answer for BKT streak count is ".$_SESSION['bkt_streak_count']);
        // mark the form so continue-handler knows this was BKT
        // $via_bkt = true;
        echo '<input type="hidden" name="via_bkt" value="1">';
    }
    echo '<input type="submit" name="submit_continue" value="Continue">';
    echo '</form>';
    
    exit;
}

if ( isset($_POST['submit_continue']) ) {
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
            ), get_permalink() );
            wp_redirect( $redirect_url );
            exit;
        } else {
            // No more exercises in this lesson; redirect to the summary page.
            // Make sure to change the page slug if needed.
            $summary_page = get_permalink( get_page_by_path( 'summary' ) );
            // Append the lesson_id as a query parameter:
            $summary_page = add_query_arg( 'lesson_id', $lesson_id, $summary_page );
            wp_redirect( $summary_page );
            exit;
        }
    } else{
        error_log("could not find current exercise");
    }
    
    // Fallback redirection:
    wp_redirect( add_query_arg( 'new_exercise', '1', get_permalink() ) );
    exit;
}


// Check if a lesson ID was passed via query parameter.
$lesson_id = isset($_GET['lesson_id']) ? intval( $_GET['lesson_id'] ) : 0;

// Check if a lesson ID was passed via query parameter.
$exercise_number = isset($_GET['exercise_number']) ? intval( $_GET['exercise_number'] ) : 0;
// Get the current term (subcategory) clicked.
$term_id = isset( $_GET['term_id'] ) ? intval( $_GET['term_id'] ) : 0;

// Get current user ID.
$student_id = get_current_user_id();

// If lesson_id is provided, retrieve the first exercise for that lesson.
// Otherwise, use your BKT algorithm to recommend an exercise based on the term.
error_log("lesson id is ".$lesson_id);
error_log("exercise number is ".$exercise_number);
error_log("lesson description is ".$_SESSION['lesson_description']);

if (
    $exercise_number == 1 &&
    $lesson_id &&
    (!isset($_SESSION['lesson_description']) || empty($_SESSION['lesson_description']))
) {
    error_log("lesson description first then continue");

    $_SESSION['lesson_description'] = true;

    // Load lesson content
    $lesson = get_post($lesson_id);
    if ($lesson) {
        echo '<div class="lesson-description">';
        echo '<h2>' . esc_html($lesson->post_title) . '</h2>';
        echo wpautop(wp_kses_post($lesson->post_content));

        // Check if lesson has exercises
        global $wpdb;
        $table = $wpdb->prefix . 'exercises';
        $exercise_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE lesson_id = %d",
            $lesson_id
        ));

        // Get course link from session
        $course_slug = isset($_SESSION['selected_course']) ? sanitize_text_field($_SESSION['selected_course']) : '';
        if ($course_slug === 'machine-learning') {
            $course_link = get_permalink(get_page_by_path('machine-learning'));
        } elseif ($course_slug === 'deep-learning') {
            $course_link = get_permalink(get_page_by_path('deep-learning'));
        } else {
            $course_link = get_permalink(get_page_by_path('course-topics'));
        }

        // Show continue button
        if ((int) $exercise_count === 0) {
            // No exercises: back to course path
            if (!is_wp_error($course_link)) {
                echo '<form method="get" action="' . esc_url($course_link) . '">';
                echo '<input type="submit" value="Continue to Module" />';
                echo '</form>';
            }
        } else {
            // Exercises exist: go to first exercise
            echo '<form method="get" action="">';
            echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
            echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
            echo '<input type="hidden" name="exercise_number" value="' . esc_attr($exercise_number) . '">';
            echo '<input type="submit" value="Start Lesson" />';
            echo '</form>';
        }

        echo '</div>';
    }

    exit;
}

elseif ($exercise_number && $lesson_id){
    error_log("get exercise for lesson");
    $recommended_exercise = get_exercise_for_lesson( $exercise_number, $lesson_id );
}else {
        
    error_log("no lesson id or exercise number. Use BKT for subcategory ".$term_id);
    $recommended_exercise = get_recommended_exercise_bkt( $term_id, $student_id );
    // If this is the first BKT hit, initialize the counter
    if ( empty( $_SESSION['bkt_streak_count'] ) ) {
        error_log("first BKT hit");
        $_SESSION['bkt_streak_count'] = 0;
    }

    if ( $recommended_exercise ) {
        $_SESSION['bkt_streak_count']++;
        // mark the form so continue-handler knows this was BKT
        $via_bkt = true;
    }
    error_log("addin bkt flag");
    error_log("BKT streak count is ".$_SESSION['bkt_streak_count']);
    
}


if ( $recommended_exercise ) {
    error_log("recommended exercise id ".$recommended_exercise->id);
    echo '<div class="recommended-exercise">';
    // echo '<h1>Recommended Exercise for ' . esc_html( $term->name ) . '</h1>';
    echo '<h2>' . esc_html( $recommended_exercise->exercise_title ) . '</h2>';
    // 👉 Only show exercise content immediately if NOT drag_and_drop
    if ( $recommended_exercise->question_type !== 'drag_and_drop'  && $recommended_exercise->question_type !== 'array_type' ) {
        echo '<div>' . wp_kses_post( str_replace('\{', '\\{', $recommended_exercise->exercise_content) ) . '</div>';
        // echo '<div>' . wp_kses( $recommended_exercise->exercise_content, $allowed_tags ) . '</div>';

    }
    // Start the form. The action posts back to the same page.
    echo '<form method="post" action="">';
    // Include hidden exercise_id so we know which exercise is being answered.
    echo '<input type="hidden" name="exercise_id" value="' . esc_attr( $recommended_exercise->id ) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr( $term_id ) . '">';
    if ( !empty($via_bkt) ) {
        echo '<input type="hidden" name="via_bkt" value="1">';
    }

   // Display input field based on question type.
   if ( 'open_text' === $recommended_exercise->question_type ) {
    $options = json_decode( $recommended_exercise->options, true );

    // Check if options exist and contain any {inputX} placeholders
    if ( !empty($options) && isset($options[0]) && preg_match('/\{input\d+\}/', $options[0]) ) {
        $template = $options[0];

        // Replace placeholders like {input1}, {input2}, ... with input fields
        $template_with_inputs = preg_replace_callback(
            '/\{input(\d+)\}/',
            function ( $matches ) {
                $index = $matches[1];
                return '<input type="text" name="user_answer[' . $index . ']" size="20" />';
            },
            esc_html( $template )
        );
        $template_with_inputs = add_tooltips_to_content( $template_with_inputs );
        echo '<div class="open-text-exercise">' . $template_with_inputs . '</div>';
    } else {
        // Fallback: simple textarea input
        echo '<textarea name="user_answer" rows="5" cols="60" placeholder="Type your answer here"></textarea>';
    }
    } elseif ( 'array_type' === $recommended_exercise->question_type){
        $full_content = $recommended_exercise->exercise_content;
        preg_match('/<table.*?>.*?<\/table>/is', $full_content, $match);
        if (!empty($match[0])) {
            $table_html = $match[0];
        } else {
            $table_html = '';
        }
        $full_content = preg_replace('/<table.*?>.*?<\/table>/is', '', $full_content);
        $full_content = add_tooltips_to_content( $full_content );
        error_log("full content is ".$full_content);
        error_log("table content is ".$table_html);
        echo '<div>' . wp_kses_post( str_replace('\{', '\\{', $full_content) ) . '</div>';
        echo '<div>' . $table_html . '</div>';

    } elseif ( 'multiple_choice' === $recommended_exercise->question_type ) {
        // For multiple choice questions where multiple answers can be selected, use checkboxes.
        $json_string = $recommended_exercise->options;
        error_log("options are ".$json_string);
        // $json_string_fixed = str_replace('\\', '\\\\', $json_string);
        error_log("escaped options are ".$json_string_fixed);
        $json = html_entity_decode($json_string, ENT_QUOTES|ENT_HTML5);
        $options = json_decode( $json, true );
        $options = add_tooltips_to_content( $options );
        error_log("decoded options are ".print_r($options, true));
        if ( is_array( $options ) ) {
            foreach ( $options as $key => $option ) {
                echo '<label style="display:block; margin-bottom:5px;">';
                echo '<input type="checkbox" name="user_answer[]" value="' . esc_attr( $key ) . '"> ' . esc_html( $option );
                echo '</label>';
            }
        } else {
            echo '<p>No options found.</p>';
        }
    } elseif ( 'one_of_many' === $recommended_exercise->question_type ) {
        // For questions of type "one_of_many", display radio buttons (only one selection is allowed)
        $json_string = $recommended_exercise->options;
        error_log("options are ".$json_string);

        $json = html_entity_decode($json_string, ENT_QUOTES|ENT_HTML5);
        $options = json_decode( $json, true );
        $options = add_tooltips_to_content( $options );
        error_log("decoded options are ".print_r($options, true));

        if ( is_array( $options ) ) {
            foreach ( $options as $key => $option ) {
                echo '<label style="display:block; margin-bottom:5px;">';
                echo '<input type="radio" name="user_answer" value="' . esc_attr( $key ) . '"> ' . esc_html( $option );
                echo '</label>';
            }
        } else {
            echo '<p>No options found.</p>';
        }
    }
    elseif ( 'drag_and_drop' === $recommended_exercise->question_type ) {
        $json_string = $recommended_exercise->options;
        $json = html_entity_decode($json_string, ENT_QUOTES|ENT_HTML5);
        $options = json_decode( $json, true );
    
        if ( is_array( $options ) ) {
            $full_content = $recommended_exercise->exercise_content;

            // 1. Extract ONLY inside <dragable>...</dragable>
            preg_match('/```(.*?)```/s', $full_content, $match);
        
            if (!empty($match[1])) {
                $content_inside_dragable = trim($match[1]);
            } else {
                $content_inside_dragable = '';
            }
            // 2. Remove <dragable>...</dragable> from content (so clean display)
            $full_content = preg_replace('/```.*?```/s', '', $full_content);
            $full_content = add_tooltips_to_content( $full_content );
            error_log("full content is ".$full_content);
            // 3. Display cleaned exercise content
            echo '<div>' . wp_kses_post( str_replace('\{', '\\{', $full_content) ) . '</div>';
    
            // Step 2: Display draggable options
            echo '<p style="margin-top:20px;">Drag these lines into the blanks:</p>';
            echo '<ul id="draggable-options" style="list-style:none;padding:0;">';
            foreach ( $options as $key => $option ) {
                echo '<li class="draggable-option" draggable="true" data-value="' . esc_attr( $key ) . '" style="padding:8px;border:1px solid #ccc;margin-bottom:5px;cursor:move;background:#f9f9f9;">' . esc_html( $option ) . '</li>';
            }
            echo '</ul>';
    
            // 3. Replace blanks {blank1}, {blank2}, etc. inside content
            foreach (range(1, count($options)) as $i) {
                $content_inside_dragable = str_replace(
                    '{blank' . $i . '}',
                    '<div class="dropzone" style="position:relative; border:2px dashed #aaa; min-height:30px; margin:5px; padding:5px; text-align:center;">
                        <div class="dropzone-content" style="pointer-events:none;">Drop here</div>
                        <input type="hidden" name="user_answer[' . $i . ']" value="">
                    </div>',
                    $content_inside_dragable
                );
            }
            error_log("content inside dragable is ".$content_inside_dragable);
            // 4. Output only the extracted content (with dropzones inserted)
            echo '<div class="exercise-content" style="margin-bottom:30px;">';
            echo wp_kses($content_inside_dragable, $allowed_tags);
            echo '</div>';
            // Step 4: Drag-and-drop logic
            echo '<script>
                const draggables = document.querySelectorAll(".draggable-option");
                const dropzones = document.querySelectorAll(".dropzone");
    
                draggables.forEach(draggable => {
                    draggable.addEventListener("dragstart", e => {
                        e.dataTransfer.setData("text/plain", draggable.dataset.value);
                        e.dataTransfer.setData("text/html", draggable.innerText);
                    });
                });
    
                dropzones.forEach(dropzone => {
                    dropzone.addEventListener("dragover", e => {
                        e.preventDefault();
                        dropzone.style.backgroundColor = "#eef";
                    });
    
                    dropzone.addEventListener("dragleave", e => {
                        dropzone.style.backgroundColor = "";
                    });
    
                    dropzone.addEventListener("drop", e => {
                        e.preventDefault();
                        const value = e.dataTransfer.getData("text/plain");
                        const text = e.dataTransfer.getData("text/html");
                        
                        dropzone.querySelector("input").value = value;
                        dropzone.querySelector(".dropzone-content").innerText = text;
                        dropzone.style.backgroundColor = "";
                    });
                });
            </script>';
        } else {
            echo '<p>No drag and drop options found.</p>';
        }
    
    }

    

    echo '<input type="submit" name="submit_answer" value="Submit Answer">';
    echo '</form>';
    echo '</div>';
}
else{
    // No exercise found — show lesson description
    unset($_SESSION['lesson_description']);
    if ( $lesson_id ) {
        $lesson = get_post( $lesson_id );
        if ( $lesson ) {
            echo '<div class="lesson-description">';
            echo '<h2>' . esc_html( $lesson->post_title ) . '</h2>';
            echo wpautop( wp_kses_post( $lesson->post_content ) );
            echo '</div>';
        }
    }
    $course_slug = isset($_SESSION['selected_course']) ? sanitize_text_field($_SESSION['selected_course']) : '';
    if ( 'machine-learning' === $course_slug ) {
        $course_link = get_permalink( get_page_by_path( 'machine-learning' ) );
    } elseif ( 'deep-learning' === $course_slug ) {
        $course_link = get_permalink( get_page_by_path( 'deep-learning' ) );
    } else {
        // Default: redirect to the courses grid page.
        $course_link = get_permalink( get_page_by_path( 'course-topics' ) );
    }
    // Show a "Go back to course" button
    if ( ! is_wp_error( $course_link ) ) {
        echo '<form method="get" action="' . esc_url( $course_link ) . '">';
        echo '<input type="submit" value="Continue" />';
        echo '</form>';
    }
}


get_footer();



/**
 * Dummy implementation for get_all_exercises().
 * Returns the total number of exercises in the table.
 *
 * @return int Count of exercises.
 */
function get_all_exercises() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';
    $all_exercises = $wpdb->get_results( "SELECT * FROM $table_name" );
    if ( empty( $all_exercises ) ) {
        return 0;
    }
    return count( $all_exercises );
}

?>
