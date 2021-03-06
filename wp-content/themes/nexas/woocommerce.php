<?php
  /**
  * The template for displaying all pages.
  * This is the template that displays all pages by default.
  * Please note that this is the WordPress construct of pages
  * and that other 'pages' on your WordPress site may use a
  * different template.
  *
  * @link https://codex.wordpress.org/Template_Hierarchy
  *
  * @package Paragon Themes
  * @subpackage Nexas
  */
  $nexas_designlayout      = nexas_get_option('nexas_sidebar_layout_option');
  get_header();
  ?>

    <section id="inner-title" class="inner-title" >
        <div class="container">
            <div class="row">
                <div class="col-md-8"><h2><?php esc_html_e('Store', 'nexas') ?></h2>
                </div>
            </div>
        </div>
    </section >

    <section id="section16" class="section16">

        <div class="container">

            <div class="row">

                <div class="col-md-<?php if ($nexas_designlayout == 'no-sidebar') {

                    echo "12";

                } else {

                    echo "9";

                } ?> left-block">

                    <?php if (have_posts()) :
                        woocommerce_content();

                    endif;
                    ?>
                </div><!-- div -->

                <?php if ( $nexas_designlayout != 'no-sidebar' ) { ?>

                    <div class="col-md-3">

                        <?php get_sidebar(); ?>

                    </div>

                <?php } ?>


            </div><!-- div -->
        </div>
    </section>
  <?php get_footer();