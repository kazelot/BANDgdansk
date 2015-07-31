<?php
/**
* Template Name: Strona główna
*/
get_header();
?>
        
        <div class="intro">
            <div class="row">
                <div class="columns small-12 medium-3">Jak posadzić drzewo?</div>
                <div class="columns  medium-1">1</div>
                <div class="columns small-12 medium-2">Znajdź działkę na mapie lub wprowadź adres</div>
                <div class="columns all medium-1">2</div>
                <div class="columns small-12 medium-2">Wypełnij formularz i poczekaj na kontakt z&nbsp;Urzędu Miejskiego</div>
                <div class="columns  medium-1">3</div>
                <div class="columns small-12 medium-2">Umów się z Urzędem na dogodny termin i ciesz się zielenią</div>
                
            </div>
        </div>
        
        <div class="row news news--header hide-for-small">
            <div class="columns medium-8"><h2>Aktualności</h2></div>
            <div class="columns medium-4 text-right"><a href="<?= get_the_permalink(9); ?>" class="more">zobacz wszystkie aktualności</a></div>
        </div>
        <div class="row news hide-for-small">
            <?php
                $query = new WP_Query(array(
                    'posts_per_page'    =>  4,
                    'post_type' =>  'post',
                ));
                if($query->have_posts())
                {
                    while($query->have_posts())
                    {
                        $query->the_post();
                        ?>
                            <article class="news columns small-12 medium-3">
                            
                                <time><?= the_time('d F Y'); ?></time>
                                <h1 class="title">
                                    <a href="<?php the_permalink(); // Get the link to this post ?>" title="<?php the_title(); ?>">
                                        <?php the_title(); // Show the title of the posts as a link ?>
                                    </a>
                                </h1>
                                <?
                                    the_excerpt(100);
                                ?>
                                
                            </article>                        
                        <?php
                    }
                }
            ?>
        </div>
        
        <?php include(dirname(__FILE__) . '/_interesting.php'); ?>                         
<?php get_footer(); ?>        