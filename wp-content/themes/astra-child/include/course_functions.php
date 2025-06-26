<?php
$allowed_tags = array(
    'div' => array(
        'class' => true,
        'style' => true,
        'draggable' => true,
        'data-value' => true
    ),
    'input' => array(
        'type' => true,
        'name' => true,
        'value' => true,
        'style' => true,
        'placeholder' => true,
        'readonly' => true,
    ),
    'span' => array(
        'class' => true,
        'style' => true
    ),
    'table' => array(
        'style' => true
    ),
    'thead' => array(),
    'tbody' => array(),
    'tr' => array(),
    'th' => array(
        'style' => true
    ),
    'td' => array(
        'style' => true
    ),
    'p' => array(),
    'br' => array(),
    'b' => array(),
    'strong' => array(),
    'i' => array(),
    'em' => array(),
    'u' => array(),
    'script' => array(
        'type' => true
    ),
);

function show_module_intro($term_id) {
    $module_intro = get_field('module_intro', 'course_topic_' . $term_id);
    echo '<div class="module-intro">' . wpautop(wp_kses_post($module_intro)) . '</div>';

    $course_id = $_SESSION['selected_course_id'] ?? 0;
    $first_lesson_id = get_first_lesson_id($term_id);

    echo '<form method="get" action="' . esc_url(get_permalink($course_id)) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($first_lesson_id) . '">';
    echo '<input type="submit" value="Continue">';
    echo '</form>';
}


function show_lesson_intro($lesson_id, $term_id) {
    $intro = get_field('lesson_intro', $lesson_id);
    echo '<div class="lesson-intro">' . wpautop(wp_kses_post($intro)) . '</div>';
    echo '<form method="get">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
    echo '<input type="hidden" name="exercise_number" value="1">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    echo '<input type="hidden" name="stage" value="exercise">';
    echo '<input type="submit" value="Start Lesson">';
    echo '</form>';
}
function render_exercise($recommended_exercise, $term_id) {
    error_log("recommended exercise id ".$recommended_exercise->id);
    echo '<div class="exercise-container">';

    $feedback = $_SESSION['last_answer_feedback'] ?? null;
    error_log("feedback is ".print_r($feedback, true));
    $submitted = ($feedback && $feedback['exercise_id'] == $recommended_exercise->id)
    ? $feedback['submitted']
    : [];

    $correct_keys = $feedback['correct_keys'] ?? [];
    error_log("correct keys are ".print_r($correct_keys, true));
    error_log("submitted keys are ".print_r($submitted, true));
    // echo '<h1>Recommended Exercise for ' . esc_html( $term->name ) . '</h1>';
    echo '<div class="exercise-title">' . esc_html( $recommended_exercise->exercise_title ) . '</div>';

    // 👉 Only show exercise content immediately if NOT drag_and_drop
    if ( $recommended_exercise->question_type !== 'drag_and_drop'  && $recommended_exercise->question_type !== 'array_type' ) {
        $exercise_html = add_tooltips_to_content( $recommended_exercise->exercise_content );
        error_log("exercise html is ".$exercise_html);
        echo '<div class="exercise-description">' . wp_kses_post( str_replace('\{', '\\{', $exercise_html) ) . '</div>';

        // echo '<div>' . wp_kses( $recommended_exercise->exercise_content, $allowed_tags ) . '</div>';

    }
    // Start the form. The action posts back to the same page.
    echo '<form method="post" action="" class="exercise-form">';

    // Include hidden exercise_id so we know which exercise is being answered.
    echo '<input type="hidden" name="exercise_id" value="' . esc_attr( $recommended_exercise->id ) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr( $term_id ) . '">';
    if ( !empty($via_bkt) ) {
        echo '<input type="hidden" name="via_bkt" value="1">';
    }

    // Display input field based on question type.
    if ( 'open_text' === $recommended_exercise->question_type ) {
        $options = json_decode( $recommended_exercise->options, true );
        $options = add_tooltips_to_content( $options );
        // Check if options exist and contain any {inputX} placeholders
        if ( !empty($options) && isset($options[0]) && preg_match('/\{input\d+\}/', $options[0]) ) {
            $template = $options[0];

            // Replace placeholders like {input1}, {input2}, ... with input fields
            $template_with_inputs = preg_replace_callback(
                '/\{input(\d+)\}/',
                function ( $matches ) {
                    $index = $matches[1];
                    return '<input type="text" name="user_answer[' . $index . ']" size="20" class="open-text-input" />';
                },
                $template
            );

            // Escape the final output safely
            echo '<div class="open-text-exercise">' . wp_kses_post( $template_with_inputs ) . '</div>';
            echo '<div class="exercise-table">' . $table_html . '</div>';
            echo '<div class="open-text-exercise">' . $template_with_inputs . '</div>';

        } else {
            // Fallback: simple textarea input
            error_log("No options found or no placeholders in options.");
            echo '<div class="open-text-exercise">';
            echo '<textarea name="user_answer" rows="5" cols="60" placeholder="Type your answer here"></textarea>';
            echo '</div>';
        }
    } elseif ( 'match_boxes' === $recommended_exercise->question_type ) {
        $options = json_decode( $recommended_exercise->options, true );

        if ( ! empty( $options ) && is_array( $options ) ) {
            $terms = array_keys( $options );
            $definitions = array_values( $options );
            $indexed_definitions = [];
            foreach ($definitions as $original_index => $definition) {
                $indexed_definitions[] = [
                    'text' => $definition,
                    'original_index' => $original_index
                ];
            }

            // Shuffle the display order (but keep original index)
            shuffle($indexed_definitions);

            echo '<div class="match-boxes-exercise">';
            echo '<table class="match-boxes-table">';
            echo '<thead><tr><th>Term</th><th>Definition</th></tr></thead>';
            echo '<tbody>';

            $i = 1;
            foreach ( $terms as $term ) {
                $submitted_value = $submitted[$i-1] ?? '';
                error_log("term is " . $term);
                error_log("submitted value is " . $submitted_value);
                error_log("correct value is " . ($i-1));
                $is_correct =  $submitted_value == ($i-1);
                $input_class = '';

                if ($submitted_value!='') {
                    $input_class = $is_correct ? 'correct-answer' : 'incorrect-answer';
                }

                echo '<tr>';
                echo '<td class="match-term">' . esc_html($term) . '</td>';
                echo '<td>';

                if ($submitted_value != '') {
                    // Show the submitted answer as a colored box (not editable)
                    echo '<div class="match-result-box ' . esc_attr($input_class) . '">';
                    echo esc_html($definitions[$submitted_value]);
                    echo '</div>';
                } else {
                    // Show the select input on first load (not submitted yet)
                    echo '<select name="user_answer[' . $i . ']" class="match-select">';
                    echo '<option value="">-- Choose a match --</option>';
                    foreach ($indexed_definitions as $item) {
                        $index = $item['original_index'];
                        $def = $item['text'];
                        echo '<option value="' . esc_attr($index) . '">' . esc_html($def) . '</option>';
                    }
                    echo '</select>';
                }

                echo '</td>';
                echo '</tr>';
                $i++;
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
    } elseif ( 'labeled_inputs' === $recommended_exercise->question_type ) {
        $json_string = $recommended_exercise->correct_answer;
        error_log("options are " . $json_string);

        $decoded = json_decode(html_entity_decode($json_string, ENT_QUOTES | ENT_HTML5), true);
        $correct_options = isset($decoded['correct_options']) ? $decoded['correct_options'] : [];

        error_log("parsed correct_options are " . print_r($correct_options, true));

        if (is_array($correct_options)) {
            echo '<div class="labeled-inputs">';
            $epsilon = 0.0001;

            foreach ($correct_options as $label => $correct_value) {
                $safe_label = esc_html($label);
                $input_name = 'user_answer[' . esc_attr($label) . ']';

                // Check if submitted (associative array from POST/verify)
                if ( $feedback && is_array($submitted) ) {

                    $user_value = isset($submitted[$label]) ? floatval($submitted[$label]) : null;

                    if ($user_value === null) {
                        $class = 'missing-answer';
                    } elseif (abs($user_value - floatval($correct_value)) < $epsilon) {
                        $class = 'correct-answer';
                    } else {
                        $class = 'incorrect-answer';
                    }

                    echo '<label class="' . esc_attr($class) . '" style="display:block; margin-bottom:8px;">';
                    echo $safe_label . ' = ';
                    echo '<input type="number" step="any" value="' . esc_attr($user_value) . '" readonly style="width:80px;">';
                    echo '</label>';
                }
                // Not submitted yet — show editable input
                else {
                    echo '<label>' . $safe_label . ' = ';
                    echo '<input type="number" step="any" name="' . $input_name . '" required style="width: 80px;"></label><br>';
                }
            }

            echo '</div>';
        } else {
            echo '<p>Unable to render labeled input fields (invalid JSON in options).</p>';
        }
    }
    elseif ( 'array_type' === $recommended_exercise->question_type){
        $full_content = $recommended_exercise->exercise_content;
        $full_content = add_tooltips_to_content( $full_content );
        preg_match('/<table.*?>.*?<\/table>/is', $full_content, $match);
        if (!empty($match[0])) {
            $table_html = $match[0];
        } else {
            $table_html = '';
        }
        $full_content = preg_replace('/<table.*?>.*?<\/table>/is', '', $full_content);
        error_log("full content is ".$full_content);
        error_log("table content is ".$table_html);
        echo '<div>' . wp_kses_post( str_replace('\{', '\\{', $full_content) ) . '</div>';
        echo '<div>' . $table_html . '</div>';

    }  elseif ( 'one_of_many' === $recommended_exercise->question_type ) {
        // For questions of type "one_of_many", display radio buttons (only one selection is allowed)
        $json_string = $recommended_exercise->options;
        
        error_log("options are ".$json_string);

        $json = html_entity_decode($json_string, ENT_QUOTES|ENT_HTML5);
        $options = json_decode( $json, true );
        $options = add_tooltips_to_content( $options );
        error_log("decoded options are ".print_r($options, true));

        if ( is_array( $options ) ) {
            foreach ($options as $key => $option) {
                $checked = in_array($key, $submitted) ? 'checked' : '';
                $class = '';
                if (in_array($key, $submitted) && in_array($key, $correct_keys)) {
                    $class = 'correct-answer';
                } elseif (in_array($key, $submitted) && !in_array($key, $correct_keys)) {
                    $class = 'incorrect-answer';
                }

                echo '<label class="' . esc_attr($class) . '" style="display:block;margin-bottom:5px;">';
                echo '<input type="radio" name="user_answer" value="' . esc_attr($key) . '" ' . $checked . '> ' . wp_kses_post($option);
                echo '</label>';
            }
        } else {
            echo '<p>No options found.</p>';
        }
    } elseif ( 'multiple_choice' === $recommended_exercise->question_type ) {
        // Decode options and correct answers
        $json_string = $recommended_exercise->options;
        $options = json_decode(html_entity_decode($json_string, ENT_QUOTES | ENT_HTML5), true);
        $options = add_tooltips_to_content($options); // Optional, for enhanced display

        // User selections (array of keys), correct answers
        $submitted = $submitted ?? [];
        $correct_keys = $correct_keys ?? [];

        if (is_array($options)) {
            foreach ($options as $key => $option_text) {
                $is_selected = in_array($key, $submitted, true);
                $is_correct = in_array($key, $correct_keys, true);

                $class = '';
                if ($is_selected && $is_correct) {
                    $class = 'correct-answer';
                } elseif ($is_selected && !$is_correct) {
                    $class = 'incorrect-answer';
                }

                $checked = $is_selected ? 'checked' : '';

                echo '<label class="' . esc_attr($class) . '" style="display:block; margin-bottom:5px;">';
                echo '<input type="checkbox" name="user_answer[]" value="' . esc_attr($key) . '" ' . $checked . '> ';
                echo wp_kses_post($option_text);
                echo '</label>';
            }
        } else {
            echo '<p class="exercise-error">Invalid options format.</p>';
        }
    } elseif ( 'code_runner' === $recommended_exercise->question_type ) {
        error_log("exercise is code runner");

        // Parse options and solution
        $options = json_decode(html_entity_decode($recommended_exercise->options), true);
        $meta = json_decode(html_entity_decode($recommended_exercise->exercise_solution), true);

        $code = $options['code'] ?? '';
        $params = $meta['params'] ?? [];

        $encoded_code = json_encode($code, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $ajax_url = admin_url('admin-ajax.php', 'https');

        // JS config
        echo "<script>const codeTemplate = $encoded_code; const ajaxUrl = '" . esc_url($ajax_url) . "';</script>";


        // Show code
        echo '<div><strong>Code:</strong></div>';
        echo '<pre style="background:#f0f0f0; padding:10px; overflow:auto;"><code>' . esc_html($code) . '</code></pre>';

        // Parameter inputs
        echo '<p><strong>Provide parameters:</strong></p>';
        foreach ($params as $param => $default_value) {
            $input_type = is_numeric($default_value) ? 'number' : 'text';
            echo '<label>' . esc_html($param) . ' = ';
            echo '<input type="' . esc_attr($input_type) . '" id="param_' . esc_attr($param) . '" value="' . esc_attr($default_value) . '" style="width:90%">';
            echo '</label><br>';
        }

        echo '<button type="button" onclick="runCodeRunner()">Run</button>';
        echo '<pre id="code_output" style="background:#eef;padding:10px;margin-top:15px;"></pre>';

        // JS to run code
        echo '<script>
            function runCodeRunner() {
                console.log("runCodeRunner triggered");
                const fields = document.querySelectorAll("[id^=\'param_\']");
                const paramMap = {};
                fields.forEach(f => {
                    const key = f.id.replace("param_", "");
                    paramMap[key] = f.type === "number" ? parseFloat(f.value) : f.value;
                });

                let finalCode = codeTemplate;
                for (const [key, value] of Object.entries(paramMap)) {
                    const pattern = new RegExp(`{{${key}}}`, "g");
                    finalCode = finalCode.replace(pattern, value);
                }

                const data = { code: finalCode };
                const formData = new URLSearchParams();
                formData.append("action", "run_code_runner");
                formData.append("data", JSON.stringify(data));
    
                console.log("Sending to admin-ajax:", {
                    url: ajaxUrl,
                    body: Object.fromEntries(formData),
                    rawCode: finalCode
                });
                fetch(ajaxUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    document.getElementById("code_output").textContent =
                        res.data?.output || res.data?.error || "Brak danych";
                })
                .catch(err => {
                    document.getElementById("code_output").textContent = "Błąd: " + err.message;
                });
            }
        </script>';
    }
    elseif ( 'geometric_interpretation' === $recommended_exercise->question_type ) {
        error_log("Rendering geometric_interpretation exercise");

        $options = json_decode(html_entity_decode($recommended_exercise->options), true);
        $meta = json_decode(html_entity_decode($recommended_exercise->exercise_solution), true);
        $params = $meta['params'] ?? [];

        $code = $options['code'] ?? '';
        $encoded_code = json_encode($code, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // 🔁 Replace with your real Render backend URL and correct endpoint
        $ajax_url = 'https://mldenizen-visualization.onrender.com/visualize';

        echo "<script>const codeTemplate = $encoded_code; const ajaxUrl = '" . esc_url($ajax_url) . "';</script>";

        echo '<div><strong>Code (Visualization):</strong></div>';
        echo '<pre style="background:#f0f0f0; padding:10px; overflow:auto;"><code>' . esc_html($code) . '</code></pre>';

        echo '<p><strong>Adjust Parameters:</strong></p>';
        foreach ($params as $param => $default) {
            $type = is_numeric($default) ? 'number' : 'text';
            echo "<label>{$param} = <input type='$type' id='param_$param' value='" . esc_attr($default) . "' style='width:90%'></label><br>";
        }

        echo '<button type="button" onclick="runCodeRunner()">Visualize</button>';
        echo '<pre id="code_output" style="background:#eef;padding:10px;margin-top:15px;"></pre>';

        echo '
        <script>
        function runCodeRunner() {
            const fields = document.querySelectorAll("[id^=\'param_\']");
            const paramMap = {};
            fields.forEach(f => {
                const key = f.id.replace("param_", "");
                paramMap[key] = f.type === "number" ? parseFloat(f.value) : f.value;
            });

            let finalCode = codeTemplate;
            for (const [key, value] of Object.entries(paramMap)) {
                const pattern = new RegExp(`{{${key}}}`, "g");
                finalCode = finalCode.replace(pattern, value);
            }

            const payload = { code: finalCode };

            fetch(ajaxUrl, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                const out = res.output || res.error || "Brak danych";
                const output = document.getElementById("code_output");
                if (out.startsWith("data:image/png;base64")) {
                    const img = new Image();
                    img.src = out;
                    output.innerHTML = "";
                    output.appendChild(img);
                } else {
                    output.textContent = out;
                }
            })
            .catch(err => {
                document.getElementById("code_output").textContent = "Błąd: " + err.message;
            });
        }
        </script>
       ';
    }


    elseif ( 'drag_and_drop' === $recommended_exercise->question_type ) {
        $json_string = $recommended_exercise->options;
        error_log("options are ".$json_string);
        $json = html_entity_decode($json_string, ENT_QUOTES|ENT_HTML5);
        error_log("escaped options are ".$json);
        
        error_log("json after adding tooltips is ".$json);
        $options = json_decode( $json, true );
        $options = add_tooltips_to_content( $options );
        error_log("decoded options are ".print_r($options, true));
        if ( is_array( $options ) ) {
            $full_content = $recommended_exercise->exercise_content;
            error_log("full content is ".$full_content);
            $full_content = add_tooltips_to_content( $full_content );
            
            error_log("full content is ".$full_content);
            // 3. Display cleaned exercise content
            
            // 3. Replace blanks {blank1}, {blank2}, etc. inside content
            foreach (range(1, count($options)) as $i) {
                $submitted_val = $feedback['submitted'][$i-1] ?? '';
                $correct_val = $feedback['correct_keys'][$i] ?? null;
                $css_class = '';
                error_log("submitted value is ".$submitted_val);
                error_log("correct value is ".$correct_val);
                if ($feedback && $correct_val !== null && $submitted_val !== '') {
                    if ($submitted_val === $correct_val) {
                        $css_class = 'correct-answer';
                    } else {
                        $css_class = 'incorrect-answer';
                    }
                }
                // force exact indent regardless of what's in the original content
                $replacement = '<div class="dropzone ' . esc_attr($css_class) . '" style="display:inline-block; border:2px dashed #aaa; min-height:30px; margin:3px 0; width:90%; padding:3px 6px; vertical-align:middle;">' .
                    '<div class="dropzone-content" style="pointer-events:none; white-space:pre; text-align:left;">' . wp_kses_post($options[$submitted_val] ?? 'Drop here') . '</div>' .
                    '<input type="hidden" name="user_answer[' . $i . ']" value="' . esc_attr($submitted_val) . '">' .
                '</div>';

                $full_content = preg_replace(
                    '/^\s*\{blank' . $i . '\}/m',
                    $replacement,
                    $full_content
                );
            }
            echo '<div>' .  str_replace('\{', '\\{', $full_content)  . '</div>';
            error_log("content inside dragable is ".$content_inside_dragable);
            // 4. Output only the extracted content (with dropzones inserted)
            // echo '<pre><code style="display:block; padding:10px;">'.$content_inside_dragable;
            // echo wp_kses($content_inside_dragable, $allowed_tags);
            // echo '</code></pre>';
            // Step 2: Display draggable options
            echo '<p style="margin-top:20px;">Drag these lines into the blanks:</p>';
            echo '<ul id="draggable-options" style="list-style:none;padding:0;">';
            foreach ( $options as $key => $option ) {
                error_log("option is |".$option."|");
                $option_with_spaces = str_replace(' ', '&nbsp;', $option);
                // echo '<li class="draggable-option" draggable="true" data-value="' . esc_attr( $key ) . '" style="padding:8px;border:1px solid #ccc;margin-bottom:5px;cursor:move;background:#f9f9f9;">' . wp_kses_post( $option ) . '</li>';
                // echo '<li class="draggable-option" draggable="true" data-value="' . esc_attr( $key ) . '" style="padding:8px;border:1px solid #ccc;margin-bottom:5px;cursor:move;background:#f9f9f9;">' . $option_with_spaces . '</li>';
                echo '<li class="draggable-option" draggable="true" data-value="' . esc_attr($key) . '" style="
                    padding:8px;
                    border:1px solid #ccc;
                    margin-bottom:5px;
                    cursor:move;
                    background:#f9f9f9;
                    white-space:pre-wrap;
                    word-break:break-word;
                    max-width:100%;
                    box-sizing:border-box;
                ">' . wp_kses_post($option) . '</li>';
            }
            echo '</ul>';
            // Step 4: Drag-and-drop logic
            echo '<script>
                const draggables = document.querySelectorAll(".draggable-option");
                const dropzones = document.querySelectorAll(".dropzone");
    
                draggables.forEach(draggable => {
                    draggable.addEventListener("dragstart", e => {
                        e.dataTransfer.setData("text/plain", draggable.dataset.value);
                        e.dataTransfer.setData("text/html", draggable.innerText);
                    });
                });
    
                dropzones.forEach(dropzone => {
                    dropzone.addEventListener("dragover", e => {
                        e.preventDefault();
                        dropzone.style.backgroundColor = "#eef";
                    });
    
                    dropzone.addEventListener("dragleave", e => {
                        dropzone.style.backgroundColor = "";
                    });
    
                    dropzone.addEventListener("drop", e => {
                        e.preventDefault();
                        const value = e.dataTransfer.getData("text/plain");
                        const text = e.dataTransfer.getData("text/html");
                        
                        dropzone.querySelector("input").value = value;
                        dropzone.querySelector(".dropzone-content").innerText = text;
                        dropzone.style.backgroundColor = "";
                        // ❌ Remove the dragged option from the list
                        const draggedOption = document.querySelector(`.draggable-option[data-value="${value}"]`);
                        if (draggedOption) {
                            draggedOption.remove();
                        }
                    });
                });
            </script>';
        } else {
            echo '<p>No drag and drop options found.</p>';
        }
    
    }

    echo '<hr class="exercise-divider">';
    echo '<div class="submit-button-container">';
    if ( $recommended_exercise->question_type === 'code_runner' || 
    $recommended_exercise->question_type === 'geometric_interpretation' || $feedback ) {
        echo '<input type="submit" name="submit_continue" value="Continue">';
    } else{
        echo '<input type="submit" name="submit_answer" value="Check"></div>';
    }
    echo '</form>';
    echo '</div>';
}

function handle_submit_answer($exercise_id, $term_id, $exercise) {
    global $wpdb;
    error_log('handle_submit_answer Exercise type: ' . $exercise->question_type);
    error_log('user_answer set: ' . (isset($_POST['user_answer']) ? 'yes' : 'no'));
    error_log('match_term set: ' . (isset($_POST['match_term']) ? 'yes' : 'no'));

    error_log('POST user_answer: ' . print_r($_POST['user_answer'], true));
    error_log('POST match_term: ' . print_r($_POST['match_term'], true));
    // Get answer
    if ( 'array_type' === $exercise->question_type ) {
        $raw = $_POST['t'] ?? [];
        $clean = [];
        foreach ($raw as $r => $cols) {
            foreach ($cols as $c => $v) {
                $clean[$r][$c] = trim(wp_strip_all_tags($v));
            }
        }
        $user_answer = wp_json_encode($clean);
    } // ✅ labeled_input expects associative array of floats (not imploded string)
    elseif ( 'labeled_inputs' === $exercise->question_type && isset($_POST['user_answer']) && is_array($_POST['user_answer']) ) {
        $user_answer = [];
        foreach ($_POST['user_answer'] as $label => $value) {
            $user_answer[ sanitize_text_field($label) ] = floatval($value);
        }
        error_log("user answer is ".print_r($user_answer, true));
    } elseif ( 'match_boxes' === $exercise->question_type && isset($_POST['user_answer']) ) {
        error_log('✅ Entered match_boxes handler');

        // We assume: $_POST['user_answer'] is a 1-indexed array of selected definition indices
        error_log('POST user_answer: ' . print_r($_POST['user_answer'], true));

        $user_answer = [];

        foreach ($_POST['user_answer'] as $i => $selected_index) {
            $user_answer[] = ($selected_index === '') ? null : intval($selected_index);
        }

        $user_answer_json = wp_json_encode($user_answer);
        error_log('✅ Final JSON user_answer: ' . $user_answer_json);

        $user_answer = $user_answer_json;
    } elseif (isset($_POST['user_answer']) && is_array($_POST['user_answer'])) {
        $user_answer = implode(',', array_map('sanitize_text_field', $_POST['user_answer']));
    }  else {
        $user_answer = sanitize_text_field($_POST['user_answer']);
    }

    // Verify
    $result = verify_answer($exercise_id, $user_answer);

    error_log("result of verify_answer is ".print_r($result, true));
    $_SESSION['last_answer_feedback'] = [
        'exercise_id' => $exercise_id,
        'submitted' => $result['user_keys'],
        'correct' => $result['correct'],
        'correct_keys' => $result['correct_keys'] ?? [], // you must return this from `verify_answer`
    ];
    // Log attempt
    $attempts_table = $wpdb->prefix . 'exercise_attempts';
    $wpdb->insert($attempts_table, [
        'exercise_id'   => $exercise_id,
        'user_id'       => get_current_user_id(),
        'user_answer'   => $user_answer,
        'is_correct'    => $result['correct'],
        'attempt_time'  => current_time('mysql'),
        'points_awarded'=> $result['points'],
    ]);

    // Return markup
    ob_start();
    echo '<div class="exercise-feedback ' . ($result['correct'] ? 'correct' : 'incorrect') . '">';
    echo $result['correct'] ? '✅ Correct!' : '❌ Incorrect.';
    echo '</div>';

    if (!$result['correct']) {
        echo '<div class="exercise-solution"><h3>Solution:</h3>';
        echo wp_kses_post($exercise->exercise_solution);
        echo '</div>';
    }

    // echo '<form method="post">';
    // echo '<input type="hidden" name="continue_next" value="1">';
    // echo '<input type="hidden" name="exercise_id" value="' . esc_attr($exercise_id) . '">';
    // echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';
    // echo '<div class="submit-button-container">';
    // echo '<input type="submit" name="submit_continue" value="Continue">';
    // echo '</div></form>';

    return ob_get_clean();
}

function show_exercise($lesson_id, $exercise_number, $term_id) {
    
    global $wpdb;
    $table = $wpdb->prefix . 'exercises';
    $exercise = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE lesson_id = %d AND exercise_number = %d",
        $lesson_id, $exercise_number
    ));
    if (isset($_POST['submit_answer']) && intval($_POST['exercise_id']) == $exercise->id) {
        echo handle_submit_answer($exercise->id, $term_id, $exercise);
        
    }
    if (!$exercise) {
        error_log('Exercise not found for lesson ID: ' . $lesson_id . ' and exercise number: ' . $exercise_number);
        wp_redirect(add_query_arg([
            'stage' => 'lesson_summary',
            'lesson_id' => $lesson_id,
            'term_id' => $term_id
        ], get_permalink()));
        exit;
    }

    render_exercise($exercise, $term_id);

    // In your submit handler, redirect to next exercise or lesson_summary
}

function show_lesson_summary($lesson_id, $term_id) {
    $summary = get_field('lesson_summary', $lesson_id);
    echo '<div class="lesson-summary">' . wpautop(wp_kses_post($summary)) . '</div>';

    // ✅ Mark the lesson as completed in user meta
    $user_id = get_current_user_id();
    $completed = get_user_meta($user_id, 'completed_lessons', true) ?: [];

    if (!in_array($lesson_id, $completed)) {
        $completed[] = $lesson_id;
        update_user_meta($user_id, 'completed_lessons', $completed);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'exercises';
    $has_exercises = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE lesson_id = %d", $lesson_id
    ));

    $course_id = $_SESSION['selected_course_id'] ?? 0;
    $course_url = get_permalink($course_id);

    echo '<form method="get" action="' . esc_url($has_exercises > 0 ? '' : $course_url) . '">';
    echo '<input type="hidden" name="lesson_id" value="' . esc_attr($lesson_id) . '">';
    echo '<input type="hidden" name="term_id" value="' . esc_attr($term_id) . '">';

    if ($has_exercises > 0) {
        echo '<input type="hidden" name="stage" value="mind_map">';
    }

    echo '<input type="submit" value="Continue">';
    echo '</form>';
}


function show_mind_map_stage($lesson_id, $term_id) {
    error_log("show mind map stage method called");
    $user_id = get_current_user_id();
    $note_key = "mindmap_note_{$lesson_id}";
    $image_key = "mindmap_image_{$lesson_id}";

    // Handle submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mindmap_note'])) {
        $note = sanitize_text_field($_POST['mindmap_note']);
        update_user_meta($user_id, $note_key, $note);

        if (!empty($_POST['canvas_data'])) {
            $image_data = $_POST['canvas_data'];
            $image_data = str_replace('data:image/png;base64,', '', $image_data);
            $image_data = base64_decode($image_data);

            $upload_dir = wp_upload_dir();
            $filename = "mindmap_{$user_id}_{$lesson_id}.png";
            $filepath = $upload_dir['path'] . '/' . $filename;
            file_put_contents($filepath, $image_data);

            $fileurl = $upload_dir['url'] . '/' . $filename;
            update_user_meta($user_id, $image_key, $fileurl);
        }

        wp_redirect(get_permalink());
        exit;
    }

    $saved_note = get_user_meta($user_id, $note_key, true);
    $saved_image = get_user_meta($user_id, $image_key, true);

    ob_start();
    echo '<h2>Mind Map: Summarize and Visualize</h2>';

    // ✅ If already submitted, just show the summary and image
    if ($saved_note && $saved_image) {
        echo '<p><strong>Your summary note:</strong></p>';
        echo '<blockquote style="border-left: 4px solid #ccc; padding-left: 10px;">' . esc_html($saved_note) . '</blockquote>';

        echo '<p><strong>Your drawing:</strong></p>';
        echo '<img src="' . esc_url($saved_image) . '" style="max-width:100%; border:1px solid #888;" />';

        echo '<p style="margin-top:20px;"><a href="' . esc_url(get_permalink()) . '" class="button">Continue</a></p>';
    } else {
        // ✅ Form shown only if submission doesn't exist
        ?>
        <form method="post" onsubmit="saveCanvasImage()" enctype="multipart/form-data">
            <p><strong>Write a one sentence summary of what you learned in this lesson.</strong> Focus on key concepts, ideas, or takeaways that you want to remember later.</p>
            <label for="mindmap_note">Summary Note:</label><br>
            <textarea name="mindmap_note" rows="6" cols="80"><?php echo esc_textarea($saved_note); ?></textarea><br><br>

            <p><strong>Now visualize what you wrote using a drawing.</strong> Try to use abstract or symbolic images – the weirder or more personal, the better for memory! <strong>Be aware, 20 sec is the upper bound!</strong></p>
            <label for="canvas">Abstract image:</label><br>
            <canvas id="canvas" width="600" height="400" style="border:1px solid #ccc;"></canvas><br>
            <input type="hidden" name="canvas_data" id="canvas_data"><br>

            <input type="submit" value="Save Mind Map">
        </form>

        <script>
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            canvas.addEventListener('mousedown', function(e) {
                drawing = true;
                const rect = canvas.getBoundingClientRect();
                ctx.beginPath();
                ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
            });

            canvas.addEventListener('mouseup', () => drawing = false);
            canvas.addEventListener('mouseout', () => drawing = false);

            canvas.addEventListener('mousemove', function(e) {
                if (!drawing) return;
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000';
                ctx.lineTo(x, y);
                ctx.stroke();
            });

            function saveCanvasImage() {
                const dataURL = canvas.toDataURL('image/png');
                document.getElementById('canvas_data').value = dataURL;
            }
        </script>
        <?php
    }

    return ob_get_clean();
}



function show_module_summary($term_id) {
    $summary = get_field('module_summary', 'course_topic_' . $term_id);
    echo '<div class="module-summary">' . wpautop(wp_kses_post($summary)) . '</div>';
    echo '<a href="' . esc_url(home_url('/courses')) . '">Back to Courses</a>';
}

function route_to_next_lesson_or_module_summary($lesson_id, $term_id) {
    $next = get_next_lesson_id($lesson_id, $term_id);
    if ($next) {
        wp_redirect(add_query_arg([
            'lesson_id' => $next,
            'term_id' => $term_id,
            'exercise_number' => 1,
            'stage' => 'lesson_intro',
        ], get_permalink()));
    } else {
        wp_redirect(add_query_arg([
            'term_id' => $term_id,
            'stage' => 'module_summary',
        ], get_permalink()));
    }
    exit;
}

function get_first_lesson_id($term_id) {
    $lessons = get_posts([
        'post_type' => 'lesson',
        'posts_per_page' => 1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'lesson_number',
        'tax_query' => [[
            'taxonomy' => 'course_topic',
            'field' => 'term_id',
            'terms' => $term_id,
        ]]
    ]);
    return $lessons ? $lessons[0]->ID : 0;
}

function get_next_lesson_id($current_lesson_id, $term_id) {
    $all_lessons = get_posts([
        'post_type' => 'lesson',
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'lesson_number',
        'order' => 'ASC',
        'tax_query' => [[
            'taxonomy' => 'course_topic',
            'field' => 'term_id',
            'terms' => $term_id,
        ]]
    ]);

    foreach ($all_lessons as $i => $lesson) {
        if ($lesson->ID == $current_lesson_id && isset($all_lessons[$i + 1])) {
            return $all_lessons[$i + 1]->ID;
        }
    }
    return 0;
}

function show_user_mind_map_library() {
    $user_id = get_current_user_id();
    $lessons = get_posts(['post_type' => 'lesson', 'numberposts' => -1]);
    ob_start();
    echo "<h2>Your Mind Map Library</h2>";
    foreach ($lessons as $lesson) {
        $note = get_user_meta($user_id, "mindmap_note_{$lesson->ID}", true);
        if ($note) {
            echo "<h3>" . esc_html(get_the_title($lesson->ID)) . "</h3>";
            echo "<p>" . esc_html($note) . "</p>";
            $image = get_user_meta($user_id, "mindmap_image_{$lesson->ID}", true);
            if ($image) echo "<img src='" . esc_url($image) . "' style='max-width:300px;'><br>";
            // Add image preview logic here
        }
    }
    return ob_get_clean();
}
add_shortcode('mind_map_library', 'show_user_mind_map_library');

function add_tooltips_to_content($content) {
    $glossary = [
        'XOR' => 'XOR (exclusive OR) is a logical operation where the result is true if the inputs are different. It is not linearly separable.',
        'MLP' => 'MLP (Multilayer Perceptron) is a type of neural network with one or more hidden layers, capable of learning non-linear functions.',
        'dot products' => 'A dot product is an operation that takes two equal-length sequences of numbers and returns a single number.',
        'step function' => 'The step function returns 1 if the input is positive, otherwise 0.',
        'error correction' => 'Error correction in perceptrons adjusts weights based on the difference between predicted and true labels.',
        'matrix inversion' => 'Matrix inversion is the process of finding a matrix that, when multiplied with the original, yields the identity matrix.',
        'eigenvalue decomposition' => 'A matrix factorization that breaks a matrix into its eigenvalues and eigenvectors.',
        'probability theory' => 'A branch of mathematics dealing with random variables, distributions, and uncertainty modeling.',
        'polynomial fitting' => 'A method of modeling relationships by fitting a polynomial curve to the data.',
        'kernel methods' => 'A technique used in machine learning to operate in high-dimensional feature spaces.',
        'least squares optimization' => 'A method for minimizing the sum of squares of differences between observed and predicted values.',
        'gradient descent' => 'An optimization algorithm that minimizes a function by iteratively moving in the direction of steepest descent.',
        'backpropagation' => 'Backpropagation is the algorithm used to train neural networks by adjusting weights via gradient descent.'
    ];

    foreach ($glossary as $term => $definition) {
        // Case-insensitive replace, only the first match per term
        $pattern = '/\b(' . preg_quote($term, '/') . ')\b/i';
        $replacement = '<span class="tooltip">\1<span class="tooltip-text">' . esc_attr($definition) . '</span></span>';
        $content = preg_replace($pattern, $replacement, $content, 1); // replace only first occurrence
    }

    return $content;
}
// add_filter('the_content', 'add_tooltips_to_content');
// add_action('wp_enqueue_scripts', function() {
//     wp_enqueue_style('tooltip-style', get_stylesheet_directory_uri() . '/css/tooltip.css');
// });
?>