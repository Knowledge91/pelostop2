<?php
/**
 * Archive pages.
 *
 * @package The7
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = presscore_config();
$config->set( 'template', 'archive' );
$config->set( 'layout', 'masonry' );
$config->set( 'template.layout.type', 'masonry' );

get_header();
?>
du hund
			<!-- Content -->
			<div id="content" class="content" role="main">

				<?php
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
exit();

				if ( have_posts() ) {
					the7_archive_loop();
                } else {
					get_template_part( 'no-results', 'search' );
                }
				?>

			</div><!-- #content -->

			<?php do_action( 'presscore_after_content' ) ?>

<?php get_footer() ?>
