<?php

function add_module_three_lesson_2_exercises($lesson_number) {
    $module_three_term = get_term_by( 'slug', 'module-three-en', 'course_topic' );
    if ( $module_three_term ) {
        $category_id = $module_three_term->term_id;
    } else {
        error_log('Term "module-three-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
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
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
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

function exercise_module_three_plugin_activate() {

    add_module_three_lesson_2_exercises(2);

}
?>