<?php
/**
 * The template for displaying any single post.
 *
 */

get_header(); // This fxn gets the header.php file and renders it ?>
    <br />
    <div class="row news">
        <div class="columns small-12"><h1>Aktualności</h1></div>
    </div>    
    <div class="row news">
			<?php if ( have_posts() ) : 
			// Do we have any posts in the databse that match our query?
			?>

				<?php while ( have_posts() ) : the_post(); 
				// If we have a post to show, start a loop that will display it
				?>

					<article class="news post columns small-12">
					
                        <time><?= the_time('d F Y'); ?></time>
						<h1 class="title"><?php the_title();  ?></h1>
						
						<div class="the-content">
							<?php the_content(); ?>
							
						</div><!-- the-content -->
						
					</article>

				<?php endwhile; // OK, let's stop the post loop once we've displayed it ?>


			<?php else : // Well, if there are no posts to display and loop through, let's apologize to the reader (also your 404 error) ?>
				
				<article class="post error">
					<h1 class="404">Nothing has been posted like that yet</h1>
				</article>

			<?php endif; // OK, I think that takes care of both scenarios (having a post or not having a post to show) ?>
            <br />
            <br />
	</div>
<?php get_footer(); // This fxn gets the footer.php file and renders it ?>
