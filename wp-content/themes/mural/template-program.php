<?php
/**
 * Template Name: Programmation
 *
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="site-content">
        <?php get_template_part('parts/inc', 'banner'); ?>

		<div class="content-wrap">
            <?php if(get_the_content()): ?>
                <section class="basic-content">
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php get_template_part('parts/inc', 'order'); ?>

            <section class="list-programs">

                

                <div class="content">
                <div id='calendar'></div>
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => array( 'program' ),
                        'posts_per_page' => 9,
                        'nopaging' => false,
                        'paged' => $paged,
                        'orderby' => array(
                            'order_event' => 'ASC',
                            'order_start_time' => 'ASC'
                        ),
                        'meta_query' => array(
                            'order_event' => array(
                                'key' => 'event_date'
                            ),
                            'order_start_time' => array(
                                'key' => 'heure_de_debut'
                            )
                        )
                    );

                    if(isset($_GET['filtre-artiste']) && $_GET['filtre-artiste'] != ''){
                        $artist_arg= [
                            'post_type' => 'artist',
                            'posts_per_page' => 1,
                            'post_name__in' => array(sanitize_text_field($_GET['filtre-artiste'])),
                            'fields' => 'ids'
                        ];
                        $q = get_posts($artist_arg);                        
                        $args['meta_query'][] = array(
                            'key' => 'artiste',
                            'value' => serialize(strval($q[0])),
                            'compare' => 'LIKE'
                        );
                    }


                if (isset($_GET['category']) && $_GET['category'] != '') {
                    $args['tax_query'][] = array(
                        'taxonomy' => 'event-category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    );                    
                }


                    if(isset($_GET['date']) && $_GET['date'] != ''){
                        $posted_date = $_GET['date'];

                        if(strrpos($posted_date, ' - ')){
                            $daterange = explode(' - ', $posted_date);

                            $start_date = DateTime::createFromFormat('d/m/Y', $daterange[0]);
                            $end_date = DateTime::createFromFormat('d/m/Y', $daterange[1]);

                            $args['meta_query'][] = array(
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATE',
                                    'value' => $end_date->format('Ymd'),
                                    'compare' => '<='
                                ),
                                array(
                                    'key' => 'date_de_fin',
                                    'type' => 'DATE',
                                    'value' => $start_date->format('Ymd'),
                                    'compare' => '>='
                                )
                            );

                        }else{
                            $date = DateTime::createFromFormat('d/m/Y', $posted_date);

                            $args['meta_query'][] = array(
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATE',
                                    'value' => $date->format('Ymd'),
                                    'compare' => '<='
                                ),
                                array(
                                    'key' => 'date_de_fin',
                                    'type' => 'DATE',
                                    'value' => $date->format('Ymd'),
                                    'compare' => '>='
                                )
                            );
                        }
                    }

                    $query = new WP_Query( $args );
                    global $wp_query;
                    // Put default query object in a temp variable
                    $tmp_query = $wp_query;
                    // Now wipe it out completely
                    $wp_query = null;
                    // Re-populate the global with our custom query
                    $wp_query = $query;
            
                    if ( $query->have_posts() ) : ?>
                        <section class="programs">
                            <?php while ( $query->have_posts() ) :
                                $query->the_post();
                                ?>
                
                                <?php //get_template_part('parts/program', 'article');
                                $events[]=(object) [
                                    'id'            => get_the_id(),
                                    'title'         =>  html_entity_decode(get_the_title()),
                                    'start'         =>  date('Y-m-d', strtotime(get_field('event_date'))),
                                    
                                    'url'           => get_the_permalink(),
                                    'classNames'           => ['event-image','event-background-'.get_the_id()]
                                ];
                                $events_backgrounds.='.event-background-'.get_the_id().'{background:url('.wp_get_attachment_image_src(get_field('image_de_levenement'), 'cta-preview')[0].'); background-size:cover;height:38px;text-align:center;}';

                                ?>

                            <?php endwhile; ?>
                        </section>
                        <?php get_template_part('parts/program', 'pager');  ?>
                    <?php else: ?>
                        <p><?php _e('No program found.', 'site-theme'); ?></p>
                    <?php endif; ?>
            
                    <?php
                    $wp_query = null;
                    $wp_query = $tmp_query;
                    wp_reset_postdata(); ?>
                </div>
			</section>
		</div>
	</article>
<?php endwhile; ?>

<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    console.log( <?php echo json_encode($events); ?>);
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'dayGrid' ],
      defaultDate: '<?php echo date('Y')."-".date('m').'-'.date('j'); ?>',
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: <?php echo json_encode($events); ?>
      
    });

    calendar.render();
  });

</script>
<style>


  #calendar {
    max-width: 100%;
    margin: 0 auto;
    font-size: 14px;
  }
  .fc-content .fc-title{
    background:rgba(0,0,0,0.8);
    color:#fff;
    display:block;
    text-transform:uppercase;
    line-height:19px;
  }
  .fc-day-grid-event .fc-content{
    white-space:unset;
    width:100%;
  }
  .fc-day-grid-event{
    display: flex;
    align-items: center;
    justify-content: top;
    overflow:hidden;
    border-radius: 0;
    border-color: transparent;
    flex-direction: column;
    padding:0;
  }
  .fc-day-grid-event{ > span
  /* these are the flex items */
  flex: 1;
}
  
  <?php echo $events_backgrounds; ?>
</style>

<?php get_footer(); ?>