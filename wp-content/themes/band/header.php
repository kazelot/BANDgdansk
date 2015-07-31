<?php
	/*-----------------------------------------------------------------------------------*/
	/* This template will be called by all other template files to begin 
	/* rendering the page and display the header/nav
	/*-----------------------------------------------------------------------------------*/
?>
<!DOCTYPE html <?php language_attributes(); ?>>
<html style="margin-top: 0 !important">
    <head>    
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title>
	        <?php bloginfo('name'); // show the blog name, from settings ?> | 
	        <?php is_front_page() ? bloginfo('description') : wp_title(''); // if we're on the home page, show the description, from the site's settings - otherwise, show the title of the post or page ?>
        </title>
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="<?= get_template_directory_uri(); ?>/vendor/foundation/css/foundation.min.css" />        
        <link rel="stylesheet" href="<?= get_template_directory_uri(); ?>/vendor/swal/sweet-alert.css" />
        <link rel="stylesheet" href="<?= get_template_directory_uri(); ?>/style.css" />
        <script src="<?= get_template_directory_uri(); ?>/vendor/swal/sweet-alert.min.js"></script>

        <?php wp_head(); ?>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>        
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>        
        <script src="//maps.googleapis.com/maps/api/js?language=pl&sensor=false&ver=3.3.8"></script>     
        
    </head>

    <body <?php body_class(); ?>>
        <div class="splash">
            <div class="row">
                <div class="columns small-10 medium-5">
                    <a href="<?= home_url('/'); ?>" class="hide-for-small"><img src="<?= get_template_directory_uri(); ?>/images/logo.png" alt="" class="logo" /></a>
                    <a href="<?= home_url('/'); ?>" class="show-for-small-only"><img src="<?= get_template_directory_uri(); ?>/images/logo.png" alt="" class="logo" /></a>
                </div>
                <div class="columns small-2 medium-7 hide-for-small menu">
                    <?php
                        $defaults = array(
                            'theme_location'  => 'primary',
                            'container'       => '',
                            'menu_class'      => 'menu',
                            'echo'            => true,

                            'before'          => '',
                            'after'           => '',
                            'items_wrap'      => '<ul class="inline-list">%3$s</ul>',
                        );

                        wp_nav_menu( $defaults );                                        
                    ?>                
                </div>
                <div class="columns small-2 hide-for-medium-up text-right">
                    <img src="<?= get_template_directory_uri(); ?>/images/menu.png" alt="" class="menu-trigger" />
                </div>
            </div>
            <?php if(is_front_page()) { ?>
            <div class="hero row">
                <div class="columns small-12 medium-8">
                    <div class="apla">
                        <h1>
                            <b>Chcesz aby Gdańsk był jeszcze bardziej zielonym miastem?</b><br />
                            Pomóż nam znaleźć miejsce, <br />gdzie można posadzić drzewa!
                        </h1>
                        <a href="<?= get_the_permalink(26); ?>">Posadź drzewo</a>
                    </div>
                </div>
            </div>
                        
            <div class="counter">
                Zgłoszonych terenów:
                <?php
                    $totalCount = 0;
                    function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {
                        global $wpdb;
                        if( empty( $key ) )
                            return;
                        $r = $wpdb->get_results( $wpdb->prepare( "
                            SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} pm
                            LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                            WHERE pm.meta_key = '%s' 
                            AND p.post_status = '%s' 
                            AND p.post_type = '%s'
                        ", $key, $status, $type ));

                        foreach ( $r as $my_r )
                            $metas[$my_r->ID] = $my_r->meta_value;

                        return $metas;
                    }  
                    
                    $metas = get_meta_values('_ilosc', 'zgloszenia');
                    foreach($metas as $meta)
                    {
                        $totalCount += $meta ? $meta : 1;
                    }
                    /*                 
                    $query = new WP_Query(array('post_type'=>'zgloszenia', 'posts_per_page'=>99999));
                    
                    foreach($query->posts as $post)
                    {
                        $count = get_post_meta($post->ID, '_ilosc');
                        $totalCount += $count[0];    
                    }
                    */
                ?>
                <b><?= (int)$totalCount; ?></b>
            </div>                        
            <? } ?>
        </div>    
