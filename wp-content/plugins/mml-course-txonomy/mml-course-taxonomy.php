<?php
/*
Plugin Name: MML Course Taxonomy
Description: Inserts custom course categories and subcategories as taxonomy terms for Mathematics for Machine Learning.
Version: 1.0
Author: Wojciech Dobrowolski
*/
function register_mml_course_taxonomy() {
    $labels = array(
        'name'              => 'Course Topics',
        'singular_name'     => 'Course Topic',
        'search_items'      => 'Search Topics',
        'all_items'         => 'All Topics',
        'parent_item'       => 'Parent Topic',
        'parent_item_colon' => 'Parent Topic:',
        'edit_item'         => 'Edit Topic',
        'update_item'       => 'Update Topic',
        'add_new_item'      => 'Add New Topic',
        'new_item_name'     => 'New Topic Name',
        'menu_name'         => 'Course Topics',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'courses'],
    );

    register_taxonomy( 'course_topic', array( 'post', 'lesson' ), $args );
  // Adjust the post type if needed
}
add_action('init', 'register_mml_course_taxonomy');
function insert_mml_categories() {
    $mml_categories = array(
        'Linear Algebra' => array(
            'Systems of Linear Equations',
            'Matrices',
            'Solving Systems of Linear Equations',
            'Vector Spaces',
            'Linear Independence',
            'Basis and Rank',
            'Linear Mappings',
            'Affine Spaces'
        ),
        'Analytic Geometry' => array(
            'Norms',
            'Inner Products',
            'Lengths and Distances',
            'Angles and Orthogonality',
            'Orthonormal Basis',
            'Orthogonal Complement',
            'Inner Product of Functions',
            'Orthogonal Projections',
            'Rotations',
        ),
        'Matrix Decompositions' => array(
            'Determinant and Trace',
            'Eigenvalues and Eigenvectors',
            'Cholesky Decomposition',
            'Eigendecomposition and Diagonalization',
            'Singular Value Decomposition',
            'Matrix Approximation',
            'Matrix Phylogeny',
        ),
        'Vector Calculus' => array(
            'Differentiation of Univariate Functions',
            'Partial Differentiation and Gradients',
            'Gradients of Vector-Valued Functions',
            'Gradients of Matrices',
            'Useful Identities for Computing Gradients',
            'Backpropagation and Automatic Differentiation',
            'Higher-Order Derivatives',
            'Linearization and Multivariate Taylor Series',
        ),
        'Probability and Distributions' => array(
            'Construction of a Probability Space',
            'Discrete and Continuous Probabilities',
            'Sum Rule, Product Rule, and Bayes\' Theorem',
            'Summary Statistics and Independence',
            'Gaussian Distribution',
            'Conjugacy and the Exponential Family',
            'Change of Variables/Inverse Transform',

        ),
        'Continuous Optimization' => array(
            'Optimization Using Gradient Descent',
            'Constrained Optimization and Lagrange Multipliers',
            'Convex Optimization',
        )
    );

    // Loop through each parent category and its subcategories
    foreach ($mml_categories as $parent_name => $children) {
        // Check if the parent term exists
        $parent_term = term_exists($parent_name, 'course_topic');
        if (!$parent_term) {
            $parent_term = wp_insert_term($parent_name, 'course_topic');
        }
        // Determine the parent term ID
        $parent_id = is_array($parent_term) ? $parent_term['term_id'] : $parent_term;

        // Insert each subcategory with the parent term ID
        foreach ($children as $child_name) {
            if (!term_exists($child_name, 'course_topic')) {
                wp_insert_term($child_name, 'course_topic', array('parent' => $parent_id));
            }
        }
    }
}
// add_action('init', 'insert_mml_categories');




