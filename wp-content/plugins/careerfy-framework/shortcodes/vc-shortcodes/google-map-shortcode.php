<?php
/**
 * Google Map Shortcode
 * @return html
 */
add_shortcode('careerfy_google_map', 'careerfy_google_map_shortcode');

function careerfy_google_map_shortcode($atts) {
    global $jobsearch_plugin_options;
    extract(shortcode_atts(array(
        'map_latitude' => '51.2',
        'map_longitude' => '0.2',
        'map_zoom' => '8',
        'map_height' => '350',
        'map_street_view' => 'yes',
        'map_scrollwheel' => 'yes',
        'map_default_ui' => 'no',
        'map_marker' => '',
        'map_styles' => '',
                    ), $atts));

    $cnt_counter = rand(1000000, 9999999);

    ob_start();
    $cont_frm_class = 'col-md-12';
    if ($map_latitude != '' && $map_longitude != '' && $map_zoom > 0) {

        $cont_frm_class = 'col-md-8';
        $location_map_type = isset($jobsearch_plugin_options['location_map_type']) ? $jobsearch_plugin_options['location_map_type'] : '';
        $mapbox_access_token = isset($jobsearch_plugin_options['mapbox_access_token']) ? $jobsearch_plugin_options['mapbox_access_token'] : '';
        $mapbox_style_url = isset($jobsearch_plugin_options['mapbox_style_url']) ? $jobsearch_plugin_options['mapbox_style_url'] : '';
        if ($location_map_type == 'mapbox') {
            wp_enqueue_script('jobsearch-mapbox');
        } else {
            if (isset($jobsearch_plugin_options['jobsearch-google-api-key']) && $jobsearch_plugin_options['jobsearch-google-api-key'] != '') {
                wp_enqueue_script('jobsearch-google-map');
            } else {
                wp_enqueue_script('careerfy-google-map');
            }
        }
        ?>
        <div class="careerfy-map"> <div id="map-<?php echo absint($cnt_counter) ?>" style="height:<?php echo absint($map_height) ?>px;"></div> </div>
        <?php
        $map_args = array(
            'location_map_type' => $location_map_type,
            'cnt_counter' => $cnt_counter,
            'map_latitude' => $map_latitude,
            'map_longitude' => $map_longitude,
            'map_zoom' => $map_zoom,
            'map_street_view' => $map_street_view,
            'map_scrollwheel' => $map_scrollwheel,
            'map_default_ui' => $map_default_ui,
            'map_styles' => $map_styles,
            'map_marker' => $map_marker,
        );
        add_action('wp_footer', function() use ($map_args) {
            global $jobsearch_plugin_options;
            extract(shortcode_atts(array(
            'location_map_type' => '',
            'cnt_counter' => '',
            'map_latitude' => '51.2',
            'map_longitude' => '0.2',
            'map_zoom' => '8',
            'map_street_view' => 'yes',
            'map_scrollwheel' => 'yes',
            'map_default_ui' => 'no',
            'map_marker' => '',
            'map_styles' => '',
                        ), $map_args));
        ?>
        <script>
            <?php
            if ($location_map_type == 'mapbox') {
                $mapbox_style_url = isset($jobsearch_plugin_options['mapbox_style_url']) ? $jobsearch_plugin_options['mapbox_style_url'] : '';
                $mapbox_access_token = isset($jobsearch_plugin_options['mapbox_access_token']) ? $jobsearch_plugin_options['mapbox_access_token'] : '';
                if ($map_styles == '') {
                    $map_styles = $mapbox_style_url;
                }
                ?>
                jQuery(document).ready(function () {
                    mapboxgl.accessToken = '<?php echo ($mapbox_access_token) ?>';
                    var map = new mapboxgl.Map({
                        container: 'map-<?php echo absint($cnt_counter); ?>',
                        style: '<?php echo ($map_styles) ?>',
                        center: [<?php echo esc_js($map_longitude) ?>, <?php echo esc_js($map_latitude) ?>],
                        scrollZoom: <?php echo ($map_scrollwheel == 'no' ? 'false' : 'true') ?>,
                        zoom: <?php echo esc_js($map_zoom) ?>
                    });
                    map.addControl(new mapboxgl.NavigationControl({
                        showCompass: false    
                    }), 'top-right');
                    <?php
                    if ($map_marker != '') {
                        ?>
                        var el = new Image();
                        el.src = '<?php echo ($map_marker) ?>';
                        el.classList.add('mapMarker');
                        el.dataset.type = 'point';
                        <?php
                    }
                    ?>
                    var marker = new mapboxgl.Marker({
                        <?php if ($map_marker != '') { ?>element: el, <?php } ?>
                        draggable: false
                    }).setLngLat([<?php echo esc_js($map_longitude) ?>, <?php echo esc_js($map_latitude) ?>]).addTo(map);
                });
                <?php
            } else {
                ?>
                function initMap_<?php echo absint($cnt_counter) ?>() {
                var myLatLng = {lat: <?php echo esc_js($map_latitude) ?>, lng: <?php echo esc_js($map_longitude) ?>};
                        var map = new google.maps.Map(document.getElementById('map-<?php echo absint($cnt_counter) ?>'), {
                        zoom: <?php echo esc_js($map_zoom) ?>,
                                center: myLatLng,
                <?php
                if ($map_street_view == 'no') {
                    ?>
                                streetViewControl: false,
                    <?php
                }
                if ($map_scrollwheel == 'no') {
                    ?>
                                scrollwheel: false,
                    <?php
                }
                if ($map_default_ui == 'yes') { ?>
                                mapTypeControl: false,
                    <?php } ?>
                            });
                <?php
                if ($map_styles != '') {

                $map_styles = stripslashes($map_styles);
                $map_styles = str_replace(array('``', '`{', '}`', '[{[{', '}]}]', '<br />', '<br>', '<br/>', '\n', '\r\n', ' ', '[{`', '`}]'), array('"', '[{', '}]', '[{', '}]', '', '', '', '', '', '', '[', ']'), $map_styles);
                $map_styles = preg_replace('/\s+/', ' ', trim($map_styles));
                ?>
                    var styles = '<?php echo ($map_styles) ?>';
                            if (styles != '') {
                    styles = jQuery.parseJSON(styles);
                            var styledMap = new google.maps.StyledMapType(
                                    styles,
                            {name: 'Styled Map'}
                            );
                            map.mapTypes.set('map_style', styledMap);
                            map.setMapTypeId('map_style');
                    }
                <?php } ?>

                var marker = new google.maps.Marker({
                position: myLatLng,
                        map: map,
                        title: '',
                        icon: '<?php echo esc_js($map_marker) ?>',
                });
                }
                google.maps.event.addDomListener(window, 'load', initMap_<?php echo absint($cnt_counter) ?>);
                <?php
            }
            ?>
        </script>
        <?php
        }, 99, 1);
    }
    $html = ob_get_clean();
    
    return $html;
}