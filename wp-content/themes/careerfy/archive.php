<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Careerfy
 */
get_header();
?>

<!--// Main Content \\-->
<div class="careerfy-main-content">

    <!--// Main Section \\-->
    <div class="careerfy-main-section">
        <div class="container">
            <div class="row">

                <?php
                $careerfy__options = careerfy_framework_options();
                $post_layout = isset($careerfy__options['careerfy-default-layout']) ? $careerfy__options['careerfy-default-layout'] : '';
                $post_sidebar = isset($careerfy__options['careerfy-default-sidebar']) ? $careerfy__options['careerfy-default-sidebar'] : '';

                $col_class = 'col-md-12';
                $content_column_class = 'col-md-6';
                if (is_active_sidebar('sidebar-1') && $post_layout == '') {
                    $col_class = 'col-md-9';
                    $content_column_class = 'col-md-12';
                } else if (is_active_sidebar($post_sidebar) && ( $post_layout == 'right' || $post_layout == 'left' )) {
                    $content_class = $post_layout == 'left' ? 'pull-right' : 'pull-left';
                    $col_class = 'col-md-9 ' . $content_class;
                    $content_column_class = 'col-md-12';
                }
                ?>

                <div class="<?php echo esc_html($col_class) ?>"> 
                    <?php if (have_posts()) : ?>
                        <div class="careerfy-posts-list careerfy-showing-result"> 
                            <?php
                            the_archive_title('<div class="careerfy-page-title"><h1>', '</h1></div>');
                            the_archive_description('<div class="archive-description">', '</div>');
                            ?>
                            <ul class="row">
                                <?php
                                /* Start the Loop */
                                while (have_posts()) : the_post();

                                    /*
                                     * Include the Post-Format-specific template for the content.
                                     * If you want to override this in a child theme, then include a file
                                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                     */
                                    set_query_var('content_column_class', $content_column_class);
                                    get_template_part('template-parts/content', get_post_format());

                                endwhile;
                                ?> 
                            </ul>
                        </div> 
                        <?php
                        careerfy_pagination();
                    else :

                        get_template_part('template-parts/content', 'none');

                    endif;
                    ?>

                </div>

                <?php get_sidebar(); ?>


            </div><!-- row -->
        </div><!-- container -->
    </div><!-- careerfy-main-section -->
</div><!-- careerfy-main-content -->

<?php
get_footer();
