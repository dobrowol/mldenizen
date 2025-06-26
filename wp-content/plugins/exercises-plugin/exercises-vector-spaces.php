<?php

function add_vector_spaces_exercises_lesson1() {
    $linear_algebra_term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
    if ( $linear_algebra_term ) {
        $category_id = $linear_algebra_term->term_id;
    } else {
        error_log('Term "vector-spaces" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, 1 );
    $options = json_encode([
        "A" => "Long Short Term Memory.",
        "B" => "Recurrent Neural Network.",
        "C" => "Convolutional Neural Network.",
        "D" => "Transformer.",
        "E" => "Generative Adversarial Network.",
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 a.i - Image recognition',
        // Let a, b ∈ ℝ \ {-1}, define a ⋆ b := ab + a + b. Show a ⋆ b ∈ ℝ \ {-1}
        'Which model is best for image recognition.',
        // Solution: If ab + a + b = -1, then a(b + 1) = -(b + 1) → a = -1 → contradiction
        'Solution: CNN.',
        'medium',
        'multiple_choice',
        $options,
        '{"correct_options": ["C"]}',
        1
    );

    $options = json_encode([
        "A" => "class CNN(nn.Module):",
        "B" => "def __init__(self):",
        "C" => "self.kernel = nn.Parameter(torch.randn(32, 1, 3, 3))",
        "D" => "x = F.conv2d(x, self.kernel, padding=1)",
        "E" => "x = F.relu(x)",
        "F" => "return self.fc(x)",
        "G" => "self.fc = nn.Linear(in_features, out_features)"
    ]);
    
    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "G",
        "5" => "D",
        "6" => "E",
        "7" => "F"
    ]);
    
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 a.ii - CNN Drag-and-Drop Code Completion',
        <<<EOT
        Drag the correct lines of code into the blanks below to complete a basic PyTorch CNN:
        

            
        ```{blank1}

            {blank2}
                {blank3}
                {blank4}

            def forward(self, x):
                {blank5}
                {blank6}
                {blank7}```
                    
        EOT,
        'Solution: The correct lines of code are A, B, C, D, E and F.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        2
    );
    $options = json_encode([
        "A" => "4 × 4 × 32",
        "B" => "28 × 28",
        "C" => "28 × 28 × 32",
        "D" => "32 × 4 × 4"
    ]);
    
    $correct_matches = json_encode([
        "1" => "D"
    ]);
    
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 a.iv – Output shape of conv layer',
        <<<EOT
        A convolution operation multiplies an input matrix by a kernel matrix element-wise, sums the results, and produces a single output value per location.
        What is the result of applying a 7×7 kernel on a 28×28 image, with padding=0, stride=7 and 32 output channels?
    
        ```
        {blank1}
        ```
        EOT,
        'Solution: The correct line is A (32 × 4 × 4).',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        3
    );
    $options = json_encode([
        "A" => "A convolution operation multiplies an input matrix by a kernel matrix element-wise, sums the results, and produces a single output value per location.",
        "B" => "A convolution operation simply adds up all the pixel values in the input matrix.",
        "C" => "A convolution operation flips the input image horizontally.",
        "D" => "A convolution operation sorts the pixel values of the input matrix."
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 a.iii - What is a Convolution Operation?',
        'Select the correct description of a 2D convolution operation used in CNNs:',
        'Solution: A convolution operation multiplies an input matrix by a kernel matrix element-wise, sums the results, and produces a single output value per location.',
        'easy',
        'one_of_many',
        $options,
        '{"correct_option": "A"}',
        4
    );
    $exercise_content = <<<HTML
        <p>Compute the convolution of the following input matrix \( f \) by the kernel matrix \( k \) using stride \(1\) and no padding.</p>

        <p>The convolution operation is defined as:</p>

        \[
        (f * k)(i,j) = \sum_m \sum_n f(i+m, j+n) \, k(m, n)
        \]

        <p>Input matrix \( f \):</p>

        \[
        \begin{bmatrix}
        2 & 3 & 1 \\\\
        0 & 1 & 2 \\\\
        1 & 0 & 1
        \\end{bmatrix}
        \]

        <p>Kernel matrix \( k \):</p>

        \[
        \begin{bmatrix}
        1 & 0 \\\\
        -1 & 1
        \\end{bmatrix}
        \]

        <p>Fill the output convolution matrix:</p>

        <table style="border-collapse:collapse;">
        <thead>
        <tr><th></th><th>Col 1</th><th>Col 2</th></tr>
        </thead>
        <tbody>
        <tr><th>Row 1</th>
        <td><input type="text" name="t[0][0]" style="width:40px;"></td>
        <td><input type="text" name="t[0][1]" style="width:40px;"></td>
        </tr>
        <tr><th>Row 2</th>
        <td><input type="text" name="t[1][0]" style="width:40px;"></td>
        <td><input type="text" name="t[1][1]" style="width:40px;"></td>
        </tr>
        </tbody>
        </table>
        HTML;

    $options = json_encode([
            'rows'    => ['Row 1', 'Row 2'],
            'columns' => ['Col 1', 'Col 2']
        ]);
    $correct_table = [
        ['3', '4'],
        ['-1', '2'],
    ];
    
    $correct_answer = json_encode([
        'table' => $correct_table
    ]);   
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 a.iv – Convolution Computation',
        $exercise_content,
        'Solution: The 2x2 convolution output matrix is: 3, 4 on the first row; -1, 2 on the second row.',
        'medium',     // difficulty level
        'array_type', // type of exercise
        $options,
        $correct_answer,
        5             // display order
    );
    
}

function add_vector_spaces_exercises_lesson2() {
    $linear_algebra_term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
    if ( $linear_algebra_term ) {
        $category_id = $linear_algebra_term->term_id;
    } else {
        error_log('Term "vector-spaces" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, 2 );
    if ( 0 === $lesson_id ) {
        error_log('No lesson found for category ID: ' . $category_id );
    }
    // Exercise 2.1 a: Show that (R\{-1}, ⋆) is an Abelian group.
    // Subexercise 1: Closure Property
    // Exercise 2.1 a.i - Closure Property
    $options = json_encode([
        "A" => "\\( ab \\) always belongs to \\( \\mathbb{R} \\setminus \\{-1\\} \\).",
        "B" => "If \\( a \\star b = -1 \\), then \\( a(b + 1) = -(b + 1) \\).",
        "C" => "\\( a + b \\) always belongs to \\( \\mathbb{R} \\setminus \\{-1\\} \\).",
        "D" => "None of the above."
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 2.1 a.i - Closure Property',
        // Let a, b ∈ ℝ \ {-1}, define a ⋆ b := ab + a + b. Show a ⋆ b ∈ ℝ \ {-1}
        'Let \\( a, b, c \\in \\mathbb{R} \\setminus \\{-1\\} \\), and define \\( a \\star b := ab + a + b \\). \\( a \\star b \\in \\mathbb{R} \\setminus \\{-1\\} \\).',
        // Solution: If ab + a + b = -1, then a(b + 1) = -(b + 1) → a = -1 → contradiction
        'Solution: Since a and b belong to \\mathbb{R} \\setminus \\{-1\\}, their product ab is in \\mathbb{R}. If ab + a + b = -1, then a(b+1) = -(b+1), which implies a = -1, contradicting a ∈ \mathbb{R} \\setminus \\{-1\\}.',
        'medium',
        'multiple_choice',
        $options,
        '{"correct_options": ["B"]}',
        1
    );
    if (false === $result) {
        error_log('Error inserting Exercise 2.1 a.i: ' . $GLOBALS['wpdb']->last_error);
    }

    // Exercise 2.1 a.ii - Associativity Property
    $options_2_1_aii = json_encode([
        "A" => "\\((a \\star b) \\star c = abc + ac + bc + ab + a + b + c\\)",
        "B" => "\\(a \\star (b \\star c) = abc + ab + ac + a + bc + b + c\\)",
        "C" => "\\(a \\star (b \\star c) = abc + ab + bc + a + b + c\\)",
        "D" => "None of the above."
    ]);
    $correct_2_1_aii = json_encode([
        "correct_options" => ["A", "B"]
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 2.1 a.ii - Associativity Property',
        'Let \\( a, b, c \\in \\mathbb{R} \\setminus \\{-1\\} \\), and define \\( a \\star b := ab + a + b \\). Prove that \\( (a \\star b) \\star c = a \\star (b \\star c) \\).',
        'Solution: Expand both expressions using the definition of \\( \\star \\). Apply associativity of multiplication and addition in \\( \\mathbb{R} \\) to show both sides are equal.',
        'medium',
        'multiple_choice',
        $options_2_1_aii,
        $correct_2_1_aii,
        2
    );

    // Exercise 2.1 a.iii - Identity Element
    $options_2_1_aiii = json_encode([
        "A" => "0 is the identity element.",
        "B" => "1 is the identity element.",
        "C" => "There is no identity element.",
        "D" => "The identity depends on \\( a \\)."
    ]);
    $correct_2_1_aiii = json_encode([
        "correct_option" => "A"
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 2.1 a.iii - Identity Element',
        'Identify the element \\( e \\in \\mathbb{R} \\setminus \\{-1\\} \\) such that for all \\( a \\in \\mathbb{R} \\setminus \\{-1\\} \\), \\( a \\star e = a \\).',
        'Solution: Let \\( e = 0 \\). Then \\( a \\star 0 = a \\cdot 0 + a + 0 = a \\), and \\( 0 \\star a = 0 \\cdot a + 0 + a = a \\). So 0 is the identity.',
        'easy',
        'one_of_many',
        $options_2_1_aiii,
        $correct_2_1_aiii,
        3
    );

    // Exercise 2.1 a.iv - Inverse Element
    $options_2_1_aiv = json_encode([
        "A" => "\\( b = \\frac{-a}{a+1} \\) is the correct inverse.",
        "B" => "\\( b = -a \\)",
        "C" => "\\( b = a \\)",
        "D" => "No inverse exists."
    ]);
    $correct_2_1_aiv = json_encode([
        "correct_option" => "A"
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 2.1 a.iv - Inverse Elements',
        'For each \\( a \\in \\mathbb{R} \\setminus \\{-1\\} \\), find \\( b \\in \\mathbb{R} \\setminus \\{-1\\} \\) such that \\( a \\star b = 0 \\).',
        'Solution: Solve \\( ab + a + b = 0 \\Rightarrow a(b+1) + b = 0 \\Rightarrow b = \\frac{-a}{a+1} \\). This is valid for all \\( a \\ne -1 \\).',
        'medium',
        'one_of_many',
        $options_2_1_aiv,
        $correct_2_1_aiv,
        4
    );

    // Exercise 2.1 b - Solve equation 3 ⋆ x ⋆ x = 15
    $correct_2_1_b = json_encode([
        "correct_answer" => [-3, 1]
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 2.1 b - Solve the Equation',
        'Solve the equation \\( 3 \\star x \\star x = 15 \\) in the Abelian group \\( (\\mathbb{R} \\setminus \\{-1\\}, \\star) \\), where \\( a \\star b := ab + a + b \\).',
        'Solution: First \\( 3 \\star x = 4x + 3 \\). Then \\( (3 \\star x) \\star x = (4x + 3)x + (4x + 3) + x = 4x^2 + 8x + 3 \\). Set equal to 15 and solve: \\( 4x^2 + 8x - 12 = 0 \\Rightarrow x^2 + 2x - 3 = 0 \\Rightarrow (x+3)(x-1) = 0 \\Rightarrow x = -3, 1 \\).',
        'hard',
        'open_text',
        '',
        $correct_2_1_b,
        5
    );

    // Exercise - Verify Abelian group properties
    $options_abelian = json_encode([
        "A" => "Verify closure.",
        "B" => "Verify associativity.",
        "C" => "Identify the identity element.",
        "D" => "Show that every element has an inverse.",
        "E" => "Verify commutativity.",
        "F" => "Verify distributivity."
    ]);
    $correct_abelian = json_encode([
        "correct_options" => ["A", "B", "C", "D", "E"]
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise: Steps to Prove \\( (\\mathbb{R} \\setminus \\{-1\\}, \\star) \\) is Abelian',
        'We consider \\( (\\mathbb{R} \\setminus \\{-1\\}, \\star) \\), where \\( a \\star b := ab + a + b \\). Which steps are needed to prove this is an Abelian group?',
        'Solution: To prove it is Abelian, verify: (A) closure, (B) associativity, (C) identity, (D) inverses, and (E) commutativity. Distributivity is not required.',
        'medium',
        'multiple_choice',
        $options_abelian,
        $correct_abelian,
        6
    );


}

function add_vector_spaces_exercises_lesson3() {
   
    
    $term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
    if ( ! $term ) {
        error_log( 'Term "vector-spaces" not found.' );
        return;
    }
    $category_id = $term->term_id;

    // 2) find the first lesson in that category
    $lesson_id = get_lesson_for_category( $category_id, 3 );
    if ( ! $lesson_id ) {
        error_log( 'No lesson found for category ID ' . $category_id );
        return;
    }


    // 3) prepare your closure exercise data as multiple choice
    $exercise_title   = "Closure under addition in \(\mathbb{Z}/n\mathbb{Z}\)";
    $exercise_number = 1;
    $exercise_content = <<<HTML
    Let \([a]\) and \([b]\) be any two elements in \(\mathbb{Z}/n\mathbb{Z}\).  Show that
    \[
    [a]\oplus[b] \,=\; [\,a + b\,] \pmod n
    \]
    is again an element of \(\mathbb{Z}/n\mathbb{Z}\).
    HTML;

    $options = json_encode([
        "A" => "\\(a+b = qn + r\\), \\(0 \\le r < n\\). Hence \\(r \\in \\mathbb{Z}/n\\mathbb{Z}\\).",
        "B" => "\\(a+b\\) might result in a rational number.",
        "C" => "The modulo operation always returns zero.",
        "D" => "Because \\(\\mathbb{Z}/n\\mathbb{Z}\\) is finite, any sum must land back in it."
    ]);

    $correct_answer = json_encode([
        'correct_option' => 'A'
    ]);

    // 4) insert it as a multiple choice exercise (#8 in lesson)
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        '',               // no immediate “solution” dump
        'medium',         // difficulty
        'one_of_many',    // 
        $options,
        $correct_answer,
        $exercise_number                 // position #8 in that lesson
    );
    // 3) prepare Subexercise 2: Associativity Property as multiple choice
    $exercise_title   = 'Exercise 2.2 a.ii – Associativity Property';
    $exercise_content = <<<HTML
    Let \\([a], [b], [c]\\) be any elements in \\(\\mathbb{Z}/n\\mathbb{Z}\\).  
    Prove that  
    \\[
      \bigl([a] \\oplus [b]\\bigr) \\oplus [c]
      \;=\;
      [a] \\oplus \bigl([b] \\oplus [c]\\bigr).
    \\]
    HTML;
    
    $associativity_options = json_encode([
        "A" => "\\( ([a]\\oplus[b])\\oplus[c] = [(a+b)+c]\\) and \\( [a]\\oplus([b]\\oplus[c]) = [a+(b+c)]\\) because integer addition is associative.",
        "B" => "Associativity does not hold because addition is not associative.",
        "C" => "Associativity depends on the value of \\(n\\).",
        "D" => "None of the above."
    ]);
    
    $associativity_correct = json_encode([
        "correct_option" => "A"
    ]);
    
    // 4) insert it as a multiple choice exercise (#2 in that lesson)
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        'Compute  
         \\(( [a]\\oplus[b] )\\oplus[c] = [a+b]\\oplus[c] = [(a+b)+c]\\)  
         and  
         \\( [a]\\oplus( [b]\\oplus[c] ) = [a]\\oplus[b+c] = [a+(b+c)]\\).  
         Since integer addition is associative, these are equal.',
        'easy',
        'one_of_many',
        $associativity_options,
        $associativity_correct,
        $exercise_number
    );
    $exercise_title   = 'Exercise 2.2 a.iii – Identity Element';
    $exercise_number++;
    $exercise_content = <<<HTML
    Identify the element \([e]\) in \(\mathbb{Z}/n\mathbb{Z}\) such that for every \([a]\) in \(\mathbb{Z}/n\mathbb{Z}\),
    \[
      [a] \oplus [e] = [a].
    \]
    HTML;
    
    $identity_options = json_encode([
        "A" => "\([1]\) is the identity element.",
        "B" => "\([0]\) is the identity element because \([a] \oplus [0] = [a]\).",
        "C" => "There is no identity element in \(\mathbb{Z}/n\mathbb{Z}\).",
        "D" => "The identity element depends on \(a\)."
    ]);
    
    $identity_correct = json_encode([
        'correct_option' => 'B'
    ]);
    
    $exercise_solution = 'The element \([0]\) acts as the identity because \([a] \oplus [0] = [a+0] = [a]\) for all \([a] \in \mathbb{Z}/n\mathbb{Z}\).';
    
    // 4) insert it as a multiple choice exercise (#3 in that lesson)
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'easy',           // difficulty
        'one_of_many',    // question type
        $identity_options,
        $identity_correct,
        $exercise_number                 // display order
    );
    
    if ( false === $result ) {
        error_log('Error inserting Exercise 2.2 a.iii: ' . $GLOBALS['wpdb']->last_error);
    }
    $exercise_title   = 'Exercise 2.2 a.iv – Inverse Elements';
    $exercise_number++;
    $exercise_content = <<<HTML
    For each element \([a]\) in \(\mathbb{Z}/n\mathbb{Z}\), find an element \([b]\) such that
    \[
    [a] \oplus [b] = [0].
    \]
    HTML;

    $inverse_options = json_encode([
        "A" => "The inverse of \([a]\) is \([-a]\)",
        "B" => "The inverse of \([a]\) is \([a]\) itself.",
        "C" => "There is no inverse element for \([a]\).",
        "D" => "The inverse of \([a]\) is \([n-a]\) when \(a \\neq 0\))."
    ]);

    $inverse_correct = json_encode([
        "correct_options" => ["A", "D"]
    ]);


    $exercise_solution = 'The inverse of \([a]\) is \([-a]\) (or equivalently \([n-a]\) when \(a \neq 0\)), since \([a] \oplus [-a] = [0]\).';

    // 4) insert it as a multiple choice exercise (#4 in that lesson)
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'medium',         // difficulty
        'multiple_choice',    // question type
        $inverse_options,
        $inverse_correct,
        $exercise_number                 // display order
    );
    // 3) prepare Exercise 2.1 d: Multiplicative Group in Z5\{0} as an array‑type exercise
    $exercise_title   = 'Exercise 2.1 d – Multiplicative Group in \(\mathbb{Z}_5\setminus\{0\}\)';
    $exercise_number++;
    $exercise_content = <<<HTML
    We now define another operation \(\otimes\) for all \(a,b\) in \(\mathbb{Z}_n\) as
    \[a \otimes b = a \times b \pmod n.\]
    Let \(n = 5\).  Fill in the times table for the set \(\{1,2,3,4\}\) under \(\otimes\).  Use the grid below with row and column headers labeling the elements of \(\mathbb{Z}_5\setminus\{0\}\).

    <table>
    <thead>
        <tr><th>⊗</th><th>1</th><th>2</th><th>3</th><th>4</th></tr>
    </thead>
    <tbody>
        <tr><th>1</th><td><input type="text" name="t[1][1]"></td><td><input type="text" name="t[1][2]"></td><td><input type="text" name="t[1][3]"></td><td><input type="text" name="t[1][4]"></td></tr>
        <tr><th>2</th><td><input type="text" name="t[2][1]"></td><td><input type="text" name="t[2][2]"></td><td><input type="text" name="t[2][3]"></td><td><input type="text" name="t[2][4]"></td></tr>
        <tr><th>3</th><td><input type="text" name="t[3][1]"></td><td><input type="text" name="t[3][2]"></td><td><input type="text" name="t[3][3]"></td><td><input type="text" name="t[3][4]"></td></tr>
        <tr><th>4</th><td><input type="text" name="t[4][1]"></td><td><input type="text" name="t[4][2]"></td><td><input type="text" name="t[4][3]"></td><td><input type="text" name="t[4][4]"></td></tr>
    </tbody>
    </table>
    HTML;

    // 4) specify table structure for rendering and validation
    $options = json_encode([
        'rows'    => ['1','2','3','4'],
        'columns' => ['1','2','3','4']
    ]);

    // correct multiplication table mod 5
    table:
    $correct_table = [
        ['1','2','3','4'],
        ['2','4','1','3'],
        ['3','1','4','2'],
        ['4','3','2','1'],
    ];
    $correct_answer = json_encode([
        'table' => $correct_table
    ]);

    $exercise_solution = <<<HTML
    The completed multiplication table modulo 5 is:
    <table>
    <thead>
        <tr><th>⊗</th><th>1</th><th>2</th><th>3</th><th>4</th></tr>
    </thead>
    <tbody>
        <tr><th>1</th><td>1</td><td>2</td><td>3</td><td>4</td></tr>
        <tr><th>2</th><td>2</td><td>4</td><td>1</td><td>3</td></tr>
        <tr><th>3</th><td>3</td><td>1</td><td>4</td><td>2</td></tr>
        <tr><th>4</th><td>4</td><td>3</td><td>2</td><td>1</td></tr>
    </tbody>
    </table>

    Since multiplication mod 5 is commutative and each nonzero element has an inverse, \((\mathbb{Z}_5\setminus\{0\},\otimes)\) is an Abelian group.
    HTML;

    // 5) insert as an array‑type exercise (#5 in that lesson)
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'medium',       // difficulty
        'array_type',   // custom table‑input type
        $options,
        $correct_answer,
        $exercise_number               // display order
    );

    if ( false === $result ) {
        error_log('Error inserting Exercise 2.1 d: ' . $GLOBALS['wpdb']->last_error);
    }
    
    // 3) define the exercise
    $exercise_title = 'Exercise 2.1 f – Multiplicative Inverses in \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\)';
    $exercise_number++;
    $exercise_content = <<<HTML
    In \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\), each element has a multiplicative inverse under \\(\\otimes\\), the operation defined by \\(a \\otimes b = a \\times b \\mod 5\\).  
    Fill in the table below by writing the inverse of each element (i.e., the element \\(b\\) such that \\(a \\otimes b = 1\\)):

    <table>
    <thead>
        <tr><th>Element</th><th>Inverse</th></tr>
    </thead>
    <tbody>
        <tr><td>1</td><td><input type="text" name="inv[1]"></td></tr>
        <tr><td>2</td><td><input type="text" name="inv[2]"></td></tr>
        <tr><td>3</td><td><input type="text" name="inv[3]"></td></tr>
        <tr><td>4</td><td><input type="text" name="inv[4]"></td></tr>
    </tbody>
    </table>
    HTML;

    // array format (row labels only)
    $options = json_encode([
        'rows' => ['1', '2', '3', '4'],
        'columns' => ['inverse']  // logically one column
    ]);

    // correct answers: inverse of a is b if a * b ≡ 1 mod 5
    $correct_table = [
        ['1'], // 1 * 1 = 1 mod 5
        ['3'], // 2 * 3 = 6 ≡ 1 mod 5
        ['2'], // 3 * 2 = 6 ≡ 1 mod 5
        ['4'], // 4 * 4 = 16 ≡ 1 mod 5
    ];

    $correct_answer = json_encode([
        'table' => $correct_table
    ]);

    $exercise_solution = <<<HTML
    The inverses in \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\) under multiplication modulo 5 are:

    <ul>
    <li>\\(1^{-1} = 1\\)</li>
    <li>\\(2^{-1} = 3\\), since \\(2 \\times 3 = 6 \\equiv 1\\mod 5\\)</li>
    <li>\\(3^{-1} = 2\\), since \\(3 \\times 2 = 6 \\equiv 1\\mod 5\\)</li>
    <li>\\(4^{-1} = 4\\), since \\(4 \\times 4 = 16 \\equiv 1\\mod 5\\)</li>
    </ul>
    HTML;

    // 4) insert the array_type inverse table
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'medium',
        'array_type',
        $options,
        $correct_answer,
        $exercise_number  // display order
    );

    // 3) define the exercise
    $exercise_title = 'Exercise 2.1 g – Properties of \\(\\otimes\\) in \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\)';
    $exercise_number++;
    $exercise_content = <<<HTML
    Which of the following properties hold for the operation \\(a \\otimes b = a \\times b \\mod 5\\) in \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\)?

    Select all that apply.
    HTML;

    $exercise_solution = <<<HTML
    In \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\), the operation \\(\\otimes\\) (i.e., multiplication modulo 5) is:

    <ul>
    <li><strong>Commutative</strong>: \\(a \\otimes b = b \\otimes a\\) for all \\(a, b\\)</li>
    <li><strong>Associative</strong>: \\((a \\otimes b) \\otimes c = a \\otimes (b \\otimes c)\\) for all \\(a, b, c\\)</li>
    </ul>

    Thus, both properties are satisfied.
    HTML;

    $options = json_encode([
        "A" => "\\(a \\otimes b = b \\otimes a\\) (Commutativity)",
        "B" => "\\((a \\otimes b) \\otimes c = a \\otimes (b \\otimes c)\\) (Associativity)",
        "C" => "\\(\\otimes\\) is not closed in \\(\\mathbb{Z}_5 \\setminus \\{0\\}\\)",
        "D" => "\\(a \\otimes (b + c) = a \\otimes b + a \\otimes c\\) (Distributive over addition)"
    ]);

    $correct_answer = json_encode([
        "correct_options" => ["A", "B"]
    ]);

    // 4) insert the multiple_choice question
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'medium',
        'multiple_choice',
        $options,
        $correct_answer,
        $exercise_number  // display order
    );

    if (false === $result) {
        error_log('Error inserting Exercise 2.1 g: ' . $GLOBALS['wpdb']->last_error);
    }
    $exercise_number++;
    // Exercise 2.2 c: Is (Z8\{0}, ⊗) a group?
    $result = add_exercise(
        $lesson_id,                                  // lesson_id – ID lekcji
        $category_id,                                // category_id – ID kategorii
        'Exercise 2.2 c - Is (Z8\\{0}, ⊗) a Group?',  // exercise_title
        'Is (Z8\\{0}, ⊗) a group? In other words, answer the question: Is (Z8\\{0}, ⊗) a group under multiplication modulo 8?', // exercise_content
        'Solution: For a set with an operation to be a group, every element must have an inverse. In Z8\\{0}, the set is {1, 2, 3, 4, 5, 6, 7}. However, many elements (for example, 2) do not have an inverse modulo 8 because gcd(2, 8) ≠ 1. Therefore, (Z8\\{0}, ⊗) is not a group.', // exercise_solution
        'easy',                                      // difficulty
        'multiple_choice',                           // question_type
        '{"A": "Yes", "B": "No"}',                   // options
        '{"correct_options": ["B"]}',                // correct_answer
        $exercise_number                                            // exercise_number (np. 0 – dostosuj według potrzeb)
    );
    if ( false === $result ) {
        error_log('Error inserting Exercise 2.2 c: ' . $GLOBALS['wpdb']->last_error);
    }
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,                                  // lesson_id – the ID of the lesson this exercise belongs to
        $category_id,                                // category_id – the category term ID (e.g., from "linear-algebra")
        'Exercise 2.2 d - Bézout Theorem and Group',  // exercise_title
        'We recall that the B&eacute;zout theorem states that two integers a and b are relatively prime (i.e., gcd(a, b) = 1) if and only if there exist two integers u and v such that au + bv = 1. Show that (Zₙ\{0}, ⊗) is a group if and only if n ∈ ℕ\{0} is prime.', // exercise_content
        'Solution: By Bézout&apos;s theorem, every nonzero element a in Zₙ has a multiplicative inverse if and only if gcd(a, n) = 1. When n is prime, every element in Zₙ\\{0} is relatively prime to n, so each element has an inverse and (Zₙ\\{0}, ⊗) forms a group. If n is composite, there will be elements that do not have an inverse, and therefore (Zₙ\\{0}, ⊗) is not a group.', // exercise_solution
        'medium',                                    // difficulty
        'multiple_choice',                           // question_type
        '{"A": "It is a group for all n ∈ N\\{0}.", "B": "It is a group if and only if n is prime.", "C": "It is a group if and only if n is composite.", "D": "It is never a group."}', // options (JSON)
        '{"correct_options": ["B"]}',                // correct_answer (JSON with one correct option)
        $exercise_number                                            // exercise_number (set to 0 if not mapping to a particular lesson)
    );
    if ( false === $result ) {
        error_log('Error inserting Exercise 2.2 d: ' . $GLOBALS['wpdb']->last_error);
    }
    $exercise_number++;
    // Set the option so these exercises are not added again.
    
    $result = add_exercise(
        $lesson_id,                                  // lesson_id – the ID of the lesson to which this exercise belongs
        $category_id,                                // category_id – from your "linear-algebra" term
        'Exercise 2.2 a - Group (Z/nZ, ⊕)',            // exercise_title
        'Let n ∈ ℕ\\{0}. For any a, b ∈ ℤ/nℤ we define a ⊕ b := a + b (mod n). Prove that (ℤ/nℤ, ⊕) is a group. Is it Abelian?', // exercise_content
        'Solution: To prove that (ℤ/nℤ, ⊕) is a group, verify closure, associativity, the existence of an identity element (0), and that every element has an inverse. Since addition modulo n is commutative, the group is Abelian.', // exercise_solution
        'easy',                                      // difficulty
        'multiple_choice',                           // question_type
        '{"A": "(Z/nℤ, ⊕) is a group.", "B": "(Z/nℤ, ⊕) is Abelian.", "C": "The identity element is 0.", "D": "Every element has an inverse.", "E": "Addition is not closed modulo n."}', // options
        '{"correct_options": ["A", "B", "C", "D"]}', // correct_answer
        $exercise_number                                           // exercise_number (or display_order) – e.g., 0
    );
    if ( false === $result ) {
        error_log('Error inserting Exercise 2.2 a: ' . $GLOBALS['wpdb']->last_error);
    }
}

function add_vector_spaces_exercises_lesson4(){

    // 1) find your “vector‑spaces” term
    $term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
    if ( ! $term ) {
        error_log( 'Term "vector-spaces" not found.' );
        return;
    }
    $category_id = $term->term_id;
    $exercise_number=1;
    // 2) find the first lesson in that category
    $lesson_id = get_lesson_for_category( $category_id, 5 );
    if ( ! $lesson_id ) {
        error_log( 'No lesson found for category ID ' . $category_id );
        return;
    }

        $exercise_title = "Least Squares as Projection onto a Subspace";
        $exercise_content = <<<HTML
        In the context of <strong>Linear Regression</strong>, consider a data matrix \(X \in \mathbb{R}^{n\times m}\), whose columns \(\{x_1,\dots,x_m\}\subset \mathbb{R}^n\) span the column space \(\mathcal{S} = \mathrm{Col}(X)\subseteq \mathbb{R}^n\).  Let \(y\in\mathbb{R}^n\) be the response vector.  In the least‐squares approach, we seek \(\hat w\in\mathbb{R}^m\) that minimizes \(\|y - Xw\|^2\).
        <br><br>
        Which of the following best describes what happens to \(y\) in this approach?
        HTML;

        $options = json_encode([
            "A" => "It is orthogonally projected onto the subspace \\(\\mathcal{S}\\).",
            "B" => "It is reflected across the subspace \\(\\mathcal{S}\\).",
            "C" => "It is rotated into the subspace \\(\\mathcal{S}\\).",
            "D" => "It is scaled to lie in the subspace \\(\\mathcal{S}\\)."
        ]);

        $solution = <<<HTML
        <strong>Solution.</strong><br>
        The normal equations \(X^T X \hat w = X^T y\) imply that \(X\hat w = P_{\\mathcal{S}}(y)\), the orthogonal projection of \(y\) onto the column space \\(\\mathcal{S}\\).  This choice of \\(\\hat w\\) minimizes \\(\\|y - Xw\\|^2\\).  Thus \\(y\\) is orthogonally projected onto \\(\\mathcal{S}\\).
        HTML;

        $correct_answer = json_encode([ 'correct_option' => "A" ]);

        add_exercise(
            $lesson_id,
            $category_id,
            $exercise_title,
            $exercise_content,
            $solution,
            'medium',
            'one_of_many',
            $options,
            $correct_answer,
            $exercise_number
        );

}
function add_vector_spaces_exercises_lesson5() {

    // 1) find your “vector‑spaces” term
    $term = get_term_by( 'slug', 'vector-spaces', 'course_topic' );
    if ( ! $term ) {
        error_log( 'Term "vector-spaces" not found.' );
        return;
    }
    $category_id = $term->term_id;

    // 2) find the first lesson in that category
    $lesson_id = get_lesson_for_category( $category_id, 5 );
    $exercise_number = 1; // next available display order
    if ( ! $lesson_id ) {
        error_log( 'No lesson found for category ID ' . $category_id );
        return;
    }
    // 3) Define the exercise: showing (Z8 \\ {0}, ⊗) is not a group
    $exercise_title = 'Exercise 2.1 h – Why \\((\\mathbb{Z}_8 \\setminus \\{0\\}, \\otimes)\\) is not a Group';
    $exercise_content = <<<HTML
    Let \\(\\otimes\\) be the multiplication operation modulo 8:  
    \\[
    a \\otimes b = a \\times b \\mod 8
    \\]  
    Which of the following statements explain why \\((\\mathbb{Z}_8 \\setminus \\{0\\}, \\otimes)\\) is <strong>not</strong> a group?
    Select all that apply.
    HTML;

    $exercise_solution = <<<HTML
    To be a group, the set must satisfy <strong>closure</strong>, <strong>associativity</strong>, contain an <strong>identity element</strong>, and each element must have an <strong>inverse</strong>.

    <ul>
    <li>\\(\\otimes\\) is closed in \\(\\mathbb{Z}_8 \\setminus \\{0\\}\\).</li>
    <li>\\(\\otimes\\) is associative (inherited from integer multiplication).</li>
    <li>But there is <strong>no identity element</strong> in \\(\\mathbb{Z}_8 \\setminus \\{0\\}\\).</li>
    <li>Also, not every element has an inverse (e.g., 4 has no inverse).</li>
    </ul>

    Thus, it fails to be a group.
    HTML;

    $options = json_encode([
        "A" => "\\(\\otimes\\) is closed in \\(\\mathbb{Z}_8 \\setminus \\{0\\}\\).",
        "B" => "\\(\\otimes\\) is not associative.",
        "C" => "There is no identity element.",
        "D" => "There is no inverse for 4."
    ]);

    $correct_answer = json_encode([
        "correct_options" => ["C", "D"]
    ]);

    // 4) Insert the exercise into the lesson
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'medium',
        'multiple_choice',
        $options,
        $correct_answer,
        $exercise_number // next available display order
    );

    if (false === $result) {
        error_log('Error inserting Exercise 2.1 h: ' . $GLOBALS['wpdb']->last_error);
    }
    // 3) define the exercise
    $exercise_number++;
    $exercise_title = 'Exercise 2.1 i – Bézout’s Theorem and When \\(\\mathbb{Z}_n \\setminus \\{0\\}\\) Forms a Group';
    $exercise_content = <<<HTML
    Recall that Bézout’s theorem states that for any two integers \\(a\\) and \\(b\\),  
    \\[
    \\gcd(a,b) = 1 \\quad \\text{if and only if there exist integers } u,v \\text{ such that } au + bv = 1.
    \\]

    Using this fact, select the key steps in proving that \\(\\mathbb{Z}_n \\setminus \\{0\\}\\) under multiplication modulo \\(n\\) is a group <strong>if and only if</strong> \\(n\\) is prime.
    HTML;

    $options = json_encode([
        "A" => "If \\(n\\) is composite, then there exists \\(a\\) such that \\(\\gcd(n,a) > 1\\).",
        "B" => "Integer multiplication is associative and the modulo operation preserves associativity.",
        "C" => "If \\(\\mathbb{Z}_n \\setminus \\{0\\}\\) is a group, then for every element \\(a\\), \\(\\gcd(n,a) = 1\\), which is the definition of a prime number.",
        "D" => "\\(0\\) is the inverse element."
    ]);

    $correct_answer = json_encode([
        "correct_options" => ["A", "B", "C"]
    ]);

    $exercise_solution = <<<HTML
    Key reasoning steps include:

    <ul>
    <li><strong>A:</strong> If \\(n\\) is composite, there exists \\(a\\) not coprime to \\(n\\), so not all elements can have inverses — violating the group property.</li>
    <li><strong>B:</strong> Associativity holds for multiplication modulo \\(n\\), and is essential for any group.</li>
    <li><strong>C:</strong> If every nonzero element has a multiplicative inverse, then every \\(a\\) must be coprime to \\(n\\), which only happens when \\(n\\) is prime.</li>
    <li><strong>D:</strong> Incorrect — \\(0\\) is excluded from \\(\\mathbb{Z}_n \\setminus \\{0\\}\\), and it is never invertible anyway.</li>
    </ul>
    HTML;

    // 4) add to database
    $result = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $exercise_solution,
        'hard',
        'multiple_choice',
        $options,
        $correct_answer,
        $exercise_number // display order
    );

    if (false === $result) {
        error_log('Error inserting Exercise 2.1 i: ' . $GLOBALS['wpdb']->last_error);
    }

    // Question 1: Z6 \ {0}
    $exercise_number++;
    $exercise_title_6 = 'Exercise 2.1 j – Is \\((\\mathbb{Z}_6 \\setminus \\{0\\}, \\otimes)\\) a Group?';
    $exercise_content_6 = 'Does \\(\\mathbb{Z}_6 \\setminus \\{0\\}\\) form a group under multiplication modulo 6?';
    $options_6 = json_encode([
        "A" => "Yes",
        "B" => "No"
    ]);
    $correct_answer_6 = json_encode([
        "correct_option" => "B"
    ]);
    $solution_6 = 'No — \\(\\mathbb{Z}_6 \\setminus \\{0\\} = \\{1,2,3,4,5\\}\\) includes elements that are not coprime to 6 (e.g., 2 and 3), so some elements lack inverses. Hence, it is not a group.';

    $result_6 = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title_6,
        $exercise_content_6,
        $solution_6,
        'easy',
        'one_of_many',
        $options_6,
        $correct_answer_6,
        $exercise_number
    );
    if (false === $result_6) {
        error_log('Error inserting Exercise 2.1 j (Z6): ' . $GLOBALS['wpdb']->last_error);
    }
    // Question 2: Z11 \ {0}
    $exercise_number++;
    $exercise_title_11 = 'Exercise 2.1 k – Is \\((\\mathbb{Z}_{11} \\setminus \\{0\\}, \\otimes)\\) a Group?';
    $exercise_content_11 = 'Does \\(\\mathbb{Z}_{11} \\setminus \\{0\\}\\) form a group under multiplication modulo 11?';
    $options_11 = json_encode([
        "A" => "Yes",
        "B" => "No"
    ]);
    $correct_answer_11 = json_encode([
        "correct_option" => "A"
    ]);
    $solution_11 = 'Yes — 11 is a prime number, so every nonzero element in \\(\\mathbb{Z}_{11}\\) is coprime to 11 and has a multiplicative inverse. Thus, it forms a group.';

    $result_11 = add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title_11,
        $exercise_content_11,
        $solution_11,
        'easy',
        'one_of_many',
        $options_11,
        $correct_answer_11,
        $exercise_number
    );
    if (false === $result_11) {
        error_log('Error inserting Exercise 2.1 k (Z11): ' . $GLOBALS['wpdb']->last_error);
    }

    // 1) define the exercise title
    $exercise_title = "Definition of a subspace";

    // 2) define the exercise content
    $exercise_number++;
    $exercise_content = <<<HTML
    Which of the following lists the necessary and sufficient conditions for a subset \\(S\\) of a vector space \\(V\\) to be a subspace of \\(V\\)?
    HTML;

    // 3) define the multiple‑choice options
    $options = json_encode([
        "A" => "Contains the zero vector; closed under vector addition; closed under scalar multiplication.",
        "B" => "Non-empty; closed under vector addition; closed under taking inverses.",
        "C" => "Contains the zero vector; closed under vector addition; closed under taking inverses.",
        "D" => "Non-empty; closed under vector addition; closed under scalar multiplication."
    ]);

    // 4) mark the correct answer
    $correct_answer = json_encode([
        'correct_option' => "A"
    ]);

    // 5) insert into the system
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        '',               // no immediate solution dump
        'easy',
        'one_of_many',
        $options,
        $correct_answer,
        $exercise_number                 // this is exercise #10 in that lesson
    );
    // 1) define the exercise title
    $exercise_title = "Definition of a subspace";

    // 2) define the exercise content
    $exercise_number++;
    $exercise_content = <<<HTML
    Fill in the gaps to list the necessary and sufficient conditions for a subset \\(S\\) of a vector space \\(V\\) to be a subspace of \\(V\\)?
    HTML;

    // 3) define the multiple‑choice options
    $options = json_encode([
        "Contains the {input1}; closed under {input2}; closed under {input3}.",
        
    ]);

    // 4) mark the correct answer
    $correct_answer = json_encode([
        'correct_answer' => ["zero vector", "vector addition", "scalar multiplication"]
    ]);

    // 5) insert into the system
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        '',               // no immediate solution dump
        'easy',
        'open_text',
        $options,
        $correct_answer,
        $exercise_number                 // this is exercise #10 in that lesson
    );
    // ---------- Question 1: W1 (multiple‑answer) ----------
    $exercise_title = "Which subspace properties does the following subset of \\(\\mathbb{R}^4\\) satisfy? (Set \\(W_1\\))";
    $exercise_number++;
    $exercise_content = <<<HTML
    Consider the subset
    \\[
    W_1 = \{(x,y,z,w)\in\mathbb{R}^4 : x + 2y - z + w = 0\}.
    \\]
    Select <strong>all</strong> of the properties below that \(W_1\) satisfies.
    HTML;
    $options = json_encode([
        "A" => "Contains the zero vector",
        "B" => "Closed under addition",
        "C" => "Closed under scalar multiplication"
    ]);
    $correct_answer = json_encode([
        'correct_options' => ["A", "B", "C"]
    ]);
    $solution = <<<HTML
    <strong>Solution.</strong>  
    Write the defining equation as a linear functional:
    \[
    \ell(x,y,z,w)=x+2y-z+w.
    \]
    1. <strong>Zero vector</strong>: \(\ell(0,0,0,0)=0\), so \((0,0,0,0)\in W_1\).  
    2. <strong>Addition</strong>: If \(\ell(u)=0\) and \(\ell(v)=0\), then \(\ell(u+v)=\ell(u)+\ell(v)=0+0=0\).  
    3. <strong>Scalar multiplication</strong>: If \(\ell(u)=0\), then \(\ell(c\,u)=c\,\ell(u)=c\cdot0=0\).  
    Hence \(W_1\) contains zero and is closed under addition and scalar multiplication.
    HTML;
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $solution,
        'easy',
        'multiple_choice',
        $options,
        $correct_answer,
        $exercise_number
    );

    // ---------- Question 2: W2 ----------
    $exercise_title = "Is the following subset of \\(\\mathbb{R}^4\\) a subspace? (Set \\(W_2\\))";
    $exercise_number++;
    $exercise_content = <<<HTML
    Consider the subset
    \\[
    W_2 = \{(x,y,z,w)\in\mathbb{R}^4 : x\,y = 0\}.
    \\]
    Is \(W_2\) a subspace of \\(\mathbb{R}^4\\)?
    HTML;
    $options = json_encode([
        "A" => "Yes",
        "B" => "No"
    ]);
    $solution = <<<HTML
    <strong>Solution.</strong>  
    1. <strong>Zero vector</strong>: \((0,0,0,0)\) satisfies \(0\cdot0=0\), so \(0\in W_2\).  
    2. <strong>Closure under addition fails</strong>: Take \(u=(1,0,0,0)\) and \(v=(0,1,0,0)\).  Both satisfy \(x\,y=0\), but
    \[
    u+v=(1,1,0,0),\quad 1\cdot1=1\neq0,
    \]
    so \(u+v\notin W_2\).  
    Thus \(W_2\) is <strong>not</strong> a subspace.
    HTML;
    $correct_answer = json_encode([ 'correct_option' => "B" ]);
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $solution,
        'easy',
        'one_of_many',
        $options,
        $correct_answer,
        $exercise_number
    );

    // ---------- Question 3: W3 ----------
    $exercise_title = "Is the following subset of \\(\\mathbb{R}^4\\) a subspace? (Set \\(W_3\\))";
    $exercise_number++;
    $exercise_content = <<<HTML
    Consider the subset
    \\[
    W_3 = \{(x,y,z,w)\in\mathbb{R}^4 : x = 2s,\;y = -s,\;z = t,\;w = 3t,\;s,t\in\mathbb{R}\}.
    \\]
    Is \(W_3\) a subspace of \\(\mathbb{R}^4\\)?
    HTML;
    $options = json_encode([
        "A" => "Yes",
        "B" => "No"
    ]);
    $solution = <<<HTML
    <strong>Solution.</strong>  
    We can rewrite any element as
    \[
    (x,y,z,w) = s\,(2,-1,0,0) \;+\; t\,(0,0,1,3).
    \]
    Thus
    \[
    W_3 = \operatorname{Span}\{(2,-1,0,0),\,(0,0,1,3)\},
    \]
    which contains the zero vector and is closed under addition and scalar multiplication. Therefore \(W_3\) is a subspace.
    HTML;
    $correct_answer = json_encode([ 'correct_option' => "A" ]);
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $solution,
        'easy',
        'one_of_many',
        $options,
        $correct_answer,
        $exercise_number
    );

    // ---------- Question 4: W4 ----------
    $exercise_title = "Is the following subset of \\(\\mathbb{R}^4\\) a subspace? (Set \\(W_4\\))";
    $exercise_number++;
    $exercise_content = <<<HTML
    Consider the subset
    \\[
    W_4 = \{(x,y,z,w)\in\mathbb{R}^4 : y\in\mathbb{Z}\}.
    \\]
    Is \(W_4\) a subspace of \\(\mathbb{R}^4\\)?
    HTML;
    $options = json_encode([
        "A" => "Yes",
        "B" => "No"
    ]);
    $solution = <<<HTML
    <strong>Solution.</strong>  
    1. <strong>Zero vector</strong>: \((0,0,0,0)\) is in \(W_4\) since \(0\in\mathbb{Z}\).  
    2. <strong>Scalar multiplication fails</strong>: Take \(v=(0,1,0,0)\in W_4\) (since \(1\in\mathbb{Z}\)) and scalar \(c=\tfrac12\).  Then
    \[
    c\,v = \bigl(0,\tfrac12,0,0\bigr),
    \]
    but \(\tfrac12\notin\mathbb{Z}\), so \(c\,v\notin W_4\).  
    Hence \(W_4\) is <strong>not</strong> a subspace.
    HTML;
    $correct_answer = json_encode([ 'correct_option' => "B" ]);
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $solution,
        'easy',
        'one_of_many',
        $options,
        $correct_answer,
        $exercise_number
    );
    // ---------- Question 4: W4 ----------
    $exercise_title = "Why set \\(W_4\\) is not a subspace of \\(\\mathbb{R}^4\\) ";
    $exercise_number++;
    $exercise_content = <<<HTML
    Consider the subset
    \\[
    W_4 = \{(x,y,z,w)\in\mathbb{R}^4 : y\in\mathbb{Z}\}.
    \\]
    Why \(W_4\) is not a subspace of \\(\mathbb{R}^4\\)?
    HTML;
    $options = json_encode([
        "A" => "Does not contain the zero vector",
        "B" => "Closed under addition",
        "C" => "Closed under scalar multiplication",
        "D" => "Closed under taking inverses"
    ]);
    $solution = <<<HTML
    <strong>Solution.</strong>  
    1. <strong>Zero vector</strong>: \((0,0,0,0)\) is in \(W_4\) since \(0\in\mathbb{Z}\).  
    2. <strong>Scalar multiplication fails</strong>: Take \(v=(0,1,0,0)\in W_4\) (since \(1\in\mathbb{Z}\)) and scalar \(c=\tfrac12\).  Then
    \[
    c\,v = \bigl(0,\tfrac12,0,0\bigr),
    \]
    but \(\tfrac12\notin\mathbb{Z}\), so \(c\,v\notin W_4\).  
    Hence \(W_4\) is <strong>not</strong> a subspace.
    HTML;
    $correct_answer = json_encode([ 'correct_option' => "C" ]);
    add_exercise(
        $lesson_id,
        $category_id,
        $exercise_title,
        $exercise_content,
        $solution,
        'easy',
        'multiple_choice',  
        $options,
        $correct_answer,
        $exercise_number
    );


}

function add_vector_spaces_exercises() {

    add_vector_spaces_exercises_lesson1();
    add_vector_spaces_exercises_lesson2();
    add_vector_spaces_exercises_lesson3();
    add_vector_spaces_exercises_lesson4();
    add_vector_spaces_exercises_lesson5();

}
?>