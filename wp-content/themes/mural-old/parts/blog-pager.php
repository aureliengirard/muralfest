<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php if($paged > 0): ?>
    <nav class="pagination c12 gutter">
        <?php
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'       => '',
            'add_args'     => false,
            'current'      => max( 1, $paged ),
            'total'        => $wp_query->max_num_pages,
            'prev_text'    => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            'next_text'    => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
            'type'         => 'list',
            'end_size'     => 3,
            'mid_size'     => 3
        ) ); ?>
    </nav>
<?php endif; ?>