<?php

/**

 * The single post loop Default template

 **/



if (have_posts()) {

    the_post();



    $td_mod_single = new td_module_single($post);

    ?>



    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>

        <div class="td-post-header">



            <?php echo $td_mod_single->get_category(); ?>



            <header class="td-post-title">

                <?php echo $td_mod_single->get_title();?>





                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>

                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>

                <?php } ?>





                <div class="td-module-meta-info">

                    <?php echo $td_mod_single->get_author();?>

                    <?php echo $td_mod_single->get_date(false);?>

                    <?php echo $td_mod_single->get_comments();?>

                    <?php echo $td_mod_single->get_views();?>

                </div>



            </header>



        </div>



        <?php echo $td_mod_single->get_social_sharing_top();?>





        <div class="td-post-content">

        <div class="list-entry-thumbnail alternate-image">  
         <?php 
            $image_gallery = get_post_meta( $post->ID, '_wstheme_image_gallery', true );
            $attachments = array_filter( explode( ',', $image_gallery ) ); 
            $buffy = '';
            if ( $attachments )
            {
            $buffy .= '<div id="td_unique_alternative_slider" class="td-theme-slider  iosSlider-col-1 td_mod_wrap">';
            $buffy .= '<div class="td-slider ">';

            foreach ($attachments as $key => $attachment) {
                $aurl = wp_get_attachment_url( $attachment  );
                $buffy .= '<div class="slides"><img src="'.$aurl.'"></div>';                       
            }
            $buffy .= '</div>'; //close slider
            $buffy .= '<i class = "td-icon-left prevButton"></i>';
            $buffy .= '<i class = "td-icon-right nextButton"></i>';
            $buffy .= '</div>';         

            }
            echo $buffy; 

            $links = get_post_meta($post->ID, 'links', true); 
            if(!empty($links)){
                
                echo "<ul class='alt-link-list'>"; 
                $counter=0;
                foreach($links as $key => $link) {

                    
                    
                    if(isset($link['label']) && isset($link['url']) && !empty($link['url'])){

                        $label = $link['label'];

                        $url = $link['url'];
                        
                        $icon = '';
                        if($key == 'website'){
                            $icon = '<i class="fa fa-globe"></i>';
                        }elseif($key == 'youtube'){
                            $icon = '<i class="fa fa-youtube-play" aria-hidden="true"></i>';
                        }elseif($key == 'android'){
                            $icon = '<i class="fa fa-android" aria-hidden="true"></i>';
                        }elseif($key == 'download'){ 
                            $icon = '<i class="fa fa-download" aria-hidden="true"></i>';
                        }elseif($key == 'facebook'){
                            $icon = '<i class="fa fa-facebook-square" aria-hidden="true"></i>';
                        }elseif($key == 'itune'){
                            $icon = '<i class="fa fa-apple" aria-hidden="true"></i>';
                        }elseif($key == 'game_streaming'){
                            $icon = '<i class="fa fa-steam-square" aria-hidden="true"></i>';
                        }

                        echo '<li class="alt-link-item"><a href="'.$url.'" target="_blank" class="alt-link-item '.$key.'-icon" > '.$icon.'</a></li>';
                        
                    }   
                
                
                }
                echo "</ul>";
            }   

            ?>
            </div>
            
            <div class="td-page-content">

                <?php require_once ( WSALTERNATIVEPATH.'template/content.php'); ?>

            </div>

        </div>

        <footer>
            
            <?php require_once ( WSALTERNATIVEPATH.'template/post-alternatives.php'); ?>

            <?php echo $td_mod_single->get_post_pagination();?>

            <?php echo $td_mod_single->get_review();?>



            <div class="td-post-source-tags">

                <?php echo $td_mod_single->get_source_and_via();?>

                <?php echo $td_mod_single->get_the_tags();?>

            </div>



            <?php echo $td_mod_single->get_social_sharing_bottom();?>

            <?php echo $td_mod_single->get_next_prev_posts();?>

            <?php echo $td_mod_single->get_author_box();?>

	        <?php echo $td_mod_single->get_item_scope_meta();?>

        </footer>



    </article> <!-- /.post -->



    <?php echo $td_mod_single->related_posts();?>



<?php

} else {

    //no posts

    echo td_page_generator::no_posts();

}