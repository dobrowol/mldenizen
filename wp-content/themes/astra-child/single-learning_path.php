<?php
/**
 * Szablon dla pojedynczych wpisów typu "Sciezka nauki"
 */
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content">
                        <?php
                        the_content();

                        // Pobieramy dane z Pods
                        $pod = pods( 'learning_path', get_the_ID() );
                        $subgrupy = $pod->field( 'podgrupy' ); // "podgrupy" – nazwa pola relacyjnego
                        if ( ! empty( $subgrupy ) ) {
                            echo '<div class="subgroups horizontal-scroll">';
                            foreach ( $subgrupy as $subgrupa ) {
                                echo '<div class="subgroup">';
                                echo '<h3>' . esc_html( $subgrupa['post_title'] ) . '</h3>';
                                // Tutaj mozesz dodac dodatkowe dane, np. opis lub obrazek
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p>Brak podgrup do wyswietlenia.</p>';
                        }
                        ?>
                    </div><!-- .entry-content -->
                </article>
                <?php
            endwhile;
        endif;
        ?>
    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>