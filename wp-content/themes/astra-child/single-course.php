<?php
/**
 * Template for displaying single Course posts
 * Overrides default single post view for post type: course
 */

get_header();

global $post;

// Set course ID in session so [ml_lesson_clusters] works automatically
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['selected_course_id'] = $post->ID;
?>

<main class="ml-course-single">
  <div class="ml-course-inner">
    <h1 class="course-title"><?php echo esc_html(get_the_title()); ?></h1>
    <div class="ml-learning-path-content">
      <?php echo do_shortcode('[ml_lesson_clusters]'); ?>
    </div>
  </div>
</main>

<style>
.ml-course-single {
    width: 100%;              /* ✅ Fill full available width */
    max-width: 100%;          /* ✅ Avoid being narrower than parent */
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    padding: 0 20px;
    box-sizing: border-box;
}

.ml-course-inner {
    width: 100%;
    max-width: 1000px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;

    /* ✅ Instead: */
    align-items: center; /* center child blocks */

    text-align: left;    /* keep text aligned right if needed */
}

/* Title */
.course-title {
    text-align: center;
    font-size: 28px;
    margin-bottom: 40px;
    width: 100%;
}
.tooltip {
    position: relative;
    cursor: help;
    border-bottom: 1px dotted #555;
}

.tooltip .tooltip-text {
    visibility: hidden;
    width: max-content;
    max-width: 300px;
    background-color: #333;
    color: #fff;
    text-align: left;
    border-radius: 6px;
    padding: 8px;
    position: absolute;
    z-index: 1000;
    bottom: 125%; /* Position above the word */
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>


<?php wp_footer(); ?>

