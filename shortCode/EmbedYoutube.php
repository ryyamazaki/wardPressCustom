<?php
function cwc_youtube($atts) {
    extract(shortcode_atts(array(
        "value" => 'http://',
        "width" => '475',
        "height" => '350',
        "name"=> 'movie',
        "allowFullScreen" => 'true',
        "allowScriptAccess"=>'always',
    ), $atts));
    return '<object style="height: '.$height.'px; width: '.$width.'px"><param name="'.$name.'" value="'.$value.'"><param name="allowFullScreen" value="'.$allowFullScreen.'"></param><param name="allowScriptAccess" value="'.$allowScriptAccess.'"></param><embed src="'.$value.'" type="application/x-shockwave-flash" allowfullscreen="'.$allowFullScreen.'" allowScriptAccess="'.$allowScriptAccess.'" width="'.$width.'" height="'.$height.'"></embed></object>';
}
add_shortcode("youtube", "cwc_youtube");

//[youtube value="http://www.youtube.com/watch?v=1aBSPn2P9bg"]
