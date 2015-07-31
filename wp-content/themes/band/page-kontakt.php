<?php
/**
 * Template Name: Kontakt
 *
 */

get_header(); // This fxn gets the header.php file and renders it ?>
    <div class="row">
        <div class="columns small-12">
            <br />
            <?php if ( have_posts() ) : 
            // Do we have any posts/pages in the databse that match our query?
            ?>

                <?php while ( have_posts() ) : the_post(); 
                // If we have a page to show, start a loop that will display it
                ?>

                    <article class="post">
                    
                        
                        
                        <div class="the-content">
                            <div class="row">
                            
                                <div class="columns small-12 medium-6">
                                    <h1 class="title"><?php the_title(); // Display the title of the page ?></h1>
                                    <?php the_content(); ?>    
                                </div>
                                <div class="columns small-12 medium-6">
                                    <h2 class="title">Formularz kontaktowy</h2>
                                    <?php echo apply_filters('the_content', '[contact-form-7 id="87" title="Formularz kontaktowy"]'); ?>
                                </div>
                            </div>
                        </div><!-- the-content -->
                        
                    </article>

                <?php endwhile; // OK, let's stop the page loop once we've displayed it ?>

            <?php else : // Well, if there are no posts to display and loop through, let's apologize to the reader (also your 404 error) ?>
                
                <article class="post error">
                    <h1 class="404">Nothing posted yet</h1>
                </article>

            <?php endif;  ?>
            <br />
            <br />
            <br />
        </div>
    </div>
<?php get_footer();  ?>