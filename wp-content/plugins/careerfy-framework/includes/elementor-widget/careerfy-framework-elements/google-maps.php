<?php

namespace CareerfyElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;


/**
 * @since 1.1.0
 */
class GoogleMaps extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'google-maps';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Google Maps', 'careerfy-frame');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fa fa-map-marker';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['careerfy'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Google Maps Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'map_latitude',
            [
                'label' => __('Latitude', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'default' => '51.2',
                'description' => __("Set Latitude of map.", "careerfy-frame"),
            ]
        );
        $this->add_control(
            'map_longitude',
            [
                'label' => __('Longitude', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'default' => '0.2',
                'description' => __("Set Longitude of map.", "careerfy-frame"),
            ]
        );
        $this->add_control(
            'map_zoom',
            [
                'label' => __('Zoom', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'default' => '8',
                'description' => __("Set Zoom for the map.", "careerfy-frame"),
            ]
        );

        $this->add_control(
            'map_height',
            [
                'label' => __('Height', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'default' => '350',
                'description' => __("Set Height for map.", "careerfy-frame"),
            ]
        );

        $this->add_control(
            'map_street_view',
            [
                'label' => __('Street View', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'yes',
                'options' => [
                    'yes' => __('Yes', 'careerfy-frame'),
                    'no' => __('No', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'map_scrollwheel',
            [
                'label' => __('Scroll Wheel control', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'yes',
                'options' => [
                    'yes' => __('Yes', 'careerfy-frame'),
                    'no' => __('No', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'map_default_ui',
            [
                'label' => __('Disable Map Type', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'no',
                'options' => [
                    'yes' => __('Yes', 'careerfy-frame'),
                    'no' => __('No', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'map_marker',
            [
                'label' => __('Marker Icon', 'careerfy-frame'),
                'type' => Controls_Manager::MEDIA,
                'description' => __("Put custom marker icon for the map.", "careerfy-frame"),
            ]
        );



        $this->add_control(
            'map_styles',
            [
                'label' => __('Styles', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __("Set map styles. You can get predefined styles from <a href=\"https://snazzymaps.com/\" target=\"_blank\">snazzymaps.com</a>", "careerfy-frame"),
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        global $jobsearch_plugin_options;
        $atts = $this->get_settings_for_display();

        $map_latitude = $atts['map_latitude'];
        $map_longitude = $atts['map_longitude'];
        $map_zoom = $atts['map_zoom'];
        $map_height = $atts['map_height'];
        $map_street_view = $atts['map_street_view'];
        $map_default_ui = $atts['map_default_ui'];
        $map_marker = $atts['map_marker'] != "" ? $atts['map_marker']['url'] : '';
        $map_styles = $atts['map_styles'];
        $map_scrollwheel = $atts['map_scrollwheel'];

        $cnt_counter = rand(1000000, 9999999);
        ob_start();
        $cont_frm_class = 'col-md-12';
        if ($map_latitude != '' && $map_longitude != '' && $map_zoom > 0) {

            $cont_frm_class = 'col-md-8';
            if (isset($jobsearch_plugin_options['jobsearch-google-api-key']) && $jobsearch_plugin_options['jobsearch-google-api-key'] != '') {
                wp_enqueue_script('jobsearch-google-map');
            } else {
                wp_enqueue_script('careerfy-google-map');
            }
            ?>
            <div class="careerfy-map">
                <div id="map-<?php echo absint($cnt_counter) ?>"
                     style="height:<?php echo absint($map_height) ?>px;"></div>
            </div>
            <?php
            $map_args = array(
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


            add_action('wp_footer', function () use ($map_args) {

                extract(shortcode_atts(array(
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
                    function initMap_<?php echo absint($cnt_counter) ?>() {
                        var myLatLng = {
                            lat: <?php echo esc_js($map_latitude) ?>,
                            lng: <?php echo esc_js($map_longitude) ?>};
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
                        var styles = '<?php echo($map_styles) ?>';
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
                </script>
                <?php
            }, 99, 1);
        }

        $html = ob_get_clean();
        echo $html;
    }

    protected function _content_template()
    {
        ?>
        <h1>Map will render on the Page</h1>

    <?php }
}