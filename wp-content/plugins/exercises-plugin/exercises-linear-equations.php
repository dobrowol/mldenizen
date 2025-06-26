
<?php

delete_option( 'algebra_exercises_added' ) ;
function add_algebra_exercises() {
    if ( ! get_option( 'algebra_exercises_added' ) ) {
        $linear_algebra_term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
        if ( $linear_algebra_term ) {
            $category_id = $linear_algebra_term->term_id;
        } else {
            error_log('Term "vector-spaces" not found in course_topic taxonomy.');
            $category_id = 0;
        }
        


        $exercise_title   = "Which of the following sets are subspaces of \\(\\mathbb{R}^3\\)?";
        $exercise_content = <<<HTML
Consider the following sets in \\(\\mathbb{R}^3\\):

1. \\[
   A = \{(\lambda,\;\lambda + 3\mu,\;\lambda - 3\mu)\mid \lambda,\mu\in\mathbb{R}\}
   \\]

2. \\[
   B = \{(\lambda^2,\;-\,\lambda^2,\;0)\mid \lambda\in\mathbb{R}\}
   \\]

3. \\[
   C = \{(\xi_1,\xi_2,\xi_3)\in\mathbb{R}^3\mid 
       \xi_1 - 2\,\xi_2 + 3\,\xi_3 = \gamma\},\;\gamma\in\mathbb{R}
   \\]

4. \\[
   D = \{(\xi_1,\xi_2,\xi_3)\in\mathbb{R}^3\mid \xi_2\in\mathbb{Z}\}
   \\]

A set is a subspace if it contains the zero vector and is closed under addition and scalar multiplication.  Note: \\(C\\) is a subspace only when \\(\gamma=0\\).
HTML;
        $options          = json_encode([
            "options" => [
                "A is a subspace; B and D are not; C iff \\(\\gamma = 0\\).",
                "Only A is a subspace; B, C, D are not.",
                "A and B are subspaces; C iff \\(\\gamma = 0\\); D is not.",
                "None of these are subspaces."
            ]
        ]);
        $correct_answer   = json_encode([ 'correct_option' =>
            "A is a subspace; B and D are not; C iff \\(\\gamma = 0\\)."
        ]);

        add_exercise(
            $lesson_id,
            $category_id,
            $exercise_title,
            $exercise_content,
            '',                 // no immediate open_text solution
            'medium',
            'one_of_many',
            $options,
            $correct_answer,
            8                   // exercise number
        );

        

        $section_term = get_term_by( 'slug', 'matrices', 'course_topic' );
        if ( $section_term ) {
            $category_id = $section_term->term_id;
        } else {
            error_log('Term "matrices" not found in course_topic taxonomy.');
            $category_id = 0;
        }
        // Pobierz ID pierwszej lekcji dla tej kategorii
        $lesson_id = get_lesson_for_category( $category_id, 1 );
        $result = add_exercise(
            $lesson_id,                                  // Lesson ID that the exercise belongs to.
            $category_id,                                // Category ID (for Matrix category).
            'Exercise: Closure Property',                // Exercise title.
            'Let A and B be arbitrary matrices in G, where G is defined as: 
              G = { [ [1, x, z], [0, 1, y], [0, 0, 1] ] : x, y, z ∈ ℝ }.
            Prove that the product A·B is also in G.',  // Exercise content.
            'Solution: Compute A · B = [ [1, x₁+x₂, z₁ + x₁·y₂ + z₂], [0, 1, y₁+y₂], [0, 0, 1] ]. 
            Since x₁+x₂, y₁+y₂ and z₁+x₁·y₂+z₂ are real numbers, the resulting matrix is of the same form as the elements in G.', 
            'easy',                                     // Difficulty.
            'one_of_many',                              // Question type: one correct answer from many.
            '{"A": "The product is in G.", "B": "The product is not in G.", "C": "Not enough information.", "D": "None of the above."}',  // Options (JSON).
            '{"correct_option": "A"}',                  // Correct answer (JSON).
            1                                           // exercise number: 1.
        );
        if ( false === $result ) {
            error_log('Error inserting Closure exercise: ' . $GLOBALS['wpdb']->last_error);
        }
        $result = add_exercise(
            $lesson_id,
            $category_id,
            'Exercise: Associativity',
            'Show that matrix multiplication in G is associative. That is, prove that for any matrices A, B, and C in G, (A·B)·C = A·(B·C).',
            'Since standard matrix multiplication is associative for all matrices, the associativity property holds for G.',
            'easy',
            'one_of_many',
            '{"A": "Associativity holds.", "B": "Associativity does not hold.", "C": "It depends on the matrices.", "D": "None of the above."}',
            '{"correct_option": "A"}',
            2  // exercise number: 2.
        );
        if ( false === $result ) {
            error_log('Error inserting Associativity exercise: ' . $GLOBALS['wpdb']->last_error);
        }
        $result = add_exercise(
            $lesson_id,
            $category_id,
            'Exercise: Identity Element',
            'Identify the identity element in G. In other words, show that there exists an element I in G such that for every matrix A in G, A·I = I·A = A.',
            'The identity element in G is the 3×3 identity matrix I = [ [1, 0, 0], [0, 1, 0], [0, 0, 1] ]. Since I ∈ G and for any A in G, A·I = I·A = A, this property holds.',
            'easy',
            'one_of_many',
            '{"A": "I is the identity element.", "B": "There is no identity element.", "C": "The identity element is different for each A.", "D": "None of the above."}',
            '{"correct_option": "A"}',
            3  // exercise number: 3.
        );
        if ( false === $result ) {
            error_log('Error inserting Identity Element exercise: ' . $GLOBALS['wpdb']->last_error);
        }
        $result = add_exercise(
            $lesson_id,
            $category_id,
            'Exercise: Inverse Elements',
            'For each matrix A in G, prove that there exists an inverse matrix A⁻¹ in G such that A·A⁻¹ = I, where I is the identity matrix.',
            'For any A = [ [1, x, z], [0, 1, y], [0, 0, 1] ] in G, its inverse is given by A⁻¹ = [ [1, -x, x·y - z], [0, 1, -y], [0, 0, 1] ], which also belongs to G.',
            'medium',
            'one_of_many',
            '{"A": "Every element has an inverse in G.", "B": "Some elements do not have inverses.", "C": "Only certain elements have inverses.", "D": "Not enough information."}',
            '{"correct_option": "A"}',
            4  // exercise number: 4.
        );
        if ( false === $result ) {
            error_log('Error inserting Inverse Elements exercise: ' . $GLOBALS['wpdb']->last_error);
        }
        $result = add_exercise(
            $lesson_id,
            $category_id,
            'Exercise: Is G an Abelian Group?',
            'Prove that G is a group under standard matrix multiplication and determine whether it is Abelian. In other words, verify if for all matrices A, B in G, A·B = B·A holds.',
            'While G satisfies closure, associativity, the existence of an identity and inverses, in general, matrix multiplication is not commutative. A counterexample can be constructed to show that A·B ≠ B·A for some matrices in G. Therefore, G is not Abelian.',
            'easy',
            'one_of_many',
            '{"A": "G is Abelian.", "B": "G is not Abelian.", "C": "G is Abelian only for specific values.", "D": "Not enough information."}',
            '{"correct_option": "B"}',
            5  // exercise number: 5.
        );
        if ( false === $result ) {
            error_log('Error inserting Commutativity exercise: ' . $GLOBALS['wpdb']->last_error);
        }

        


        $section_term = get_term_by( 'slug', 'matrices', 'course_topic' );
        if ( $section_term ) {
            $category_id = $section_term->term_id;
        } else {
            error_log('Term "matrices" not found in course_topic taxonomy.');
            $category_id = 0;
        }

        $lesson_id = get_lesson_for_category( $category_id, 1 );
        $difficulty  = 'medium';
        $question_type = 'matrix_type';
        $exercise_number = 1;

        // Exercise (a): Matrix Multiplication (impossible multiplication)
        // Matrices:
        // A = [ [1, 2],
        //       [4, 5],
        //       [7, 8] ]
        // B = [ [1, 1, 0],
        //       [0, 1, 1],
        //       [1, 0, 1] ]
        $exercise_title_a = 'Matrix Multiplication (a)';
        $exercise_content_a = 'Calculate the product of the following matrices:
        A = [ [1, 2],
            [4, 5],
            [7, 8] ]
        B = [ [1, 1, 0],
            [0, 1, 1],
            [1, 0, 1] ]';
        $exercise_solution_a = 'Multiplication is not possible because the number of columns in matrix A (2) does not match the number of rows in matrix B (3).';

        add_exercise($lesson_id, $category_id, $exercise_title_a, $exercise_content_a, $exercise_solution_a, $difficulty, $question_type, '', '', $exercise_number);
        $exercise_number++;
        // Exercise (b): Matrix Multiplication 3x3 * 3x3
        // Matrices:
        // A = [ [1, 2, 3],
        //       [4, 5, 6],
        //       [7, 8, 9] ]
        // B = [ [1, 1, 0],
        //       [0, 1, 1],
        //       [1, 0, 1] ]
        $exercise_title_b = 'Matrix Multiplication (b)';
        $exercise_content_b = 'Calculate the product of the following matrices:
        A = [ [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9] ]
        B = [ [1, 1, 0],
            [0, 1, 1],
            [1, 0, 1] ]';
        $exercise_solution_b = 'The result of A * B is:
        [ [4, 3, 5],
        [10, 9, 11],
        [16, 15, 17] ]';

        add_exercise($lesson_id, $category_id, $exercise_title_b, $exercise_content_b, $exercise_solution_b, $difficulty, $question_type, '', '', $exercise_number);
        $exercise_number++;
        // Exercise (c): Matrix Multiplication (reversed order)
        // Matrices:
        // A = [ [1, 1, 0],
        //       [0, 1, 1],
        //       [1, 0, 1] ]
        // B = [ [1, 2, 3],
        //       [4, 5, 6],
        //       [7, 8, 9] ]
        $exercise_title_c = 'Matrix Multiplication (c)';
        $exercise_content_c = 'Calculate the product of the following matrices:
        A = [ [1, 1, 0],
            [0, 1, 1],
            [1, 0, 1] ]
        B = [ [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9] ]';
        $exercise_solution_c = 'The result of A * B is:
        [ [5, 7, 9],
        [11, 13, 15],
        [8, 10, 12] ]';

        add_exercise($lesson_id, $category_id, $exercise_title_c, $exercise_content_c, $exercise_solution_c, $difficulty, $question_type, '', '', $exercise_number);
        $exercise_number++;
        // Exercise (d): Multiplication of a 2x4 matrix by a 4x2 matrix
        // Matrices:
        // A = [ [1, 2, 1, 2],
        //       [4, 1, -1, -4] ]
        // B = [ [0, 3],
        //       [1, -1],
        //       [2, 1],
        //       [5, 2] ]
        $exercise_title_d = 'Matrix Multiplication (d)';
        $exercise_content_d = 'Calculate the product of the following matrices:
        A = [ [1, 2, 1, 2],
            [4, 1, -1, -4] ]
        B = [ [0, 3],
            [1, -1],
            [2, 1],
            [5, 2] ]';
        $exercise_solution_d = 'The result of A * B is:
        [ [14, 6],
        [-21, 2] ]';

        add_exercise($lesson_id, $category_id, $exercise_title_d, $exercise_content_d, $exercise_solution_d, $difficulty, $question_type, '', '', $exercise_number);
        $exercise_number++;
        // Exercise (e): Multiplication of a 4x2 matrix by a 2x4 matrix
        // Matrices:
        // A = [ [0, 3],
        //       [1, -1],
        //       [2, 1],
        //       [5, 2] ]
        // B = [ [1, 2, 1, 2],
        //       [4, 1, -1, -4] ]
        $exercise_title_e = 'Matrix Multiplication (e)';
        $exercise_content_e = 'Calculate the product of the following matrices:
        A = [ [0, 3],
            [1, -1],
            [2, 1],
            [5, 2] ]
        B = [ [1, 2, 1, 2],
            [4, 1, -1, -4] ]';
        $exercise_solution_e = 'The result of A * B is:
        [ [12, 3, -3, -12],
        [-3, 1, 2, 6],
        [6, 5, 1, 0],
        [13, 12, 3, 2] ]';

        add_exercise($lesson_id, $category_id, $exercise_title_e, $exercise_content_e, $exercise_solution_e, $difficulty, $question_type, '', '', $exercise_number);

        $section_term = get_term_by( 'slug', 'systems-of-linear-equations', 'course_topic' );
        if ( $section_term ) {
            $category_id = $section_term->term_id;
        } else {
            error_log('Term "systems-of-linear-equationss" not found in course_topic taxonomy.');
            $category_id = 0;
        }
        
        // Pobierz ID pierwszej lekcji dla tej kategorii
        $lesson_id = get_lesson_for_category( $category_id, 1 );
        $difficulty   = 'Medium';
        $question_type = 'one_of_many';
        $exercise_number = 1; // starting exercise number
        
        // -------------------------------------
        // Exercise (a)
        // -------------------------------------
        // Problem statement:
        // Find the set S of all solutions x for the inhomogeneous linear system Ax = b, where
        //
        // A = [ [ 1,  1, -1, -1],
        //       [ 2,  5, -7, -5],
        //       [ 2, -1,  1,  3],
        //       [ 5,  2, -4,  2] ]
        //
        // and
        //
        // b = [ 1, -2, 4, 6 ]ᵀ.
        //
        // One possible answer is that the system is inconsistent (i.e. S is empty).
        
        $exercise_title_a = "Solution Set of Linear System (a)";
        $exercise_content_a = "Find the set S of all solutions x for the inhomogeneous linear system Ax = b, where
        
        A = [
          [ 1,  1, -1, -1 ],
          [ 2,  5, -7, -5 ],
          [ 2, -1,  1,  3 ],
          [ 5,  2, -4,  2 ]
        ]
        
        and
        
        b = [ 1, -2, 4, 6 ]ᵀ.
        
        Hint: Use Gaussian elimination to determine consistency.";
        // For a one_of_many type exercise we leave the open text solution empty (or explanatory)
        // and instead provide answer options in JSON.
        $exercise_solution_a = ""; 
        $options_a = json_encode([
            "options" => [
                "The system has no solutions.",
                "The system has a unique solution.",
                "The system has infinitely many solutions."
            ]
        ]);
        $correct_answer_a = "The system has no solutions.";
        
        add_exercise($lesson_id, $category_id, $exercise_title_a, $exercise_content_a, $exercise_solution_a, $difficulty, $question_type, $options_a, $correct_answer_a, $exercise_number);
        $exercise_number++; // increment exercise number
        
        // -------------------------------------
        // Exercise (b)
        // -------------------------------------
        // Problem statement:
        // Find the set S of all solutions x for the inhomogeneous linear system Ax = b, where
        //
        // A = [ [ 1, -1,  0,  0,  1],
        //       [ 1,  1,  0, -3,  0],
        //       [ 2, -1,  0,  1, -1],
        //       [-1,  2,  0, -2, -1] ]
        //
        // and
        //
        // b = [ 3, 6, 5, -1 ]ᵀ.
        //
        // One possible answer is a parametric solution:
        // x1 = 3+t, x2 = 2t, x3 = s, x4 = t-1, x5 = t, with free parameters t, s ∈ ℝ.
        
        $exercise_title_b = "Solution Set of Linear System (b)";
        $exercise_content_b = "Find the set S of all solutions x for the inhomogeneous linear system Ax = b, where
        
        A = [
          [ 1, -1,  0,  0,  1 ],
          [ 1,  1,  0, -3,  0 ],
          [ 2, -1,  0,  1, -1 ],
          [-1,  2,  0, -2, -1 ]
        ]
        
        and
        
        b = [ 3, 6, 5, -1 ]ᵀ.
        
        Hint: Use Gaussian elimination to express the solutions in parametric form.";
        $exercise_solution_b = "";
        $options_b = json_encode([
            "options" => [
                "x1 = 3+t,  x2 = 2t,  x3 = s,  x4 = t-1,  x5 = t,  with t, s ∈ ℝ.",
                "x1 = 3,  x2 = 2,  x3 = 0,  x4 = -1,  x5 = 0.",
                "x1 = t,  x2 = t+3,  x3 = s,  x4 = 2t-1,  x5 = s+1,  with t, s ∈ ℝ."
            ]
        ]);
        $correct_answer_b = "x1 = 3+t,  x2 = 2t,  x3 = s,  x4 = t-1,  x5 = t,  with t, s ∈ ℝ.";
        
        add_exercise($lesson_id, $category_id, $exercise_title_b, $exercise_content_b, $exercise_solution_b, $difficulty, $question_type, $options_b, $correct_answer_b, $exercise_number);
        $exercise_number++; // increment exercise number
        
        // -------------------------------------
        // Exercise (2.6)
        // -------------------------------------
        // Problem statement:
        // Using Gaussian elimination, find all solutions of the inhomogeneous system Ax = b, where
        //
        // A = [ [0, 1, 0, 0, 1, 0],
        //       [0, 0, 0, 1, 1, 0],
        //       [0, 1, 0, 0, 0, 1] ]
        //
        // and
        //
        // b = [ 2, -1, 1 ]ᵀ.
        //
        // One possible answer in parametric form is:
        // x = ( t, 2−r, s, −1−r, r, r−1 ), where t, s, r ∈ ℝ.
        
        $exercise_title_26 = "Solutions Using Gaussian Elimination (Exercise 2.6)";
        $exercise_content_26 = "Using Gaussian elimination, find all solutions of the inhomogeneous linear system Ax = b, where
        
        A = [
          [0, 1, 0, 0, 1, 0],
          [0, 0, 0, 1, 1, 0],
          [0, 1, 0, 0, 0, 1]
        ]
        
        and
        
        b = [ 2, -1, 1 ]ᵀ.
        
        Express the solution in terms of free parameters.";
        $exercise_solution_26 = "";
        $options_26 = json_encode([
            "options" => [
                "x = ( t, 2−r, s, −1−r, r, r−1 ), where t, s, r ∈ ℝ.",
                "x = ( t, 2+r, s, −1+r, r, 1−r ), where t, s, r ∈ ℝ.",
                "x = ( 0, 2, 0, −1, 0, −1 )."
            ]
        ]);
        $correct_answer_26 = "x = ( t, 2−r, s, −1−r, r, r−1 ), where t, s, r ∈ ℝ.";
        
        add_exercise($lesson_id, $category_id, $exercise_title_26, $exercise_content_26, $exercise_solution_26, $difficulty, $question_type, $options_26, $correct_answer_26, $exercise_number);
        $exercise_number++; // increment exercise number
        
        // -------------------------------------
        // Exercise (2.7)
        // -------------------------------------
        // Problem statement:
        // Find all solutions x = [x₁, x₂, x₃]ᵀ ∈ ℝ³ of the equation Ax = 12x, with the additional constraint x₁+x₂+x₃ = 1,
        // where
        //
        // A = [ [6, 4, 3],
        //       [6, 0, 9],
        //       [0, 8, 0] ].
        //
        // One possible answer is:
        // x = [ 3/8, 3/8, 1/4 ].
        
        $lesson_id = get_lesson_for_category( $category_id, 2 );

        $exercise_title_27 = "Eigenvalue Problem with Constraint (Exercise 2.7)";
        $exercise_content_27 = "Find all solutions x = [x₁, x₂, x₃]ᵀ ∈ ℝ³ of the equation Ax = 12x, where
        
        A = [
          [6, 4, 3],
          [6, 0, 9],
          [0, 8, 0]
        ]
        
        subject to the constraint
        
          x₁ + x₂ + x₃ = 1.
        
        Hint: This is an eigenvalue problem with an additional normalization condition.";
        $exercise_solution_27 = "";
        $options_27 = json_encode([
            "options" => [
                "x = [ 3/8, 3/8, 1/4 ].",
                "x = [ 1/3, 1/3, 1/3 ].",
                "x = [ 1/2, 1/4, 1/4 ]."
            ]
        ]);
        $correct_answer_27 = "x = [ 3/8, 3/8, 1/4 ].";
        
        add_exercise($lesson_id, $category_id, $exercise_title_27, $exercise_content_27, $exercise_solution_27, $difficulty, $question_type, $options_27, $correct_answer_27, $exercise_number);

        




        $difficulty    = 'Medium';
        $question_type = 'one_of_many';
        $exercise_number++;

        // --------------------------------------------------
        // Exercise (a): Inverse of a 3x3 Matrix
        // --------------------------------------------------
        // Given matrix A:
        // A = [ [2, 3, 4],
        //       [3, 4, 5],
        //       [4, 5, 6] ]
        //
        // Observation: The rows of A are linearly dependent (each consecutive row differs by [1,1,1]),
        // hence the determinant is zero and the matrix is singular (noninvertible).

        $exercise_title_a = "Determine the Inverse of Matrix A (Exercise a)";
        $exercise_content_a = "Determine the inverse of the following matrix, if it exists:

        A = [
        [2, 3, 4],
        [3, 4, 5],
        [4, 5, 6]
        ]

        Note: If the matrix is singular, state that the inverse does not exist.";
        // In this case, one valid answer is that the matrix is not invertible.
        $exercise_solution_a = "";
        $options_a = json_encode([
            "options" => [
                "Matrix A is singular and does not have an inverse.",
                "A^(-1) = [ [ -1, 3, -1 ], [ 3, -5, 2 ], [ -1, 2, -1 ] ].",
                "A^(-1) = [ [ 1, -3, 1 ], [ -3, 5, -2 ], [ 1, -2, 1 ] ]."
            ]
        ]);
        $correct_answer_a = "Matrix A is singular and does not have an inverse.";

        add_exercise($lesson_id, $category_id, $exercise_title_a, $exercise_content_a, $exercise_solution_a, $difficulty, $question_type, $options_a, $correct_answer_a, $exercise_number);
        $exercise_number++; // increment exercise number

        // --------------------------------------------------
        // Exercise (b): Inverse of a 4x4 Matrix
        // --------------------------------------------------
        // Given matrix A:
        // A = [ [1, 0, 1, 0],
        //       [0, 1, 1, 0],
        //       [1, 1, 0, 1],
        //       [1, 1, 1, 0] ]
        //
        // After appropriate calculations (e.g., using Gaussian elimination), one possible inverse is:
        // A^(-1) =
        // [ [ 0,  -1,  0,  1 ],
        //   [ -1,  0,  0,  1 ],
        //   [ 1,   1,  0, -1 ],
        //   [ 1,   1,  1, -2 ] ].
        // (Other equivalent forms are possible, but this is one acceptable answer.)

        $exercise_title_b = "Determine the Inverse of Matrix A (Exercise b)";
        $exercise_content_b = "Determine the inverse of the following matrix, if it exists:

        A = [
        [1, 0, 1, 0],
        [0, 1, 1, 0],
        [1, 1, 0, 1],
        [1, 1, 1, 0]
        ]

        Note: If there is more than one acceptable form, select one of the correct inverses.";
        $exercise_solution_b = "";
        $options_b = json_encode([
            "options" => [
                "A^(-1) = [ [0, -1, 0, 1], [-1, 0, 0, 1], [1, 1, 0, -1], [1, 1, 1, -2] ].",
                "Matrix A is singular and does not have an inverse.",
                "A^(-1) = [ [1, 0, -1, 1], [0, 1, -1, 1], [1, -1, 0, -1], [1, 1, 1, 0] ]."
            ]
        ]);
        $correct_answer_b = "A^(-1) = [ [0, -1, 0, 1], [-1, 0, 0, 1], [1, 1, 0, -1], [1, 1, 1, -2] ].";

        add_exercise($lesson_id, $category_id, $exercise_title_b, $exercise_content_b, $exercise_solution_b, $difficulty, $question_type, $options_b, $correct_answer_b, $exercise_number);
        $exercise_number++; // increment exercise number for further exercises if needed




        // Example parameters for linear algebra exercises on vector independence
        $section_term = get_term_by( 'slug', 'linear-independence', 'course_topic' );
        if ( $section_term ) {
            $category_id = $section_term->term_id;
        } else {
            error_log('Term "linear-independence" not found in course_topic taxonomy.');
            $category_id = 0;
        }
        
        // Pobierz ID pierwszej lekcji dla tej kategorii
        $lesson_id = get_lesson_for_category( $category_id, 1 );
        $difficulty    = 'Medium';
        $question_type = 'one_of_many';
        $exercise_number = 1; // starting exercise number

        // -----------------------------------------------------------------
        // Exercise (a): Linear Independence of Vectors in ℝ³
        // -----------------------------------------------------------------
        // Vectors:
        // x₁ = [2, -1, 3]ᵀ
        // x₂ = [1,  1, -2]ᵀ
        // x₃ = [3, -3, 8]ᵀ
        //
        // Note: One can verify that 2*x₁ - x₂ equals x₃, so the vectors are linearly dependent.
        $exercise_title_a = "Are these vectors in ℝ³ linearly independent? (a)";
        $exercise_content_a = "Determine whether the following set of vectors in ℝ³ is linearly independent:

        x₁ = [2, -1, 3]ᵀ  
        x₂ = [1,  1, -2]ᵀ  
        x₃ = [3, -3, 8]ᵀ  

        Hint: Check if one vector can be written as a combination of the others.";
        $exercise_solution_a = ""; // The open text solution is left empty for one_of_many type.
        $options_a = json_encode([
            "options" => [
                "They are linearly independent.",
                "They are linearly dependent.",
                "They are independent if scaled properly.",
                "There is not enough information to determine independence."
            ]
        ]);
        // The correct answer is that they are linearly dependent.
        $correct_answer_a = "They are linearly dependent.";
        add_exercise($lesson_id, $category_id, $exercise_title_a, $exercise_content_a, $exercise_solution_a, $difficulty, $question_type, $options_a, $correct_answer_a, $exercise_number);
        $exercise_number++; // increment exercise number

        // -----------------------------------------------------------------
        // Exercise (b): Linear Independence of Vectors in ℝ⁵
        // -----------------------------------------------------------------
        // Vectors:
        // x₁ = [1, 2, 1, 0, 0]ᵀ
        // x₂ = [1, 1, 0, 1, 1]ᵀ
        // x₃ = [1, 0, 0, 1, 1]ᵀ
        //
        // Note: By setting up a linear combination equal to the zero vector, one finds that the only solution is trivial—thus, these vectors are linearly independent.
        $exercise_title_b = "Are these vectors in ℝ⁵ linearly independent? (b)";
        $exercise_content_b = "Determine whether the following set of vectors in ℝ⁵ is linearly independent:

        x₁ = [1, 2, 1, 0, 0]ᵀ  
        x₂ = [1, 1, 0, 1, 1]ᵀ  
        x₃ = [1, 0, 0, 1, 1]ᵀ  

        Hint: Set up a linear combination a·x₁ + b·x₂ + c·x₃ = 0 and solve for a, b, and c.";
        $exercise_solution_b = "";
        $options_b = json_encode([
            "options" => [
                "They are linearly independent.",
                "They are linearly dependent.",
                "They are independent only if one vector is removed.",
                "They are dependent when a non-trivial solution exists."
            ]
        ]);
        // The correct answer is that they are linearly independent.
        $correct_answer_b = "They are linearly independent.";
        add_exercise($lesson_id, $category_id, $exercise_title_b, $exercise_content_b, $exercise_solution_b, $difficulty, $question_type, $options_b, $correct_answer_b, $exercise_number);
        $exercise_number++; // increment exercise number if needed for further exercises


        

        // Set the option so these exercises are not added again.
        update_option( 'algebra_exercises_added', true );
    }
}


?>