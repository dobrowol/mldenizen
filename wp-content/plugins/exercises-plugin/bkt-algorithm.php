<?php
// bkt-algorithm.php

/**
 * Retrieve all exercise attempts for a given user and category.
 */

 
function get_user_attempts( $user_id, $category_id ) {
    global $wpdb;
    $attempts_table = $wpdb->prefix . 'exercise_attempts';
    
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT a.* 
             FROM $attempts_table AS a
             INNER JOIN {$wpdb->prefix}exercises AS e ON a.exercise_id = e.id
             WHERE a.user_id = %d AND e.category_id = %d",
            $user_id, $category_id
        )
    );
    
    return $results;
}
/**
 * Aktualizuje poziom opanowania na podstawie modelu Bayesian Knowledge Tracing.
 *
 * @param float $prev_mastery Poprzednie prawdopodobieństwo opanowania (0 - 1).
 * @param bool  $is_correct   Czy odpowiedź była poprawna? (true/false)
 * @return float Zaktualizowane prawdopodobieństwo opanowania.
 */
function update_user_mastery($user_id, $category_id, $new_mastery) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_mastery';

    // Check if there is already an entry for this user and category.
    $existing = $wpdb->get_var(
        $wpdb->prepare("SELECT id FROM $table_name WHERE user_id = %d AND category_id = %d", $user_id, $category_id)
    );

    if ($existing) {
        // Update the existing record.
        $wpdb->update(
            $table_name,
            array(
                'mastery'      => $new_mastery,
                'last_updated' => current_time('mysql')
            ),
            array(
                'id' => $existing
            ),
            array(
                '%f',
                '%s'
            ),
            array('%d')
        );
    } else {
        // Insert a new record.
        $wpdb->insert(
            $table_name,
            array(
                'user_id'     => $user_id,
                'category_id' => $category_id,
                'mastery'     => $new_mastery,
                'last_updated'=> current_time('mysql')
            ),
            array(
                '%d',
                '%d',
                '%f',
                '%s'
            )
        );
    }
}

function update_mastery($prev_mastery, $is_correct) {
    // Parametry modelu – te wartości należy dobrać na podstawie danych empirycznych lub literatury
    $P_T = 0.1; // Transition probability: szansa na naukę po wykonaniu zadania
    $P_S = 0.1; // Slip probability: szansa na błąd pomimo opanowania
    $P_G = 0.2; // Guess probability: szansa na poprawne zgadywanie, gdy nie opanowano zagadnienia

    if ($is_correct) {
        // Prawdopodobieństwo uzyskania poprawnej odpowiedzi, gdy umiejętność jest opanowana: 1 - P(S)
        // Prawdopodobieństwo uzyskania poprawnej odpowiedzi, gdy umiejętność nie jest opanowana: P(G)
        $numerator = $prev_mastery * (1 - $P_S);
        $denom = $prev_mastery * (1 - $P_S) + (1 - $prev_mastery) * $P_G;
    } else {
        // Jeśli odpowiedź jest błędna:
        // P(błędna|L) = P(S) i P(błędna|¬L) = 1 - P(G)
        $numerator = $prev_mastery * $P_S;
        $denom = $prev_mastery * $P_S + (1 - $prev_mastery) * (1 - $P_G);
    }

    // Aby uniknąć dzielenia przez zero:
    if ($denom == 0) {
        $posterior = 0;
    } else {
        $posterior = $numerator / $denom;
    }

    // Uwzględniamy efekt nauki: nowe prawdopodobieństwo opanowania wzrasta o P(T)
    $updated_mastery = $posterior + (1 - $posterior) * $P_T;
    return $updated_mastery;
}
/**
 * Updates and returns the mastery level for a given user and category using BKT.
 *
 * @param int   $user_id     The current user's ID.
 * @param int   $category_id The category (skill) ID.
 * @return float The updated mastery level.
 */
function run_bkt_for_user( $user_id, $category_id ) {
    global $wpdb;
    
    // Get the stored mastery from the user_mastery table.
    $mastery_table = $wpdb->prefix . 'user_mastery';
    $stored_mastery = $wpdb->get_var(
         $wpdb->prepare("SELECT mastery FROM $mastery_table WHERE user_id = %d AND category_id = %d", $user_id, $category_id)
    );
    
    // If no mastery is stored yet, use a default value (e.g., 0.3).
    if ( is_null( $stored_mastery ) ) {
         $stored_mastery = 0.3;
    }
    
    // Retrieve all attempts for the user in this category.
    // (get_user_attempts() should return an array of attempt objects sorted by time.)
    $attempts = get_user_attempts( $user_id, $category_id );
    
    // If there are attempts, update mastery based on the most recent one.
    if ( ! empty( $attempts ) ) {
        // Get the latest attempt (assuming attempts are sorted chronologically).
        $last_attempt = end( $attempts );
        // Update mastery using the last attempt's result.
        $updated_mastery = update_mastery( $stored_mastery, $last_attempt->is_correct );
    } else {
         $updated_mastery = $stored_mastery;
    }
    
    // Save the updated mastery value back into the database.
    update_user_mastery( $user_id, $category_id, $updated_mastery );
    
    return $updated_mastery;
}


/**
 * Get the next recommended exercise based on BKT.
 * This function can use the mastery level to choose an exercise of appropriate difficulty.
 */
function get_recommended_exercise_bkt( $category_id, $user_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'exercises';
    
    // Run the BKT algorithm to get the user's mastery level.
    $mastery = run_bkt_for_user( $user_id, $category_id );
    
    // Use mastery to determine desired difficulty:
    // e.g., if mastery is low, choose an "easy" exercise;
    // if mastery is high, choose a "hard" exercise.
    if ( $mastery < 0.4 ) {
        $desired_difficulty = 'easy';
    } elseif ( $mastery < 0.7 ) {
        $desired_difficulty = 'medium';
    } else {
        $desired_difficulty = 'hard';
    }
    
    // Query the exercises table for the given category and desired difficulty.
    $exercises = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE category_id = %d AND difficulty = %s",
            $category_id, $desired_difficulty
        )
    );
    
    if ( empty( $exercises ) ) {
        // If none found, fallback: choose any exercise from the category.
        $exercises = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM $table_name WHERE category_id = %d", $category_id )
        );
    }
    
    if ( empty( $exercises ) ) {
        return false;
    }
    
    // For now, simply select one at random.
    $recommended = $exercises[ array_rand( $exercises ) ];
    return $recommended;
}
?>