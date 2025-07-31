<?php
/*
Plugin Name: Exercises Plugin Too
Description: Creates the custom table for exercises and adds default exercises.
Version: 1.0
Author: Wojciech Dobrowolski
*/
require_once plugin_dir_path( __FILE__ ) . 'bkt-algorithm.php';
require_once plugin_dir_path( __FILE__ ) . 'exercises-vector-spaces.php';
require_once plugin_dir_path( __FILE__ ) . 'exercises-module-one.php';
require_once plugin_dir_path( __FILE__ ) . 'exercises-module-two.php';
require_once plugin_dir_path( __FILE__ ) . 'exercises-module-three.php';

function register_lesson_post_type() {
    $labels = array(
        'name'               => 'Lessons',
        'singular_name'      => 'Lesson',
        'menu_name'          => 'Lessons',
        'name_admin_bar'     => 'Lesson',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Lesson',
        'new_item'           => 'New Lesson',
        'edit_item'          => 'Edit Lesson',
        'view_item'          => 'View Lesson',
        'all_items'          => 'All Lessons',
        'search_items'       => 'Search Lessons',
        'not_found'          => 'No lessons found.',
        'not_found_in_trash' => 'No lessons found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'lesson'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'         => array( 'course_topic' ), // <-- Add this line
    );

    register_post_type( 'lesson', $args );
}
add_action( 'init', 'register_lesson_post_type', 5 );

// Add the lesson number meta box in the lesson edit screen.
function lesson_number_meta_box() {
    add_meta_box(
        'lesson_number_meta',        // Unique ID for the meta box
        'Lesson Number',             // Box title displayed in the admin
        'lesson_number_meta_box_html', // Callback function that outputs the content
        'lesson',                    // The custom post type where this box appears
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'lesson_number_meta_box' );

// Callback function to render the input field.
function lesson_number_meta_box_html( $post ) {
    // Use nonce for verification.
    wp_nonce_field( basename( __FILE__ ), 'lesson_number_nonce' );
    // Retrieve the existing lesson number if available.
    $lesson_number = get_post_meta( $post->ID, 'lesson_number', true );
    ?>
    <label for="lesson_number_field">Lesson Number</label>
    <input type="number" name="lesson_number_field" id="lesson_number_field" value="<?php echo esc_attr( $lesson_number ); ?>" style="width:100%;" />
    <?php
}

// Save the meta box input.
function save_lesson_number_meta( $post_id ) {
    // Verify the nonce before proceeding.
    if ( ! isset( $_POST['lesson_number_nonce'] ) || ! wp_verify_nonce( $_POST['lesson_number_nonce'], basename(__FILE__) ) ) {
        return $post_id;
    }
    // Check autosave.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // Check the user’s permissions.
    if ( 'lesson' === $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    
    // Update the lesson number meta field.
    if ( isset( $_POST['lesson_number_field'] ) ) {
        update_post_meta( $post_id, 'lesson_number', sanitize_text_field( $_POST['lesson_number_field'] ) );
    }
}
add_action( 'save_post', 'save_lesson_number_meta' );


/**
 * Dodaje po dwie lekcje do każdej podkategorii w taksonomii "course_topic" (gdzie parent != 0).
 */

function add_default_lessons_to_subcategories() {
    if ( get_option('default_lessons_added') ) {
        return;
    }

    // Pobierz wszystkie główne kategorie (parent = 0) w taksonomii "course_topic"
    $parent_terms = get_terms( array(
        'taxonomy'   => 'course_topic',
        'parent'     => 0,
        'hide_empty' => false,
    ) );

    if ( ! empty( $parent_terms ) && ! is_wp_error( $parent_terms ) ) {
        // Dla każdej głównej kategorii
        foreach ( $parent_terms as $parent ) {
            // Pobierz wszystkie podkategorie dla tej głównej kategorii
            $child_terms = get_terms( array(
                'taxonomy'   => 'course_topic',
                'parent'     => $parent->term_id,
                'hide_empty' => false,
            ) );

            if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
                // Dla każdej podkategorii dodaj dwie lekcje
                foreach ( $child_terms as $child ) {
                    // Lekcja 1
                    $lesson1 = array(
                        'post_title'    => 'Lesson 1 for ' . $child->name,
                        'post_content'  => 'Content of Lesson 1 for ' . $child->name,
                        'post_status'   => 'publish',
                        'post_type'     => 'lesson', // upewnij się, że typ "lesson" jest zarejestrowany
                        'meta_input'    => array(
                            'course_topic'  => $child->term_id, // powiązanie z daną podkategorią
                            'lesson_number' => 1,
                        ),
                    );
                    error_log('Insert lesson 1 for ' . $child->name );
                    $lesson_id = wp_insert_post( $lesson1 );
                    if ( ! is_wp_error( $lesson_id ) ) {
                        wp_set_object_terms( $lesson_id, $child->term_id, 'course_topic' );
                    }
                
                    // Lekcja 2
                    $lesson2 = array(
                        'post_title'    => 'Lesson 2 for ' . $child->name,
                        'post_content'  => 'Content of Lesson 2 for ' . $child->name,
                        'post_status'   => 'publish',
                        'post_type'     => 'lesson',
                        'meta_input'    => array(
                            'course_topic'  => $child->term_id,
                            'lesson_number' => 2,
                        ),
                    );
                    error_log('Insert lesson 2 for ' . $child->name );
                    $lesson_id = wp_insert_post( $lesson2 );
                    if ( ! is_wp_error( $lesson_id ) ) {
                        wp_set_object_terms( $lesson_id, $child->term_id, 'course_topic' );
                    }
                }
                
            } else {
                error_log('No subcategories found for parent term: ' . $parent->name);
            }
        }
    } else {
        error_log('No parent course_topic terms found.');
    }
    update_option('default_lessons_added', true);
}
add_action('init', 'add_default_lessons_to_subcategories');

// Function to create the exercises table.
function create_exercises_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        lesson_id mediumint(9) NOT NULL,
        category_id mediumint(9) NOT NULL,
        exercise_title text NOT NULL,
        exercise_content text,
        exercise_solution text,
        difficulty varchar(50) NOT NULL,
        question_type varchar(50) NOT NULL,
        options text,
        correct_answer text,
        exercise_number int NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function add_exercise( $lesson_id, $category_id, $exercise_title, $exercise_content, $exercise_solution, $difficulty, $question_type, $options = '', $correct_answer = '', $exercise_number = 1 ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';

    // Sanitize the data
    $lesson_id        = intval( $lesson_id );
    $category_id      = intval( $category_id );
    $exercise_title   = sanitize_text_field( $exercise_title );
    
    // $exercise_content = sanitize_textarea_field( $exercise_content );
    // $exercise_content = escape_latex($exercise_content);

    $exercise_solution = wp_kses_post( $exercise_solution );
    $difficulty       = sanitize_text_field( $difficulty );
    $question_type    = sanitize_text_field( $question_type );
    $options          = wp_kses_post( $options );
    $correct_answer   = sanitize_text_field( $correct_answer );
    $exercise_number  = intval( $exercise_number );

    $data = array(
        'lesson_id'         => $lesson_id,
        'category_id'       => $category_id,
        'exercise_title'    => $exercise_title,
        'exercise_content'  => $exercise_content,
        'exercise_solution' => $exercise_solution,
        'difficulty'        => $difficulty,
        'question_type'     => $question_type,
        'options'           => $options,
        'correct_answer'    => $correct_answer,
        'exercise_number'   => $exercise_number,
        'created_at'        => current_time('mysql')
    );
    
    // The format: 
    // %d for lesson_id, %d for category_id, then 7 %s for the text fields, 
    // %d for exercise_number, and %s for created_at.
    $format = array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s');

    return $wpdb->insert( $table_name, $data, $format );
}


/**
 * Pobiera ID pierwszej lekcji (post typu 'lesson') dla podanej kategorii (term_id) 
 * na podstawie meta pola 'exercise_number' = 1.
 *
 * @param int $category_id
 * @return int|0 ID lekcji lub 0, jeśli nie znaleziono.
 */


function get_lesson_for_category( $category_id, $lesson_nbr ) {
    $args = array(
        'post_type'      => 'lesson',
        'tax_query'      => array(
            array(
                'taxonomy' => 'course_topic',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
        'meta_query'     => array(
            array(
                'key'     => 'lesson_number',
                'value'   => $lesson_nbr,
                'compare' => '='
            ),
        ),
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'ASC'
    );
    $query = new WP_Query( $args );
    $args = array(
        'post_type'      => 'lesson',
        'tax_query'      => array(
            array(
                'taxonomy' => 'course_topic',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'ASC'
    );
    $lessons_query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        $query->the_post();
        $lesson_id = get_the_ID();
        wp_reset_postdata();
        return $lesson_id;
    }
    return 0;
}

function exercise_plugin_activate() {
    create_exercises_table();

        // First: Module One
    exercise_module_one_plugin_activate();

    // Then: Module Two
    exercise_module_two_plugin_activate();

    // Then: Module Three
    exercise_module_three_plugin_activate();
}
register_activation_hook( __FILE__, 'exercise_plugin_activate' );



// function get_first_exercise_for_lesson( $lesson_id ) {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'exercises';

//     $exercise = $wpdb->get_row( $wpdb->prepare(
//          "SELECT * FROM $table_name WHERE lesson_id = %d ORDER BY exercise_number ASC LIMIT 1",
//          $lesson_id
//     ) );
    
//     return $exercise;
// }
function get_exercise_for_lesson( $exercise_id, $lesson_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';

    $exercise = $wpdb->get_row( $wpdb->prepare(
         "SELECT * FROM $table_name WHERE lesson_id = %d AND exercise_number = %d ORDER BY exercise_number ASC LIMIT 1",
         $lesson_id, $exercise_id
    ) );
    
    return $exercise;
}
function verify_answer( $exercise_id, $user_answer ) {
    error_log( 'Verifying answer for exercise ID: ' . $exercise_id );
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';

    $exercise = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $exercise_id ) );
    if ( ! $exercise ) {
        return array( 'correct' => false, 'points' => 0 );
    }

    // For multiple_choice questions
    if ( 'multiple_choice' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
        if ( ! isset( $correct_data['correct_options'] ) ) {
            return array( 'correct' => false, 'points' => 0 );
        }
        $correct_options = $correct_data['correct_options'];
        error_log( 'Correct options: ' . print_r( $correct_options, true ) );
        
        $user_options = array_map( 'trim', explode( ',', $user_answer ) );
        error_log( 'User options: ' . print_r( $user_options, true ) );
        $num_correct_options = count( $correct_options );
        $num_user_correct = count( array_intersect( $user_options, $correct_options ) );
        $is_correct = ( $num_user_correct === $num_correct_options && count($user_options) === $num_correct_options );
        $points = ($num_correct_options > 0) ? ( ( $num_user_correct * 25 ) / $num_correct_options ) : 0;
        return array( 'correct' => $is_correct, 'points' => $points, 'correct_keys' => $correct_options, 'user_keys' => $user_options );
    } elseif ( 'match_boxes' === $exercise->question_type ) {
        $user_data = json_decode($user_answer, true); // list of selected indices

        if (!is_array($user_data)) {
            return array('correct' => false, 'points' => 0);
        }

        $expected = range(0, count($user_data) - 1); // expected correct order
        $correct_count = 0;

        foreach ($user_data as $i => $submitted_index) {
            if ($submitted_index === $expected[$i]) {
                $correct_count++;
            }
        }

        $total = count($expected);
        $is_correct = ($correct_count === $total);
        $points = ($total > 0) ? ($correct_count * 100 / $total) : 0;

        return array(
            'correct' => $is_correct,
            'points' => $points,
            'correct_keys' => $expected,
            'user_keys' => $user_data,
        );
    } elseif ( 'labeled_inputs' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
        if ( ! isset( $correct_data['correct_options'] ) || ! is_array( $correct_data['correct_options'] ) ) {
            return array( 'correct' => false, 'points' => 0 );
        }
        $correct_options = $correct_data['correct_options'];
        error_log( 'Correct labeled answers: ' . print_r( $correct_options, true ) );
        error_log( 'User labeled answers: ' . print_r( $user_answer, true ) );

        $epsilon = 0.0001;
        $num_total = count( $correct_options );
        $num_correct = 0;
        $user_keys = array_keys( $user_answer );

        foreach ( $correct_options as $key => $correct_value ) {
            if ( isset( $user_answer[$key] ) && abs( floatval($user_answer[$key]) - floatval($correct_value) ) < $epsilon ) {
                $num_correct++;
            }
        }

        $is_correct = ( $num_correct === $num_total );
        $points = ( $num_total > 0 ) ? ( $num_correct * 100 ) / $num_total : 0;

        return array( 
            'correct' => $is_correct, 
            'points' => $points,
            'correct_keys' => $correct_options,
            'user_keys' => $user_answer
        );
    }
    // For open_text questions
    elseif ( 'open_text' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
        
        if ( isset( $correct_data['correct_options'] ) && is_array($correct_data['correct_options']) ) {
            $correct_options = array_map('strtolower', array_map('trim', $correct_data['correct_options']));
            $user_response = strtolower( trim( $user_answer ) );

            // Check if the user's response matches any of the accepted correct options
            $is_correct = in_array( $user_response, $correct_options, true );
        } else {
            // Direct comparison (case-insensitive, trimmed)
            $is_correct = strtolower( trim( $user_answer ) ) === strtolower( trim( $exercise->correct_answer ) );
        }

        $points = $is_correct ? 25 : 0;
        return array( 'correct' => $is_correct, 'points' => $points, 'correct_keys' => $correct_options, 'user_keys' => array($user_response) );
    }

    // For one_of_many questions
    elseif ( 'one_of_many' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
        if ( ! isset( $correct_data['correct_option'] ) ) {
            return array( 'correct' => false, 'points' => 0 );
        }
        error_log("user answer ".trim( $user_answer )." correct option ".trim( $correct_data['correct_option'] ));
        $is_correct = ( trim( $user_answer ) === trim( $correct_data['correct_option'] ) );
        error_log("correct ".( trim( $user_answer ) === trim( $correct_data['correct_option'] ) ));
        error_log("is_correct " . ($is_correct ? 'true' : 'false'));
        $points = $is_correct ? 25 : 0;
        return array( 'correct' => $is_correct, 'points' => $points, 'correct_keys' => array($correct_data['correct_option']), 'user_keys' => array($user_answer) );
    }

    // ✅ For array_type (e.g. times tables or inverse tables)
    elseif ( 'array_type' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
        $user_data    = json_decode( $user_answer, true );

        if ( ! isset( $correct_data['table'] ) || ! is_array( $user_data ) ) {
            return array( 'correct' => false, 'points' => 0 );
        }

        $correct_table = $correct_data['table'];
        $total_cells   = 0;
        $correct_cells = 0;

        for ( $i = 0; $i < count($correct_table); $i++ ) {
            for ( $j = 0; $j < count($correct_table[$i]); $j++ ) {
                $total_cells++;
                if (
                    isset($user_data[$i][$j]) &&
                    intval($user_data[$i][$j]) === intval($correct_table[$i][$j])
                ) {
                    $correct_cells++;
                }
            }
        }

        $is_correct = ( $correct_cells === $total_cells );
        $points = ($total_cells > 0) ? round(( $correct_cells * 25 ) / $total_cells, 2) : 0;

        return array( 'correct' => $is_correct, 'points' => $points );
    }
    elseif ( 'drag_and_drop' === $exercise->question_type ) {
        $correct_data = json_decode( $exercise->correct_answer, true );
    
        if ( ! is_array($correct_data) ) {
            return array( 'correct' => false, 'points' => 0 );
        }
    
        $user_options = array_map('trim', explode(',', $user_answer)); // Split by comma
    
        $num_correct = 0;
        $total_blanks = count($correct_data);
    
        // Compare sequentially: correct order matters
        foreach ( range(1, $total_blanks) as $i ) {
            $correct_option = $correct_data[$i]; // e.g., "A"
            $user_option = isset($user_options[$i - 1]) ? $user_options[$i - 1] : null;
    
            if ($user_option === $correct_option) {
                $num_correct++;
            }
        }
    
        $is_correct = ($num_correct === $total_blanks);
        $points = ($total_blanks > 0) ? ( ($num_correct * 25) / $total_blanks ) : 0;
    
        return array( 'correct' => $is_correct, 'points' => $points, 'correct_keys' => $correct_data, 'user_keys' => $user_options );
    }

    return array( 'correct' => false, 'points' => 0 , 'correct_keys' => $correct_data, 'user_keys' => array($user_answer) );
}


function create_exercise_attempts_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercise_attempts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        exercise_id mediumint(9) NOT NULL,
        user_id bigint(20) NOT NULL,
        user_answer text NOT NULL,
        is_correct tinyint(1) NOT NULL,
        attempt_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        points_awarded INT DEFAULT 0,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_exercise_attempts_table' );
function create_user_mastery_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_mastery';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        category_id mediumint(9) NOT NULL,
        mastery float NOT NULL,
        last_updated datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_user_mastery_table' );
/**
 * Sprawdza, czy lekcja jest odblokowana dla danego użytkownika.
 * Załóżmy, że użytkownik ma zapisane ukończone lekcje jako array w user meta 'completed_lessons'
 *
 * @param int $user_id
 * @param int $lesson_term_id  Term ID of the lesson.
 * @return bool
 */


 function is_lesson_unlocked( $user_id, $lesson_term_id ) {
    // Pobierz listę ukończonych lekcji z user meta
    $completed_lessons = get_user_meta( $user_id, 'completed_lessons', true );
    if ( ! is_array( $completed_lessons ) ) {
        $completed_lessons = array();
    }
    
    // Załóżmy, że jeśli lekcja ma numer 1, jest odblokowana domyślnie.
    // Możesz przechowywać numer lekcji w dodatkowym polu lub rozpoznać go z nazwy.
    // Dla uproszczenia: jeśli $lesson_term_id (lub numer lekcji) jest 101 (oznacza Lesson 1), odblokuj.
    // W praktyce musisz stworzyć logikę, która sprawdza, czy użytkownik ukończył poprzednią lekcję.
    
    // Przykładowa logika: jeśli lista ukończonych lekcji zawiera numer poprzedniej lekcji,
    // to bieżąca lekcja jest odblokowana. Na starcie odblokowana jest tylko lekcja 1.
    // Załóżmy, że numer lekcji to pole 'exercise_number' przechowywane jako meta terminu.
    $exercise_number = get_term_meta( $lesson_term_id, 'exercise_number', true );
    
    if ( empty( $exercise_number ) ) {
        // Jeśli nie ma numeru, zakładamy, że nie można odblokować.
        return false;
    }
    
    if ( $exercise_number == 1 ) {
        return true;
    }
    
    // Sprawdź, czy poprzednia lekcja (exercise_number - 1) jest ukończona.
    $prev_exercise_number = $exercise_number - 1;
    
    // Zakładamy, że w user meta przechowujemy numery ukończonych lekcji.
    if ( in_array( $prev_exercise_number, $completed_lessons ) ) {
        return true;
    }
    
    return false;
}

function check_set_course_param() {
    if ( isset( $_GET['set_course'] ) ) {
        if ( session_status() === PHP_SESSION_NONE ) {
            session_start();
        }
        // Zapisz wybrany kurs (slug) do sesji
        $_SESSION['selected_course'] = sanitize_text_field( $_GET['set_course'] );
        
        // Opcjonalnie usuń parametr z adresu – przekierowujemy do tej samej strony bez parametru.
        $redirect_url = remove_query_arg( 'set_course' );
        wp_redirect( $redirect_url );
        exit;
    }
}
add_action( 'init', 'check_set_course_param' );

function create_code_runner_exercise($lesson_id, $category_id, $exercise_number, $title, $description, $instruction, $code_template, $params) {
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – ' . $title,
        json_encode([
            'description' => "<p>$description</p>",
            'params' => $params
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        $instruction,
        'easy',
        'code_runner',
        json_encode(['code' => $code_template], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        '',
        $exercise_number
    );
}

?>