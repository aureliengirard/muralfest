<div class="content">
    <div class="iframe-style">
        <div class="fluid-iframe">
            <?php
            $iframe = get_sub_field('video');
            
            // use preg_match to find iframe src
            preg_match('/src="(.+?)"/', $iframe, $matches);
            $src = $matches[1];
            
            // add extra params to iframe src
            $params = array(
                'controls'    => 1,
                'enablejsapi' => 1,
                'rel' => 0,
                'origin' => esc_url( home_url( '/' ) ),
                'showinfo' => 0,
                'wmode' => 'opaque',
            );
            $new_src = add_query_arg($params, $src);
            
            $iframe = str_replace($src, $new_src, $iframe);
            
            $lastslash = strrpos($src, '/')+1;
            $endID = strpos($src, '?', $lastslash);
            $videoID = substr($src, $lastslash, $endID-$lastslash);
            
            // add extra attributes to iframe html
            $attributes = 'frameborder="0" id="video_'.$videoID.'"';
            
            $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $iframe);
            
            // echo $iframe
            echo $iframe;
            ?>
        </div>
    </div>
</div>