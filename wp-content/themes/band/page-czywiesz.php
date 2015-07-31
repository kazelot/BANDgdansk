<?php
/**
 * Template Name: Czy wiesz, że?
 *
 */

get_header(); // This fxn gets the header.php file and renders it ?>
    <script src="<?= get_template_directory_uri(); ?>/js/masonry.js"></script>
    <div class="row">
        <div class="columns small-12 ">
            <br />
            <h1>Czy wiesz, że?</h1>
            <br />
            <div class="masonry">
                <?php 
                $query = new WP_Query(array(
                    'post_type' =>  'czywieszze',
                    'posts_per_page'=>  999,
                ));
                foreach($query->posts as $post)
                {
                    ?>
                    <div class="czywieszze">
                        <div class="inner">
                            <b class="title"><?= $post->post_title; ?></b>
                            <?= apply_filters('the_content', $post->post_content); ?>
                        </div>
                    </div>
                    <?   
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        $('.masonry').masonry({
            columnWidth: 360,
            itemSelector: '.czywieszze'
        });    
    </script>
<?php get_footer();  ?>