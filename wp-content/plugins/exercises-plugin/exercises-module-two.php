<?php

function add_probability_distributions_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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
    - \( 1 - p \) is the probability of failure (i.e., \( X = 0 \)).<br>
        <br>
    <strong>Example:</strong> <br>
    Using the parameters from our scenario:<br>
    - \( P(X = 1) = 0.2 \)<br>
    - \( P(X = 0) = 0.8 \)<br>
        <br>
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
    Suppose a user has a 20% chance of clicking a button on any page they visit. <span class="tooltip">They visit 3 pages independently.<span class="tooltip-text">Told ya, that's the sacred incantation right there: independent and identically distributed. Or as the Neuronite monks chant it during the Midnight Sampling Ritual: "i.i.d., i.i.d., I summon thee, O Central Limit Theorem!”. Each visit, each click, each little stochastic decision dances alone and yet follows the same mysterious distribution scroll. No collusion, no memory, no hidden whispers between trials. Just pure, unadulterated statistical solitude — identical in law, independent in fate. Like triplets raised in separate libraries.</span></span><br>
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
    $exercise_number++;

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Deriving the Poisson from Binomial',
        <<<EOT
    The <span class="tooltip"><strong>Poisson distribution</strong><span class="tooltip-text">It's the cool-headed cousin of the Binomial distribution, perfect for modeling rare events over time or space. Think radioactive decays, incoming emails, or goats crossing a bridge — all happening independently, without a fixed number of trials.</span></span> emerges as a limit of the Binomial distribution when:<br><br>

    - The number of trials \( n \\to \infty \),<br>
    - The probability of success \( p \\to 0 \),<br>
    - But the expected number of successes \( \lambda = np \) stays constant.<br><br>

    In that case, we get the Poisson distribution with parameter \( \lambda \), and the PMF becomes:

    \[
    P(X = k) = \\frac{e^{-\lambda} \lambda^k}{k!}
    \]

    This makes the Poisson a great model when we’re counting how many rare events occur in a fixed interval of time or space.

    <strong>Question:</strong><br>
    Which of the following scenarios would best be modeled by a Poisson distribution?
    EOT,
        'Solution: A – The Poisson distribution models counts of rare, independent events in a fixed time/space — like customer arrivals.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "The number of customers arriving at a store in an hour.",
            "B" => "The number of times a coin lands on heads in 10 flips.",
            "C" => "The height of students in a classroom.",
            "D" => "The probability of rain tomorrow."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "A"
        ]),
        $exercise_number
    );
    $exercise_number++;

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Applying the Poisson Distribution',
        <<<EOT
    The <strong>Poisson distribution</strong> is defined by a single parameter \( \lambda \), which represents the average number of events in a given interval (time, space, etc.).<br><br>

    Its PMF is given by:

    \[
    P(X = k) = \\frac{e^{-\lambda} \lambda^k}{k!}
    \]

    Suppose the number of emails you receive per hour follows a Poisson distribution with \( \lambda = 2 \).

    <strong>Question:</strong><br>
    What is the probability that you receive exactly 3 emails in an hour?

    EOT,
        'Solution: D – \( P(X = 3) = \\frac{e^{-2} \cdot 8}{6} = \\frac{8e^{-2}}{6} \approx 0.180 \)',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "0.270",
            "B" => "0.220",
            "C" => "0.090",
            "D" => "0.180"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "D"
        ]),
        $exercise_number
    );
    $exercise_number++;

}

function add_exponential_family_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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
    EOT,
        'Solution: Taking the natural logarithm of both sides of the equation from step 2, \( e^{A(\\theta)} = \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \), we get \( A(\\theta) = \\ln \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \). Answer: B.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "\( A(\\theta) = \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \)",
            "B" => "\( A(\\theta) = \\ln \\int h(y) \\exp\\left(\\eta(\\theta) \\cdot T(y)\\right) \, dy \)",
            "C" => "\( A(\\theta) = \\exp\\left(\\int h(y) \\eta(\\theta) \\cdot T(y) \, dy\\right) \) ",
            "D" => "\( A(\\theta) = h(y) \\ln\\left(\\eta(\\theta) \\cdot T(y)\\right) \)"
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
            "B" => "Transforms natural parameter",
            "C" => "Computes base measure",
            "D" => "Ensures normalization",
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "D"]),
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
        <br>
    1. Identify \( \\eta(p) \) from the exponential form.<br>
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
        'Solution: The natural parameter \(\\eta(\theta) \) determines the shape or behavior of the distribution of \( y \) by weighting the sufficient statistic \( T(y) \) in the exponent, influencing the probability density or mass function. For example, in a Bernoulli distribution, \(\\eta = \ln\left(\frac{p}{1 - p}\right) \) controls the probability of success. Answer: B.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "It controls the shape or behavior of the distribution of \( y \).",
            "B" => "It normalizes the distribution to ensure it sums to 1.",
            "C" => "It defines the base measure of the distribution.",
            "D" => "It computes the sufficient statistic for \( y \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "A"]),
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

    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Natural Parameter in a Binary Classifier',
        <<<EOT
    To understand why transforming to exponential's family form matters, let’s take a quick look into the future.

    Imagine you're building a 
    <span class="tooltip">
    neural network binary classifier
    <span class="tooltip-text">
        Ah, the neural network binary classifier — the enchanted golem of modern computation. Born of algebraic incantations and trained on offerings of data, it peers into the void of inputs and dares to answer: “Yes” or “No.” Or, more precisely, “0.973, probably yes.”

        Don’t let its layers fool you. This is no accident of wires and hope. Hidden beneath the surface lies a pact with probability — a sigmoid twist of fate that transforms cold numbers into decisions.

        In ancient scrolls (and recent arXiv papers), it is whispered: this creature learns by minimizing loss, backpropagating regret, and converging on the truth (or something adjacent).

        But take heed: feed it junk, and it will hallucinate logic. Worship its output blindly, and you'll find yourself knee-deep in false positives wondering where it all went wrong. Tread wisely, O builder of classifiers.
    </span>
    </span>. It takes real-valued inputs, passes them through several layers, and ultimately produces a prediction. The network takes real-valued inputs, processes them through layers, and finally produces a prediction.

    Below is a simplified diagram of the process:

    <div class="exercise-image">
        <img src="https://mldenizen.com/wp-content/uploads/2025/07/logistic_regression.png" alt="Neural network prediction pipeline: input → linear output z → sigmoid(z) → predicted probability" style="max-width: 100%;">
    </div>
    <p>
        {blank1}<span style="display:inline-block; width:1px;"></span>
        {blank2}<span style="display:inline-block; width:20px;"></span>
        {blank3}<span style="display:inline-block; width:40px;"></span>
        {blank4}
    </p>
    <br>    
    Match each label below to the correct part of the diagram.

    EOT,
        'Solution: The linear output \( z \) is the natural parameter (logit); the sigmoid reverses it to produce a probability, which is the mean parameter.',
        'medium',
        'drag_and_drop',
        json_encode([
            "A" => "z = Natural Parameter (Logit).",
            "B" => "sigmoid(z) = Probability (Inverse of Natural Parameter).",
            "C" => "Input Features.",
            "D" => "Neural Network Layers."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "C",
            "2" => "D",
            "3" => "A",
            "4" => "B"
        ]),
        $exercise_number
    );
    $exercise_number++;


}
function add_glm_exercises_to_the_lesson($lesson_number){

    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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
    <strong>Generalized Linear Models (GLMs)</strong> extend the exponential family to incorporate <span class="tooltip">covariates <span class="tooltip-text">A covariate, dear traveler of the Data Plains, is one of those mysterious creatures that quietly influences your outcome variable while pretending it’s just “passing through.” It’s the input — the X to your Y, the question to your answer, the weather to your umbrella sales. It doesn’t shout. It doesn’t demand attention. But without it, your precious prediction would be floating in statistical space like a lost sock in a cosmic dryer.

    Think of covariates as the party guests who bring all the drama but never appear on the invitation. They shape the night, alter the mood, and leave you wondering why everyone’s crying over the guacamole.

    You don’t have to understand them fully just yet — many a Neuronite spent entire epochs mistaking them for noise or furniture.

    But heed this warning: random covariate shift is coming. It looms just offstage, wrapped in distribution drift and armed with a sinister smile. When it arrives, your model — so carefully trained, so sure of itself — will suddenly forget how to tie its own shoelaces.

    So mark the word: covariate. Whisper it into your loss function. Write it in the margin of your notes. One day soon, you'll need it. Probably right after deployment.</span></span>, modeling the relationship between a response variable \( y \) and predictors \( \mathbf{x} \). A GLM consists of three components:<br>
    1. <strong>Random Component</strong>: The response \( y \) follows a distribution from the exponential family (e.g., Gaussian, Bernoulli, Poisson).<br>
    2. <strong>Systematic Component</strong>: A linear predictor \( \\eta = \mathbf{w} \cdot \mathbf{x} \), where \( \mathbf{w} \) are weights and \( \mathbf{x} \) are covariates.<br>
    3. <strong>Link Function</strong>: A function \( g \) such that \( g(\mu) = \\eta \), where \( \mu = E[y] \) is the expected value of the response.<br>
        <br>
    Key properties of GLMs include:<br>
    - They use the exponential family to model various response distributions.<br>
    - The link function connects the linear predictor to the expected response.<br>
    - The log-likelihood is concave, allowing efficient maximum likelihood estimation (MLE) via iterative methods like Newton-Raphson.<br>
        <br>
    <strong>Question:</strong><br>
    Which of the following are true properties of GLMs? Select all that apply.<br>

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
    $result = add_exercise(
    $lesson_id,
    $category_id,
    'Exercise ' . $exercise_number . ' – From Exponential Family to GLM',
    <<<EOT
    Suppose the conditional distribution of the target variable is from the <strong>exponential family</strong>:

    \[
    p(y \mid \\theta) = h(y) \\exp(\\eta \cdot T(y) - A(\\eta))
    \]

    We now define the following assumptions for a Generalized Linear Model (GLM):<br>
    1. The input \( \mathbf{x} \in \mathbb{R}^n \).<br>
    2. The natural parameter \(\\eta \) is a linear function of the input: \(\\eta = \mathbf{x}^\\top \boldsymbol{w} \).<br>
    3. The model output at test time is the expected value \( \mu = \mathbb{E}[y \mid \mathbf{x}] \).<br>
    <br>
    Given this, the link function connects the expectation to the linear predictor:  
    \[
    g(\mu) =\\eta = \mathbf{x}^\\top \boldsymbol{w}
    \]
    and the <strong>hypothesis function</strong> is:
    \[
    h(\mathbf{x}) = \mathbb{E}[y \mid \mathbf{x}]
    \]
    <br>
    <strong>Question:</strong><br>
    Which of the following are assumptions or structural components of GLMs based on the exponential family framework?

    EOT,
        'Solution: <br>
    A: True — The conditional distribution \( y \mid \mathbf{x} \) is from the exponential family.<br>
    B: True — The natural parameter \(\\eta \) is modeled as a linear function of input \( \mathbf{x} \).<br>
    C: True — The output of the model is the expected value \( \mu \), making the hypothesis function \( h(\mathbf{x}) = \mathbb{E}[y \mid \mathbf{x}] \).<br>
    D: False — The natural parameter is not necessarily equal to the expected value; rather, \( \mu \) is obtained via a link function \( g(\mu) =\\eta \).<br>
    Correct answers: A, B, C.',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The conditional distribution \( y \mid \mathbf{x} \) is in the exponential family.",
            "B" => "The natural parameter is a linear function of input: \(\\eta = \mathbf{x}^\\top \boldsymbol{w} \).",
            "C" => "The hypothesis function returns the expected value: \( h(\mathbf{x}) = \mathbb{E}[y \mid \mathbf{x}] \).",
            "D" => "The natural parameter equals the expected value: \(\\eta = \mathbb{E}[y \mid \mathbf{x}] \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "C"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Model vs. Distribution in GLMs',
        <<<EOT
    In a Generalized Linear Model (GLM), it is important to distinguish between the <strong>model</strong> and the <strong>distribution</strong>:<br><br>

    - The <strong>model</strong> defines how we compute the natural parameter \(\\eta \) from the input \( \mathbf{x} \). Usually:  
    \[
        \\eta = \mathbf{x}^\\top \boldsymbol{w}
    \]<br>

    - The <strong>distribution</strong> specifies how the target variable \( y \) is generated, given \(\\eta \), and must belong to the <strong>exponential family</strong>.  
    That is:  
    \[
        p(y \mid\\eta) = h(y) \\exp(\\eta \cdot T(y) - A(\\eta))
    \]<br><br>

    <strong>Key relationship:</strong>  
    The <strong>output of the model</strong> (i.e. \(\\eta \)) becomes the <strong>parameter of the distribution</strong>.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following correctly describe the distinction between the model and the distribution in a GLM?

    EOT,
        "Solution:<br>
    A: True — The model maps input \( \mathbf{x} \) to the natural parameter \(\\eta \).<br>
    B: True — The distribution describes how \( y \) is generated given \(\\eta \), and belongs to the exponential family.<br>
    C: False — \(\\eta \) is the output of the model, not the observed variable.<br>
    D: True — The distribution's natural parameter is determined by the model's output.<br>
    Correct answers: A, B, D.",
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The model defines how \(\\eta \) is computed from \( \mathbf{x} \).",
            "B" => "The distribution defines how \( y \) is generated, given \(\\eta \).",
            "C" => "The natural parameter \(\\eta \) is the observed output.",
            "D" => "The model produces \(\\eta \), which serves as the parameter for the distribution."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "D"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – What Does Training a GLM Actually Learn?',
        <<<EOT
    In a Generalized Linear Model (GLM), we assume that the conditional distribution of \( y \mid \mathbf{x} \) belongs to the exponential family, with the natural parameter \(\\eta = \mathbf{x}^\\top \boldsymbol{w} \).<br><br>

    During training, we are given a dataset of input-output pairs \( (\mathbf{x}^{(i)}, y^{(i)}) \), and we aim to choose model parameters \( \boldsymbol{w} \) that best explain the data.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following statements are correct?

    EOT,
        'Solution:<br>
    A: False — We do not learn the distribution parameters directly; \(\\eta \) is derived from \( \mathbf{x} \) and \( \boldsymbol{w} \).<br>
    B: True — The parameters we optimize are the weights \( \boldsymbol{w} \) in the linear model.<br>
    C: True — The natural parameter \(\\eta \) depends on \( \boldsymbol{w} \), which we learn.<br>
    D: False — We do not estimate \(\\eta \) independently of \( \mathbf{x} \); it is computed via the model.<br>
    Correct answers: B, C',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "We learn the parameters of the distribution directly, such as \(\\eta \).",
            "B" => "We optimize the model weights \( \boldsymbol{w} \), not the distribution parameters.",
            "C" => "The natural parameter \(\\eta \) depends on the model weights \( \boldsymbol{w} \).",
            "D" => "We estimate \(\\eta \) directly without using \( \mathbf{x} \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["B", "C"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – How Do We Train a GLM?',
        <<<EOT
    In Generalized Linear Models (GLMs), we train the model by estimating parameters \( \boldsymbol{w} \) that maximize the likelihood of the observed data.<br><br>

    Given data pairs \( (\mathbf{x}^{(i)}, y^{(i)}) \), we assume that the response \( y^{(i)} \) follows an exponential family distribution whose natural parameter \(\\eta^{(i)} = \mathbf{x}^{(i)\\top} \boldsymbol{w} \).<br><br>

    The model is trained using:

    \[
    \boldsymbol{w}^* = \arg\max_{\boldsymbol{w}} \sum_{i=1}^{n} \log p(y^{(i)} \mid \mathbf{x}^{(i)}; \boldsymbol{w})
    \]

    This expression is often differentiable.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following statements about GLM training are true?

    EOT,
        'Solution:<br>
    A: True — Training a GLM means maximizing the likelihood of the observed data.<br>
    B: False — We do not use closed-form least squares; GLMs generalize beyond Gaussian.<br>
    C: True — Gradient ascent is commonly used to optimize the log-likelihood.<br>
    D: True — The objective function is the log-likelihood, derived from the exponential family.<br>
    Correct answers: A, C, D',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "GLMs are trained using maximum likelihood estimation.",
            "B" => "GLMs always use least squares to find a closed-form solution.",
            "C" => "GLMs are often optimized using gradient ascent on the log-likelihood.",
            "D" => "The training objective is the log-likelihood of the exponential family distribution."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "C", "D"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gradient Update Rule in GLM Training',
        <<<EOT
    In a Generalized Linear Model (GLM), we train the model by maximizing the log-likelihood using gradient-based optimization. For a single training example \( (\mathbf{x}, y) \), the gradient of the log-likelihood with respect to parameter \( \\theta_j \) is:

    \[
    \\frac{\partial \\ell}{\partial \\theta_j} = (y - \hat{y}) x_j
    \]

    where \( \hat{y} = h(\mathbf{x}) = \mathbb{E}[y \mid \mathbf{x}] \) is the model's predicted value.<br><br>

    Using gradient ascent with learning rate \( \alpha \). <br>

    This rule adjusts each parameter based on the input value \( x_j \), the learning rate, and the prediction error.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following correctly describes the learning update rule for parameter \( \\theta_j \) in GLM training?

    EOT,
        'Solution:<br>
    
    A: False — This is the Perceptron update rule (does not use \( \hat{y} \)).<br>
    B: True — This is the correct gradient ascent rule for log-likelihood in GLMs.<br>
    C: False — This form would move in the wrong direction (negative gradient).<br>
    D: False — Division by \( x_j \) is incorrect and has no basis in the MLE derivation.<br>
    Correct answer: B',
        'medium',
        'one_of_many',
        json_encode([
            
            "A" => "\( \\theta_j \leftarrow \\theta_j + \alpha y x_j \)",
            "B" => "\( \\theta_j \leftarrow \\theta_j + \alpha (y - \hat{y}) x_j \)",
            "C" => "\( \\theta_j \leftarrow \\theta_j - \alpha (y - \hat{y}) x_j \)",
            "D" => "\( \\theta_j \leftarrow \\theta_j + \alpha\\frac{(y - \hat{y})}{x_j} \)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "B"]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – From Stochastic to Batch Gradient Ascent',
        <<<EOT
    In Generalized Linear Models (GLMs), we often use <strong>gradient ascent</strong> to maximize the log-likelihood. For a single training example \( (\mathbf{x}^{(i)}, y^{(i)}) \), the parameter update rule for \( \\theta_j \) is:

    \[
    \\theta_j \leftarrow \\theta_j + \alpha (y^{(i)} - \hat{y}^{(i)}) x^{(i)}_j
    \]

    where \( \hat{y}^{(i)} \) is the predicted value for the \( i \)-th input.

    <br><br>
    In <strong>batch gradient ascent</strong>, instead of updating using a single example, we compute the gradient over the entire dataset of \( n \) examples. <br>

    This provides a smoother and more stable update by averaging over the dataset.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following correctly represents the batch gradient update rule for parameter \( \\theta_j \) in GLMs?

    EOT,
        'Solution:<br>
    A: True — This is the correct batch gradient ascent rule.<br>
    B: False — This omits the error term \( y - \hat{y} \).<br>
    C: False — Averaging instead of summing is optional, but this form drops the learning rate.<br>
    D: False — Subtracting the sum of gradients is gradient descent, not ascent.<br>
    Correct answer: A',
        'medium',
        'one_of_many',
        json_encode([   
            "A" => "\( \\theta_j \leftarrow \\theta_j + \alpha \sum_{i=1}^{n} x^{(i)}_j \)",
            "B" => "\( \\theta_j \leftarrow \sum_{i=1}^{n} (y^{(i)} - \hat{y}^{(i)}) x^{(i)}_j \)",
            "C" => "\( \\theta_j \leftarrow \\theta_j - \alpha \sum_{i=1}^{n} (y^{(i)} - \hat{y}^{(i)}) x^{(i)}_j \)",
            "D" => "\( \\theta_j \leftarrow \\theta_j + \alpha \sum_{i=1}^{n} (y^{(i)} - \hat{y}^{(i)}) x^{(i)}_j \)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "D"]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Terminology in Generalized Linear Models',
        <<<EOT
    In the context of Generalized Linear Models (GLMs), several key terms describe the structure inherited from the exponential family:

    <br><br>
    - <strong>Natural parameter</strong> \(\\eta \): The parameter that appears linearly in the exponent of the exponential family distribution. In GLMs, \(\\eta = \mathbf{x}^\\top \boldsymbol{w} \).
    <br>
    - <strong>Canonical link function</strong>: A link function \( g \) that satisfies \( g(\mu) =\\eta \). When this link function matches the natural parameter directly, it's called canonical.
    <br>
    - <strong>Canonical response</strong>: The expected value \( \mu = \mathbb{E}[y] \), which is related to the natural parameter.

    This gradient defines the <strong>canonical response</strong>.

    <br><br>
    <strong>Question:</strong><br>
    Which of the following statements are true?

    EOT,
        'Solution:<br>
    A: True — The natural parameter appears linearly in the exponential family form.<br>
    B: True — The canonical link maps \( \mu \) to \(\\eta \), often with \( g(\mu) = \log\left(\\frac{\mu}{1 - \mu} \\right) \) for Bernoulli.<br>
    C: True — The canonical response is \( \mu =\\frac{dA(\\eta)}{d\eta} \), the gradient of the log-partition function.<br>
    D: False — The natural parameter \(\\eta \) is computed from input, not equal to the expected value \( \mu \).<br>
    Correct answers: A, B, C',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The natural parameter appears linearly in the exponential family distribution.",
            "B" => "The canonical link function relates the expected value \( \mu \) to \(\\eta \).",
            "C" => "The canonical response \( \mu \) is the gradient of the log-partition function: \( \mu = \\frac{dA(\\eta)}{d\eta} \).",
            "D" => "The natural parameter \(\\eta \) equals the expected value \( \mu \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "C"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Parametrizations in GLMs',
        <<<EOT
    In Generalized Linear Models (GLMs), there are three different kinds of parameters involved, depending on the level of abstraction:

    <br><br>
    1. <strong>Model parameters</strong> \( \boldsymbol{\\theta} \): These are the parameters of the linear predictor \(\\eta = \mathbf{x}^\\top \boldsymbol{\\theta} \). These are what we learn during training.
    <br><br>
    2. <strong>Natural parameter</strong> \(\\eta \): This is the parameter used in the exponential family form of the distribution:
    \[
    p(y) = h(y) \\exp(\\eta \cdot T(y) - A(\\eta))
    \]
    The natural parameter \(\\eta \) is produced by the model but not learned directly.
    <br><br>
    3. <strong>Canonical parameters</strong>: These refer to standard parameters used to describe common distributions — e.g.:
    <ul>
    <li>\( \phi \) for Bernoulli (probability of success)</li>
    <li>\( \mu \) and \( \sigma \) for Gaussian (mean and standard deviation)</li>
    </ul>

    <br>
    GLMs define a mapping:
    \[
    \mathbf{x} \xrightarrow{\\text{model}}\\eta \xrightarrow{\\text{link function}} \mu
    \]

    <br>
    <strong>Question:</strong><br>
    Which of the following statements are true?

    EOT,
        'Solution:<br>
    A: True — Model parameters \( \\theta \) determine \(\\eta \) through \(\\eta = \mathbf{x}^\\top \boldsymbol{\\theta} \).<br>
    B: True — The model outputs the natural parameter \(\\eta \), which is then used by the distribution.<br>
    C: False — We do not directly optimize the canonical parameters like \( \phi \) or \( \mu \); they’re derived via link functions.<br>
    D: True — The only parameters learned during training are the model parameters \( \boldsymbol{\\theta} \).<br>
    Correct answers: A, B, D',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The model parameters \( \boldsymbol{\\theta} \) determine the natural parameter \(\\eta \).",
            "B" => "The natural parameter \(\\eta \) is the output of the linear model.",
            "C" => "We directly learn canonical parameters like \( \mu \) and \( \phi \).",
            "D" => "The only parameters learned during training are the linear model weights \( \boldsymbol{\\theta} \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "D"]]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Connections Between Model, Natural, and Canonical Parameters',
        <<<EOT
    In a Generalized Linear Model (GLM), three types of parameters are involved:

    <br><br>
    - <strong>Model parameters</strong> \( \boldsymbol{\\theta} \): The weights of the linear model.<br>
    - <strong>Natural parameter</strong> \(\\eta \): The parameter in the exponential family form.<br>
    - <strong>Canonical parameter</strong> (e.g. \( \mu \), \( \phi \)): The standard parameter of the distribution, such as the mean or probability.

    <br><br>
    The relationships between these parameters are:

    <ol>
    <li><strong>From model to natural parameter:</strong>  
    \[
    \\eta = \mathbf{x}^\\top \boldsymbol{\\theta}
    \]
    This is a linear transformation.</li>
    
    <li><strong>From natural to canonical parameter:</strong>  
    \[
    \mu =\\frac{dA(\\eta)}{d\eta}
    \]
    This gives the canonical response (expected value). In the canonical link case:
    \[
    \\eta = g(\mu)
    \]</li>
    </ol>

    <br>
    <strong>Question:</strong><br>
    Which of the following describe correct relationships between parameters in a GLM?

    EOT,
        'Solution:<br>
    A: True — The natural parameter is a linear function of the input and model weights.<br>
    B: True — The expected value (canonical response) is given by \( \mu = A(\\eta) \).<br>
    C: False — We do not compute \( \mu \) directly from \( \mathbf{x} \); we go through \(\\eta \).<br>
    D: True — The canonical link function connects \( \mu \) and \(\\eta \).<br>
    Correct answers: A, B, D',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The natural parameter \(\\eta \) is computed as \( \mathbf{x}^\\top \boldsymbol{\\theta} \).",
            "B" => "The canonical response \( \mu \) is the derivative of the log-partition function: \( \mu = \\frac{dA}{d\eta} \).",
            "C" => "We compute \( \mu \) directly from \( \mathbf{x} \), skipping \(\\eta \).",
            "D" => "The canonical link function connects the canonical response \( \mu \) and natural parameter \(\\eta \)."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "B", "D"]]),
        $exercise_number
    );
    $exercise_number++;
    // $result = add_exercise(
    //     $lesson_id,
    //     $category_id,
    //     'Exercise ' . $exercise_number . ' – Why Is Logistic Regression Still Called Regression?',
    //     <<<EOT
    // In traditional machine learning terms:<br>
    // - <strong>Regression</strong> means predicting a continuous output.<br>
    // - <strong>Classification</strong> means predicting discrete categories.<br>
    //     <br>
    // Yet in statistics, both <strong>linear regression</strong> and <strong>logistic regression</strong> are called <strong>regression models</strong> — even though logistic regression is used for classification.<br>

    // <br><br>
    // <strong>Why?</strong><br>
    // Because in Generalized Linear Models (GLMs), “regression” refers not to the output type, but to the structure of the model:<br>
    // - Both models use a <strong>linear function of inputs</strong>: \(\\eta = \mathbf{x}^\\top \boldsymbol{\\theta} \)<br>
    // - Both models use that \(\\eta \) to predict the <strong>expected value</strong> of \( y \), possibly through a <strong>link function</strong>.<br>
    //     <br>
    // For example:<br>
    // <ul>
    // <li>Linear regression: \( \mu =\\eta \)</li>
    // <li>Logistic regression: \( \mu = \sigma(\\eta) =\\frac{1}{1 + e^{-\\eta}} \)</li>
    // </ul>

    // In both cases, the <strong>structure is a regression on the expected value</strong> — even if the final prediction is categorical.

    // <br><br>
    // <strong>Question:</strong><br>
    // Why is logistic regression still called "regression"?

    // EOT,
    //     'Solution:<br>
    // A: True — Logistic regression uses a linear model to predict the expected value (probability), just like linear regression.<br>
    // B: False — Logistic regression is not misnamed; it follows the GLM definition of regression.<br>
    // C: False — It is not called regression because of a mistake; it’s deliberate and structural.<br>
    // D: True — In GLMs, “regression” refers to the linear structure, not to the output type.<br>
    // Correct answers: A, D',
    //     'easy',
    //     'multiple_choice',
    //     json_encode([
    //         "A" => "Because logistic regression models a transformed expected value via a linear predictor.",
    //         "B" => "Because someone misnamed it and it stuck.",
    //         "C" => "Because it was originally used for continuous values.",
    //         "D" => "Because in GLMs, regression refers to modeling structure, not output type."
    //     ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    //     json_encode(["correct_options" => ["A", "D"]]),
    //     $exercise_number
    // );
    // $exercise_number++;

}
function add_exponential_family_two_exercises_to_the_lesson($lesson_number){

    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }
    $exercise_number = 1;
    // Retrieve the ID of the first lesson for this category.
    $lesson_id = get_lesson_for_category( $category_id, $lesson_number );
    
    

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
    What is the sufficient statistic \( T(y) \) for the Gaussian distribution in the exponential family form?<br>

    EOT,
        'Solution: From the rewritten PDF, the exponent is \( \\frac{\mu y}{\sigma^2} - \\frac{\mu^2}{2\sigma^2} \). The term involving \( y \) is \( \\frac{\mu y}{\sigma^2} \), so the sufficient statistic \( T(y) = y \). Answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "\( y^2 \)",
            "B" => "\( [y, y^2] \)",
            "C" => "\( y \)",
            "D" => "\( \\frac{y}{\sigma^2} \)"
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

    


}

function add_repetition_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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
    In neural network models, neurons process input features to produce an output based on a weighted sum and an activation function. Some neurons are designed to accept <strong>binary inputs</strong>, where each input feature \( x_i \in \{0, 1\}\). Consider the following neuron types:<br>
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
        <br>
    Consider the following scenarios:<br>
    - <strong>Scenario A</strong>: A single-layer perceptron for binary classification (e.g., AND gate).<br>
    - <strong>Scenario B</strong>: A neural network output layer for multiclass classification (e.g., digit recognition).<br>
    - <strong>Scenario C</strong>: A hidden layer in a modern neural network.<br>
        <br>
    <strong>Question:</strong><br>
    Which scenarios correctly match the activation function to the neuron type? Select all that apply.<br>

    EOT,
        'Solution:  <br>
    - Scenario A: A single-layer perceptron for binary classification typically uses a threshold function (A is true).  <br>
    - Scenario B: Multiclass classification uses softmax to output probabilities (B is true).  <br>
    - Scenario C: Hidden layers typically use functions like ReLU or sigmoid, not threshold (C is false).  <br>
    - Scenario D: Scenario B does not use threshold (D is false).  <br>
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
    // Exercise 4: Geometric Change After Misclassifying a Positive Example
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Geometric Effect of Misclassification',
        <<<EOT
    In the perceptron algorithm, when a <strong>positive input</strong> (true label \( y = 1 \)) is misclassified as negative (\( \hat{y} = 0 \)), the weight vector is updated using:<br>
    <br>
    \[
    \mathbf{w} \leftarrow \mathbf{w} + \\eta \mathbf{x}
    \]<br>
    <br>
    This update changes the direction of the weight vector.<br>
    <br>
    <strong>Geometric interpretation:</strong> <br>
    <br>
    <img src="https://mldenizen.com/wp-content/uploads/2025/07/miscalssification.png" alt="Misclasiification of positive input" style="max-width:100%; height:auto; border:1px solid #ccc; padding:5px;"><br>
    <br>
    <strong>Question:</strong><br>
    After this update, does the weight vector become more aligned (i.e., the angle between \( \mathbf{w} \) and \( \mathbf{x} \) decreases), or less aligned with the input vector \( \mathbf{x} \)?
    EOT,
        'Solution:  
    Since the weight vector is updated by adding the input vector \( \mathbf{x} \), the new weight vector points more in the direction of \( \mathbf{x} \).<br>
    This reduces the angle between them, making them more aligned.<br>
    <strong>Answer: It becomes more aligned.</strong>
    <br>
    <img src="https://mldenizen.com/wp-content/uploads/2025/07/updated_decision_boundary.png" alt="Update makes weight vector more aligned" style="max-width:100%; height:auto; border:1px solid #ccc; padding:5px;"><br>
    <br>',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "Less aligned with the input vector",
            "B" => "Unchanged in direction",
            "C" => "More aligned with the input vector",
            "D" => "Orthogonal to the input vector"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "correct_option" => "C"
        ]),
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
        null,
        json_encode([
            "correct_options" => ["\(w_0\)" => "0",
            "\(w_1\)" => "1"]
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
    The <strong>binomial distribution</strong> models the number of successes in \( n \) independent trials, each with success probability \( p \). The probability mass function is:

    \[
    P(X = k) = \binom{n}{k} p^k (1 - p)^{n - k}
    \]

    where \( \binom{n}{k} = \\frac{n!}{k!(n - k)!} \).<br>
        <br>
    Consider a scenario with \( n = 5 \) trials and success probability \( p = 0.4 \).<br>
        <br>
    <strong>Question:</strong><br>
    Compute the probability of exactly 3 successes, i.e., \( P(X = 3) \). Round to 3 decimal places.<br>
        <br>
    1. Calculate the binomial coefficient \( \binom{5}{3} \).  <br>
    2. Compute \( p^k (1 - p)^{n - k} \).  <br>
    3. Combine to find \( P(X = 3) \).<br>
    EOT,
        'Solution:  
    1. \( \binom{5}{3} =\\frac{5!}{3!2!} =\\frac{120}{6 \cdot 2} = 10 \).  
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
    Training a perceptron involves updating the weight vector \( \mathbf{w} \) and bias \( b \) to correctly classify inputs. The process includes:<br>
    - Computing the weighted sum \( z = \mathbf{w} \cdot \mathbf{x} + b \).<br>
    - Applying an activation function (e.g., threshold).<br>
    - Updating weights based on the error between predicted and true labels.<br>
        <br>
    Match each component of the perceptron training process to its description by dragging the terms to the correct boxes.<br>
        <br>
    - <strong>Weighted Sum</strong>         {blank1}<br>
    - <strong>Activation Function</strong>  {blank2}<br>
    - <strong>Error Calculation</strong>    {blank3}<br>
    - <strong>Weight Update</strong>        {blank4}<br>


    EOT,
        'Solution:  <br>
        - Weighted Sum: Calculates \( \mathbf{w} \cdot \mathbf{x} + b \).  <br>
        - Activation Function: Applies a function like threshold or sigmoid.  <br>
        - Error Calculation: Computes \( error = y - \hat{y} \).  <br>
        - Weight Update: Adjusts \( \mathbf{w} \) using the update rule \( \mathbf{w} \leftarrow \mathbf{w} +\\eta (error) \mathbf{x} \).',
        'easy',
        'drag_and_drop',
        json_encode([
            "A" => "Adjusts \( \mathbf{w} \) using the update rule \( \mathbf{w} \leftarrow \mathbf{w} + \\eta (error) \mathbf{x} \)",
            "B" => "Applies a function like threshold or sigmoid to the weighted sum.",
            "C" => "Calculates \( \mathbf{w} \cdot \mathbf{x} + b \).",
            "D" => "Computes \( error = y - \hat{y} \) to determine the prediction error."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "C",
            "2" => "B",
            "3" => "D",
            "4" => "A"
        ]),
        $exercise_number
    );
    $exercise_number++;

}
function add_bernoulli_distribution_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
        $category_id = 0;
    }

    $exercise_number = 1;
    $lesson_id = get_lesson_for_category($category_id, $lesson_number);

    // Exercise 1: Bernoulli Definition
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Bernoulli Distribution Definition',
        <<<EOT
    The <strong>Bernoulli distribution</strong> is a special case of the binomial distribution with a single trial (\( n = 1 \)).<br><br>
    <strong>Question:</strong><br>
    What values can a Bernoulli random variable take, and what is its probability mass function (PMF)?<br><br>

    EOT,
        'Solution:  
    Correct PMF is \( P(Y = y) = p^y (1 - p)^{1 - y} \) for \( y \in \\{0, 1\\} \).  
    Binomial coefficient for \( n = 1 \) is 1, so it simplifies to that form.  
    Answer: A.',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "Values 0 or 1; \( P(Y = y) = \binom{1}{x} p^y (1 - p)^{1 - y} \)",
            "B" => "Values 0 or 1; \( P(Y = y) = py \)",
            "C" => "Values 0 or 1; \( P(Y = y) = p^y (1 - p)^{1 - y} \)",
            "D" => "Values 0 or 1; \( P(Y = y) = (1 - p)^y \)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 2: Bernoulli Moments
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Expected Value and Variance of Bernoulli',
        <<<EOT
    A Bernoulli-distributed random variable \( Y \sim \\text{Bernoulli}(p) \) has:<br>
    - <strong>Expected Value</strong>: \( E[Y] = p \)<br>
    - <strong>Variance</strong>: \( \\text{Var}(Y) = p(1 - p) \)<br><br>
    <strong>Question:</strong><br>
    Compute the expected value and variance of a Bernoulli random variable with \( p = 0.7 \).<br>
    Provide both values.
    EOT,
        'Solution:<br>
    - Expected Value: \( E[Y] = 0.7 \)<br>
    - Variance: \( \\text{Var}(Y) = 0.7 \\cdot 0.3 = 0.21 \)',
        'easy',
        'labeled_inputs',
        null,
        json_encode([
            "correct_options" => ["expected_value" => "0.7", "variance" => "0.21"]
        ]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Bernoulli as Exponential Family
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Bernoulli in Exponential Family',
        <<<EOT
    The Bernoulli distribution is also part of the <strong>exponential family</strong> and can be written as:

    \[
    p(y | p) = p^y (1 - p)^{1 - y} = \\exp\\left( y \\ln\\left(\\frac{p}{1 - p}\\right) + \\ln(1 - p) \\right)
    \]

    <strong>Question:</strong><br>
    What are the components of the exponential family form for Bernoulli?<br>
    Match the following:<br><br>
    - \( T(y) \): {blank1}<br>
    - \( \\eta(p) \): {blank2}<br>
    - \( A(p) \): {blank3}<br>
    - \( h(y) \): {blank4}<br>
    EOT,
        'Solution: <br>
    - \( T(y) = y \) (sufficient statistic)  <br>
    - \( \\eta(p) = \\ln\\left(\\frac{p}{1 - p}\\right) \) (natural parameter)  <br>
    - \( A(p) = -\\ln(1 - p) \) (log-partition)  <br>
    - \( h(y) = 1 \) (base measure)',
        'medium',
        'drag_and_drop',
        json_encode([
            "A" => "y",
            "B" => "ln(p/(1 - p))",
            "C" => "-ln(1 - p)",
            "D" => "1"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "A",
            "2" => "B",
            "3" => "C",
            "4" => "D"
        ]),
        $exercise_number
    );
    $exercise_number++;
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Logit and Sigmoid from Bernoulli',
        <<<EOT
        The <strong>Bernoulli distribution</strong> can be expressed in exponential family form:

        \[
        p(x) = \\exp\\left(x \cdot \\eta - A(\\eta)\\right)
        \]

        where the natural parameter \( \\eta \) is related to the success probability \( p \) by:

        \[
        \\eta = \ln\\left(\\frac{p}{1 - p}\\right)
        \]

        This expression defines the <strong>canonical link function</strong> for the Bernoulli distribution — it maps the mean \( p \) to the natural parameter \( \\eta \).<br><br>

        Solve this equation for \( p \). The resulting expression is called the <strong>canonical response function</strong>.<br><br>

        <strong>Question:</strong><br>
        Which of the following expressions correctly represents the <strong>canonical response function</strong> for the Bernoulli distribution?
    EOT,
        'Solution:<br>
        Starting from the canonical link function:<br>
        \[
        \\eta = \\ln\\left(\\frac{p}{1 - p}\\right)
        \]<br>
        Solving for \( p \):<br>
        \[
        p = \\frac{1}{1 + e^{-\\eta}}
        \]<br>
        This is the <strong>canonical response function</strong> for the Bernoulli distribution — also known as the <strong>logistic (sigmoid)</strong> function.<br><br>
        Correct answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "\(p = \\ln(1 + e^{-\\eta})\)",
            "B" => "\(p = e^{-\\eta}\)",
            "C" => "\(p = \\frac{1}{1 + e^{-\\eta}}\)",
            "D" => "\(p = 1 - e^{-\\eta}\)",
            "E" => "\(p = \\frac{e^{-\\eta}}{1 + e^{-\\eta}}\)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "C"]),
        $exercise_number
    );
    $exercise_number++;


    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Canonical Link Function for Bernoulli',
        <<<EOT
        In a <strong>generalized linear model (GLM)</strong>, the link function connects the expected value \( \mu \) of the response variable to the linear predictor \( \\eta \).<br><br>

        For the Bernoulli distribution, the canonical link is the <strong>logit</strong> function:

        \[
        g(\mu) = \ln\left(\\frac{\mu}{1 - \mu}\\right)
        \]

        This function is the inverse of the <strong>logistic (sigmoid)</strong> function:

        \[
        \mu = \\sigma(\\eta) = \\frac{1}{1 + e^{-\\eta}}
        \]

        Hence, logistic regression arises from using the Bernoulli distribution with its canonical link function. The model predicts the <strong>log-odds</strong> of success as a linear combination of input features.

        <br><br>
        <strong>Question:</strong><br>
        Which of the following statements are true? Select all that apply.
        <br><br>
        A. The logit link function is the inverse of the sigmoid function.<br>
        B. Logistic regression uses the canonical link for the Gaussian distribution.<br>
        C. In logistic regression, \( \\eta = \\mathbf{w} \\cdot \\mathbf{x} \) represents the log-odds.<br>
        D. The expected value \( \mu \) is mapped from \( \\eta \) using the exponential function.<br>
        E. The canonical link function for Bernoulli is \( \ln\\left(\\frac{\mu}{1 - \mu}\\right) \).<br>
    EOT,
        'Solution:<br>
        - A is true: the logit is the inverse of sigmoid.<br>
        - B is false: logistic regression uses the Bernoulli distribution, not Gaussian.<br>
        - C is true: logistic regression models log-odds via a linear predictor.<br>
        - D is false: \( \mu = \\frac{1}{1 + e^{-\\eta}} \), not \( e^{\\eta} \).<br>
        - E is true: this is the canonical link for Bernoulli.<br><br>
        Correct answers: A, C, E.',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "The logit link function is the inverse of the sigmoid function.",
            "B" => "Logistic regression uses the canonical link for the Gaussian distribution.",
            "C" => "In logistic regression, η = w ⋅ x represents the log-odds.",
            "D" => "The expected value μ is mapped from η using the exponential function.",
            "E" => "The canonical link function for Bernoulli is ln(μ / (1 − μ))."
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "C", "E"]]),
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
            "\(\\eta\)" => "-0.1",
            "\(\mu\)" => "0.475"
        ]]),
        $exercise_number
    );
    $exercise_number++;
        // Exercise 5: Exponential Family for Logistic Regression
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Logistic Regression in Exponential Family',
        <<<EOT
    Logistic regression models the target variable \( y \in \{0, 1\} \) as a Bernoulli distribution, parameterized by \( p = \sigma(\mathbf{w} \cdot \mathbf{x}) \), where \( \sigma(z) =\\frac{1}{1 + e^{-z}} \). In the exponential family, the natural parameter \(\\eta \) relates to the linear predictor.
    <br>
    Given the canonical form of the Bernoulli distribution:<br>

    \[
    P(y | \\eta) = (1 - \sigma(\\eta)) \\exp(y \\eta)
    \]

    where \( \\eta = \ln\left(\\frac{p}{1 - p}\\right) \), and \( p = \sigma(\\eta) \).<br>
        <br>
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

function add_binomial_distribution_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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

    <strong>Question:</strong><br>  
    What is the natural parameter \( \\eta(p) \) for the binomial distribution when \( p = 0.6 \)? Round to 2 decimal places.<br>
        <br>
    1. Identify \( \\eta(p) \) from the exponential form.<br>
    2. Compute \( \\eta(p) \) for \( p = 0.6 \).<br>
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
    The binomial distribution, modeling a target variable with \( n \) trials and success probability \( p \), has the following moments:<br>
    - <strong>Expected Value</strong>: \( E[X] = np \)<br>
    - <strong>Variance</strong>: \( \\text{Var}(X) = np(1 - p) \)<br>
        <br>
    Consider a binomial random variable \( X \) with \( n = 10 \) and \( p = 0.2 \).<br>
        <br>
    <strong>Question:</strong><br>  
    Compute the expected value and variance of \( X \). Provide the answers as numerical values.<br>
        <br>
    1. Calculate \( E[X] = np \).<br>
    2. Calculate \( \\text{Var}(X) = np(1 - p) \).<br>
    EOT,
        'Solution:  <br>
    1. \( E[X] = np = 10 \cdot 0.2 = 2 \).  <br>
    2. \( \text{Var}(X) = np(1 - p) = 10 \cdot 0.2 \cdot (1 - 0.2) = 10 \cdot 0.2 \cdot 0.8 = 1.6 \).  <br>
    Answer: Expected Value = 2, Variance = 1.6.',
        'easy',
        'labeled_inputs',
        null,
        json_encode([
            "correct_options" => ["expected_value" => "2",
            "variance" => "1.6"]
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
    For a binomial distributed target variable \( Y \) with \( n \) trials and success probability \( p \), the probability mass function is:

    \[
    P(Y = y) = \binom{n}{k} p^y (1 - p)^{n - y}
    \]

    The <strong>log-likelihood function</strong> for a single observation \( y \) is:

    \[
    \\ell(p) = \ln\left(\binom{n}{k}\\right) + y \ln(p) + (n - y) \ln(1 - p)
    \]

    Consider \( n = 8 \) and an observed number of successes \( y = 3 \).<br>
        <br>
    <strong>Question:</strong><br>  
    Which of the following represents the correct log-likelihood function for this observation? Assume the binomial coefficient term is constant and focus on the variable-dependent terms.<br>
        <br>
    EOT,
        'Solution:  
    The log-likelihood is \( \\ell(p) = \ln\left(\binom{8}{3}\right) + 3 \ln(p) + (8 - 3) \ln(1 - p) \).  
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
    p(y | p) = h(y) \\exp\left(\\eta(p) \cdot T(y) - A(p)\\right)
    \]

    For the binomial distribution with PMF \( P(Y = y) = \binom{n}{y} p^y (1 - p)^{n - y} \), the exponential form is:

    \[
    P(Y = y) = \binom{n}{y} (1 - p)^n \\exp\left(y \ln\left(\\frac{p}{1 - p}\\right)\\right)
    \]

    <strong>Question:</strong><br>  
    Match each component of the binomial distribution to its exponential family counterpart by dragging the terms to the correct boxes.<br>
        <br>
    - \( h(y) \)  <br>
    - \( T(y) \)  <br>
    - \( \\eta(p) \)  <br>
    - \( A(p) \) <br>
        <br>
    Descriptions:<br>
    - Sufficient statistic \( T(y) \)   {blank1}<br>
    - Natural parameter  \( \\eta(p) \) {blank2}<br>
    - Log-partition function \( A(p) \) {blank3}<br>
    - Base measure \( h(y) \)           {blank4}<br>
    EOT,
        'Solution:  <br>
    - \( T(y) = y \) (Sufficient statistic)  <br>
    - \(\\eta(p) = \ln\left(\frac{p}{1 - p}\right) \) (Natural parameter)  <br>
    - \( A(p) = -n \ln(1 - p) \) (Log-partition function)<br>
    - \( h(y) = \binom{n}{y} \) (Base measure)  <br>',
        'medium',
        'drag_and_drop',
        json_encode([
            "A" => "-n ln(1-p)",
            "B" => "binom(n,y)",
            "C" => "y",
            "D" => "ln(p/(1-p))"  
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "C",
            "2" => "D",
            "3" => "A",
            "4" => "B"
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

    Consider an experiment with \( n = 10 \) trials and \( k = 4 \) successes.<br>
        <br>
    <strong>Question:</strong><br>  
    Compute the MLE for \( p \). Select all that apply.<br>
        <br>
    A. The MLE is \( \hat{p} = \\frac{k}{n} \).  <br>
    B. The MLE is \( \hat{p} = 0.4 \).  <br>
    C. The derivative \( \\frac{d\\ell}{dp} \) is positive for all \( p \).  <br>
    D. The log-likelihood is concave, ensuring a unique maximum.<br>
    EOT,
        'Solution:  <br>
    Solving the derivative: \( \\frac{k}{p} = \\frac{n - k}{1 - p} \), so \( \hat{p} = \\frac{k}{n} \).<br>  
    For \( n = 10 \), \( k = 4 \): \( \hat{p} = \\frac{4}{10} = 0.4 \).  <br>
    The second derivative of the log-likelihood is negative, confirming concavity.  <br>
    The derivative is not always positive; it equals zero at the maximum.  <br>
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
    $module_two_term = get_term_by( 'slug', 'module-two-en', 'course_topic' );
    if ( $module_two_term ) {
        $category_id = $module_two_term->term_id;
    } else {
        error_log('Term "module-two-en" not found in course_topic taxonomy.');
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

    Given a score vector \( \mathbf{z} = [0, 1, -1] \), compute the softmax probabilities for each class.<br>
        <br>
    <strong>Question:</strong><br>  
    What is the softmax probability for the second class (corresponding to \( z_2 = 1 \))? Round to 3 decimal places.<br>
        <br>
    1. Compute \( e^{z_i} \) for each \( z_i \).<br>
    2. Calculate the sum \( \sum_{j=1}^3 e^{z_j} \).<br>
    3. Compute \( \sigma(\mathbf{z})_2 = \\frac{e^{z_2}}{\sum_{j=1}^3 e^{z_j}} \).
    EOT,
        'Solution:  
    1. \( e^{z_1} = e^0 = 1 \), \( e^{z_2} = e^1 \approx 2.718 \), \( e^{z_3} = e^{-1} \approx 0.368 \).  <br>
    2. Sum: \( 1 + 2.718 + 0.368 \approx 4.086 \).     <br>
    3. \( \sigma(\mathbf{z})_2 =\\frac{e^1}{4.086} \approx\\frac{2.718}{4.086} \approx 0.665 \).  <br>
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

    Here, \( \mathbf{W} \) is the weight matrix, and \( \mathbf{x} \) is the input feature vector. The categorical distribution ensures that the probabilities sum to 1 and are non-negative.<br>
        <br>
    <strong>Question:</strong><br>  
    Which of the following statements about the softmax function’s role in modeling the categorical distribution are true? Select all that apply.<br>
        <br>
    A. The softmax function guarantees that probabilities sum to 1.  <br>
    B. The softmax function outputs negative probabilities for some classes.  <br>
    C. The softmax function is invariant to adding a constant to all input scores. <br> 
    D. The softmax function requires the input scores to be positive.<br>
    EOT,
        'Solution:  <br>
    - A: True (softmax normalizes probabilities to sum to 1).  <br>
    - B: False (softmax outputs non-negative probabilities).  <br>
    - C: True (adding a constant to all scores does not change the probabilities due to normalization).  <br>
    - D: False (softmax accepts any real-valued scores).  <br>
    Correct answers: A, C.',
        'medium',
        'multiple_choice',
        json_encode([
            "A" => "Probabilities sum to 1",
            "B" => "Outputs negative probabilities",
            "C" => "Invariant to constant shift",
            "D" => "Requires positive inputs"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_options" => ["A", "C"]]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 3: Gradient of Cross-Entropy Loss
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gradient of Softmax Loss',
        <<<EOT
    In softmax regression, the loss function is typically the <span class="tooltip"><strong>cross-entropy loss</strong><span class="tooltip-text">Admit it: that "typically" was slick, wasn’t it? Makes it sound like we’re being casual, not doctrinal. But no worries — I’ll just tell you it comes from the GLM. Nothing more. As the great mathematician Kuratowski once said, “You don’t have to read it on the first pass.” (Which is academic code for: you’ll come back eventually, when the pain of not knowing outweighs the pain of trying to understand.) </span></span>, defined for a single observation with true label \( y \) (one-hot encoded as \( \mathbf{y} \)) and predicted probabilities \( \hat{\mathbf{y}} = \sigma(\mathbf{W} \mathbf{x}) \):

    \[
    L = -\sum_{k=1}^K y_k \ln(\hat{y}_k)
    \]

    The gradient of the loss with respect to the weight vector \( \mathbf{w}_k \) for class \( k \) is:

    \[
    \\nabla_{\mathbf{w}_k} L = (\hat{y}_k - y_k) \mathbf{x}
    \]

    Given:<br>
    - Input vector \( \mathbf{x} = [1, 2] \).<br>
    - Predicted probabilities \( \hat{\mathbf{y}} = [0.2, 0.7, 0.1] \).<br>
    - True label is class 2 (i.e., \( \mathbf{y} = [0, 1, 0] \)).<br>
        <br>
    <strong>Question:</strong><br>  
    Compute the gradient \( \\nabla_{\mathbf{w}_2} L \) for the weight vector of class 2. Provide the components rounded to 2 decimal places.<br>
        <br>
    1. Identify \( \hat{y}_2 \) and \( y_2 \).<br>
    2. Compute \( \hat{y}_2 - y_2 \).<br>
    3. Multiply by \( \mathbf{x} \) to obtain the gradient.
    EOT,
        'Solution:  
    1. \( \hat{y}_2 = 0.7 \), \( y_2 = 1 \).  
    2. \( \hat{y}_2 - y_2 = 0.7 - 1 = -0.3 \).  
    3. Gradient: \( (-0.3) \cdot [1, 2] = [-0.3, -0.6] \).  
    Answer: [-0.30, -0.60].',
        'hard',
        'labeled_inputs',
        null,
        json_encode(["correct_options" =>[
            "\(w_{2,0}\)" => "-0.30",
            "\(w_{2,1}\)" => "-0.60"
        ]]),
        $exercise_number
    );
    $exercise_number++;

    // Exercise 4: Softmax Regression Components
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Components of Softmax Regression',
        <<<EOT
    Softmax regression extends logistic regression to multiclass classification by modeling the target variable as a categorical distribution. The key components include:<br>
    - <strong>Score Calculation</strong>: Computing \( \mathbf{z} = \mathbf{W} \mathbf{x} \), where \( \mathbf{W} \) is the weight matrix.<br>
    - <strong>Softmax Activation</strong>: Transforming scores into probabilities using \( \sigma(\mathbf{z})_k = \\frac{e^{z_k}}{\sum_j e^{z_j}} \).<br>
    - <strong>Loss Function</strong>: Typically cross-entropy loss, \( L = -\sum_k y_k \ln(\hat{y}_k) \).<br>
    - <strong>Gradient Update</strong>: Adjusting weights using the gradient of the loss.<br>
        <br>
    <strong>Question:</strong><br>  
    Match each component of softmax regression to its description by dragging the terms to the correct boxes.<br>
        <br>

    Score Calculation    {blank1}<br>
    Softmax Activation   {blank2}<br>
    Loss Function        {blank3}<br>
    Gradient Update      {blank4}<br>
    EOT,
        'Solution:  
    - Score Calculation: Computes the linear combination of weights and inputs.  
    - Softmax Activation: Transforms scores into a probability distribution.  
    - Loss Function: Measures the error between predicted and true labels.  
    - Gradient Update: Adjusts weights based on the loss gradient.',
        'easy',
        'drag_and_drop',
        json_encode([
            "A" => "Transforms to probabilities",
            "B" => "Computes linear combination",
            "C" => "Adjusts weights",
            "D" => "Measures prediction error"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "B",
            "2" => "A",
            "3" => "D",
            "4" => "C"
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
    In softmax regression, the predicted class is the one with the highest probability. Given:<br>
    - Input vector \( \mathbf{x^T} = [1, -1] \) (including bias).<br>
    - Weight matrix \( \mathbf{W} \) with rows \( \mathbf{w}_1 = [0.5, 0.2] \), \( \mathbf{w}_2 = [-0.3, 0.4] \), \( \mathbf{w}_3 = [0.1, -0.2] \).<br>
    - Scores \( \mathbf{z} = \mathbf{W} \mathbf{x} \).<br>
        <br>
    <strong>Question:</strong><br>  
    Compute the <strong>predicted probability for class 2</strong>, i.e. compute:<br>
        <br>
    1. Compute:<br>
    - \( z_1 = \mathbf{w}_1 \cdot \mathbf{x} \).<br>
    - \( z_2 = \mathbf{w}_2 \cdot \mathbf{x} \).<br>
    - \( z_3 = \mathbf{w}_3 \cdot \mathbf{x} \).<br>
    2: Apply softmax: <br>
    - Numerator: \( e^{z_2} \)<br>
    - Denominator: \( e^{z_1} + e^{z_2} + e^{z_3} \)<br>


    EOT,
        'Solution: <br>
        Step 1: Compute logits: <br>
        - \( z_1 = \mathbf{w}_1 \cdot \mathbf{x} = 1 \cdot 2 + 0 \cdot 3 = 2 \)<br>
        - \( z_2 = \mathbf{w}_2 \cdot \mathbf{x} = 0 \cdot 2 + 1 \cdot 3 = 3 \)<br>
        - \( z_3 = \mathbf{w}_3 \cdot \mathbf{x} = 1 \cdot 2 + 1 \cdot 3 = 5 \)<br><br>
        Step 2: Apply softmax: <br>
        - Numerator: \( e^{z_2} = e^{-0.7} = 0.496 \)<br>
        - Denominator: \( e^{0.3} + e^{-0.7} + e^{0.3} = 1.349 + 0.496 + 1.349\)<br>
        - Final answer: \( \hat{y}_2 = \\frac{0.496}{3.196} \approx 0.155 \)',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "-0.155",
            "B" => "3.196",
            "C" => "0.496",
            "D" => "0.155"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode(["correct_option" => "D"]),
        $exercise_number
    );
    $exercise_number++;

}
function add_gaussian_distribution_exercises_to_the_lesson($lesson_number) {
    $module_two_term = get_term_by('slug', 'module-two-en', 'course_topic');
    $category_id = $module_two_term ? $module_two_term->term_id : 0;
    $lesson_id = get_lesson_for_category($category_id, $lesson_number);
    $exercise_number = 1;

    // Exercise 1: Gaussian PDF
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gaussian PDF',
        <<<EOT
    The probability density function (PDF) of the Gaussian distribution with mean \( \mu \) and variance \( \sigma^2 \) is:

    \[
    p(x) =\\frac{1}{\sqrt{2\pi \sigma^2}} \\exp\left( -\\frac{(x - \mu)^2}{2\sigma^2} \\right)
    \]

    <strong>Question:</strong><br>
    What is the value of the PDF at \( x = 1 \), given \( \mu = 0 \), \( \sigma^2 = 1 \)? Round to 4 decimal places.
    EOT,
            'Solution:  
    \[
    p(1) =\\frac{1}{\sqrt{2\pi}} \\exp\left(-\\frac{1}{2}\\right) \approx 0.2419
    \]
    Answer: 0.2419',
        'easy',
        'one_of_many',
        json_encode([
            "A" => "0.6065",
            "B" => "0.2419",
            "C" => "0.3989",
            "D" => "0.1353"
        ]),
        json_encode(["correct_option" => "B"]),
        $exercise_number++
    );

    // Exercise 2: Gaussian in Exponential Family Form
    $result = add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Gaussian with Fixed Variance in Exponential Family',
        <<<EOT
    The Gaussian distribution with <strong>fixed variance</strong> \( \sigma^2 \) can be expressed in the exponential family form as:

    \[
    p(y) = h(y) \\exp\left( \\eta y - A(\\eta) \\right)
    \]

    This is a single-parameter exponential family, where the sufficient statistic \( T(y) \) captures all the information about \( y \) needed for estimating the mean.

    <br><br>
    <strong>Question:</strong><br>
    What is the sufficient statistic \( T(y) \) for the fixed-variance Gaussian?

    EOT,
        'Solution:  
    With fixed variance, the Gaussian becomes a one-parameter exponential family and the sufficient statistic is \( T(y) = y \).  
    Answer: C.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "(y, y^2)",
            "B" => "y^2",
            "C" => "y",
            "D" => "None of the above"
        ]),
        json_encode(["correct_option" => "C"]),
        $exercise_number++
    );


    // Exercise 3: Mean and Variance of Gaussian
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Moments of Gaussian Distribution',
        <<<EOT
    A Gaussian random variable with parameters \( \mu = 2 \), \( \sigma^2 = 4 \) is given.

    <strong>Question:</strong><br>
    What are the expected value and variance of this distribution?
    EOT,
            'Solution:  <br>
    Expected value is \( \mu = 2 \), variance is \( \sigma^2 = 4 \).  <br>
    Answer: Expected Value = 2, Variance = 4.',
        'easy',
        'labeled_inputs',
        null,
        json_encode(["correct_options"=>[
            "expected_value" => "2",
            "variance" => "4"
        ]]),
        $exercise_number++
    );

    // Exercise 4: Log-Likelihood for Gaussian
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Log-Likelihood of Gaussian',
        <<<EOT
    For \( x_1 = 2 \), \( x_2 = 4 \), assuming a Gaussian distribution with known variance \( \sigma^2 = 1 \), the log-likelihood function for mean \( \mu \) is:

    \[
    \\ell(\mu) = -\\frac{1}{2} \sum_{i=1}^{2} (x_i - \mu)^2 + \\text{const}
    \]

    <strong>Question:</strong><br>
    Which value of \( \mu \) maximizes the log-likelihood?
    EOT,
            'Solution:  <br>
    The log-likelihood is maximized at the sample mean:  
    \[
    \hat{\mu} =\\frac{2 + 4}{2} = 3
    \]
    Answer: 3.',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "2",
            "B" => "2.5",
            "C" => "3",
            "D" => "3.5"
        ]),
        json_encode(["correct_option" => "C"]),
        $exercise_number++
    );
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
    2. \( \mu = \sigma(-0.1) =\\frac{1}{1 + e^{0.1}} \approx\\frac{1}{1 + 1.1052} \approx 0.475 \).  <br>
    3. \( y - \mu = 1 - 0.475 = 0.525 \).  <br>
    4. Gradient = \( 0.525 \cdot [1, 2] = [0.525, 1.050] \).  <br>
    Answer: [0.525, 1.050].',
        'hard',
        'labeled_inputs',
        null,
        json_encode([
            "correct_options" => [
                "\(w_0\)" => "0.525",
                "\(w_1\)" => "1.050"
            ]
        ]),
        $exercise_number
    );
    $exercise_number++;
}

function add_glm_summary_exercises_to_the_lesson($lesson_number){
    $module_two_term = get_term_by('slug', 'module-two-en', 'course_topic');
    $category_id = $module_two_term ? $module_two_term->term_id : 0;
    $lesson_id = get_lesson_for_category($category_id, $lesson_number);
    $exercise_number = 1;

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Estimating \( \mu \) in Gaussian GLM',
        <<<EOT
    <p>You're solving a <strong>regression problem</strong>. That means, for every input \( x \), the target variable \( y \) is drawn from a Gaussian distribution:</p>

    

    \[
    y \sim \mathcal{N}(\mu, \sigma^2)
    \]

    <p><strong>Question:</strong><br>
    Which picture correctly describes the situation:
    
    EOT,
        'To complete the model, you must minimize the squared error using gradient descent (or closed-form solution) to find the optimal \( \\theta \).',
        'medium',
        'one_of_many',
        json_encode([
            "A" => '<img src=\"https://mldenizen.com/wp-content/uploads/2025/07/gaussian_assumption_2.png\" alt=\"For every x, y is drawed from Gaussian distribution\" style=\"max-width: 80%; height: auto; border: 1px solid #ccc; padding: 4px; background: white;\" />',
            "B" => '<img src=\"https://mldenizen.com/wp-content/uploads/2025/07/gaussian_assumption.png\" alt=\"For every x, y is drawed from Gaussian distribution\" style=\"max-width: 80%; height: auto; border: 1px solid #ccc; padding: 4px; background: white;\" />',

        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        json_encode(["correct_option" => "B"]),
        $exercise_number++
    );


    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Estimating \( \mu \) in Gaussian GLM',
        <<<EOT
    <p>You're solving a <strong>regression problem</strong>. That means, for every input \( x \), the target variable \( y \) is drawn from a Gaussian distribution:</p>

    \[
    y \sim \mathcal{N}(\mu, \sigma^2)
    \]

    <p>You assume \( \sigma = 1 \), and you want to model \( \mu \) as a linear function of the input \( x \), i.e., estimate \( \mu = \\theta^T x \).</p>

    <p>Because the Gaussian distribution belongs to the <strong>exponential family</strong>, you use a <strong>Generalized Linear Model (GLM)</strong>. For Gaussian, the natural (canonical) parameter is:</p>

    \[
    \\eta = \mu
    \]

    <p>And the canonical link function is the identity:</p>

    \[
    g(\mu) = \mu = \\theta^T x
    \]

    <p>So estimating \( \mu \) becomes equivalent to estimating \( \\theta \). You use <strong>Maximum Likelihood Estimation</strong> by minimizing the <strong>negative log-likelihood</strong>, which gives the classic squared error loss:</p>

    \[
    \mathcal{L}(\\theta) = \sum_{i=1}^{n} (y_i - \\theta^T x_i)^2
    \]

    <p><strong>Visual:</strong> For every \( x \), the vertical bell curve below shows the assumed distribution of \( y \):</p>

    <img src="https://mldenizen.com/wp-content/uploads/2025/07/guassian_distribution_linear_correlation.png" alt="Vertical Gaussian distributions along regression line" style="max-width: 100%; height: auto; border: 1px solid #ccc; padding: 4px; background: white;" />

    <p><strong>Question:</strong> What is left to complete the model fitting?</p>
    EOT,
        'To complete the model, you must minimize the squared error using gradient descent (or closed-form solution) to find the optimal \( \\theta \).',
        'medium',
        'one_of_many',
        json_encode([
            "A" => "Identify the canonical link function for the Gaussian distribution",
            "B" => "Differentiate the log-partition function to compute the mean",
            "C" => "Minimize the negative log-likelihood (squared loss) to solve for \( \\theta \)",
            "D" => "Apply a sigmoid transformation to estimate class probabilities"
        ]),
        json_encode(["correct_option" => "C"]),
        $exercise_number++
    );

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Sequence: Fitting a Binary Classifier in GLM',
        <<<EOT
    <p>In logistic regression (a GLM for binary classification), you assume the target variable follows a Bernoulli distribution and use a sigmoid to map linear outputs to probabilities.</p>
        
    <img src="https://mldenizen.com/wp-content/uploads/2025/07/binary_modeled_sigmoid.png" alt="Using sigmoid function to model target variable" style="max-width: 100%; height: auto; border: 1px solid #ccc; padding: 4px; background: white;" />

    <p>Put the following steps in the correct order:</p>

    <ol>
        <li>{blank1}</li>
        <li>{blank2}</li>
        <li>{blank3}</li>
        <li>{blank4}</li>
    </ol>
    EOT,
        'Solution:
            1. Start from labeled binary data (0 or 1)<br>
            2. Assume the probability of 1 is modeled by a sigmoid of a linear function: \\( \\sigma(\\theta^T x + b) \\)<br>
            3. Use Maximum Likelihood Estimation to learn \\( \\theta \\) and \\( b \\)<br>
            4. Predict class by thresholding the sigmoid output at 0.5',
        'medium',
        'drag_and_drop',
        json_encode([
            "A" => "Use Maximum Likelihood Estimation to learn how steep the sigmoid is (\\( \\theta \\)) and its horizontal position( \\( b \\))",
            "B" => "Predict class by thresholding the sigmoid output at 0.5",
            "C" => "Assume the probability of 1 is modeled by a sigmoid of a linear function: \\( \\sigma(\\theta^T x + b) \\)",
            "D" => "Start from labeled binary data (0 or 1)"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "D",
            "2" => "C",
            "3" => "A",
            "4" => "B"
        ]),
        $exercise_number++
    );

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Logistic Regression: Predicting Probability',
        <<<EOT
    In logistic regression, the probability is computed using a sigmoid function over a linear combination:

    \[
    P(y = 1 \mid \mathbf{x}) = \sigma(\mathbf{w}^\\top \mathbf{x} + b), \quad \sigma(z) = \\frac{1}{1 + e^{-z}}
    \]

    Given:
    <ul>
    <li>\( \mathbf{x} = [2, 1] \)</li>
    <li>\( \mathbf{w} = [1.0, -0.5] \)</li>
    <li>\( b = 0 \)</li>
    </ul>

    <strong>Question:</strong><br>
    Compute the predicted probability that \( y = 1 \).
    EOT,
        'Solution:
    The linear combination is \( z = 1.0 \cdot 2 + (-0.5) \cdot 1 = 1.5 \)<br>
    The probability is \( \sigma(1.5) = \\frac{1}{1 + e^{-1.5}} \approx 0.817 \).',
        'medium',
        'labeled_inputs',
        null,
        json_encode([
            "correct_options" => [
                "\( p(Y==1)\)" => "0.817"
            ]
        ]),
        $exercise_number++
    );
    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – From Data to Expected Value',
        <<<EOT
    The Generalized Linear Model (GLM) framework provides a clear sequence of steps from raw data to computing a predicted value.

    <strong>Task:</strong><br>
    Put the following steps in the correct logical order — starting from data and ending with computing the expected value of the target variable.<br>
        <br>
       1. {blank1}<br>
       2. {blank2}<br>
       3. {blank3}<br>
       4. {blank4}<br>
       5. {blank5}
    EOT,
        'Solution:
    Correct order:<br>
    1. Identify the nature of the target variable (e.g., binary, count, continuous).<br>
    2. Select a probability distribution from the exponential family that models the target.<br>
    3. Connect the distribution’s natural (canonical) parameter to a linear combination of input features.<br>
    4. Fit the model by minimizing the negative log-likelihood (NLL) of the PMF written in canonical form, typically using gradient descent on the linear parameter vector \( \boldsymbol{\\theta} \).<br>
    5. Use the model to compute the expected value of the target variable for new data.
    ',
        'medium',
        'drag_and_drop',
        json_encode([
            "A" => "Select a distribution from the exponential family",
            "B" => "Identify the nature of the target variable",
            "C" => "Fit the model by minimizing the negative log-likelihood using gradient descent on θ",
            "D" => "Compute the expected value for new inputs",
            "E" => "Link the canonical parameter to a linear combination of inputs"
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        json_encode([
            "1" => "B",
            "2" => "A",
            "3" => "E",
            "4" => "C",
            "5" => "D"
        ]),
        
        $exercise_number++
    );

    add_exercise(
        $lesson_id,
        $category_id,
        'Exercise ' . $exercise_number . ' – Cross-Entropy: Choose All That Apply',
        <<<EOT
    Cross-entropy loss arises naturally from several members of the exponential family when optimizing via Maximum Likelihood. Below are several expressions—some true, some impostors.

    <strong>Task:</strong><br>
    Select <u>all</u> the options that represent correct cross-entropy loss functions derived from probability distributions.

    EOT,
        'Solution:
    Correct options:<br>
    True Binary cross-entropy from Bernoulli:  
    \[
    L = - \left[ y \log(\hat{y}) + (1 - y) \log(1 - \hat{y}) \right]
    \]  
    True Binary cross-entropy from Binomial (n trials):  
    \[
    L = - \left[ k \log(\hat{p}) + (n - k) \log(1 - \hat{p}) \right]
    \]  
    True Multinomial cross-entropy from Softmax:  
    \[
    L = - \sum_{i=1}^K y_i \log(\hat{y}_i)
    \]  
    False - Others are either fake or misleading.',
        'medium',
        'multiple_choice',
        json_encode([
            "A"=>"Cross-entropy from Gaussian:\n\\[ L = \\frac{1}{2}(y - \\hat{y})^2 \\]",
            "B"=>"Polynomial entropy loss:\n\\[ L = x^3 \\log(x) - 2x \\]",
            "C"=>"Binary cross-entropy from Binomial:\n\\[ L = - \\left[ k \\log(\\hat{p}) + (n - k) \\log(1 - \\hat{p}) \\right] \\]",
            "D"=>"Multinomial cross-entropy from Softmax:\n\\[ L = - \\sum_{i=1}^K y_i \\log(\\hat{y}_i) \\]",
            "E"=>"Uniform distribution entropy penalty:\n\\[ L = -\\log\\left(\\frac{1}{N}\\right) \\]",
            "F"=>"Binary cross-entropy from Bernoulli:\n\\[ L = - \\left[ y \\log(\\hat{y}) + (1 - y) \\log(1 - \\hat{y}) \\right] \\]",
        ]),
        json_encode([
            "correct_options" => [
                "C", "D", "F"
            ]
        ]),
        $exercise_number++
    );


}
function exercise_module_two_plugin_activate() {

    add_probability_distributions_exercises_to_the_lesson(1);
    add_exponential_family_exercises_to_the_lesson(2);
    add_exponential_family_two_exercises_to_the_lesson(3);
    add_glm_exercises_to_the_lesson(4);
    add_repetition_exercises_to_the_lesson(5);
    add_bernoulli_distribution_exercises_to_the_lesson(6);
    add_binomial_distribution_exercises_to_the_lesson(7);
    add_gaussian_distribution_exercises_to_the_lesson(8);
    add_multinomial_distribution_exercises_to_the_lesson(9);
    add_glm_summary_exercises_to_the_lesson(10);
}
?>