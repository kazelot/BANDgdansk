            <section class="interesting">
                <div class="row">
                    <div class="columns medium-4 small-12">
                        <h2>Czy wiesz że?</h2>
                        
                        <p>Najwyższe żyjące drzewo świata ma wysokość wynoszącą 115.61m.</p>
                        <p>Najgrubsze żyjące drzewo świata: cyprysik meksykański o średnicy ponad 14 m.</p>
                    </div>
                    <div class="columns medium-8 hide-for-small">
                        <h2>Ostatnio zasadzone<br />drzewa</h2>
                        
                        <div class="row">
                            <?php
                                $query = new WP_Query(array('post_type'=>'zgloszenia', 'posts_per_page'=>4, 'meta_key'=>'_status', 'meta_value'=>'planted'));
                                $drzewa = typyObiektow();
                                foreach($query->posts as $post)
                                {
                                    $typy = get_post_meta($post->ID, '_drzewa');
                                    $typy = json_decode($typy[0]);
                                    foreach($drzewa as $key => $info)
                                    {
                                        if($info[1] == $typy[0])
                                        {
                                            $name = $key;
                                            $icon = $info[0];
                                            break;
                                        }
                                    }                          
                                    ?>
                                    <div class="columns medium-6">
                                        <img src="<?= get_template_directory_uri(); ?>/images/drzewa/<?= $icon; ?>" alt="<?= $name; ?>" class="left" />
                                        <b><?= $name; ?></b><br />                                        
                                        <?= get_post_meta($post->ID, '_ulica', true); ?>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>