<?php

function add_naive_bayes_lesson($lesson_number){
    $module_three_term = get_term_by( 'slug', 'module-three-en', 'course_topic' );
    if ( $module_three_term ) {
        $category_id = $module_three_term->term_id;
    } else {
        error_log('Term "module-three-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    // --- Discriminative vs Generative: what do we learn? (single-correct multiple choice) ---

    $options = json_encode([
        "A" => "Only the marginal distribution P(x).",
        "B" => "The posterior P(y|x) directly.",
        "C" => "The class-conditional P(x|y) together with the class prior P(y).",
        "D" => "Only the decision boundary without probabilities.",
        "E" => "The conditional P(y|x) and then derive P(x|y)."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["C"] // single correct choice
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Discriminative vs. Generative: What Do We Learn?',
        <<<EOT
    In <strong>discriminative</strong> learning we model \\(P(y\\mid x)\\).  
    In <strong>generative</strong> learning, what do we typically model?

    <strong>Hint:</strong> In generative approaches we specify how data are generated <em>given the class</em> and include the class prior. Using Bayes' rule then yields \\(P(y\\mid x)\\).
    EOT,
        'Solution: C — Generative models learn P(x|y) and P(y). Equivalently, this defines the joint P(x,y) = P(x|y) P(y), from which P(y|x) is obtained via Bayes’ rule.',
        'easy',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;
    $options = json_encode([
        "input1", "input2", "input3"
    ]
    );

    $correct_option = json_encode([
        "correct_options" => [
            "input1" => "x|y=1",
            "input2" => "y=1",
            "input3"  => "x"
        ]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Fill Bayes’ Rule for P(y=1|x)',
        <<<EOT
    Fill the three blanks to write <strong>Bayes’ rule</strong> for the posterior \\(P(y=1\\mid x)\\):

    \\[
    P(y=1\\mid x) = \\frac{P({input1}),P({input2})}{P({input3})}.
    \\]

    Type <strong>only</strong> the content that goes <em>inside</em> each \\(P(\\cdot)\\).  <br>
    Examples of formatting that are accepted: <code>x|y=1</code>, <code>x | y=1</code>, etc.<br>
        <br>
    <strong>Hint:</strong> Bayes’ rule combines the <strong>likelihood</strong> and the <strong>prior</strong> over the <strong>evidence</strong>.
    EOT,
        'Solution: Bayes’ rule here is P(y=1|x) = P(x|y=1) · P(y=1) / P(x).',
        'easy',
        'labeled_inputs',
        $options,
        $correct_option,
        $exercise_number
    );

    $exercise_number++;
    // --- Naive Bayes: what kinds of data can it work on? (multiple choice) ---

    $options = json_encode([
        "A" => "Bag-of-Words or TF-IDF vectors for text (counts/frequencies).",
        "B" => "Binary indicator vectors (feature present/absent).",
        "C" => "Real-valued continuous features modeled per class with a Gaussian.",
        "D" => "Low-cardinality categorical features after one-hot encoding (or via categorical NB).",
        "E" => "Mixed feature types by modeling each feature with an appropriate likelihood (e.g., Gaussian for numeric, Bernoulli/Multinomial for discrete).",
        "F" => "Arbitrary graphs or variable-length sequences with no feature extraction or featurization.",
        "G" => "Raw images as 2-D arrays with no vectorization or feature engineering.",
        "H" => "Free-form text strings with no tokenization or vectorization."
    ]);

    $correct_matches = json_encode([
        // Select all that apply
        "correct_options" => ["A","B","C","D","E"]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – What Data Can Naive Bayes Use?',
        <<<EOT
    Select all data representations on which <strong>Naive Bayes</strong> can be directly applied (assuming standard modeling choices for each variant).

    EOT,
        'Solution: A, B, C, D, E. Naive Bayes requires fixed-length feature vectors. It works with text counts/TF-IDF (Multinomial NB), binary indicators (Bernoulli NB), real-valued features (Gaussian NB), categorical features after one-hot encoding (or categorical NB), and even mixed feature types if each feature is modeled with an appropriate likelihood under the conditional-independence assumption. Raw graphs, variable-length sequences, images, or free text must first be featurized into vectors.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

}
function add_gaussian_discriminant_analysis_lesson($lesson_number) {
    $module_three_term = get_term_by( 'slug', 'module-three-en', 'course_topic' );
    if ( $module_three_term ) {
        $category_id = $module_three_term->term_id;
    } else {
        error_log('Term "module-three-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
        // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $code_covariance_visualization=<<<PY
    import numpy as np
    import matplotlib.pyplot as plt
    import io
    import base64

    # Injected parameters
    mu0 = np.array({{mu0}})
    mu1 = np.array({{mu1}})
    cov0 = np.array({{cov0}})
    cov1 = np.array({{cov1}})
    n_samples = {{n_samples}}
    seed = {{seed}}

    np.random.seed(seed)
    X0 = np.random.multivariate_normal(mu0, cov0, n_samples)
    X1 = np.random.multivariate_normal(mu1, cov1, n_samples)
    X = np.vstack([X0, X1])
    y = np.array([0]*n_samples + [1]*n_samples)

    def gaussian_logpdf(x, mu, cov_inv, cov_det):
        diff = x - mu
        return -0.5 * np.sum(diff @ cov_inv * diff, axis=1) - 0.5 * np.log(cov_det + 1e-10)

    # Precompute inverses and determinants
    cov0_inv = np.linalg.inv(cov0)
    cov1_inv = np.linalg.inv(cov1)
    det0 = np.linalg.det(cov0)
    det1 = np.linalg.det(cov1)

    def predict_proba(X):
        logp0 = gaussian_logpdf(X, mu0, cov0_inv, det0)
        logp1 = gaussian_logpdf(X, mu1, cov1_inv, det1)
        log_joint0 = logp0 + np.log(0.5)
        log_joint1 = logp1 + np.log(0.5)
        max_log = np.maximum(log_joint0, log_joint1)
        probs = np.exp(log_joint1 - max_log) / (np.exp(log_joint0 - max_log) + np.exp(log_joint1 - max_log))
        return probs

    # Visualization using fig and ax
    fig, ax = plt.subplots(figsize=(6,6))
    ax.scatter(X[:,0], X[:,1], c=y, cmap='RdBu', edgecolors='k', alpha=0.6)

    xlim = plt.xlim()
    ylim = plt.ylim()
    xx, yy = np.meshgrid(np.linspace(*xlim, 200), np.linspace(*ylim, 200))
    grid = np.c_[xx.ravel(), yy.ravel()]
    Z = predict_proba(grid).reshape(xx.shape)

    ax.contour(xx, yy, Z, levels=[0.5], colors='black')
    ax.set_title("Decision Boundary: GDA / QDA (NumPy only)")
    ax.set_xlabel("x₁")
    ax.set_ylabel("x₂")
    ax.grid(True)

    # Save plot as base64 PNG string
    buf = io.BytesIO()
    fig.savefig(buf, format='png', bbox_inches='tight')
    buf.seek(0)
    b64 = base64.b64encode(buf.read()).decode('utf-8')
    print("data:image/png;base64," + b64)

    PY;

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise – Covariance and GDA Boundary',
        '<p>This exercise lets you visualize how the decision boundary of a Gaussian Discriminant Analysis (GDA) model changes when you vary the covariance matrices of the classes. By default, both classes share the same covariance (LDA setting). Try changing the values to explore Quadratic Discriminant Analysis (QDA) behavior.</p>
        <p><strong>Instructions:</strong> Modify the covariance matrices of class 0 and class 1. Run the visualization to see how the boundary adapts.</p>',
        json_encode([
            'params' => [
                'mu0' => '[0, 0]',
                'mu1' => '[3, 3]',
                'cov0' => '[[1, 0], [0, 1]]',
                'cov1' => '[[1, 0], [0, 1]]',
                'n_samples' => 100,
                'seed' => 42
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        'easy',
        'geometric_interpretation',
        json_encode(['code' => $code_covariance_visualization], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        '',
        $exercise_number
    );
    $exercise_number++;
}

function add_GNB_vs_GDA_comparison_lesson($lesson_number){
    $module_three_term = get_term_by( 'slug', 'module-three-en', 'course_topic' );
    if ( $module_three_term ) {
        $category_id = $module_three_term->term_id;
    } else {
        error_log('Term "module-three-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;
    // --- GNB Exercise 1: drag_and_drop (per-class diagonal covariances as variance vectors) ---

    $options = json_encode([
        "A" => "    X0 = X[y == 0]",
        "B" => "    X1 = X[y == 1]",
        "C" => "    mu0 = np.mean(X0, axis=0)",
        "D" => "    mu1 = np.mean(X1, axis=0)",
        "E" => "    diff0 = X0 - mu0",
        "F" => "    diff1 = X1 - mu1",
        "G" => "    sigma2_0 = np.mean(diff0**2, axis=0)",
        "H" => "    sigma2_1 = np.mean(diff1**2, axis=0)"
    ]);

    $correct_matches = json_encode([
        "1" => "A", // X0 = X[y == 0]
        "2" => "B", // X1 = X[y == 1]
        "3" => "C", // mu0 = mean(X0)
        "4" => "D", // mu1 = mean(X1)
        "5" => "E", // diff0 = X0 - mu0
        "6" => "F", // diff1 = X1 - mu1
        "7" => "G", // sigma2_0 = mean(diff0**2, axis=0)
        "8" => "H", // sigma2_1 = mean(diff1**2, axis=0)
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Class-Conditional Diagonal Covariances for Gaussian Naive Bayes (Two Classes)',
        <<<EOT
        Arrange the code lines to compute <strong>Gaussian Naive Bayes</strong> stats for two classes: per-class mean vectors and <strong>diagonal covariances</strong> (stored as variance vectors \\(\\sigma^2_k\\)).
        Assume \\(X\\) has shape \\((n\\_{samples}, d)\\) and \\(y \\in \\{0,1\\}\\).

        <pre><code>
        import numpy as np

        def gnb_class_stats(X, y):
        {blank1}
        {blank2}
        {blank3}
        {blank4}
        {blank5}
        {blank6}
        {blank7}
        {blank8}
            # (Optional) If you need matrices:
            # Sigma0 = np.diag(sigma2_0)
            # Sigma1 = np.diag(sigma2_1)
        </code></pre>

        Hints:
        - <strong>Naive Bayes assumption</strong>: features are conditionally independent → <strong>diagonal</strong> covariances.
        - MLE per feature: \\(\\sigma^2_{k,j} = \\frac{1}{n_k}\\sum_i (x^{(i)}_{k,j} - \\mu_{k,j})^2\\).
        - For *unbiased* estimates, divide by \\(n_k - 1\\) (i.e., use <code>np.var(..., ddof=1)</code>).
        </br></br></br>
        EOT,
        'Solution: Split by class, compute class means, center to get diff0/diff1, then per-feature MLE variances sigma2_0 = mean(diff0**2) and sigma2_1 = mean(diff1**2). These are the diagonal entries for GNB.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    // --- GNB Exercise 2: open_text (fill the class-0 variance vector) ---

    $options = null;

    // Accept common equivalent expressions (with/without spaces, mean/sum/var forms)
    $correct = json_encode([
        "correct_option" => [
            "(diff0**2).mean(axis=0)",
            "np.mean(diff0**2, axis=0)",
            "np.sum(diff0**2, axis=0)/len(X0)",
            "np.var(X0, axis=0)",
            "np.var(X0,axis=0)",
            "np.var(X0, axis=0, ddof=0)",
            "np.var(X0,axis=0,ddof=0)"
        ]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Fill the Class-0 Variance Vector (GNB)',
        <<<EOT
        Complete the missing <strong>variance vector</strong> expression for <strong>Gaussian Naive Bayes</strong> (class 0).
        Assume \\(X\\) has shape \\((n_{samples}, d)\\), \\(y \\in \\{0,1\\}\\), and:

        <pre><code>
        import numpy as np

        def gnb_class_stats(X, y):
            X0 = X[y == 0]
            X1 = X[y == 1]

            mu0 = np.mean(X0, axis=0)
            mu1 = np.mean(X1, axis=0)

            diff0 = X0 - mu0
            diff1 = X1 - mu1

            # Fill only the expression on the right-hand side (no "sigma2_0 ="):
            sigma2_0 = {blank1}

            # (For class 1, analogously: sigma2_1 = np.mean(diff1**2, axis=0))
            return mu0, mu1, sigma2_0
        </code></pre>

        <strong>Type exactly the expression only</strong> (without <code>sigma2_0 =</code>), e.g. <code>...</code>.

        <em>Reminder (MLE):</em>
        \\[
        \\sigma^2_{0} = \\operatorname{mean}\\big((X_0 - \\mu_0)^{\\odot 2}\\big)\\;\\text{(axis 0)}.
        \\]
        You may also use <code>np.var(X0, axis=0)</code> (default <code>ddof=0</code>).
        EOT,
        'Solution: A correct expression is (diff0**2).mean(axis=0). Equivalently: np.mean(diff0**2, axis=0) or np.var(X0, axis=0) for MLE.',
        'medium',
        'open_text',
        $options,
        $correct,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "G" => "    diff1 = X1 - mu1",
        "A" => "    n_samples = len(X)",
        "D" => "    mu0 = np.mean(X0, axis=0)",
        "C" => "    X1 = X[y == 1]",
        "H" => "    Sigma = (diff0.T @ diff0 + diff1.T @ diff1) / n_samples",
        "B" => "    X0 = X[y == 0]",
        "F" => "    diff0 = X0 - mu0",
        "E" => "    mu1 = np.mean(X1, axis=0)"
    ]);

    $correct_matches = json_encode([
        "1" => "A", // n_samples = len(X)
        "2" => "B", // X0 = X[y == 0]
        "3" => "C", // X1 = X[y == 1]
        "4" => "D", // mu0 = mean(X0)
        "5" => "E", // mu1 = mean(X1)
        "6" => "F", // diff0 = X0 - mu0
        "7" => "G", // diff1 = X1 - mu1
        "8" => "H", // Sigma = (...)
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Pooled Covariance for Linear Discriminant Analysis version of GDA (Two Classes)',
        <<<EOT
        Arrange the code lines in the correct order to compute the <strong>shared covariance matrix</strong> (MLE) for <strong>LDA</strong> with <strong>two classes</strong> (labels 0 and 1). 
        Assume \\(X\\) has shape \\((n\\_samples, d)\\) and \\(y \\in \\{0,1\\}\\).

        <pre><code>
        import numpy as np

        def lda_shared_covariance(X, y):
        {blank1}
        {blank2}
        {blank3}
        {blank4}
        {blank5}
        {blank6}
        {blank7}
        {blank8}
        </code></pre>

        Hints:
        - \\(X0\\), \\(X1\\): subsets of samples for classes 0 and 1.
        - \\(\\mu_0\\), \\(\\mu_1\\): class mean vectors.
        - MLE estimator: \\(\\Sigma = (\\Delta_0^T\\Delta_0 + \\Delta_1^T\\Delta_1)/n\\_{samples}\\).
        - For an unbiased pooled estimator, divide by \\(n\\_{samples} - K\\) (here \\(K=2\\)).
        </br></br></br>
        EOT,
        'Solution: Compute class means and centered differences per class; sum their scatter and divide by n_samples. This yields the pooled (shared) covariance used in LDA. QDA uses a separate full Σ_k per class; GNB uses per-class diagonal Σ_k.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;
    // Open-text exercise: user must type the Sigma expression.
    // Everything else is visible. The exact correct answer is:
    // "diff0.T @ diff0 + diff1.T @ diff1) / n_samples"

    $options = null;

    $correct = json_encode([
        "correct_option" => [
            "(diff0.T@diff0+diff1.T@diff1)/n_samples"
        ]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Fill the Shared Covariance (LDA)',
        <<<EOT
        Complete the missing <strong>Sigma expression</strong> (open text) for the <strong>shared covariance matrix</strong> (MLE) in LDA with two classes (0 and 1).
        Assume \\(X\\) has shape \\((n_{samples}, d)\\) and \\(y \\in \\{0,1\\}\\).

        <pre><code>
        import numpy as np

        def lda_shared_covariance(X, y):
            n_samples = len(X)
            X0 = X[y == 0]
            X1 = X[y == 1]

            mu0 = np.mean(X0, axis=0)
            mu1 = np.mean(X1, axis=0)

            diff0 = X0 - mu0
            diff1 = X1 - mu1

            # Fill only the expression on the right-hand side (no "Sigma ="):
            Sigma = {blank1}

            return Sigma
        </code></pre>

        <strong>Type exactly the expression only</strong> (without <code>Sigma =</code>), e.g. <code>...</code>.

        <em>Reminder:</em> The pooled MLE is
        \\[
        \\Sigma = \\frac{\\Delta_0^\\top \\Delta_0 + \\Delta_1^\\top \\Delta_1}{n_{samples}},
        \\]
        where \\(\\Delta_k = X_k - \\mu_k\\).
        EOT,
        'Solution: The correct expression is: (diff0.T @ diff0 + diff1.T @ diff1) / n_samples.',
        'medium',
        'open_text',
        $options,
        $correct,
        $exercise_number
    );

    $exercise_number++;
    // --- QDA Exercise 1: drag_and_drop (class-specific covariances) ---

    $options = json_encode([
        "A" => "    X0 = X[y == 0]",
        "B" => "    X1 = X[y == 1]",
        "C" => "    mu0 = np.mean(X0, axis=0)",
        "D" => "    mu1 = np.mean(X1, axis=0)",
        "E" => "    diff0 = X0 - mu0",
        "F" => "    diff1 = X1 - mu1",
        "G" => "    Sigma0 = (diff0.T @ diff0) / len(X0)",
        "H" => "    Sigma1 = (diff1.T @ diff1) / len(X1)"
    ]);

    $correct_matches = json_encode([
        "1" => "A", // X0 = X[y == 0]
        "2" => "B", // X1 = X[y == 1]
        "3" => "C", // mu0 = mean(X0)
        "4" => "D", // mu1 = mean(X1)
        "5" => "E", // diff0 = X0 - mu0
        "6" => "F", // diff1 = X1 - mu1
        "7" => "G", // Sigma0 = ...
        "8" => "H", // Sigma1 = ...
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Class-Specific Covariances for Quadratic Discriminant Analysis (Two Classes)',
        <<<EOT
        Arrange the code lines in the correct order to compute the <strong>class-specific covariance matrices</strong> (MLE) for <strong>QDA</strong> with <strong>two classes</strong> (labels 0 and 1).
        Assume \\(X\\) has shape \\((n\\_samples, d)\\) and \\(y \\in \\{0,1\\}\\).

        <pre><code>
        import numpy as np

        def qda_class_covariances(X, y):
        {blank1}
        {blank2}
        {blank3}
        {blank4}
        {blank5}
        {blank6}
        {blank7}
        {blank8}
        </code></pre>

        Hints:
        - \\(X0\\), \\(X1\\): subsets of samples for classes 0 and 1.
        - \\(\\mu_0\\), \\(\\mu_1\\): class means.
        - MLE estimators:
        \\(\\Sigma_0 = \\Delta_0^\\top \\Delta_0 / n_0\\), \\(\\Sigma_1 = \\Delta_1^\\top \\Delta_1 / n_1\\),
        where \\(\\Delta_k = X_k - \\mu_k\\), \\(n_k = |X_k|\\).
        - For *unbiased* per-class estimates, divide by \\(n_k - 1\\) instead.
        </br></br></br>
        EOT,
        'Solution: Compute per-class means and centered differences, then form Σ₀ and Σ₁ separately as (Δ₀ᵀΔ₀)/n₀ and (Δ₁ᵀΔ₁)/n₁. QDA keeps full Σₖ per class; LDA pools them into one shared Σ.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    // --- QDA Exercise 2: open_text (fill the class-0 covariance expression) ---

    $options = null;

    // Accept a few common equivalent answers (spaces, len vs shape, n0, with/without parentheses)
    $correct = json_encode([
        "correct_option" => [
            "(diff0.T@diff0)/len(X0)",
            "(diff0.T @ diff0)/len(X0)",
            "diff0.T@diff0/len(X0)",
            "diff0.T @ diff0 / len(X0)",
            "(diff0.T@diff0)/n0",
            "(diff0.T @ diff0)/n0",
            "(diff0.T@diff0)/X0.shape[0]",
            "(diff0.T @ diff0)/X0.shape[0]"
        ]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Fill the Class-Specific Covariance (QDA)',
        <<<EOT
        Complete the missing <strong>Sigma0 expression</strong> (open text) for <strong>QDA</strong> (class 0).
        Assume \\(X\\) has shape \\((n_{samples}, d)\\), \\(y \\in \\{0,1\\}\\), and:

        <pre><code>
        import numpy as np

        def qda_class_covariances(X, y):
            X0 = X[y == 0]
            X1 = X[y == 1]

            mu0 = np.mean(X0, axis=0)
            mu1 = np.mean(X1, axis=0)

            diff0 = X0 - mu0
            diff1 = X1 - mu1

            # Fill only the expression on the right-hand side (no "Sigma0 ="):
            Sigma0 = {blank1}

            # (For class 1, analogously: Sigma1 = (diff1.T @ diff1) / len(X1))
        </code></pre>

        <strong>Type exactly the expression only</strong> (without <code>Sigma0 =</code>), e.g. <code>...</code>.

        <em>Reminder (MLE):</em>
        \\[
        \\Sigma_k = \\frac{\\Delta_k^\\top \\Delta_k}{n_k},\\quad
        \\Delta_k = X_k - \\mu_k,\\; n_k = |X_k|.
        \\]
        Use <code>len(X0)</code>, <code>n0</code>, or <code>X0.shape[0]</code> for \\(n_0\\). Spacing does not matter.
        EOT,
        'Solution: A correct expression is (diff0.T @ diff0) / len(X0). For class 1, (diff1.T @ diff1) / len(X1).',
        'medium',
        'open_text',
        $options,
        $correct,
        $exercise_number
    );

    $exercise_number++;

}

function add_gradient_descent_lesson($lesson_number){
$module_three_term = get_term_by( 'slug', 'module-three-en', 'course_topic' );
    if ( $module_three_term ) {
        $category_id = $module_three_term->term_id;
    } else {
        error_log('Term "module-three-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;
}
function exercise_module_three_plugin_activate() {

    add_naive_bayes_lesson(1);
    add_gaussian_discriminant_analysis_lesson(2);
    add_gradient_descent_lesson(3);
    add_GNB_vs_GDA_comparison_lesson(6);

}
?>