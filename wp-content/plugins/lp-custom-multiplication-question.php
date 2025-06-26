<?php
/**
 * Plugin Name: LearnPress Custom Multiplication Table Question
 * Description: Adds a custom question type for multiplication table of ℤ₅ (the set {0, 1, 2, 3, 4} with multiplication modulo 5) to LearnPress.
 * Version: 1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Make sure LearnPress is active.
if ( ! class_exists( 'LP_Question' ) ) {
    return;
}

/**
 * Register our custom question type.
 */
function lp_register_custom_question_type( $types ) {
    $types['multiplication_table'] = __( 'Multiplication Table (ℤ₅)', 'lp-custom' );
    return $types;
}
add_filter( 'learn_press_question_types', 'lp_register_custom_question_type' );

/**
 * Custom Multiplication Table Question Class.
 */
add_action( 'plugins_loaded', function() {
    if ( class_exists( 'LP_Question' ) ) {
if ( ! class_exists( 'LP_Question_Multiplication_Table' ) ) {

    class LP_Question_Multiplication_Table extends LP_Question {

        // Define the internal type.
        protected $_question_type = 'multiplication_table';

        /**
         * Return question type.
         *
         * @param string $context (optional)
         * @return string
         */
        public function get_type( $context = '' ) {
            return 'multiplication_table';
        }

        /**
         * Return question title.
         *
         * @param string $context (optional)
         * @return string
         */
        public function get_title( $context = '' ) {
            error_log( 'LP_Question_Multiplication_Table::get_title called with context: ' . $context );
            return __( 'Multiplication Table (ℤ₅)', 'lp-custom' );
        }

        /**
         * (Optional) Define any settings fields required for the admin interface.
         * Even an empty array may be necessary for the question type to be marked as complete.
         *
         * @return array
         */
        public function get_settings_fields() {
            return array();
        }

        /**
         * Renders the multiplication table question.
         * This displays a 5x5 HTML table with input fields where the user must enter (row x col) mod 5.
         */
        public function render() {
            ?>
            <div class="lp-multiplication-table-question">
                <p><?php _e( 'Fill in the multiplication table for ℤ₅ (each cell should equal (row x column) mod 5):', 'lp-custom' ); ?></p>
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>*</th>
                            <?php for ( $i = 0; $i < 5; $i++ ) : ?>
                                <th><?php echo $i; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ( $row = 0; $row < 5; $row++ ) : ?>
                            <tr>
                                <th><?php echo $row; ?></th>
                                <?php for ( $col = 0; $col < 5; $col++ ) : ?>
                                    <td>
                                        <input type="text" 
                                               name="question_<?php echo $this->get_id(); ?>[<?php echo $row; ?>][<?php echo $col; ?>]" 
                                               size="2" 
                                               value="">
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        /**
         * Check the submitted answer.
         *
         * Expects an array in the format: [row][col] containing user-entered values.
         *
         * @param mixed $submitted The user submitted answer.
         * @return int  1 if the complete table is correct; 0 if any value is incorrect.
         */
        public function check( $submitted = null ) {
            // Ensure we have an array.
            $submitted = is_array( $submitted ) ? $submitted : array();
            $all_correct = true;
            for ( $row = 0; $row < 5; $row++ ) {
                for ( $col = 0; $col < 5; $col++ ) {
                    // Calculate expected result: (row * col) mod 5.
                    $expected = ( $row * $col ) % 5;
                    if ( ! isset( $submitted[ $row ][ $col ] ) || intval( $submitted[ $row ][ $col ] ) !== $expected ) {
                        $all_correct = false;
                        // Optionally, you could break out early or collect per-cell feedback.
                    }
                }
            }
            return $all_correct ? 1 : 0;
        }
    }
}
}
} );
/**
 * Filter to load our custom question class when the question type is multiplication_table.
 */
function lp_load_custom_question_class( $class_name, $question_type ) {
    if ( $question_type === 'multiplication_table' ) {
        return 'LP_Question_Multiplication_Table';
    }
    return $class_name;
}
add_filter( 'learn_press_question_class', 'lp_load_custom_question_class', 10, 2 );

/**
 * (Optional) Template Path Filter.
 * If you have a custom template file for rendering the question, place it in a "templates" folder in this plugin.
 * If the file doesn't exist, the render() method of the class will be used.
 */
function lp_custom_question_template( $template, $question ) {
    if ( $question->get_type() === 'multiplication_table' ) {
        $custom_template = plugin_dir_path( __FILE__ ) . 'templates/question-multiplication_table.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter( 'learn_press_question_template_path', 'lp_custom_question_template', 10, 2 );

add_action( 'learn_press_before_build_question_menu', function() {
    error_log( 'Building question menu' );
} );
