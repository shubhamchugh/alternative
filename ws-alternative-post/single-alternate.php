<?php

/**

 * The template for displaying all single posts

 *

 */

//locate_template(.'includes/wp_booster/td_single_template_vars.php', true);



get_header();



global $loop_module_id, $loop_sidebar_position, $post, $td_sidebar_position;

//$td_post_theme_settings = get_post_meta($post->ID, 'td_post_theme_settings',true);
//print_r($td_post_theme_settings);
//$loop_sidebar_position = $td_post_theme_settings['td_sidebar_position'];
$td_mod_single = new td_module_single($post);

?>

<div class="td-main-content-wrap td-container-wrap">



    <div class="td-container td-post-template-default <?php echo $td_sidebar_position; ?>">

        <div class="td-crumb-container"><?php echo td_page_generator::get_single_breadcrumbs($td_mod_single->title); ?></div>



        <div class="td-pb-row">

            <?php



            //the default template

            switch ($loop_sidebar_position) {

                default: //sidebar right

					?>

                        <div class="td-pb-span8 td-main-content" role="main">

                            <div class="td-ss-main-content">

                                <?php

                                 require_once ( WSALTERNATIVEPATH.'loop-alternative.php');

                                comments_template('', true);

                                ?>

                            </div>

                        </div>

                        <div class="td-pb-span4 td-main-sidebar" role="complementary">

                            <div class="td-ss-main-sidebar">

                                <?php get_sidebar(); ?>

                            </div>

                        </div>

                    <?php

                    break;



                case 'sidebar_left':

                    ?>

                        <div class="td-pb-span8 td-main-content <?php echo $td_sidebar_position; ?>-content" role="main">

                            <div class="td-ss-main-content">

                                <?php

                                require_once ( WSALTERNATIVEPATH.'loop-alternative.php');

                                comments_template('', true);

                                ?>

                            </div>

                        </div>

		                <div class="td-pb-span4 td-main-sidebar" role="complementary">

			                <div class="td-ss-main-sidebar">

				                <?php get_sidebar(); ?>

			                </div>

		                </div>

                    <?php

                    break;



                case 'no_sidebar':

                    td_global::$load_featured_img_from_template = 'td_1068x0';

                    ?>

                        <div class="td-pb-span12 td-main-content" role="main">

                            <div class="td-ss-main-content">

                                <?php

                                require_once ( WSALTERNATIVEPATH.'loop-alternative.php');

                                comments_template('', true);

                                ?>

                            </div>

                        </div>

                    <?php

                    break;



            }

            ?>

        </div> <!-- /.td-pb-row -->

    </div> <!-- /.td-container -->

</div> <!-- /.td-main-content-wrap -->

<?php get_footer(); ?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery("#td_unique_alternative_slider").iosSlider({
        snapToChildren: true,
        desktopClickDrag: true,
        keyboardControls: false,
        responsiveSlideContainer: true,
        responsiveSlides: true, 
        autoSlide: true,
        infiniteSlider: true,
        navPrevSelector: jQuery("#td_unique_alternative_slider .prevButton"),
        navNextSelector: jQuery("#td_unique_alternative_slider .nextButton")
    });
});
</script>