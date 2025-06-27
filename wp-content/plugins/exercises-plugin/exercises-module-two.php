<?php
/**
 * Plugin Name: Exercises Module Two
 * Description: A plugin to demonstrate exercises for module two.
 * Version: 1.0
 * Author: Wojciech Dobrowolski
 * 
 * @package ExercisesModuleTwo
 */
function add_probability_distributions_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );

    $exercise_number = 1;
    $options = json_encode([
        
        "A" => "A distribution over multiple categories, normalized to sum to one.",
        "B" => "A distribution over two outcomes: success (1) with probability p, and failure (0) with probability 1-p.",
        "C" => "A continuous distribution used for modeling the time between events.",
        "D" => "A distribution where each value is equally likely across a given range."
    ], JSON_UNESCAPED_UNICODE);

    $correct_matches = json_encode([
        "correct_option" => "B"
    ]);
    // ---------------------------------------------------------------------------
    $result = add_exercise(
        $lesson_id, // your Lesson 1 ID
        $category_id,
        'Exercise ' . $exercise_number . ' – What is the Bernoulli Distribution?',
        <<<EOT
        The <span class="tooltip"><strong>Bernoulli distribution</strong><span class="tooltip-text">Just the simplest of all distributions, a binary oracle: 1 or 0, success or failure, cat or not-a-cat. The great coin flip of statistical destiny.</span></span> models a binary outcome: it returns 1 ("success") with probability <code>p</code> and 0 ("failure") with probability <code>1 - p</code>.<br>
        <br>
        <strong>Example:</strong><br> Tossing a biased coin that lands heads with probability <code>p = 0.7</code>. Let "heads" be encoded as 1 and "tails" as 0.<br>
        <br>
        <strong>Question:</strong><br> What is the correct definition of the Bernoulli distribution?<br>
        EOT,
        'Solution: A – A distribution over two outcomes: success (1) with probability p, and failure (0) with probability 1-p.',
        'easy',
        'one_of_many',
        $options,
        $correct_matches,
        $exercise_number
    );
    $exercise_number++;
    // ---------------------------------------------------------------------------
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Introduction to PMF with Bernoulli',
        <<<EOT
    Suppose you are modeling whether a user clicks a button on a website. We define the variable \( X \) such that:<br>
    - \( X = 1 \) if the user clicks,<br>
    - \( X = 0 \) if the user does not click.<br>
        <br>
    If the probability of a click is \( p = 0.2 \), we need a function that gives the probability of observing either outcome. This is the role of the <span class="tooltip"><strong>Probability Mass Function (PMF)</strong><span class="tooltip-text">PMF is the ancient scroll that tells you exactly how likely the universe is to hand you each possible outcome — usually just before handing you something else entirely, with a smug statistical shrug.</span></span>, which defines the probability that a <span class="tooltip"><strong>discrete random variable</strong><span class="tooltip-text">is the kind of creature that only deals in distinct, countable outcomes — like a bureaucrat who refuses to acknowledge anything between “approved,” “denied,” and “lost in the system,” even when reality is clearly leaking between the forms.</span></span> takes a specific value.<br>
        <br>
    For the <strong>Bernoulli distribution</strong>, which models binary outcomes, the PMF is given by:<br>
        
    \[
    P(X = x) = p^x (1 - p)^{1 - x}, \quad \\text{for } x \in \{0, 1\}
    \]

    where:<br>
    - \( p \) is the probability of success (i.e., \( X = 1 \)),<br>
    - \( 1 - p \) is the probability of failure (i.e., \( X = 0 \)).<b>

    <strong>Example:</strong> <br>
    Using the parameters from our scenario:<br>
    - \( P(X = 1) = 0.2 \)<br>
    - \( P(X = 0) = 0.8 \)<br>

    <strong>Question:</strong><br>  
    You observe a single data point: the user clicked (i.e., \( X = 1 \)). What is the value of the PMF for this observation?<br>
        <br>
    Assume \( p = 0.84 \). Use the PMF formula to compute \( P(X = 1) \).
    EOT,
        'Solution: A – Since \( x = 1 \), we plug into the PMF: \( P(1) = 0.2^1 \cdot (1 - 0.2)^0 = 0.2 \cdot 1 = 0.2 \)',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "0.2",
            "B" => "0.8",
            "C" => "0.16",
            "D" => "0.84"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "D"
        ]),
        $exercise_number
    );
    $exercise_number++;
    // ---------------------------------------------------------------------------
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Definition of the Binomial Distribution',
        <<<EOT
    The <span class="tooltip"><strong>Binomial distribution</strong><span class="tooltip-text">is the stern accountant of the probability world — it counts how many successes you get out of a fixed number of yes-or-no trials, flipping coins, making decisions, or taking chances, all while muttering, “independent and identical, or it doesn’t count.”</span></span> is used to model the number of successes in a fixed number of independent trials, where each trial results in either success or failure, and the probability of success remains constant.<br>
        <br>
    Formally, a random variable \( X \sim \\text{Binomial}(n, p) \) if:<br>
    - \( n \) is the number of independent trials, <br>
    - \( p \) is the probability of success in each trial,<br>
    - \( X \) counts the number of successes.<br>
        <br>
    The probability mass function (PMF) of the Binomial distribution is:

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    <strong>Question:</strong>  <br>
    Which of the following scenarios is best described by a Binomial distribution?<br>

    EOT,
        'Solution: C – The Binomial distribution applies when we count the number of times an event (like "heads") happens in a fixed number of independent trials with constant probability.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "Measuring the time between calls in a call center.",
            "B" => "Predicting the stock market return over a month.",
            "C" => "Flipping a coin 10 times and counting how often it lands on heads.",
            "D" => "Choosing a random number between 1 and 100."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "C"
        ]),
        $exercise_number
    );
        $exercise_number++;
    // ---------------------------------------------------------------------------
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Introduction to the Binomial Distribution',
        <<<EOT
    The <strong>Binomial distribution</strong> models the number of successes in a fixed number of independent trials, where each trial has only two outcomes (success or failure), and each success occurs with probability \( p \).<br>
        <br>
    The <strong>Probability Mass Function (PMF)</strong> of the Binomial distribution is:<br>

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    where:<br>
    - \( n \) is the number of trials,<br>
    - \( k \) is the number of observed successes,<br>
    - \( p \) is the probability of success on a single trial.<br>

    <strong>Example:</strong>  <br>
    Suppose a user has a 20% chance of clicking a button on any page they visit. <span class="tooltip">They visit 3 pages independently.<span class="tooltip-text">Told ya, that's the sacred incantation right there: independent and identically distributed. Or as the Neuronite monks chant it during the Midnight Sampling Ritual: "i.i.d., i.i.d., I summon thee, O Central Limit Theorem!”. Each visit, each click, each little stochastic decision dances alone and yet follows the same mysterious distribution scroll. No collusion, no memory, no hidden whispers between trials. Just pure, unadulterated statistical solitude — identical in law, independent in fate. Like triplets raised in separate libraries./span></span><br>
        <br>
    <strong>Question:</strong>  <br>
    What is the probability that the user clicks the button exactly once during those 3 visits?<br>
        <br>
    Use the formula above with:<br>
    - \( n = 3 \)<br>
    - \( k = 1 \)<br>
    - \( p = 0.2 \)<br>
    EOT,
        'Solution: B – We compute \( P(X = 1) = \binom{3}{1} \cdot 0.2^1 \cdot 0.8^2 = 3 \cdot 0.2 \cdot 0.64 = 0.384 \)',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "0.512",
            "B" => "0.128",
            "C" => "0.384",
            "D" => "0.256"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "C"
        ]),
        $exercise_number
    );

}

function add_exponential_family_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );


    // Exercise 1: Deriving the Log-Partition Function
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Deriving the Log-Partition Function',
        <<<EOT
    The <strong>exponential family</strong> is a class of probability distributions with a probability density (or mass) function expressed as:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right)
    \]

    where:<br>
    - \( h(y) \): Base measure, a non-negative function.<br>
    - \( T(y) \): Sufficient statistic, capturing the structure of the data.<br>
    - \( \\eta(\\theta) \): Natural parameter, a function of the distribution’s parameters.<br>
    - \( A(\\theta) \): Log-partition function, ensuring normalization.<br>
        <br>
    By definition the distribution integrates (or sums) to 1:

    \[
    \int p(y | \\theta) \, dy = 1
    \]

    For a continuous distribution, substitute the exponential family form:

    \[
    \int h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right) \, dy = 1
    \]

    <strong>Derivation Steps:</strong><br>
    1. Because \( A(\\theta) \) does not depend on \( y \), factor out terms independent of \( y \):

    \[
    e^{-A(\\theta)} \int h(y) \\exp\left(\\eta(\\theta) \cdot T(y)\\right) \, dy = 1
    \]

    2. Solve for the integral:

    \[
    \int h(y) \\exp\left(\\eta(\\theta) \cdot T(y)\\right) \, dy = e^{A(\\theta)}
    \]

    <strong>Question:</strong><br>  
    What is the expression for \( A(\\theta) \)?
    <br>
    A. \( A(\\theta) = \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \)  <br>
    B. \( A(\\theta) = \\ln \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \) <br> 
    C. \( A(\\theta) = \\exp\\left(\\int h(y) \\eta(\\theta) \\cdot T(y) \, dy\\right) \)  <br>
    D. \( A(\\theta) = h(y) \\ln\\left(\\eta(\\theta) \\cdot T(y)\\right) \)<br>
    EOT,
        'Solution: Taking the natural logarithm of both sides of the equation from step 2, \( e^{A(\\theta)} = \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \), we get \( A(\\theta) = \\ln \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \). Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "Integral of h(y) exp(η(θ) · T(y))",
            "B" => "Log of integral of h(y) exp(η(θ) · T(y))",
            "C" => "Exp of integral of h(y) η(θ) · T(y)",
            "D" => "h(y) ln(η(θ) · T(y))"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 2: Function of the Log-Partition Function
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Role of the Log-Partition Function',
        <<<EOT
    The <strong>exponential family</strong> is a class of probability distributions with the form:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right) = h(y) \\frac{\\exp\left(\\eta(\\theta) \cdot T(y)\\right)}{\\exp\left(A(\\theta)\\right)}
    \]

    where:<br>
    - \( h(y) \): Base measure.<br>
    - \( T(y) \): Sufficient statistic.<br>
    - \( \\eta(\\theta) \): Natural parameter.<br>
    - \( A(\\theta) \): Log-partition function.<br>
    <br>
    The log-partition function \( A(\\theta) \) plays a critical role in the exponential family.<br>
        <br>
    <strong>Question:</strong><br>
    What is the primary function of \( A(\\theta) \)?

    EOT,
        'Solution: The log-partition function \( A(\theta) \) ensures that the probability distribution integrates (or sums) to 1, acting as a normalization constant. Answer: B.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "Defines sufficient statistic",
            "B" => "Ensures normalization",
            "C" => "Transforms natural parameter",
            "D" => "Computes base measure"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 3: Bernoulli to Canonical Form
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Canonical Form of Bernoulli Likelihood',
        <<<EOT
    Consider a binary classification problem where the target variable \( y \in \{0, 1\} \) is modeled as a <strong>Bernoulli distribution</strong>. The probability mass function (PMF) for a single observation is:

    \[
    P(y | p) = p^y (1 - p)^{1 - y}
    \]

    To express this in the exponential family’s canonical form, we transform the PMF using logarithmic and exponential operations. The exponential family form is:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right)
    \]

    <strong>Step-by-Step Transformation:</strong><br>
    1. Start with the PMF: \( P(y | p) = p^y (1 - p)^{1 - y} \).<br>
    2. Rewrite using exponents: \( P(y | p) = (1 - p) \cdot \left(\\frac{p}{1 - p}\\right)^y \).<br>
    3. Take the natural logarithm inside the exponent: \( \left(\\frac{p}{1 - p}\\right)^y = \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right)\\right) \).<br>
    4. Thus, the PMF becomes: \( P(y | p) = (1 - p) \cdot \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right)\\right) = \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right) -\ln(1 - p) \\right)\).<br>
    5. Identify components:<br> 
    - \( h(y) = 1 \),<br>
    - \( T(y) = y \),<br>
    - \( \\eta(p) = \ln\left(\\frac{p}{1 - p}\\right) \),<br>
    - \( A(p) = -\ln(1 - p) \).<br>
        <br>
    This is the canonical form, where \( \\eta(p) \) is the natural parameter.<br>
    <br>
    <strong>Question:</strong><br>  
    What is the natural parameter \( \\eta(p) \) for the binomial distribution when \( p = 0.6 \)? Round to 2 decimal places.

    1. Identify \( \\eta(p) \) from the exponential form.
    2. Compute \( \\eta(p) \) for \( p = 0.6 \).
    EOT,
        'Solution:  
    From the exponential form, \( \\eta(p) = \ln\left(\\frac{p}{1 - p}\\right) \).  
    For \( p = 0.6 \):  
    \( \\eta(p) = \ln\left(\\frac{0.6}{1 - 0.6}\\right) = \ln\left(\\frac{0.6}{0.4}\\right) = \ln(1.5) \\approx 0.41 \).  
    Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.18",
            "B" => "0.41",
            "C" => "0.69",
            "D" => "1.10"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 3: Reverting Natural Parameter to Logistic Function
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Reverting Bernoulli Natural Parameter',
        <<<EOT
    From the previous question we know that Bernoulli PMF becomes: \( P(y | p) = (1 - p) \cdot \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right)\\right) = \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right) -\ln(1 - p) \\right)\).<br>
    We can identify exponential components:<br> 
    - \( h(y) = 1 \),<br>
    - \( T(y) = y \),<br>
    - \( \\eta(p) = \ln\left(\\frac{p}{1 - p}\\right) \),<br>
    - \( A(p) = -\ln(1 - p) \).<br>
        <br>
    The natural parameter is \( \\eta(p) = \ln\left(\\frac{p}{1 - p}\\right) \).<br>
        <br>
    <strong>Question:</strong><br>
    Revert the relationship to express \( p \) as a function of \( \\eta \). Which function represents \( p(\\eta) \)?<br>
        <br>

    EOT,
        'Solution:  
    Starting with \( \\eta = \ln\left(\\frac{p}{1 - p}\\right) \), exponentiate both sides:  
    \( e^{\\eta} = \\frac{p}{1 - p} \).  
    Solve for \( p \):  
    \( p = e^{\\eta} (1 - p) \), so \( p + e^{\\eta} p = e^{\\eta} \), thus \( p (1 + e^{\\eta}) = e^{\\eta} \), and \( p = \\frac{e^{\\eta}}{1 + e^{\\eta}} \).  
    This is the logistic function, since \( \\frac{e^{\\eta}}{1 + e^{\\eta}} = \\frac{1}{1 + e^{-\\eta}} = \\sigma(\\eta) \).  
    Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "η / (1 + η)",
            "B" => "1 / (1 + e^η)",
            "C" => "1 / (1 + e^-η)",
            "D" => "e^η"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 6: Role of the Natural Parameter
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Role of the Natural Parameter',
        <<<EOT
    The <strong>exponential family</strong> is a class of probability distributions with the form:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right)
    \]

    where:<br>
    - \( h(y) \): Base measure.<br>
    - \( T(y) \): Sufficient statistic.<br>
    - \( \\eta(\\theta) \): Natural parameter.<br>
    - \( A(\\theta) \): Log-partition function.<br>
        <br>
    The natural parameter \( \\eta(\\theta) \) plays a critical role in defining the distribution of the response variable \( y \).<br>
        <br>
    <strong>Question:</strong><br>
    What is the primary role of the natural parameter \( \\eta(\\theta) \) in the exponential family?<br>

    EOT,
        'Solution: The natural parameter \( \eta(\theta) \) determines the shape or behavior of the distribution of \( y \) by weighting the sufficient statistic \( T(y) \) in the exponent, influencing the probability density or mass function. For example, in a Bernoulli distribution, \( \eta = \ln\left(\frac{p}{1 - p}\right) \) controls the probability of success. Answer: B.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "It normalizes the distribution to ensure it sums to 1.",
            "B" => "It controls the shape or behavior of the distribution of \( y \).",
            "C" => "It defines the base measure of the distribution.",
            "D" => "It computes the sufficient statistic for \( y \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 7: Matching η and A(η) with their roles (Thermostat analogy)
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Match η and A(η) to Their Roles',
        <<<EOT
    The <strong>exponential family</strong> expresses a probability distribution using two key functions:
    <ul>
    <li>\( \\eta \) – the natural parameter, controlling the shape or "behavior" of the distribution,</li>
    <li>\( A(\\eta) \) – the log-partition function, ensuring the result is a valid probability distribution.</li>
    </ul>

    To make this more intuitive, let’s use the <strong>thermostat analogy</strong>:

    <strong>Match each concept on the left to its corresponding role on the right:</strong>
    EOT,
        'Solution: η is like setting the thermostat — it determines the behavior of the distribution. A(η) is the internal circuitry that ensures total probability adds up to 1. Correct matches: "Natural parameter η" → "Controls the behavior of the distribution (like the thermostat setting)", and "Log-partition function A(η)" → "Ensures the probabilities sum to 1 (like the thermostat circuitry)".',
        'easy',
        'match_boxes',
        json_encode([
            "Natural parameter η" => "thermostat setting: it controls the temperature (the behavior of the system).",
            "Log-partition function A(η)" => "thermostat's internal circuitry that makes sure the energy distribution still sums to 100%."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([]),  // no special correct_option needed for match_boxes
        $exercise_number
    );
    $exercise_number++;

    

}

function add_glm_exercises_to_the_lesson($lesson_number){

    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    error_log("Adding GLM exercises with number $lesson_number to lesson ID: $lesson_id, category ID: $category_id");
    // Exercise 5: Generalized Linear Models and Exponential Family
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Properties of Generalized Linear Models',
        <<<EOT
    <strong>Generalized Linear Models (GLMs)</strong> extend the exponential family to incorporate covariates, modeling the relationship between a response variable \( y \) and predictors \( \mathbf{x} \). A GLM consists of three components:<br>
    1. <strong>Random Component</strong>: The response \( y \) follows a distribution from the exponential family (e.g., Gaussian, Bernoulli, Poisson).<br>
    2. <strong>Systematic Component</strong>: A linear predictor \( \eta = \mathbf{w} \cdot \mathbf{x} \), where \( \mathbf{w} \) are weights and \( \mathbf{x} \) are covariates.<br>
    3. <strong>Link Function</strong>: A function \( g \) such that \( g(\mu) = \eta \), where \( \mu = E[y] \) is the expected value of the response.<br>
        <br>
    Key properties of GLMs include:<br>
    - They use the exponential family to model various response distributions.<br>
    - The link function connects the linear predictor to the expected response.<br>
    - The log-likelihood is concave, allowing efficient maximum likelihood estimation (MLE) via iterative methods like Newton-Raphson.<br>
        <br>
    <strong>Question:</strong><br>
    Which of the following are true properties of GLMs? Select all that apply.<br>
        <br>
    A. The response variable must follow a Gaussian distribution.  <br>
    B. The link function relates the linear predictor to the expected response.  <br>
    C. GLMs are an extension of the exponential family with covariates.  <br>
    D. The log-likelihood in GLMs is concave, ensuring a unique MLE solution.<br>
    EOT,
        'Solution:  <br>
    - A: False (GLMs can model various exponential family distributions, not just Gaussian).  <br>
    - B: True (the link function is a core component of GLMs).  <br>
    - C: True (GLMs extend the exponential family by incorporating covariates).  <br>
    - D: True (the log-likelihood is concave for exponential family distributions in GLMs).  <br>
    Correct answers: B, C, D.',
        "medium",
        'multiple_choice',
        json_encode([
            "A" => "Response must be Gaussian",
            "B" => "Uses a link function",
            "C" => "Extends exponential family with covariates",
            "D" => "Log-likelihood is concave"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => [ "B", "C", "D"]]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 2: Generalized Linear Model and MLE
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Logistic Regression belongs to Exponential Family',
        <<<EOT
    A <strong>generalized linear model (GLM)</strong> is a flexible framework for modeling relationships between a response variable and predictors, extending linear regression to non-Gaussian distributions. A GLM consists of three components:<br>
    1. <strong>Random Component</strong>: The response variable follows a distribution from the exponential family (e.g., Bernoulli for logistic regression).<br>
    2. <strong>Systematic Component</strong>: A linear predictor \( \\eta = \mathbf{w} \cdot \mathbf{x} \), where \( \mathbf{w} \) are weights and \( \mathbf{x} \) are features.<br>
    3. <strong>Link Function</strong>: A function \( g \) such that \( g(\mu) = \\eta \), where \( \mu \) is the expected value of the response. For logistic regression, the link function is the logit, \( g(\mu) = \ln\left(\\frac{\mu}{1 - \mu}\\right) \).<br>
        <br>
    GLMs are typically solved using <strong>maximum likelihood estimation (MLE)</strong>. The likelihood function is constructed based on the exponential family distribution, and the log-likelihood is maximized with respect to the weights \( \mathbf{w} \). Since the log-likelihood is often non-linear, iterative optimization methods like gradient descent or Newton-Raphson are used to find the optimal weights.
    <br>
        <br>
    Given a logistic regression model with features \( \mathbf{x} = [1, 2] \) (including bias) and weights \( \mathbf{w} = [0.5, -0.3] \), the linear predictor is:<br>

    \[
    \\eta = \mathbf{w} \cdot \mathbf{x}
    \]

    The predicted probability is obtained via the logistic function:

    \[
    \mu = \sigma(\\eta) = \\frac{1}{1 + e^{-\\eta}}
    \]

    <strong>Question:</strong>  
    Compute the predicted probability \( \mu \). Round to 3 decimal places.
        <br>
        <br>
    1. Calculate \( \\eta \).  <br>
    2. Compute \( \mu = \sigma(\\eta) \).<br>
    EOT,
        'Solution:  <br>
    1. \( \\eta = 0.5 \cdot 1 + (-0.3) \cdot 2 = 0.5 - 0.6 = -0.1 \).  <br>
    2. \( \mu = \\frac{1}{1 + e^{0.1}} \\approx \\frac{1}{1 + 1.1052} \\approx 0.475 \).  <br>
    Answer: \( \mu \\approx 0.475 \).',
        'medium',
        'labeled_inputs',
        null,
        json_encode(["correct_options" => [
            "eta" => "-0.1",
            "mu" => "0.475"
        ]]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Gaussian Distribution in Exponential Family
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gaussian in Exponential Family',
        <<<EOT
    The <strong>Gaussian distribution</strong> with mean \( \mu \) and variance \( \sigma^2 \) has a probability density function:

    \[
    p(y | \mu, \sigma^2) = \\frac{1}{\sqrt{2\pi\sigma^2}} \\exp\left(-\\frac{(y - \mu)^2}{2\sigma^2}\\right)
    \]

    The <strong>exponential family</strong> form is:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right)
    \]

    To express the Gaussian distribution in this form, rewrite the PDF:<br>
        <br>
    1. Expand the exponent:

    \[
    -\\frac{(y - \mu)^2}{2\sigma^2} = -\\frac{y^2 - 2y\mu + \mu^2}{2\sigma^2} = -\\frac{y^2}{2\sigma^2} + \\frac{\mu y}{\sigma^2} - \\frac{\mu^2}{2\sigma^2}
    \]

    2. Rewrite the PDF:

    \[
    p(y | \mu, \sigma^2) = \\frac{1}{\sqrt{2\pi\sigma^2}} \\exp\left(-\\frac{y^2}{2\sigma^2}\\right) \\exp\left(\\frac{\mu y}{\sigma^2} - \\frac{\mu^2}{2\sigma^2}\\right)
    \]

    <strong>Question:</strong><br>  
    What is the sufficient statistic \( T(y) \) for the Gaussian distribution in the exponential family form?

    A. \( y^2 \)  
    B. \( [y, y^2] \)  
    C. \( y \)  
    D. \( \\frac{y}{\sigma^2} \)
    EOT,
        'Solution: From the rewritten PDF, the exponent is \( \\frac{\mu y}{\sigma^2} - \\frac{\mu^2}{2\sigma^2} \). The term involving \( y \) is \( \\frac{\mu y}{\sigma^2} \), so the sufficient statistic \( T(y) = y \). Answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "y^2",
            "B" => "[y, y^2]",
            "C" => "y",
            "D" => "y/σ^2"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;
    // Exercise 4: Properties of Exponential Family
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Properties of Exponential Family',
        <<<EOT
    The <strong>exponential family</strong> distributions have several important properties related to maximum likelihood estimation (MLE) and moments. For a distribution in the form:

    \[
    p(y | \\theta) = h(y) \\exp\left(\\eta(\\theta) \cdot T(y) - A(\\theta)\\right)
    \]

    Key properties include:<br>
    - The log-likelihood function for MLE is concave, ensuring a unique maximum. <br>
    - The expected value of the sufficient statistic \( E[T(y)] \) equals the derivative of the log-partition function: \( E[T(y)] = \\frac{dA(\\theta)}{d\\eta} \).<br>
    - The variance of the sufficient statistic is the second derivative: \( \\text{Var}(T(y)) = \\frac{d^2 A(\\theta)}{d\\eta^2} \).<br>
        <br>
    When performing MLE, we typically maximize the log-likelihood \( \\ell(\\theta) \). However, in optimization, it is common to minimize the negative log-likelihood \( -\\ell(\\theta) \).<br>
        <br>
    <strong>Question:</strong><br>
    What is a property of the negative log-likelihood \( -\\ell(\\theta) \) in the exponential family?<br>

    EOT,
        'Solution: Since the log-likelihood \( \\ell(\\theta) \) is concave for exponential family distributions, the negative log-likelihood \( -\\ell(\\theta) \) is convex (the negative of a concave function is convex). This convexity ensures that minimizing \( -\\ell(\\theta) \) has a unique solution. Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "Concave",
            "B" => "Convex",
            "C" => "Neither concave nor convex",
            "D" => "Linear"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 4: Log-Likelihood Gradient for Logistic Regression
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gradient of Logistic Log-Likelihood',
        <<<EOT
    In logistic regression, the log-likelihood is maximized to estimate the weights \( \mathbf{w} \). For a single observation with target \( y \in \{0, 1\} \), features \( \mathbf{x} \), and predicted probability \( \mu = \sigma(\mathbf{w} \cdot \mathbf{x}) \), the log-likelihood is:

    \[
    \\ell(\mathbf{w}) = y \ln(\mu) + (1 - y) \ln(1 - \mu)
    \]

    To optimize \( \mathbf{w} \), we compute the gradient of the log-likelihood with respect to \( \mathbf{w} \):

    \[
    \\nabla_{\mathbf{w}} \\ell(\mathbf{w}) = (y - \mu) \mathbf{x}
    \]

    Consider a single observation with \( y = 1 \), \( \mathbf{x} = [1, 2] \), and \( \mathbf{w} = [0.5, -0.3] \).

    <strong>Question:</strong>  <br>
    Compute the gradient \( \\nabla_{\mathbf{w}} \\ell(\mathbf{w}) \). Round each component to 3 decimal places.<br>
        <br>
    1. Calculate \( \mathbf{w} \cdot \mathbf{x} \).<br>
    2. Compute \( \mu = \sigma(\mathbf{w} \cdot \mathbf{x}) \).<br>
    3. Compute the gradient \( (y - \mu) \mathbf{x} \).<br>
    EOT,
        'Solution:  <br>
    1. \( \mathbf{w} \cdot \mathbf{x} = 0.5 \cdot 1 + (-0.3) \cdot 2 = 0.5 - 0.6 = -0.1 \).  <br>
    2. \( \mu = \sigma(-0.1) = \frac{1}{1 + e^{0.1}} \approx \frac{1}{1 + 1.1052} \approx 0.475 \).  <br>
    3. \( y - \mu = 1 - 0.475 = 0.525 \).  <br>
    4. Gradient = \( 0.525 \cdot [1, 2] = [0.525, 1.050] \).  <br>
    Answer: [0.525, 1.050].',
        'hard',
        'labeled_inputs',
        null,
        json_encode([
            "correct_options" => [
                "w0" => "0.525",
                "w1" => "1.050"
            ]
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 5: Exponential Family for Logistic Regression
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Logistic Regression in Exponential Family',
        <<<EOT
    Logistic regression models the target variable \( y \in \{0, 1\} \) as a Bernoulli distribution, parameterized by \( p = \sigma(\mathbf{w} \cdot \mathbf{x}) \), where \( \sigma(z) = \frac{1}{1 + e^{-z}} \). In the exponential family, the natural parameter \( \eta \) relates to the linear predictor.
    <br>
    Given the canonical form of the Bernoulli distribution:<br>

    \[
    P(y | \\eta) = (1 - \sigma(\\eta)) \\exp(y \\eta)
    \]

    where \( \\eta = \ln\left(\\frac{p}{1 - p}\\right) \), and \( p = \sigma(\\eta) \).

    <strong>Question:</strong>  <br>
    For a logistic regression model with features \( \mathbf{x} = [1, -1] \) and weights \( \mathbf{w} = [0.2, 0.3] \), compute the natural parameter \( \\eta \). Round to 2 decimal places.<br>
        <br>
    1. Compute \( \mathbf{w} \cdot \mathbf{x} \).  <br>
    2. Since \( \\eta = \mathbf{w} \cdot \mathbf{x} \) in logistic regression, report \( \\eta \).<br>
    EOT,
        'Solution:  <br>
    1. \( \mathbf{w} \cdot \mathbf{x} = 0.2 \cdot 1 + 0.3 \cdot (-1) = 0.2 - 0.3 = -0.1 \).  <br>
    2. \( \\eta = -0.1 \).  <br>
    Answer: A.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "-0.10",
            "B" => "0.10",
            "C" => "-0.50",
            "D" => "0.50"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "A"]),
        $exercise_number
    );
    $exercise_number++;
}

function add_repetition_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );

    // Exercise 1: Identifying Neuron with Binary Inputs
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Binary Inputs in Neuron Types',
        <<<EOT
    In neural network models, neurons process input features to produce an output based on a weighted sum and an activation function. Some neurons are designed to accept **binary inputs**, where each input feature \( x_i \in \{0, 1\}\). Consider the following neuron types:<br>
    - <strong>McCulloch-Pitts Neuron</strong>: An early computational model (1943) that uses binary inputs and outputs, designed to mimic logical operations with a threshold activation.<br>
    - <strong>Perceptron</strong>: A single-layer model (1958) introduced by Rosenblatt, capable of learning linear decision boundaries, typically accepting continuous or binary inputs but often using binary inputs in early applications.<br>
    - <strong>Modern Neuron</strong>: A neuron in contemporary neural networks, typically part of deep networks, processing continuous real-valued inputs with activation functions like ReLU or sigmoid.<br>

    <strong>Question:</strong><br>
    Which neuron type is most closely associated with using strictly binary inputs?<br>

    EOT,
        'Solution: The McCulloch-Pitts neuron is designed to use strictly binary inputs (\( \{0, 1\} \)) to model logical operations, making it the most closely associated with binary inputs. The Perceptron can handle continuous or binary inputs, though early examples used binary inputs, and Modern Neurons typically process continuous inputs. Answer: A.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "McCulloch-Pitts Neuron",
            "B" => "Perceptron",
            "C" => "Modern Neuron",
            "D" => "None"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "A"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 2: Matching Activation Functions
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Activation Functions in Neurons',
        <<<EOT
    Perceptrons use <strong>activation functions</strong> to transform the weighted Perceptron inputs into an output. Two common activation functions are:
    - <strong>Threshold Function</strong>: Outputs 1 if the weighted sum exceeds a threshold, otherwise 0, i.e., \( f(z) = 1 \) if \( z \geq \\theta \), else \( f(z) = 0 \).
    - <strong>Softmax Function</strong>: Outputs a probability distribution over multiple classes, i.e., \( \sigma(\mathbf{z})_i = \\frac{e^{z_i}}{\sum_j e^{z_j}} \).

    Consider the following scenarios:
    - <strong>Scenario A</strong>: A single-layer perceptron for binary classification (e.g., AND gate).
    - <strong>Scenario B</strong>: A neural network output layer for multiclass classification (e.g., digit recognition).
    - <strong>Scenario C</strong>: A hidden layer in a modern neural network.

    <strong>Question:</strong><br>
    Which scenarios correctly match the activation function to the neuron type? Select all that apply.<br>

    EOT,
        'Solution:  
    - Scenario A: A single-layer perceptron for binary classification typically uses a threshold function (A is true).  
    - Scenario B: Multiclass classification uses softmax to output probabilities (B is true).  
    - Scenario C: Hidden layers typically use functions like ReLU or sigmoid, not threshold (C is false).  
    - Scenario D: Scenario B does not use threshold (D is false).  
    Correct answers: A, B.',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "Scenario A uses threshold",
            "B" => "Scenario B uses softmax",
            "C" => "Scenario C uses threshold",
            "D" => "Scenario B uses threshold"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B"]]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Geometric Interpretation of Perceptron Training
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Geometric Interpretation of Perceptron Training',
        <<<EOT
    In a perceptron, the decision boundary is defined by the weight vector \( \mathbf{w} \) and bias \( b \), where the classification rule is based on the sign of \( \mathbf{w} \cdot \mathbf{x} + b \). Geometrically, the weight vector is perpendicular to the decision boundary, and training adjusts \( \mathbf{w} \) to minimize classification errors. If an input \( \mathbf{x} \) is positive (correctly classified as class 1) but the perceptron incorrectly predicts class 0 (i.e., \( \mathbf{w} \cdot \mathbf{x} + b < 0 \)), the weight vector is updated to reduce the angle between \( \mathbf{w} \) and \( \mathbf{x} \).<br>
    <br>
    Given:<br>
    - Input vector \( \mathbf{x} = [1, 2] \).<br>
    - Weight vector \( \mathbf{w} = [-1, -1] \).<br>
    - Bias \( b = 0 \).<br>
    - Learning rate \( \\eta = 1 \).<br>
    - True label \( y = 1 \), but predicted \( \hat{y} = 0 \) (since \( \mathbf{w} \cdot \mathbf{x} = -3 < 0 \)).<br>
    <br>
    The perceptron update rule is:<br>
        <br>
    \[
    \mathbf{w} \leftarrow \mathbf{w} + \\eta (y - \hat{y}) \mathbf{x}
    \]

    <strong>Question:</strong><br>
    Compute the updated weight vector \( \mathbf{w} \) after one iteration. Provide the components of the new \( \mathbf{w} \).<br>

    1. Calculate \( y - \hat{y} \).  <br>
    2. Update \( \mathbf{w} \) using the perceptron update rule.<br>
    EOT,
        'Solution:  
    1. \( y = 1 \), \( \hat{y} = 0 \), so \( y - \hat{y} = 1 - 0 = 1 \).  
    2. Update: \( \mathbf{w} \leftarrow [-1, -1] + 1 \cdot 1 \cdot [1, 2] = [-1, -1] + [1, 2] = [0, 1] \).  
    Answer: [0, 1].',
        'medium',
        'labeled_inputs',
        json_encode([
            "w0" => "Updated weight w0",
            "w1" => "Updated weight w1"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "w0" => "0",
            "w1" => "1"
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 4: Binomial Distribution Probability
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Binomial Distribution Calculation',
        <<<EOT
    The **binomial distribution** models the number of successes in \( n \) independent trials, each with success probability \( p \). The probability mass function is:

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    where \( \binom{n}{k} = \\frac{n!}{k!(n - k)!} \).

    Consider a scenario with \( n = 5 \) trials and success probability \( p = 0.4 \).

    **Question:**  
    Compute the probability of exactly 3 successes, i.e., \( P(X = 3) \). Round to 3 decimal places.

    1. Calculate the binomial coefficient \( \binom{5}{3} \).  
    2. Compute \( p^k (1 - p)^{n - k} \).  
    3. Combine to find \( P(X = 3) \).
    EOT,
        'Solution:  
    1. \( \binom{5}{3} = \frac{5!}{3!2!} = \frac{120}{6 \cdot 2} = 10 \).  
    2. \( p^3 = (0.4)^3 = 0.064 \), \( (1 - p)^{5 - 3} = (0.6)^2 = 0.36 \).  
    3. \( P(X = 3) = 10 \cdot 0.064 \cdot 0.36 = 0.2304 \approx 0.230 \).  
    Answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.154",
            "B" => "0.200",
            "C" => "0.230",
            "D" => "0.288"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 5: Components of Perceptron Training
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Perceptron Training Process',
        <<<EOT
    Training a perceptron involves updating the weight vector \( \mathbf{w} \) and bias \( b \) to correctly classify inputs. The process includes:
    - Computing the weighted sum \( z = \mathbf{w} \cdot \mathbf{x} + b \).
    - Applying an activation function (e.g., threshold).
    - Updating weights based on the error between predicted and true labels.

    Match each component of the perceptron training process to its description by dragging the terms to the correct boxes.

    - **Weighted Sum**  
    - **Activation Function**  
    - **Weight Update**  
    - **Error Calculation**

    Descriptions:
    - Computes \( y - \hat{y} \) to determine the prediction error.
    - Applies a function like threshold or sigmoid to the weighted sum.
    - Adjusts \( \mathbf{w} \) using the update rule \( \mathbf{w} \leftarrow \mathbf{w} + \\eta (y - \hat{y}) \mathbf{x} \).
    - Calculates \( \mathbf{w} \cdot \mathbf{x} + b \).
    EOT,
        'Solution:  
    - Weighted Sum: Calculates \( \mathbf{w} \cdot \mathbf{x} + b \).  
    - Activation Function: Applies a function like threshold or sigmoid.  
    - Weight Update: Adjusts \( \mathbf{w} \) using the update rule.  
    - Error Calculation: Computes \( y - \hat{y} \).',
        'easy',
        'drag_and_drop',
        json_encode([
            "Weighted Sum" => "Computes the linear combination",
            "Activation Function" => "Transforms the linear combination",
            "Weight Update" => "Adjusts weights based on error",
            "Error Calculation" => "Determines prediction error"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "Weighted Sum" => "Calculates w · x + b",
            "Activation Function" => "Applies a function like threshold or sigmoid",
            "Weight Update" => "Adjusts w using the update rule",
            "Error Calculation" => "Computes y - ŷ"
        ]),
        $exercise_number
    );
    $exercise_number++;

}
function add_binomial_distribution_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );


    // Exercise 1: Binomial in Exponential Family Form
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Binomial in Exponential Family',
        <<<EOT
    The <strong>binomial distribution</strong> models the number of successes \( k \) in \( n \) independent trials, each with success probability \( p \). Its probability mass function (PMF) is:

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    The <strong>exponential family</strong> form for a distribution is:

    \[
    p(x | \\theta) = h(x) \\exp\left(\\eta(\\theta) \cdot T(x) - A(\\theta)\\right)
    \]

    To express the binomial distribution in this form, rewrite the PMF as:

    \[
    P(X = k) = \binom{n}{k} (1 - p)^n \left(\\frac{p}{1 - p}\\right)^k = \binom{n}{k} (1 - p)^n \\exp\left(k \ln\left(\\frac{p}{1 - p}\\right)\\right)
    \]

    **Question:**  
    What is the natural parameter \( \\eta(p) \) for the binomial distribution when \( p = 0.6 \)? Round to 2 decimal places.

    1. Identify \( \\eta(p) \) from the exponential form.
    2. Compute \( \\eta(p) \) for \( p = 0.6 \).
    EOT,
        'Solution:  
    From the exponential form, \( \\eta(p) = \ln\left(\\frac{p}{1 - p}\\right) \).  
    For \( p = 0.6 \):  
    \( \\eta(p) = \ln\left(\\frac{0.6}{1 - 0.6}\\right) = \ln\left(\\frac{0.6}{0.4}\\right) = \ln(1.5) \approx 0.41 \).  
    Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.18",
            "B" => "0.41",
            "C" => "0.69",
            "D" => "1.10"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 2: Expected Value and Variance
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Moments of Binomial Distribution',
        <<<EOT
    The binomial distribution, modeling a target variable with \( n \) trials and success probability \( p \), has the following moments:
    - **Expected Value**: \( E[X] = np \)
    - **Variance**: \( \\text{Var}(X) = np(1 - p) \)

    Consider a binomial random variable \( X \) with \( n = 10 \) and \( p = 0.2 \).

    **Question:**  
    Compute the expected value and variance of \( X \). Provide the answers as numerical values.

    1. Calculate \( E[X] = np \).
    2. Calculate \( \\text{Var}(X) = np(1 - p) \).
    EOT,
        'Solution:  
    1. \( E[X] = np = 10 \cdot 0.2 = 2 \).  
    2. \( \text{Var}(X) = np(1 - p) = 10 \cdot 0.2 \cdot (1 - 0.2) = 10 \cdot 0.2 \cdot 0.8 = 1.6 \).  
    Answer: Expected Value = 2, Variance = 1.6.',
        'easy',
        'labeled_inputs',
        json_encode([
            "expected_value" => "Expected Value (E[X])",
            "variance" => "Variance (Var(X))"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "expected_value" => "2",
            "variance" => "1.6"
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Log-Likelihood Function
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Log-Likelihood of Binomial',
        <<<EOT
    For a binomial distributed target variable \( X \) with \( n \) trials and success probability \( p \), the probability mass function is:

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    The <strong>log-likelihood function</strong> for a single observation \( k \) is:

    \[
    \\ell(p) = \ln\left(\binom{n}{k}\right) + k \ln(p) + (n - k) \ln(1 - p)
    \]

    Consider \( n = 8 \) and an observed number of successes \( k = 3 \).

    **Question:**  
    Which of the following represents the correct log-likelihood function for this observation? Assume the binomial coefficient term is constant and focus on the variable-dependent terms.

    A. \( 3 \ln(p) + 5 \ln(1 - p) \)  
    B. \( 8 \ln(p) + 3 \ln(1 - p) \)  
    C. \( 3 \ln(p) + 8 \ln(1 - p) \)  
    D. \( 5 \ln(p) + 3 \ln(1 - p) \)
    EOT,
        'Solution:  
    The log-likelihood is \( \ell(p) = \ln\left(\binom{8}{3}\right) + 3 \ln(p) + (8 - 3) \ln(1 - p) \).  
    Ignoring the constant \( \ln\left(\binom{8}{3}\right) \), the variable-dependent terms are:  
    \( 3 \ln(p) + 5 \ln(1 - p) \).  
    Answer: A.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "3 ln(p) + 5 ln(1 - p)",
            "B" => "8 ln(p) + 3 ln(1 - p)",
            "C" => "3 ln(p) + 8 ln(1 - p)",
            "D" => "5 ln(p) + 3 ln(1 - p)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "A"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 4: Components of Binomial Exponential Form
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Exponential Family Components',
        <<<EOT
    The binomial distribution can be expressed in the exponential family form:

    \[
    p(k | p) = h(k) \\exp\left(\\eta(p) \cdot T(k) - A(p)\\right)
    \]

    For the binomial distribution with PMF \( P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k} \), the exponential form is:

    \[
    P(X = k) = \binom{n}{k} (1 - p)^n \\exp\left(k \ln\left(\\frac{p}{1 - p}\\right)\\right)
    \]

    **Question:**  
    Match each component of the binomial distribution to its exponential family counterpart by dragging the terms to the correct boxes.

    - \( h(k) \)  
    - \( T(k) \)  
    - \( \\eta(p) \)  
    - \( A(p) \)

    Descriptions:
    - Sufficient statistic
    - Natural parameter
    - Log-partition function
    - Base measure
    EOT,
        'Solution:  
    - \( h(k) = \binom{n}{k} \) (Base measure)  
    - \( T(k) = k \) (Sufficient statistic)  
    - \( \eta(p) = \ln\left(\frac{p}{1 - p}\right) \) (Natural parameter)  
    - \( A(p) = -n \ln(1 - p) \) (Log-partition function)',
        'medium',
        'drag_and_drop',
        json_encode([
            "h(k)" => "Base measure",
            "T(k)" => "Sufficient statistic",
            "eta(p)" => "Natural parameter",
            "A(p)" => "Log-partition function"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "h(k)" => "binom(n,k)",
            "T(k)" => "k",
            "eta(p)" => "ln(p/(1-p))",
            "A(p)" => "-n ln(1-p)"
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 5: Maximum Likelihood Estimation
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – MLE for Binomial Parameter',
        <<<EOT
    The binomial distribution’s log-likelihood function is used to estimate the success probability \( p \) via <strong>maximum likelihood estimation (MLE)</strong>. For a single observation \( X = k \) with \( n \) trials, the log-likelihood is:

    \[
    \\ell(p) = \ln\left(\binom{n}{k}\\right) + k \ln(p) + (n - k) \ln(1 - p)
    \]

    To find the MLE, take the derivative with respect to \( p \), set it to zero, and solve:

    \[
    \\frac{d\\ell}{dp} = \\frac{k}{p} - \\frac{n - k}{1 - p} = 0
    \]

    Consider an experiment with \( n = 10 \) trials and \( k = 4 \) successes.

    **Question:**  
    Compute the MLE for \( p \). Select all that apply.

    A. The MLE is \( \hat{p} = \\frac{k}{n} \).  
    B. The MLE is \( \hat{p} = 0.4 \).  
    C. The derivative \( \\frac{d\\ell}{dp} \) is positive for all \( p \).  
    D. The log-likelihood is concave, ensuring a unique maximum.
    EOT,
        'Solution:  
    Solving the derivative: \( \\frac{k}{p} = \\frac{n - k}{1 - p} \), so \( \hat{p} = \\frac{k}{n} \).  
    For \( n = 10 \), \( k = 4 \): \( \hat{p} = \\frac{4}{10} = 0.4 \).  
    The second derivative of the log-likelihood is negative, confirming concavity.  
    The derivative is not always positive; it equals zero at the maximum.  
    Correct answers: A, B, D.',
        'hard',
        'multiple_choice',
        json_encode([
            "A" => "MLE is k/n",
            "B" => "MLE is 0.4",
            "C" => "Derivative is always positive",
            "D" => "Log-likelihood is concave"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "D"]]),
        $exercise_number
    );
    $exercise_number++;

}
function add_multinomial_distribution_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );

    // Exercise 1: Softmax Probability Calculation
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Computing Softmax Probabilities',
        <<<EOT
    The <strong>softmax function</strong> transforms a vector of real-valued scores \( \mathbf{z} = [z_1, z_2, \ldots, z_K] \) into a probability distribution over \( K \) classes. The softmax probability for class \( i \) is:

    \[
    \sigma(\mathbf{z})_i = \\frac{e^{z_i}}{\sum_{j=1}^K e^{z_j}}
    \]

    Given a score vector \( \mathbf{z} = [0, 1, -1] \), compute the softmax probabilities for each class.

    **Question:**  
    What is the softmax probability for the second class (corresponding to \( z_2 = 1 \))? Round to 3 decimal places.

    1. Compute \( e^{z_i} \) for each \( z_i \).
    2. Calculate the sum \( \sum_{j=1}^3 e^{z_j} \).
    3. Compute \( \sigma(\mathbf{z})_2 = \\frac{e^{z_2}}{\sum_{j=1}^3 e^{z_j} \).
    EOT,
        'Solution:  
    1. \( e^{z_1} = e^0 = 1 \), \( e^{z_2} = e^1 \approx 2.718 \), \( e^{z_3} = e^{-1} \approx 0.368 \).  
    2. Sum: \( 1 + 2.718 + 0.368 \approx 4.086 \).  
    3. \( \sigma(\mathbf{z})_2 = \frac{e^1}{4.086} \approx \frac{2.718}{4.086} \approx 0.665 \).  
    Answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.245",
            "B" => "0.500",
            "C" => "0.665",
            "D" => "0.900"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 2: Categorical Distribution in Softmax
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Softmax and Categorical Distribution',
        <<<EOT
    In softmax regression, the target variable follows a <strong>categorical distribution</strong>, where the probability of class \( k \) is given by the softmax function:

    \[
    P(y = k | \mathbf{x}, \mathbf{W}) = \sigma(\mathbf{W} \mathbf{x})_k = \\frac{e^{\mathbf{w}_k \cdot \mathbf{x}}}{\sum_{j=1}^K e^{\mathbf{w}_j \cdot \mathbf{x}}}
    \]

    Here, \( \mathbf{W} \) is the weight matrix, and \( \mathbf{x} \) is the input feature vector. The categorical distribution ensures that the probabilities sum to 1 and are non-negative.

    **Question:**  
    Which of the following statements about the softmax function’s role in modeling the categorical distribution are true? Select all that apply.

    A. The softmax function guarantees that probabilities sum to 1.  
    B. The softmax function outputs negative probabilities for some classes.  
    C. The softmax function is invariant to adding a constant to all input scores.  
    D. The softmax function requires the input scores to be positive.
    EOT,
        'Solution:  
    - A: True (softmax normalizes probabilities to sum to 1).  
    - B: False (softmax outputs non-negative probabilities).  
    - C: True (adding a constant to all scores does not change the probabilities due to normalization).  
    - D: False (softmax accepts any real-valued scores).  
    Correct answers: A, C.',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "Probabilities sum to 1",
            "B" => "Outputs negative probabilities",
            "C" => "Invariant to constant shift",
            "D" => "Requires positive inputs"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "A", "2" => "C"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Gradient of Cross-Entropy Loss
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gradient of Softmax Loss',
        <<<EOT
    In softmax regression, the loss function is typically the <strong>cross-entropy loss</strong>, defined for a single observation with true label \( y \) (one-hot encoded as \( \mathbf{y} \)) and predicted probabilities \( \hat{\mathbf{y}} = \sigma(\mathbf{W} \mathbf{x}) \):

    \[
    L = -\sum_{k=1}^K y_k \ln(\hat{y}_k)
    \]

    The gradient of the loss with respect to the weight vector \( \mathbf{w}_k \) for class \( k \) is:

    \[
    \nabla_{\mathbf{w}_k} L = (\hat{y}_k - y_k) \mathbf{x}
    \]

    Given:
    - Input vector \( \mathbf{x} = [1, 2] \).
    - Predicted probabilities \( \hat{\mathbf{y}} = [0.2, 0.7, 0.1] \).
    - True label is class 2 (i.e., \( \mathbf{y} = [0, 1, 0] \)).

    **Question:**  
    Compute the gradient \( \nabla_{\mathbf{w}_2} L \) for the weight vector of class 2. Provide the components rounded to 2 decimal places.

    1. Identify \( \hat{y}_2 \) and \( y_2 \).
    2. Compute \( \hat{y}_2 - y_2 \).
    3. Multiply by \( \mathbf{x} \) to obtain the gradient.
    EOT,
        'Solution:  
    1. \( \hat{y}_2 = 0.7 \), \( y_2 = 1 \).  
    2. \( \hat{y}_2 - y_2 = 0.7 - 1 = -0.3 \).  
    3. Gradient: \( (-0.3) \cdot [1, 2] = [-0.3, -0.6] \).  
    Answer: [-0.30, -0.60].',
        'hard',
        'labeled_inputs',
        json_encode([
            "w2_0" => "Gradient component for w2,0",
            "w2_1" => "Gradient component for w2,1"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "w2_0" => "-0.30",
            "w2_1" => "-0.60"
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 4: Softmax Regression Components
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Components of Softmax Regression',
        <<<EOT
    Softmax regression extends logistic regression to multiclass classification by modeling the target variable as a categorical distribution. The key components include:
    - **Score Calculation**: Computing \( \mathbf{z} = \mathbf{W} \mathbf{x} \), where \( \mathbf{W} \) is the weight matrix.
    - **Softmax Activation**: Transforming scores into probabilities using \( \sigma(\mathbf{z})_k = \\frac{e^{z_k}}{\sum_j e^{z_j}} \).
    - **Loss Function**: Typically cross-entropy loss, \( L = -\sum_k y_k \ln(\hat{y}_k) \).
    - **Gradient Update**: Adjusting weights using the gradient of the loss.

    **Question:**  
    Match each component of softmax regression to its description by dragging the terms to the correct boxes.

    - **Score Calculation**  
    - **Softmax Activation**  
    - **Loss Function**  
    - **Gradient Update**

    Descriptions:
    - Computes the linear combination of weights and inputs.
    - Transforms scores into a probability distribution.
    - Measures the error between predicted and true labels.
    - Adjusts weights based on the loss gradient.
    EOT,
        'Solution:  
    - Score Calculation: Computes the linear combination of weights and inputs.  
    - Softmax Activation: Transforms scores into a probability distribution.  
    - Loss Function: Measures the error between predicted and true labels.  
    - Gradient Update: Adjusts weights based on the loss gradient.',
        'easy',
        'drag_and_drop',
        json_encode([
            "Score Calculation" => "Computes linear combination",
            "Softmax Activation" => "Transforms to probabilities",
            "Loss Function" => "Measures prediction error",
            "Gradient Update" => "Adjusts weights"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "Score Calculation" => "Computes Wx",
            "Softmax Activation" => "Transforms scores into a probability distribution",
            "Loss Function" => "Measures the error between predicted and true labels",
            "Gradient Update" => "Adjusts weights based on the loss gradient"
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 5: Softmax Regression Prediction
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Softmax Prediction',
        <<<EOT
    In softmax regression, the predicted class is the one with the highest probability. Given:
    - Input vector \( \mathbf{x} = [1, -1] \) (including bias).
    - Weight matrix \( \mathbf{W} \) with rows \( \mathbf{w}_1 = [0.5, 0.2] \), \( \mathbf{w}_2 = [-0.3, 0.4] \), \( \mathbf{w}_3 = [0.1, -0.2] \).
    - Scores \( \mathbf{z} = \mathbf{W} \mathbf{x} \).

    **Question:**  
    Compute the score for class 2 (i.e., \( z_2 = \mathbf{w}_2 \cdot \mathbf{x} \)). Select the correct value.

    1. Compute \( z_2 = \mathbf{w}_2 \cdot \mathbf{x} \).
    EOT,
        'Solution:  
    1. \( z_2 = \mathbf{w}_2 \cdot \mathbf{x} = (-0.3) \cdot 1 + 0.4 \cdot (-1) = -0.3 - 0.4 = -0.7 \).  
    Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.1",
            "B" => "-0.7",
            "C" => "-0.2",
            "D" => "0.5"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;

}
function add_linear_regression_exercises_to_the_lesson($lesson_number){
    $module_two_term = get_term_by( 'slug', 'module-two', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
}
function exercise_module_two_plugin_activate() {

    add_probability_distributions_exercises_to_the_lesson(1);
    add_exponential_family_exercises_to_the_lesson(2);
    add_glm_exercises_to_the_lesson(3);
    add_repetition_exercises_to_the_lesson(4);
    add_binomial_distribution_exercises_to_the_lesson(5);
    add_multinomial_distribution_exercises_to_the_lesson(6);
    add_linear_regression_exercises_to_the_lesson(7);
}
register_activation_hook( __FILE__, 'exercise_module_two_plugin_activate' );
?>