<?php
	/*-----------------------------------------------------------------------------------*/
	/* This template will be called by all other template files to finish 
	/* rendering the page and display the footer area/content
	/*-----------------------------------------------------------------------------------*/
?>
        <?php if(!is_front_page()) { ?>
            
            <div class="intro">
                <div class="row">
                    <div class="columns small-12 medium-3">Jak zasadzić drzewo?</div>
                    <div class="columns  medium-1">1</div>
                    <div class="columns small-12 medium-2">Znajdź działkę na mapie lub wprowadź adres</div>
                    <div class="columns all medium-1">2</div>
                    <div class="columns small-12 medium-2">Wypełnij formularz i poczekaj na kontakt z&nbsp;Urzędu Miejskiego</div>
                    <div class="columns  medium-1">3</div>
                    <div class="columns small-12 medium-2">Umów się z Urzędem na dogodny termin i ciesz się zielenią</div>  
                </div>
            </div>                
             

            <?php include(dirname(__FILE__) . '/_interesting.php'); ?>                 
            
        <?php } ?>
        <footer class="row">
            <div class="columns small-12 medium-8">
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
            <div class="columns small-12 medium-4 small-text-center medium-text-right">
                <a href="http://gdansk.pl/" target="_blank"><img src="<?= get_template_directory_uri(); ?>/images/logo.gdansk.png" alt="Gdańsk" /></a>
            </div>
        </footer>
        <script>
            $('.menu-trigger').click(function() {
                $('body').toggleClass('hasMenu');
            })
        </script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-55414074-2', 'auto');
            ga('send', 'pageview');
        </script>        
        <?php wp_footer(); ?>
    </body>
</html>
