<?php
/**
 * Plugin Name: Exercises Module One
 * Description: A plugin to demonstrate exercises for module one.
 * Version: 1.0
 * Author: Wojciech Dobrowolski
 * 
 * @package ExercisesModuleOne
 */
function add_module_one_lesson_2_exercises($lesson_number) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );

    $options = json_encode([
        "A" => "It uses weighted inputs and a step activation function.",
        "B" => "It outputs the product of inputs and weights.",
        "C" => "It mimics the sigmoid neuron with backpropagation.",
        "D" => "It is a probabilistic model trained with gradient descent."
    ]);
    
    $correct_matches = json_encode([
        "1" => "A"
    ]);
    
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.1 – McCulloch-Pitts Neuron',
        <<<EOT
    The McCulloch-Pitts neuron is a simple mathematical model of a biological neuron. It is one of the earliest models used to study neural computation.
    
    The output of the neuron is defined as:
    
    $$
    y = 
    \\begin{cases}
    1 & \\text{if } \\sum_{i=1}^{n} w_i x_i \\geq \\theta \\\\
    0 & \\text{otherwise}
    \\end{cases}
    $$
    
    Where:
    - \( x_i \) are the binary inputs (0 or 1),
    - \( w_i \) are the weights (usually fixed),
    - \( \\theta \) is the threshold,
    - \( y \) is the binary output.
    
    Which of the following best describes the McCulloch-Pitts neuron?

    {blank1}

    EOT,
        'Solution: A – The McCulloch-Pitts neuron uses weighted binary inputs and a threshold step function to determine output.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "1",
        "B" => "0"
    ]);

    $correct_matches = json_encode([
        "1" => "A"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.2 – McCulloch-Pitts Neuron Calculation',
        <<<EOT
    Consider a McCulloch-Pitts neuron with three binary inputs:

    - \( x = [1, 0, 1] \)
    - weights \( w = [1, 1, 1] \)
    - threshold \( \\theta = 2 \)

    The neuron computes its output using the rule:

    $$
    y = 
    \\begin{cases}
    1 & \\text{if } \\sum_{i=1}^{n} w_i x_i \\geq \\theta \\\\
    0 & \\text{otherwise}
    \\end{cases}
    $$

    What is the output of the neuron for the given inputs?

    {blank1}

    EOT,
        'Solution: A – The weighted sum is \(1 + 0 + 1 = 2\), which meets the threshold \(\\theta = 2\), so the output is 1.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $options = json_encode([
        "A" => "Dot product, threshold function, binary logic",
        "B" => "Numerical integration, complex numbers, interpolation",
        "C" => "Linear algebra, partial derivatives, exponential function",
        "D" => "Logarithmic function, random variables, maximum likelihood estimation"
    ]);
    
    $correct_matches = json_encode([
        "1" => "A"
    ]);
    
    $exercise_number++;
    
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.3 – Mathematical Concepts in the McCulloch-Pitts Neuron',
        <<<EOT
    The McCulloch-Pitts neuron relies on simple mathematical operations to simulate a biological neuron's decision process.
    
    Which of the following mathematical concepts are directly used in the McCulloch-Pitts neuron model?

    {blank1}

    EOT,
        'Solution: A – The model uses a dot product of weights and inputs, a threshold (step) function, and binary logic for input/output.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    
    $exercise_number = 4;

    $options = json_encode([
        "A" => "def mcculloch_pitts_neuron(inputs, weights, threshold):",
        "B" => "    weighted_sum = sum(x * w for x, w in zip(inputs, weights))",
        "C" => "    return 1 if weighted_sum >= threshold else 0",
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.4 – Implementing a McCulloch-Pitts Neuron (Code Assembly)',
        <<<EOT
    The McCulloch-Pitts neuron returns 1 if the weighted sum of inputs is greater than or equal to a threshold, and 0 otherwise.

    Drag and drop the lines of code below into the correct order to complete the function:
    <pre><code>
    {blank1}
        {blank2}
        {blank3}
    # Example: output = mcculloch_pitts_neuron([1, 0, 1], [1, 1, 1], 2)
    </code></pre>

    EOT,
        'Solution: The function uses the dot product of inputs and weights, compares it to the threshold, and returns a binary output accordingly.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    

}

function add_module_one_lesson_3_exercises($lesson_number) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;

    $options = json_encode([
        "A" => "It adjusts weights based on classification error and uses a step function as activation.",
        "B" => "It performs matrix inversion and probabilistic estimation to infer outputs.",
        "C" => "It is a trainable neuron that uses sigmoid activation and gradient descent.",
        "D" => "It encodes binary logic gates through fixed weights and thresholds."
    ]);

    $correct_matches = json_encode([
        "1" => "A"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Original Perceptron (Rosenblatt)',
        <<<EOT
    The <strong>original perceptron</strong>, proposed by Frank Rosenblatt in 1958, is a simple single-layer binary classifier. It learns by adjusting weights based on errors in prediction.

    The output is computed as:

    $$
    y = 
    \\begin{cases}
    1 & \\text{if } \\sum_{i=1}^{n} w_i x_i + b \\geq 0 \\\\
    0 & \\text{otherwise}
    \\end{cases}
    $$

    During training, weights are updated as:

    $$
    w_i := w_i + \\eta (y_{true} - y_{pred}) x_i
    $$

    Where:
    - \( x_i \): input feature  
    - \( w_i \): weight  
    - \( b \): bias  
    - \( \\eta \): learning rate  
    - \( y_{true} \): actual label  
    - \( y_{pred} \): predicted output  

    Which of the following best describes the behavior and training method of the original perceptron?

    {blank1}

    EOT,
        'Solution: A – The perceptron learns by updating weights when its binary prediction is incorrect, using a step function and simple rule-based weight adjustments.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "w_i := w_i - η × ∇L(w_i), where ∇L is the gradient of loss",
        "B" => "w_i := w_i + η × (y_true - y_pred) × x_i",
        "C" => "w_i := w_i × (1 + η), scaling the weights multiplicatively",
        "D" => "w_i := w_i + η × y_true × x_i, regardless of prediction"
    ]);

    $correct_option = json_encode([
        "correct_option" => "B"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Perceptron Learning Algorithm',
        <<<EOT
    When the original perceptron misclassifies an input, it updates its weights according to a simple rule.

    Which of the following describes the correct update rule for the perceptron?

    <strong>Variables used:</strong><br>
    - \( w_i \): weight associated with input feature \( x_i \)  <br>
    - \( η \): learning rate (a small positive constant)  <br>
    - \( x_i \): input feature value  <br>
    - \( y_{true} \): the correct (true) label (0 or 1)  <br>
    - \( y_{pred} \): the predicted output of the perceptron (0 or 1)  <br>
    - \( ∇L(w_i) \): the gradient of a loss function with respect to weight \( w_i \) (used in gradient descent)

    Note: The perceptron uses a <strong>step function</strong>, not gradient-based optimization.
    EOT,
        'Solution: B – The perceptron updates weights only when the prediction is wrong, using: \( w_i := w_i + η (y_{true} - y_{pred}) x_i \). This rule adjusts weights in the direction of reducing the error.',
        'medium',
        'one_of_many',
        $options,
        $correct_option,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "The Perceptron uses real inputs, a step function, and can be trained using simple rule, while the McCulloch-Pitts neuron cannot be trained.",
        "B" => "The McCulloch-Pitts neuron uses continuous values for inputs, while the Perceptron only uses binary inputs.",
        "C" => "The Perceptron uses a step function for activation, while the McCulloch-Pitts neuron uses a sigmoid activation function.",
        "D" => "Both the Perceptron and McCulloch-Pitts neuron can be trained using backpropagation."
    ]);

    $correct_matches = json_encode([
        "correct_option" => "A"
    ]);
    // <strong>Hint:</strong> 
    // - The Perceptron is trainable, uses binary inputs, and employs a step function.
    // - The McCulloch-Pitts neuron was an early model that does not support training.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Perceptron vs. McCulloch-Pitts Neuron',
        <<<EOT
    The <strong>Perceptron</strong> and the <strong>McCulloch-Pitts neuron</strong> are both simple artificial neuron models, but they differ in key aspects.

    Which of the following best describes the difference between the Perceptron and the McCulloch-Pitts neuron?

    EOT,
        'Solution: A – The Perceptron uses real inputs, a step function, and can be trained using gradient descent, while the McCulloch-Pitts neuron cannot be trained.',
        'medium',
        'one_of_many',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $correct_option = json_encode([
        "correct_options" => ["output" => 0,]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Perceptron Output Calculation',
        <<<EOT
    Given a Perceptron with the following parameters:

    - <strong>Input vector:</strong> \( x = [1, 2] \)
    - <strong>Weights:</strong> \( w = [0.5, -1.0] \)
    - <strong>Bias:</strong> \( b = 0.5 \)
    - <strong>Activation function:</strong> Step function (outputs 1 if the input is greater than or equal to 0, else outputs 0)

    The Perceptron computes its output as:

    $$
    y = 
    \\begin{cases}
    1 & \\text{if } \\sum w_i x_i + b \\geq 0 \\\\
    0 & \\text{otherwise}
    \\end{cases}
    $$

    <strong>Step 1:</strong> Compute the weighted sum:  
    \( z = w_1 \cdot x_1 + w_2 \cdot x_2 + b \)<br>

    <strong>Step 2:</strong> Apply the step function:  
    \( y = 1 \) if \( z \geq 0 \), otherwise \( y = 0 \).<br>

    What is the output of the Perceptron?

    EOT,
        'Solution:  
    Step 1: \( z = 0.5 \cdot 1 + (-1.0) \cdot 2 + 0.5 = 0.5 - 2.0 + 0.5 = -1.0 \)  
    Step 2: Since \( z = -1.0 \) is less than 0, the output is \( y = 0 \).',
        'medium',
        'labeled_inputs',
        null,
        $correct_option,
        $exercise_number
    );

    $exercise_number++;

    $correct_option = json_encode([
        "correct_options" => [
            "w1" => 1.5,
            "w2" => 1.0,
            "b"  => 1.5
        ]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Perceptron Training Step Calculation',
        <<<EOT
    Consider the following Perceptron parameters:

    - <strong>Input vector:</strong> \( x = [1, 2] \)
    - <strong>Weights:</strong> \( w = [0.5, -1.0] \)
    - <strong>Bias:</strong> \( b = 0.5 \)
    - <strong>Learning rate:</strong> \( \\eta = 1.0 \)
    - <strong>True label:</strong> \( y_{true} = 1 \)
    - <strong>Predicted label:</strong> \( y_{pred} = 0 \) (calculated using the step function)

    The Perceptron updates its weights and bias using the following update rule:

    $$
    w_i := w_i + \\eta (y_{true} - y_{pred}) x_i
    $$

    $$
    b := b + \\eta (y_{true} - y_{pred})
    $$

    <strong>Step 1:</strong> Compute the error:  
    \( error = y_{true} - y_{pred} \)<br>

    <strong>Step 2:</strong> Update the weights using the learning rule.<br>

    <strong>Step 3:</strong> Update the bias using the same rule. <br><br>

    What are the new values for the weights and bias after the update?

    EOT,
        'Solution:  
    Step 1: Error = \( y_{true} - y_{pred} = 1 - 0 = 1 \)  
    Step 2:  
    - \( w_1 = w_1 + \eta \times error \times x_1 = 0.5 + 1.0 \times 1 \times 1 = 1.5 \)  
    - \( w_2 = w_2 + \eta \times error \times x_2 = -1.0 + 1.0 \times 1 \times 2 = 1.0 \)  
    Step 3: Bias update:  
    - \( b = b + \eta \times error = 0.5 + 1.0 \times 1 = 1.5 \)  
    Thus, the new weights are \( w = [1.5, 1.0] \) and the new bias is \( b = 1.5 \).',
        'medium',
        'labeled_inputs',
        null,
        $correct_option,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "The Perceptron uses dot products, a step function, and error correction to update weights.",
        "B" => "The Perceptron uses matrix inversion, eigenvalue decomposition, and probability theory.",
        "C" => "The Perceptron uses polynomial fitting, kernel methods, and least squares optimization.",
        "D" => "The Perceptron uses gradient descent and backpropagation for weight updates."
    ]);

    $correct_matches = json_encode([
        "correct_option" => "A"
    ]);

    // <strong>Hint:</strong>
    // - The Perceptron calculates a weighted sum of inputs.
    // - It uses a <strong>step function</strong> to produce the output.
    // - It updates weights using <strong>error correction</strong> when it misclassifies a sample.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Mathematical Concepts in Perceptron',
        <<<EOT
    The <strong>Perceptron</strong> uses several key mathematical concepts in its operation, such as the computation of weighted sums, activation through a step function, and updating weights based on errors.

    Which of the following best describes the mathematical concepts used in the Perceptron?


    EOT,
        'Solution: A – The Perceptron relies on dot products for weighted sums, a step function for output, and error correction to adjust weights when it misclassifies an input.',
        'medium',
        'one_of_many',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "            x_i = X[i]",
        "B" => "            y_pred = predict(x_i, w, b)",
        "C" => "            error = y[i] - y_pred",
        "D" => "            w += eta * error * x_i",
        "E" => "            b += eta * error",
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "D",
        "5" => "E",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Perceptron by Hand in Python',
        <<<EOT
    Complete the Python code for a <strong>Perceptron</strong> that can learn from training data and update its weights using the Perceptron learning rule.

    The Perceptron uses a step function for activation and updates weights based on the error between the predicted and true label.
    <br>
    <pre><code>
    import numpy as np<
    def predict(x, w, b):
        return 1 if np.dot(w, x) + b >= 0 else 0
    def perceptron(X, y, eta, epochs):
        for epoch in range(epochs):
              for i in range(len(X)):
                {blank1}
                {blank2}
                {blank3}
                {blank4}
                {blank5}

        return w, b
    </code></pre>
    EOT,
        'Solution: This code implements the Perceptron, with a `predict` function to make predictions and a `perceptron` function to train the model using error correction.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "optimizer.zero_grad()",
        "B" => "outputs = model(inputs)",
        "C" => "loss = criterion(outputs, labels)",
        "D" => "loss.backward()",
        "E" => "optimizer.step()"
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "D",
        "5" => "E",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Perceptron with PyTorch',
        <<<EOT
    Complete the PyTorch code for implementing a simple Perceptron.

        <pre><code>
    import torch

    model = torch.nn.Sequential(torch.nn.Linear(2, 1), torch.nn.Sigmoid())
    criterion = torch.nn.BCELoss()
    optimizer = torch.optim.SGD(model.parameters(), lr=0.1)
    inputs = torch.tensor([[1.0, 2.0], [2.0, 1.0], [1.0, 1.0], [2.0, 2.0]])
    labels = torch.tensor([[1.0], [1.0], [0.0], [0.0]])
    {blank1}
    {blank2}
    {blank3}
    {blank4}
    {blank5}
    </code></pre>
 
    EOT,
        'Solution: This code implements a Perceptron using PyTorch with one input layer, sigmoid activation, and a binary cross-entropy loss.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

}
function add_module_one_lesson_3_run_exercises($lesson_number) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;

    $mcculoch_pitts_code = <<<PY
    import numpy as np

    def mcculloch_pitts_neuron(inputs, weights, threshold):
        weighted_sum = sum(x * w for x, w in zip(inputs, weights))
        return 1 if weighted_sum >= threshold else 0

    inputs = {{X}}
    weights = {{weights}}
    threshold = {{threshold}}

    res = mcculloch_pitts_neuron(inputs, weights, threshold)
    print("Final result:", res)
    PY;
    add_exercise(
        $lesson_id,
        $category_id,
        'Run McCulloch-Pitts with Parameters',
        '<p>Test result of McCulloch-Pitts neuron depending on input values and threshold:</p>', // content = description HTML
        json_encode([
            'params' => [
            'X' => '[1, 0]',
            'weights' => '[1, 1]',
            'threshold' => '1.5'
        ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), // solution = config metadata
        'easy',
        'code_runner',
        json_encode(['code' => $mcculoch_pitts_code], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        '', // no correct answer
        1
    );

    $perceptron_code = <<<PY
        import numpy as np

        def predict(x, w, b):
            return 1 if np.dot(w, x) + b >= 0 else 0

        def perceptron(X, y, eta, epochs):
            w = np.zeros(X.shape[1])
            b = 0
            for epoch in range(epochs):
                for i in range(len(X)):
                    x_i = X[i]
                    y_pred = predict(x_i, w, b)
                    error = y[i] - y_pred

                    w += eta * error * x_i
                    b += eta * error
                    
            return w.tolist(), b

        X = np.array({{X}})
        y = np.array({{y}})
        eta = {{eta}}
        epochs = {{epochs}}

        w, b = perceptron(X, y, eta, epochs)
        print("Final weights:", w)
        print("Final bias:", b)
        PY;

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1 – Run Perceptron with Parameters',
        '<p>Test how the Perceptron updates weights depending on input values:</p>', // content = description HTML
        json_encode([
            'params' => [
                'X' => '[[0,0],[0,1],[1,0],[1,1]]',
                'y' => '[0,0,0,1]',
                'eta' => '0.1',
                'epochs' => '10'
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), // solution = config metadata
        'easy',
        'code_runner',
        json_encode(['code' => $perceptron_code], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        '', // no correct answer
        2
    );
    $code_geometric = <<<PY
    import numpy as np
    import matplotlib.pyplot as plt
    import io
    import base64

    def predict(x, w, b):
        return 1 if np.dot(w, x) + b >= 0 else 0

    def plot_decision_boundary(x_i, y_i, w, b, w_old, title, update_type=None, show_vectors=True):
        x_min, x_max = -0.5, 1.5
        y_min, y_max = -0.5, 1.5
        xx, yy = np.meshgrid(np.linspace(x_min, x_max, 100),
                            np.linspace(y_min, y_max, 100))
        grid = np.c_[xx.ravel(), yy.ravel()]
        Z = np.array([predict(pt, w, b) for pt in grid])
        Z = Z.reshape(xx.shape)

        plt.contourf(xx, yy, Z, alpha=0.3, cmap='bwr')
        plt.scatter(X[:, 0], X[:, 1], c=y, cmap='bwr', edgecolor='k', label='Samples')
        plt.title(title)
        plt.xlim(x_min, x_max)
        plt.ylim(y_min, y_max)
        plt.grid(True)


        origin = np.array([0, 0])
        scale = 0.5 / np.linalg.norm(w) if np.linalg.norm(w) > 0 else 0
        old_scale = 0.5 / np.linalg.norm(w_old) if np.linalg.norm(w_old) > 0 else 0
        w_scaled = w * scale
        w_old_scaled = w_old * old_scale

        plt.quiver(*origin, *w_scaled, angles='xy', scale_units='xy', scale=1,
                    color='green', label='New weight vector w')
        plt.quiver(*origin, *w_old_scaled, angles='xy', scale_units='xy', scale=1,
                    color='red', label='Old weight vector w')
        plt.quiver(*origin, *x_i, angles='xy', scale_units='xy', scale=2.5,
                    color='gray', alpha=0.5, label=update_type)

        plt.legend(loc='upper left')


    def run_and_plot(X, y, eta, epochs):
        fig, axs = plt.subplots(1, 3, figsize=(12, 4))

        w = np.zeros(X.shape[1])
        b = 0

        # plt.sca(axs[0])
        # plot_decision_boundary(X, y, w, b, 'Epoch 0')
        nbr_of_plots = 0
        for epoch in range(epochs):
            for i in range(len(X)):
                x_i = X[i]
                y_pred = predict(x_i, w, b)
                error = y[i] - y_pred
                w_old = w.copy()
                w += eta * error * x_i
                b += eta * error

                if not np.array_equal(w_old, w) and nbr_of_plots <= 2:
                    if error < 0:
                        update_type = "Negative input sample"
                    else:
                        update_type = "Positive input sample"
                    plt.sca(axs[nbr_of_plots])
                    plot_decision_boundary(x_i, y[i], w, b, w_old, f'Update {nbr_of_plots+1}', update_type)
                    nbr_of_plots += 1

        # plt.sca(axs[2])
        # plot_decision_boundary(X, y, w, b, f'Epoch {epochs}')

        plt.tight_layout()
        buf = io.BytesIO()
        plt.savefig(buf, format='png')
        buf.seek(0)
        img_b64 = base64.b64encode(buf.read()).decode('utf-8')
        print("data:image/png;base64," + img_b64)

    X = np.array([[0,0],[0,1],[1,0],[1,1]])
    y = np.array([0,0,0,1])
    eta = 0.1
    epochs = 10

    run_and_plot(X, y, eta, epochs)
    PY;


    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 3 – Visualize Perceptron Boundary',
        '<p>This exercise visualizes the decision boundary at different stages of training.</p>', // content = description HTML
        json_encode([
            'params' => [
                'X' => '[[0,0],[0,1],[1,0],[1,1]]',
                'y' => '[0,0,0,1]',
                'eta' => '0.1',
                'epochs' => '10'
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), // solution = config metadata
        'easy',
        'geometric_interpretation',
        json_encode(['code' => $code_geometric], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        '', // no correct answer
        3
    );
    

}
function add_module_one_lesson_0_exercises() {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, 5 );
    $exercise_number = 1;

    $options = json_encode([
        "A" => "It computes a series of weighted sums followed by nonlinear activation functions.",
        "B" => "It outputs the average of all inputs to smooth the signal.",
        "C" => "It performs a convolution of the input using a kernel matrix.",
        "D" => "It uses discrete binary logic without any training."
    ]);

    $correct_matches = json_encode([
        "1" => "A"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.5 – Multilayer Perceptron (MLP) Output Formula',
        <<<EOT
    A <strong>Multilayer Perceptron (MLP)** is a type of feedforward neural network composed of one or more hidden layers. Each layer computes:

    $$
    h^{(l)} = f(W^{(l)} h^{(l-1)} + b^{(l)})
    $$

    Where:
    - \( h^{(l)} \) is the output of layer \( l \),
    - \( W^{(l)} \) is the weight matrix,
    - \( b^{(l)} \) is the bias vector,
    - \( f \) is a nonlinear activation function (e.g., ReLU, sigmoid),
    - \( h^{(0)} = x \), the input vector.

    Which of the following best describes how a Multilayer Perceptron computes its output?

    {blank1}

    EOT,
        'Solution: A – An MLP performs matrix multiplication and bias addition at each layer, followed by a nonlinear activation.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;
    $options = json_encode([
        "A" => "McCulloch-Pitts neurons are trainable, while MLPs are not.",
        "B" => "Both models use only binary logic and no activation functions.",
        "C" => "McCulloch-Pitts is a simplified, non-trainable model; MLPs are trainable networks with nonlinear activations.",
        "D" => "MLPs are limited to binary inputs and step functions like McCulloch-Pitts.",
        "E" => "There is no mathematical difference between McCulloch-Pitts and MLPs."
    ]);
    
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.6 – McCulloch-Pitts vs. MLP',
        'Which of the following best compares the McCulloch-Pitts neuron and a Multilayer Perceptron (MLP)?',
        'Solution: C – McCulloch-Pitts is a static logic model, while MLPs are trainable neural networks using differentiable activation functions like ReLU or sigmoid.',
        'medium',
        'multiple_choice',
        $options,
        '{"correct_options": ["C"]}',
        $exercise_number
    );
    $exercise_number++;
    $correct = json_encode([
        "correct_option" => [6]
    ]);
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise 1.7 – MLP Forward Pass Calculation',
        <<<EOT
    Consider a simple MLP with one hidden layer. It uses the ReLU activation function \( f(x) = \max(0, x) \).

    <strong>Input vector:</strong> \( x = [1, 2] \)  
    <strong>Hidden layer weights:</strong>  
    \( W^{(1)} = \begin{bmatrix} 1 & -1 \\\\ 0 & 1 \end{bmatrix} \),  
    <strong>Hidden biases:</strong> \( b^{(1)} = [0, 1] \)

    <strong>Output layer weights:</strong>  
    \( W^{(2)} = [1, 2] \),  
    <strong>Output bias:</strong> \( b^{(2)} = 0 \)

    <strong>Step 1:</strong> Compute the hidden layer output:  
    \( h = \text{ReLU}(W^{(1)} \cdot x + b^{(1)}) \)

    <strong>Step 2:</strong> Compute the final output:  
    \( y = W^{(2)} \cdot h + b^{(2)} \)

    What is the final output \( y \) of the network?
    EOT,
        'Solution: Step-by-step:
    1. \( W^{(1)} \cdot x = [1*1 + (-1)*2, 0*1 + 1*2] = [-1, 2] \)
    2. Add bias: \( [-1, 2] + [0, 1] = [-1, 3] \)
    3. Apply ReLU: \( \max(0, -1) = 0 \), \( \max(0, 3) = 3 \) → \( h = [0, 3] \)
    4. Output: \( y = [1, 2] \cdot [0, 3] + 0 = 0*1 + 3*2 = 6 \)',
        'medium',
        'open_text',
        null,
        $correct,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "Matrix multiplication, vector addition, ReLU activation",
        "B" => "Boolean logic, integer division, histogram equalization",
        "C" => "Polynomial interpolation, modular arithmetic, sorting",
        "D" => "Complex numbers, Fourier transform, matrix inversion"
    ]);

    $correct_option = json_encode([
        "correct_option" => "A"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Mathematical Concepts in MLP Forward Pass',
        <<<EOT
    In the previous exercise, you computed the output of a simple Multilayer Perceptron (MLP) using specific weights, biases, and the ReLU activation function.

    Which of the following sets of mathematical concepts are directly involved in this computation?
    EOT,
        'Solution: A – Forward propagation in an MLP includes matrix multiplication (for weighted inputs), vector addition (bias), and nonlinear activation (ReLU).',
        'medium',
        'one_of_many',
        $options,
        $correct_option,
        $exercise_number
    );

    $exercise_number++;

$options = json_encode([
    "C" => "    return 1 / (1 + np.exp(-x))",
    "E" => "    h = sigmoid(np.dot(W1, x) + b1)",
    "F" => "    y = sigmoid(np.dot(W2, h) + b2)",
    "G" => "    return y"
]);

$correct_matches = json_encode([
    "1" => "A",
    "2" => "B",
    "3" => "C",
    "4" => "D",
]);

$result = add_exercise(
    $lesson_id,
    $category_id,
    'Exercise ' . $exercise_number . ' – Implementing Classic MLP with Sigmoid Activation',
    <<<EOT
    Complete the Python code for a simple Multilayer Perceptron (MLP) as originally used in neural networks. It uses the sigmoid activation function in both the hidden and output layers.
    <pre><code>
    import numpy as np
    def sigmoid(x):
    {blank1}
    def mlp_forward(x, W1, b1, W2, b2):
    {blank2}
    {blank3}
    {blank4}
    </code></pre>
    
    EOT,
        'Solution: This MLP uses sigmoid activation in both layers, as was common in early neural network models prior to the adoption of ReLU.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

$options = json_encode([
    "A" => "    nn.Linear(2, 3),",         // input to hidden
    "B" => "    nn.Sigmoid(),",            // hidden activation
    "C" => "    nn.Linear(3, 1),",         // hidden to output
    "D" => "    nn.Sigmoid()",             // output activation
    "E" => ")"
]);

$correct_matches = json_encode([
    "1" => "A",
    "2" => "B",
    "3" => "C",
    "4" => "D",
    "5" => "E",
]);

$result = add_exercise(
    $lesson_id,
    $category_id,
    'Exercise ' . $exercise_number . ' – Define a Classic MLP Using PyTorch Sequential',
    <<<EOT
    Use PyTorch’s `nn.Sequential` to define a simple MLP with one hidden layer and sigmoid activation functions. Drag the code blocks into the correct order.
    <pre><code>
    import torch
    import torch.nn as nn
    mlp = nn.Sequential(
    

    {blank1}
    {blank2}
    {blank3}
    {blank4}
    {blank5}
    </code></pre>
    
    EOT,
        'Solution: `nn.Sequential` allows stacking layers like `Linear` and `Sigmoid` in order. This defines a fully connected 2→3→1 architecture using sigmoid activations.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

}

function add_module_one_lesson_4_exercises($lesson_number = 4) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;

    $options = json_encode([
        "A" => "It uses binary inputs and a fixed step function like McCulloch-Pitts.",
        "B" => "It multiplies inputs by weights, adds bias, and passes the result through a nonlinear activation function.",
        "C" => "It works without any weights or bias and returns a constant value.",
        "D" => "It requires training with only logical rules, not gradient-based methods."
    ]);

    $correct_matches = json_encode([
        "1" => "B"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Modern Artificial Neuron',
        <<<EOT
    The modern artificial neuron is the basic building block of current neural networks such as MLPs, CNNs, and transformers.

    Which of the following best describes a modern artificial neuron used in today's neural networks?
    ```
    {blank1}
    ```
    EOT,
        'Solution: B – A modern neuron computes a weighted sum of its inputs, adds a bias term, and passes the result through a nonlinear activation function like ReLU or sigmoid.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "A modern neuron uses continuous activation functions, while the Perceptron uses a step function for activation.",
        "B" => "A Perceptron is a full network, while a modern neuron is just a single computation unit.",
        "C" => "Modern neurons are typically trained using backpropagation, while the Perceptron only uses the Perceptron learning rule.",
        "D" => "A Perceptron can perform more complex tasks than a modern neuron."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A","C"]
    ]);
    // <strong>Hint:</strong>
    // - The Perceptron uses a step function for binary classification.
    // - Modern neurons are typically trained using backpropagation for deep learning tasks.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Comparing a Modern Neuron and a Perceptron',
        <<<EOT
    Which of the following best describes the difference between a modern artificial neuron and a Perceptron?

    EOT,
        'Solution: A, C – Modern neurons use continuous activations like ReLU or sigmoid, while the Perceptron uses a step function to produce binary output.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "Both use fixed step functions and cannot be trained using gradient descent.",
        "B" => "McCulloch-Pitts neurons use continuous activations, while modern neurons use binary thresholds.",
        "C" => "Modern neurons are differentiable and trainable; McCulloch-Pitts neurons are static logic-based units.",
        "D" => "There is no functional difference between McCulloch-Pitts and modern artificial neurons."
    ]);

    $correct_matches = json_encode([
        "1" => "C"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Modern Neuron vs. McCulloch-Pitts Neuron',
        <<<EOT
    Which of the following best explains the difference between a modern artificial neuron and the McCulloch-Pitts neuron?
    ```
    {blank1}
    ```
    EOT,
        'Solution: C – The McCulloch-Pitts model is a static, binary logic function with no trainable parameters. In contrast, modern neurons support differentiable activations, weights, biases, and are trained via gradient descent.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $correct_option = json_encode([
        "correct_options" => ["output" => 0.3775]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Modern Neuron Output Calculation',
        <<<EOT
    A modern artificial neuron receives two inputs:

    - \( x = [1.0, 2.0] \)  
    - weights \( w = [0.5, -1.0] \)  
    - bias \( b = 1.0 \)

    The neuron performs the following operations:

    1. Compute the weighted sum:  
    \( z = w_1 \cdot x_1 + w_2 \cdot x_2 + b \)

    2. Apply the sigmoid activation function:  
    \( \sigma(z) = \\frac{1}{1 + e^{-z}} \)

    What is the final output of the neuron? (Round to 4 decimal places)

    EOT,
        'Solution:  
    Step 1: \( z = 1.0 \cdot 0.5 + 2.0 \cdot (-1.0) + 1.0 = 0.5 - 2.0 + 1.0 = -0.5 \)  
    Step 2: \( \sigma(-0.5) = 1 / (1 + e^{0.5}) \approx 0.3775 \)',
        'medium',
        'labeled_inputs',
        null,
        $correct_option,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "Dot product, bias addition, sigmoid activation",
        "B" => "Polynomial fitting, sorting, histogram analysis",
        "C" => "Bitwise logic, discrete mathematics, binary trees",
        "D" => "Gradient descent, eigenvalue decomposition, FFT"
    ]);

    $correct_option = json_encode([
        "correct_options" => ["A"]
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Mathematical Concepts in a Modern Neuron',
        <<<EOT
    In the previous exercise, you computed the output of a modern artificial neuron using real-valued inputs, weights, a bias term, and the sigmoid activation function.

    Which of the following groups of mathematical concepts are directly involved in this computation?
    EOT,
        'Solution: A – A modern neuron uses a dot product (weighted sum), adds a bias, and applies a nonlinear function like sigmoid.',
        'easy',
        'multiple_choice',
        $options,
        $correct_option,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "    return 1 / (1 + np.exp(-z))",
        "B" => "    z = np.dot(w, x) + b",
        "C" => "    return sigmoid(z)"
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Modern Neuron by Hand in Python',
        <<<EOT
    Complete the Python code to implement a <strong>modern neuron</strong>. The neuron computes the weighted sum of inputs, adds a bias, and applies the <strong>sigmoid activation</strong> to get the output.
    <pre><code>
    import numpy as np
    def sigmoid(z):
    {blank1}

    def modern_neuron(x, w, b):
    {blank2}
    {blank3}
    </code></pre>
    EOT,
        'Solution: This code implements a modern neuron, including the sigmoid activation function and the weighted sum of inputs.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "import torch",
        "B" => "model = torch.nn.Sequential(torch.nn.Linear(2, 1), torch.nn.Sigmoid())",
        "C" => "inputs = torch.tensor([[1.0, 2.0]])",
        "D" => "output = model(inputs)",
        "E" => "loss = torch.nn.BCELoss()"
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "D",
        "5" => "E"
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Modern Neuron with PyTorch',
        <<<EOT
    Complete the Python code to implement a <strong>modern neuron</strong> using <strong>PyTorch</strong>. The neuron computes the weighted sum of inputs, adds a bias, and applies the sigmoid activation.
    <pre><code>
    {blank1}
    {blank2}
    {blank3}
    {blank4}
    {blank5}
    </code></pre>
    EOT,
        'Solution: This code defines a simple modern neuron using PyTorch with `Linear` for the weighted sum and `Sigmoid` for the activation.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

    }

function add_module_one_lesson_5_exercises($lesson_number = 5) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );

    $exercise_number=1;

    $options = json_encode([
        "A" => "Backpropagation computes the gradients of the loss function with respect to the weights and updates the weights using the gradient descent optimization.",
        "B" => "Backpropagation adjusts the learning rate for each neuron based on the output values.",
        "C" => "Backpropagation is only used in convolutional neural networks and not in MLPs.",
        "D" => "Backpropagation calculates the output of a neural network without using gradients."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);
    // <strong>Hint:</strong> 
    // - Backpropagation is used during the <strong>training phase</strong> to optimize weights based on the error.
    // - It uses <strong>gradient descent</strong> to minimize the loss function.

    // {blank1}
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Concept of Backpropagation',
        <<<EOT
    Which of the following best describes the idea behind <strong>backpropagation</strong> in neural networks?


    EOT,
        'Solution: A – Backpropagation computes the gradients of the loss function with respect to the weights and updates the weights using gradient descent optimization.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "Perceptron learning is used for binary classification with a step function, while backpropagation is used for multi-layer networks and uses gradient descent for optimization.",
        "B" => "Perceptron learning uses backpropagation to compute gradients, whereas backpropagation requires the Perceptron learning rule for weight updates.",
        "C" => "Perceptron learning updates weights using a continuous error signal, while backpropagation uses a discrete error signal.",
        "D" => "Perceptron learning is only used in supervised learning, while backpropagation can be used in unsupervised learning as well."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);

    // <strong>Hint:</strong> 
    // - Perceptron learning is simpler and typically used for single-layer networks with binary classification tasks.
    // - Backpropagation is more complex and is used in multi-layer neural networks for tasks involving optimization using gradients.

    // {blank1}
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Key Differences Between Perceptron Learning and Backpropagation',
        <<<EOT
    Which of the following best describes the key differences between <strong>Perceptron learning</strong> and <strong>backpropagation</strong>?


    EOT,
        'Solution: A – Perceptron learning is used for binary classification with a step function, while backpropagation is used for multi-layer networks and uses gradient descent for optimization.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "Perceptron learning is used for binary classification with a step function, while backpropagation is used for multi-layer networks and uses gradient descent for optimization.",
        "B" => "Perceptron learning uses backpropagation to compute gradients, whereas backpropagation requires the Perceptron learning rule for weight updates.",
        "C" => "Perceptron learning updates weights using a continuous error signal, while backpropagation uses a discrete error signal.",
        "D" => "Perceptron learning is only used in supervised learning, while backpropagation can be used in unsupervised learning as well."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);

    // <strong>Hint:</strong> 
    // - Perceptron learning is simpler and typically used for single-layer networks with binary classification tasks.
    // - Backpropagation is more complex and is used in multi-layer neural networks for tasks involving optimization using gradients.

    // {blank1}
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Key Differences Between Perceptron Learning and Backpropagation',
        <<<EOT
    Which of the following best describes the key differences between <strong>Perceptron learning</strong> and <strong>backpropagation</strong>?
    EOT,
        'Solution: A – Perceptron learning is used for binary classification with a step function, while backpropagation is used for multi-layer networks and uses gradient descent for optimization.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "The Perceptron cannot solve XOR because it is not linearly separable, but an MLP with backpropagation can learn the non-linear decision boundary.",
        "B" => "Both the Perceptron and MLP can solve XOR because they both use linear decision boundaries.",
        "C" => "The Perceptron can solve XOR using backpropagation, while an MLP cannot.",
        "D" => "Backpropagation solves XOR by adjusting weights with a step function, in a multilayer network allowing for a non-linear solution."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);

    // <strong>Hint:</strong> 
    // - The Perceptron can only learn linearly separable problems, while an MLP with hidden layers can learn non-linear decision boundaries through backpropagation.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – How Backpropagation Solves XOR',
        <<<EOT
    The XOR problem is a binary classification problem where the output is 1 if the inputs are different and 0 if they are the same.

    Which of the following best describes how <strong>backpropagation</strong> and an <strong>MLP</strong> can solve the XOR problem?
    
    EOT,
        'Solution: A – The Perceptron cannot solve XOR because it is not linearly separable, but an MLP with backpropagation can learn the non-linear decision boundary.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $correct_option = json_encode([
        "correct_options" => [
            "-0.0971", "-0.3475", "0.0488",
            "-0.3450", "0.3432"
        ]
    ]);

    $exercise_content = '
        <p>In this exercise, you will compute the backpropagation for training a simple neural network (MLP) to solve the XOR problem. The network has one hidden layer with two neurons, using the sigmoid activation function.</p>
        
        <h3>XOR Dataset:</h3>
        <table>
            <tr><th>Input A</th><th>Input B</th><th>XOR Output</th></tr>
            <tr><td>0</td><td>0</td><td>0</td></tr>
            <tr><td>0</td><td>1</td><td>1</td></tr>
            <tr><td>1</td><td>0</td><td>1</td></tr>
            <tr><td>1</td><td>1</td><td>0</td></tr>
        </table>
        
        <p><strong>Initial weights and biases:</strong></p>
        <ul>
            <li>Layer 1 (input to hidden):
                <ul>
                    <li>\\( w_1 = 0.5 \\), \\( w_2 = -0.5 \\), \\( b_1 = 0.0 \\)</li>
                </ul>
            </li>
            <li>Layer 2 (hidden to output):
                <ul>
                    <li>\\( w_3 = 0.5 \\), \\( b_2 = 0.0 \\)</li>
                </ul>
            </li>
        </ul>

        <h3>Step 1: Forward Pass (Compute outputs for each input)</h3>
        <ol>
            <li>Compute the weighted sum for the hidden layer:  
                \\( z_1 = w_1 \\cdot x_1 + w_2 \\cdot x_2 + b_1 \\)
            </li>
            <li>Apply the sigmoid activation function to get the hidden layer output:  
                \\( a_1 = \\sigma(z_1) \\)
            </li>
            <li>Compute the weighted sum for the output layer:  
                \\( z_2 = w_3 \\cdot a_1 + b_2 \\)
            </li>
            <li>Apply the sigmoid activation function to get the final output:  
                \\( y_{pred} = \\sigma(z_2) \\)
            </li>
        </ol>

        <h3>Step 2: Compute Error and Backpropagate</h3>
        <ol>
            <li>Compute the <strong>error</strong>:  
                \\( error = y_{true} - y_{pred} \\)
            </li>
            <li>Compute the <strong>gradient</strong> for the output layer:  
                \\( \\frac{\\partial E}{\\partial w_3} = error \\cdot a_1 \\cdot (1 - a_1) \\)
            </li>
            <li>Compute the <strong>gradient</strong> for the hidden layer:  
                \\( \\frac{\\partial E}{\\partial w_1} = error \\cdot w_3 \\cdot a_1 \\cdot (1 - a_1) \\cdot x_1 \\)
            </li>
            <li>Update the weights using the learning rate \\( \\eta = 0.1 \\).</li>
        </ol>

        <p>What are the updated weights and biases after one training iteration (using the above formulas) for each layer?</p>
    ';

    $exercise_solution = 'Solution:<br>
    After the forward pass and backpropagation step, the updated weights and biases for each layer are:<br>
    - Layer 1 weights update: \\( w_1 = -0.0971, w_2 = -0.3475, b_1 = 0.0488 \\)<br>
    - Layer 2 weights update: \\( w_3 = -0.3450, b_2 = 0.3432 \\)';

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Compute Backpropagation for XOR',
        $exercise_content,
        $exercise_solution,
        'medium',
        'open_text',
        null,
        $correct_option,
        $exercise_number
    );

    
    $exercise_number++;

    $correct_option = json_encode([
        "correct_options" => [0.0]
    ]);

    $exercise_content = '
        <p>The <strong>ReLU (Rectified Linear Unit)</strong> activation function is defined as:</p>

        <p>\\[
        \text{ReLU}(x) = 
        \begin{cases} 
        x & \text{if } x > 0 \\\\
        0 & \text{otherwise}
        \end{cases}
        \\]</p>

        <p><strong>Given the following parameters:</strong></p>
        <ul>
            <li><strong>Input vector:</strong> \\( x = [1.0, -2.0] \\)</li>
            <li><strong>Weights:</strong> \\( w = [0.5, 0.5] \\)</li>
            <li><strong>Bias:</strong> \\( b = 0.5 \\)</li>
        </ul>

        <h3>Step 1: Compute the weighted sum:</h3>
        <p>\\( z = w_1 \\cdot x_1 + w_2 \\cdot x_2 + b \\)</p>

        <h3>Step 2: Apply the ReLU activation:</h3>
        <p>\\( y = \text{ReLU}(z) \\)</p>

        <p>What is the output \\( y \\) of the neuron after applying the ReLU activation?</p>
    ';

    $exercise_solution = 'Solution:<br>
    Step 1: \\( z = 0.5 \\cdot 1.0 + 0.5 \\cdot (-2.0) + 0.5 = 0.5 - 1.0 + 0.5 = 0.0 \\)<br>
    Step 2: \\( y = \text{ReLU}(0.0) = 0 \\) because \\( z \\leq 0 \\).';

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – ReLU Activation Function',
        $exercise_content,
        $exercise_solution,
        'medium',
        'open_text',
        null,
        $correct_option,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "ReLU activation is used in the forward pass to compute outputs and in the backward pass to compute gradients, where it is non-zero for positive inputs.",
        "B" => "ReLU activation is used only in the forward pass to compute outputs, and its gradient is always 1, regardless of the input.",
        "C" => "ReLU activation is used to limit the output values, but does not affect the backpropagation gradients.",
        "D" => "ReLU activation in the forward pass sets negative values to zero, and in backpropagation, it zeros the gradients for negative inputs."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A", "D"]
    ]);
    
    // <strong>Hint:</strong> 
    // - ReLU sets all negative values to 0 in the forward pass and computes gradients accordingly in the backward pass.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – ReLU Concepts in Backpropagation and ReLU Activation',
        <<<EOT
    Which of the following best describe the concepts related to the <strong>ReLU activation function</strong> in both the <strong>forward pass</strong> and <strong>backpropagation</strong>?


    EOT,
        'Solution:  
        - A – ReLU is used in the <strong>forward pass</strong> to compute outputs, and in <strong>backpropagation</strong>, it computes gradients, being non-zero for positive inputs.  
        - D – ReLU zeroes out negative values in the <strong>forward pass</strong> and also zeroes out gradients for negative inputs in <strong>backpropagation</strong>.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "ReLU is computationally efficient because it is simple to compute and avoids the vanishing gradient problem.",
        "B" => "ReLU activation is differentiable everywhere, making it more suitable for optimization algorithms.",
        "C" => "ReLU can produce negative output values, which helps neural networks learn complex patterns.",
        "D" => "ReLU is invariant to the scale of inputs, meaning it handles large or small values equally well."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);

    //     <strong>Hint:</strong> 
    // - ReLU's ability to handle large inputs and prevent gradient issues is key to its popularity in deep learning.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Why is ReLU Popular?',
        <<<EOT
    Which of the following best explains why <strong>ReLU</strong> (Rectified Linear Unit) is widely used in neural networks?

    EOT,
        'Solution: A – ReLU is computationally efficient, being simple to compute, and avoids the vanishing gradient problem due to its non-saturating nature.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );

    $exercise_number++;

    $options = json_encode([
        "A" => "    return 1 / (1 + np.exp(-z))",
        "B" => "    z1 = np.dot(w1, x) + b1",
        "C" => "    a1 = sigmoid(z1)",
        "D" => "    z2 = np.dot(w2, a1) + b2",
        "E" => "    a2 = sigmoid(z2)",
        "F" => "    return a2, z1, a1, z2",
        "G" => "    error = y_true - a2",
        "H" => "    dz2 = error * a2 * (1 - a2)",
        "I" => "    dw2 = np.dot(dz2, a1.T)",
        "J" => "    dz1 = np.dot(w2.T, dz2) * a1 * (1 - a1)",
        "K" => "    dw1 = np.dot(dz1, x.T)",
        "L" => "    return dw1, dw2, dz1, dz2"
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "D",
        "5" => "E",
        "6" => "F",
        "7" => "G",
        "8" => "H",
        "9" => "I",
        "10" => "J",
        "11" => "K",
        "12" => "L",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Backpropagation by Hand',
        <<<EOT
    Complete the code for <strong>backpropagation</strong>. This code computes the forward pass, calculates the gradients, and updates the weights during backpropagation.
    <pre><code>
    import numpy as np
    def sigmoid(z):
        {blank1}
    def forward_pass(x, w1, b1, w2, b2):
        {blank2}
        {blank3}
        {blank4}
        {blank5}
        {blank6}
    def backward_pass(x, y_true, a2, z1, a1, w2):
        {blank7}
        {blank8}
        {blank9}
        {blank10}
        {blank11}
        {blank12}
    </code></pre>

    EOT,
        'Solution: This code defines the forward pass and backpropagation steps using the sigmoid activation function and calculates gradients for weight updates.',
        'medium',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "outputs = model(inputs)",
        "B" => "y_true = torch.tensor([[1.0]])",
        "C" => "loss.backward()",
        "D" => "optimizer = torch.optim.SGD(model.parameters(), lr=0.1)",
        "E" => "optimizer.step()"
    ]);

    $correct_matches = json_encode([
        "1" => "A",
        "2" => "B",
        "3" => "C",
        "4" => "D",
        "5" => "E",
    ]);

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Implementing Backpropagation with PyTorch',
        <<<EOT
    Complete the code to implement <strong>backpropagation</strong> for a simple neural network in <strong>PyTorch</strong>. The network has one hidden layer and uses <strong>sigmoid</strong> activation.
    <pre><code>
    import torch
    model = torch.nn.Sequential(torch.nn.Linear(2, 2), torch.nn.Sigmoid(), torch.nn.Linear(2, 1), torch.nn.Sigmoid())
    inputs = torch.tensor([[1.0, 2.0]], requires_grad=True)
    {blank1}
    loss_fn = torch.nn.BCELoss()
    {blank2}
    loss = loss_fn(outputs, y_true)
    {blank3}
    {blank4}
    {blank5}
    </code></pre>
    EOT,
        'Solution: This code defines a simple neural network using PyTorch with backpropagation using `loss.backward()` and the optimizer to update weights.',
        'easy',
        'drag_and_drop',
        $options,
        $correct_matches,
        $exercise_number
    );

}
function add_module_one_lesson_6_exercises($lesson_number) {
    $module_one_term = get_term_by( 'slug', 'module-one', 'course_topic' );
    if ( $module_one_term ) {
        $category_id = $module_one_term->term_id;
    } else {
        error_log('Term "module-one" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    $exercise_number = 1;

    $options = json_encode([
        "A" => "Both operate with binary inputs and binary outputs.",
        "B" => "Both use backpropagation to adjust weights during training.",
        "C" => "Both use a threshold-based activation function to decide whether to fire or not.",
        "D" => "Both are capable of solving complex non-linear problems like XOR."
    ]);

    $correct_matches = json_encode([
        "correct_option" => "C"
    ]);
    // <strong>Hint:** Both models share some fundamental ideas, but one of them can learn, while the other cannot.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Common Features between McCulloch-Pitts and Perceptron',
        <<<EOT
    Which of the following are common features between the <strong>McCulloch-Pitts neuron</strong> and the <strong>Perceptron</strong>?
    EOT,
        'Solution:  
        - A – Both use a threshold-based activation function, deciding whether the neuron will fire or not based on the weighted sum of inputs.  
        - C – Both models work with binary inputs and produce binary outputs.',
        'medium',
        'one_of_many',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "Both use weighted inputs and an activation function to compute the output.",
        "B" => "Both can handle binary inputs and binary outputs.",
        "C" => "Both have a trainable bias term.",
        "D" => "Both use the sigmoid activation function for training."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A", "C"]
    ]);
    // <strong>Hint:** While the Perceptron is simpler, modern neurons build on similar principles.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Common Features between Perceptron and Modern Neuron',
        <<<EOT
    Which of the following are common features between the <strong>Perceptron</strong> and the <strong>modern artificial neuron</strong>?
    EOT,
        'Solution:  
        - A – Both the <strong>Perceptron</strong> and the <strong>modern neuron</strong> use <strong>weighted inputs</strong> and an <strong>activation function</strong> to compute the output.  
        - C – Both have a <strong>trainable bias</strong> term, which helps shift the decision boundary and improve learning.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "The Perceptron introduced the ability to train and adjust weights through the Perceptron learning rule.",
        "B" => "The Perceptron uses real-valued inputs, unlike the McCulloch-Pitts neuron which uses only binary inputs.",
        "C" => "The Perceptron has a non-linear activation function, whereas the McCulloch-Pitts neuron only uses a step function.",
        "D" => "The Perceptron uses multiple layers to solve more complex problems, while the McCulloch-Pitts neuron is a single-layer model."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A"]
    ]);
    // <strong>Hint:** The Perceptron can <strong>learn</strong> and improve its performance over time.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Biggest Improvement of Perceptron Compared to McCulloch-Pitts',
        <<<EOT
    What is the biggest improvement in the <strong>Perceptron</strong> compared to the <strong>McCulloch-Pitts neuron</strong>?
    EOT,
        'Solution:  
        - A – The <strong>Perceptron</strong> introduced the ability to <strong>train</strong> and <strong>adjust weights</strong> through the Perceptron learning rule, allowing it to learn from its mistakes and solve more complex problems.  
        - This was a major advancement compared to the <strong>McCulloch-Pitts neuron</strong>, which did not have any learning capability.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;

    $options = json_encode([
        "A" => "The Perceptron is a single-layer model, while the modern neuron can be part of multi-layer networks.",
        "B" => "The Perceptron uses binary inputs, while modern neurons can handle real-valued inputs.",
        "C" => "The Perceptron uses a step function, while modern neurons typically use non-linear activation functions like ReLU or sigmoid.",
        "D" => "The Perceptron does not use backpropagation, while modern neurons rely on backpropagation for training."
    ]);

    $correct_matches = json_encode([
        "correct_options" => ["A", "C", "D"]
    ]);
    // <strong>Hint:</strong> Think about architecture, inputs, activation functions, and training methods.
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Biggest Difference Between Perceptron and Modern Neuron',
        <<<EOT
    What is the biggest difference between the <strong>Perceptron</strong> and the <strong>modern artificial neuron</strong>?
    EOT,
        'Solution:  
        - A – The <strong>Perceptron</strong> is a <strong>single-layer</strong> model, while the modern neuron can be part of <strong>multi-layer networks</strong> (e.g., deep learning).  
        - B – Both <strong>Perceptron</strong> and modern neurons use <strong>real-valued inputs</strong>.  
        - C – The <strong>Perceptron</strong> uses a <strong>step function</strong>, while modern neurons typically use <strong>non-linear activation functions</strong> like ReLU or sigmoid.  
        - D – The <strong>Perceptron</strong> does not use <strong>backpropagation</strong> for training, while modern neurons rely on <strong>backpropagation</strong> to adjust weights and improve learning.',
        'medium',
        'multiple_choice',
        $options,
        $correct_matches,
        $exercise_number
    );


}
function exercise_module_one_plugin_activate() {

    add_module_one_lesson_2_exercises(2);
    add_module_one_lesson_3_exercises(3);
    add_module_one_lesson_3_run_exercises(4);    
    add_module_one_lesson_4_exercises(5);
    add_module_one_lesson_5_exercises(6);
    add_module_one_lesson_6_exercises(7);
}
register_activation_hook( __FILE__, 'exercise_module_one_plugin_activate' );
?>