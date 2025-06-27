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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const active = document.getElementById("active-module");
    if (active) {
        active.scrollIntoView({ behavior: "smooth", block: "start" });
    }
});
</script>
<?php wp_footer(); ?>

