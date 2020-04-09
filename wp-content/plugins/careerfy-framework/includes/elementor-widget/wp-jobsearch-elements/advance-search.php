<?php

namespace CareerfyElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

/**
 * @since 1.1.0
 */
class AdvanceSearch extends Widget_Base
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
        return 'advance-search';
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
        return __('Advance Search', 'careerfy-frame');
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
        return 'fa fa-gear';
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
        return ['wp-jobsearch'];
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
        $all_page = array();
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $all_page[$page->ID] = $page->post_title;
            }
        }
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Advance Search Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'view',
            [
                'label' => __('Style', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'view1',
                'options' => [
                    'view1' => __('Style 1', 'careerfy-frame'),
                    'view2' => __('Style 2', 'careerfy-frame'),
                    'view3' => __('Style 3', 'careerfy-frame'),
                    'view4' => __('Style 4', 'careerfy-frame'),
                    'view5' => __('Style 5', 'careerfy-frame'),
                    'view6' => __('Style 6', 'careerfy-frame'),
                    'view7' => __('Style 7', 'careerfy-frame'),
                    'view8' => __('Style 8', 'careerfy-frame'),
                    'view9' => __('Style 9', 'careerfy-frame'),
                    'view10' => __('Style 10', 'careerfy-frame'),
                    'view11' => __('Style 11', 'careerfy-frame'),
                    'view12' => __('Style 12', 'careerfy-frame'),
                    'view13' => __('Style 13', 'careerfy-frame'),
                    'view14' => __('Style 14', 'careerfy-frame'),
                    'view15' => __('Style 15', 'careerfy-frame'),
                    'view16' => __('Style 16', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'bg_img',
            [
                'label' => __('Background Image', 'careerfy-frame'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'view' => 'view12'
                ]
            ]
        );
        $this->add_control(
            'small_search_title',
            [
                'label' => __('Small Title', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => array('view18', 'view19', 'view20')
                ]
            ]
        );
        $this->add_control(
            'srch_title',
            [
                'label' => __('Title', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7', 'view9', 'view11', 'view12', 'view13', 'view15', 'view16')
                ]
            ]
        );
        $this->add_control(
            'no_total_jobtypes',
            [
                'label' => __('Total Number of Job Types', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => array('view14', 'view15')
                ]
            ]
        );
        $this->add_control(
            'srch_desc',
            [
                'label' => __('Description', 'careerfy-frame'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7', 'view9', 'view11', 'view12', 'view13', 'view15', 'view16')
                ]
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'banner_img',
            [
                'label' => __('Image', 'careerfy-frame'),
                'type' => Controls_Manager::MEDIA,
                'description' => __('Will effect on style 11', 'careerfy-frame'),

            ]
        );
        $repeater->add_control(
            'img_link',
            [
                'label' => __('Image Link', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Will effect on style 11', 'careerfy-frame'),
            ]
        );

        $this->add_control(
            'adv_banner_images',
            [
                'label' => __('Advance Search Banner Will effect on style 11', 'careerfy-frame'),
                'type' => Controls_Manager::REPEATER,
                'description' => __('Will effect on style 11', 'careerfy-frame'),
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'img_link' => __(get_site_url(), 'careerfy-frame'),
                    ]
                ],
                'title_field' => '{{{ img_link }}}',
            ]
        );

        $this->add_control(
            'txt_below_forms_1',
            [
                'label' => __('Text Below Form 1', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => 'view9'
                ]
            ]
        );

        $this->add_control(
            'result_page',
            [
                'label' => __('Search Result Page by Jobs', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'options' => $all_page,
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view4', 'view5', 'view6', 'view7', 'view8', 'view9', 'view11', 'view10', 'view12', 'view14', 'view15', 'view16')
                ]
            ]
        );

        $this->add_control(
            'result_page_2',
            [
                'label' => __('Search Result Page by Employer', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'options' => $all_page,
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view4', 'view5', 'view6', 'view7', 'view8', 'view9', 'view11', 'view10', 'view12', 'view14', 'view15', 'view16')
                ]
            ]
        );

        $this->add_control(
            'txt_below_forms_2',
            [
                'label' => __('Text Below Form 2', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => 'view9'
                ]
            ]
        );
        $this->add_control(
            'result_page_3',
            [
                'label' => __('Search Result Page by Candidate', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'options' => $all_page,
                'condition' => [
                    'view' => array('view9', 'view12')
                ]
            ]
        );

        $this->add_control(
            'txt_below_forms_3',
            [
                'label' => __('Text Below Form 3', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'view' => 'view9'
                ]
            ]
        );
        $this->add_control(
            'btn1_txt',
            [
                'label' => __('Button 1 Text', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __("This will not show in Search Style 4.", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'btn1_url',
            [
                'label' => __('Button 1 URL', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __("This will not show in Search Style 4.", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'btn_1_icon',
            [
                'label' => __('Button 1 Icon', 'careerfy-frame'),
                'type' => Controls_Manager::ICON,
                'description' => __("", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'btn2_txt',
            [
                'label' => __('Button 2 Text', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __("This will only show in Search Style 1.", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'btn2_url',
            [
                'label' => __('Button 2 URL', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                'description' => __("This will only show in Search Style 1.", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'btn_2_icon',
            [
                'label' => __('Button 2 Icon', 'careerfy-frame'),
                'type' => Controls_Manager::ICON,
                'description' => __("This will only show in Search Style 1.", "careerfy-frame"),
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'field_settings',
            [
                'label' => __('Fields Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'keyword_field',
            [
                'label' => __('Keyword Field', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'careerfy-frame'),
                    'hide' => __('Hide', 'careerfy-frame'),
                ],
            ]
        );
        $this->add_control(
            'autofill_keyword',
            [
                'label' => __('Keyword Suggestions', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'no',
                'options' => [
                    'no' => __('No', 'careerfy-frame'),
                    'yes' => __('Yes', 'careerfy-frame'),
                ],
            ]
        );
        $this->add_control(
            'location_field',
            [
                'label' => __('Location Field', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'careerfy-frame'),
                    'hide' => __('Hide', 'careerfy-frame'),
                ],
            ]
        );
        $this->add_control(
            'radius_field',
            [
                'label' => __('Location Radius', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'careerfy-frame'),
                    'hide' => __('Hide', 'careerfy-frame'),
                ],
            ]
        );
        $this->add_control(
            'autofill_location',
            [
                'label' => __('Location Suggestions', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'no',
                'options' => [
                    'no' => __('No', 'careerfy-frame'),
                    'yes' => __('Yes', 'careerfy-frame'),
                ],
            ]
        );
        $this->add_control(
            'auto_geo_location',
            [
                'label' => __('AutoFill Geo Location', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'no',
                'options' => [
                    'no' => __('No', 'careerfy-frame'),
                    'yes' => __('Yes', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'category_field',
            [
                'label' => __('Sector Field', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'careerfy-frame'),
                    'hide' => __('Hide', 'careerfy-frame'),
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'color_settings',
            [
                'label' => __('Color Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'search_title_color',
            [
                'label' => __('Color Settings', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'view' => array('view1', 'view2', 'view3', 'view5', 'view6', 'view7')
                ]
            ]
        );
        $this->add_control(
            'search_paragraph_color',
            [
                'label' => __('Paragraph Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->add_control(
            'search_link_color',
            [
                'label' => __('Link Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->add_control(
            'search_btn_bg_color',
            [
                'label' => __('Button Background Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->add_control(
            'search_btn_txt_color',
            [
                'label' => __('Button Text Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        global $wpdb;
        $atts = $this->get_settings_for_display();

        extract(shortcode_atts(array(
            'view' => 'view1',
            'bg_img' => '',
            'srch_title' => '',
            'srch_desc' => '',
            'srch_bg_img' => '',
            'result_page' => '',
            'result_page_2' => '',
            'result_page_3' => '',
            'txt_below_forms_1' => '',
            'txt_below_forms_2' => '',
            'txt_below_forms_3' => '',
            'radius_field' => 'show',
            'btn1_txt' => '',
            'btn1_url' => '',
            'btn2_txt' => '',
            'btn2_url' => '',
            'btn_1_icon' => '',
            'btn_2_icon' => '',
            'search_title_color' => '',
            'search_paragraph_color' => '',
            'search_link_color' => '',
            'search_btn_txt_color' => '',
            'search_btn_bg_color' => '',
            'search_bg_color' => '',
            'keyword_field' => 'show',
            'location_field' => 'show',
            'category_field' => 'show',
            'autofill_keyword' => 'no',
            'autofill_location' => 'no',
            'auto_geo_location' => 'no',
            'no_total_jobtypes' => '',
            'adv_banner_images' => '',
            'first_srch_desc' => '',
            'small_search_title' => '',
        ), $atts));


        $rand_num = rand();

        $design_css_class = '';

        $transparent_bg_color = '';

        // search title color
        $adv_search_title_color = '';
        if (isset($search_title_color) && !empty($search_title_color)) {
            $adv_search_title_color = ' style="color:' . $search_title_color . ' !important"';
        }
        // search paragraph color
        $adv_search_paragraph_color = '';
        if (isset($search_paragraph_color) && !empty($search_paragraph_color)) {
            $adv_search_paragraph_color = ' style="color:' . $search_paragraph_color . ' !important"';
        }
        // search link color
        $adv_search_link_color = '';
        if (isset($search_link_color) && !empty($search_link_color)) {
            $adv_search_link_color = ' style="color:' . $search_link_color . ' !important"';
        }
        // search buuton text color
        $adv_search_btn_txt_color = '';
        if (isset($search_btn_txt_color) && !empty($search_btn_txt_color)) {
            $adv_search_btn_txt_color = ' color:' . $search_btn_txt_color . ' !important;';
        }
        // search button backgroung color
        $adv_search_btn_bg_color = '';
        if (isset($search_btn_bg_color) && !empty($search_btn_bg_color)) {
            $adv_search_btn_bg_color = ' background-color:' . $search_btn_bg_color . ' !important;';
        }
        $button_style = '';
        if (!empty($adv_search_btn_txt_color) || !empty($adv_search_btn_bg_color)) {
            $button_style = ' style="' . $adv_search_btn_txt_color . $adv_search_btn_bg_color . '"';
        }

        $job_types = '';
        if ($view == 'view14' || $view == 'view15' || $view == 'view16') {
            $job_types = $no_total_jobtypes != "" ? get_terms('jobtype', array('number' => $no_total_jobtypes)) : get_terms('jobtype');
        }

        ob_start();
        if (class_exists('JobSearch_plugin')) {
            if ($view == 'view16') { ?>
                <!-- Banner -->
                <h1><?php echo $srch_title ?></h1>
                <br>
                <p><?php echo $srch_desc ?></p>
                <br>
                <form class="careerfy-banner-twelve-search" method="get"
                      action="<?php echo(get_permalink($result_page)); ?>">
                    <i class="careerfy-icon careerfy-search"></i>
                    <?php if ($keyword_field == 'show') {
                        if ($autofill_keyword == 'yes') {
                            wp_enqueue_script('jobsearch-search-box-sugg');
                        }
                        ?>
                        <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                            <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                   name="search_title" data-type="job" type="text">
                            <span class="sugg-search-loader"></span>
                        </div>
                    <?php } ?>
                    <input type="submit" value="<?php echo esc_html__('Find Jobs', 'careerfy-frame') ?>">
                </form>


                <!-- Banner -->
            <?php } else if ($view == 'view15') { ?>
                <div class="careerfy-eighteen-banner">
                    <span class="careerfy-eighteen-banner-transparent"></span>
                    <h1><?php echo $srch_title ?></h1>
                    <span><?php echo $srch_desc ?></span>
                    <form method="get" action="<?php echo(get_permalink($result_page)); ?>">
                        <?php if ($keyword_field == 'show') {
                            if ($autofill_keyword == 'yes') {
                                wp_enqueue_script('jobsearch-search-box-sugg');
                            }
                            ?>
                            <div class="careerfy-eighteen-banner-title <?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                       name="search_title" data-type="job" type="text">
                                <span class="sugg-search-loader"></span>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <?php foreach ($job_types as $job_types_info) { ?>
                            <div class="careerfy-eighteen-search-radio">
                                <input type="radio" name="job_type"
                                       value="<?php echo esc_html__($job_types_info->slug, 'careerfy-frame') ?>"
                                       id="radio-one" class="form-radio"
                                       checked="">
                                <label for="radio-one" <?php echo $adv_search_link_color ?> ><?php echo esc_html__($job_types_info->name, 'careerfy-frame') ?></label>
                            </div>
                        <?php } ?>
                        <input type="submit" value="<?php echo esc_html__('Search', 'careerfy-frame') ?>">
                    </form>
                </div>
            <?php } else if ($view == 'view14') { ?>
                <div class="careerfy-seventeen-search">
                    <form method="get" action="<?php echo(get_permalink($result_page)); ?>">
                        <ul>
                            <li>
                                <?php if ($keyword_field == 'show') {
                                    if ($autofill_keyword == 'yes') {
                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                    }
                                    ?>
                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                               name="search_title" data-type="job" type="text">
                                        <span class="sugg-search-loader"></span>
                                    </div>
                                <?php }

                                if ($autofill_location == 'yes') { ?>
                                    <span class="loc-loader"></span>
                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                           autocomplete="off"
                                           class="jobsearch_search_location_field"
                                           type="text">
                                    <input type="hidden"
                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                           name="location">
                                <?php } else { ?>
                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                           name="location" type="text">
                                    <?php
                                } ?>
                            </li>
                            <li>
                                <span><?php echo esc_html__('Filter:', 'careerfy-frame') ?></span>
                                <?php foreach ($job_types as $job_types_info) { ?>
                                    <div class="careerfy-seventeen-search-radio">
                                        <input type="radio" name="job_type"
                                               value="<?php echo esc_html__($job_types_info->slug, 'careerfy-frame') ?>"
                                               id="radio-one"
                                               class="form-radio" <?php echo $adv_search_link_color ?>>
                                        <label for="radio-one"><?php echo esc_html__($job_types_info->name, 'careerfy-frame') ?></label>
                                    </div>
                                <?php } ?>
                                <input type="submit" value="<?php echo esc_html__('Search', 'careerfy-frame') ?>">
                            </li>
                        </ul>
                    </form>
                </div>
            <?php } else if ($view == 'view13') { ?>
                <div class="careerfy-sixteen-banner">
                    <?php if ($srch_title != '') { ?>
                        <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                    <?php }
                    if ($srch_desc != '') { ?>
                        <span <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                    <?php } ?>
                    <form method="get" action="<?php echo(get_permalink($result_page)); ?>">
                        <?php if ($keyword_field == 'show') {
                            if ($autofill_keyword == 'yes') {
                                wp_enqueue_script('jobsearch-search-box-sugg');
                            } ?>
                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                       name="search_title" data-type="job" type="text">
                                <span class="sugg-search-loader"></span>
                            </div>
                        <?php } ?>
                        <input type="submit" value="<?php esc_html_e("Find Jobs", 'careerfy-frame') ?>">
                    </form>
                    <div class="clearfix"></div>
                    <div class="careerfy-sixteen-banner-tags">
                        <small><?php echo esc_html__('Popular Keywords', 'careerfy-frame') ?></small>
                        <?php
                        $all_sectors = get_terms(array(
                            'taxonomy' => 'sector',
                            'hide_empty' => false,
                            'number' => 3,
                        ));

                        if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                            ob_start();
                            foreach ($all_sectors as $term_sector) { ?>
                                <a href="<?php echo($term_sector->term_id) ?>"><?php echo($term_sector->name) ?></a>
                            <?php }
                            $srchfield_html = ob_get_clean();
                            echo apply_filters('jobsearch_careerfy_advance_search_sh_frmcat', $srchfield_html, $all_sectors);
                        } ?>

                    </div>
                </div>
            <?php } else if ($view == 'view12') { ?>
                <style>
                    .banner-bg-img-<?php echo $rand_num ?> {
                        background: url("<?php echo $bg_img['url'] ?>") no-repeat;
                    }

                </style>

                <div class="careerfy-fifteen-banner">
                    <div class="careerfy-fifteen-banner-inner banner-bg-img-<?php echo $rand_num ?>">
                        <?php if ($srch_title != '') { ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                        <?php }
                        if ($srch_desc != '') { ?>
                            <span class="careerfy-fifteen-banner-description" <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                        <?php } ?>
                        <div class="careerfy-fifteen-banner-tabs">
                            <ul class="careerfy-banner-eleven-tabs-nav">
                                <li class="active"><a data-toggle="tab"
                                                      href="#home"><?php echo esc_html__('Find a Job', 'careerfy-frame') ?></a>
                                </li>
                                <?php
                                ob_start();
                                ?>
                                <li><a data-toggle="tab"
                                       href="#menu1"><?php echo esc_html__('Find a Candidate', 'careerfy-frame') ?></a>
                                </li>
                                <?php
                                $html = ob_get_clean();
                                echo apply_filters('careerfy_adv_srch_sh_view12_findcand_tab', $html);
                                ?>
                            </ul>
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <form method="get" action="<?php echo(get_permalink($result_page)); ?>"
                                          class="careerfy-fifteen-banner-search">
                                        <ul>
                                            <li>
                                                <?php if ($keyword_field == 'show') {
                                                    if ($autofill_keyword == 'yes') {
                                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                                    }
                                                    ?>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php

                                                $all_sectors = get_terms(array(
                                                    'taxonomy' => 'sector',
                                                    'hide_empty' => false,
                                                ));
                                                if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                                    <div class="careerfy-select-style">
                                                        <select name="sector_cat" class="selectize-select">
                                                            <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                            <?php
                                                            foreach ($all_sectors as $term_sector) { ?>
                                                                <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php
                                                if ($autofill_location == 'yes') { ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <?php
                                                } ?>

                                            </li>
                                            <li><input type="submit"
                                                       value="<?php esc_html_e("Find Jobs", 'careerfy-frame') ?>">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <form method="get" action="<?php echo(get_permalink($result_page_3)); ?>"
                                          class="careerfy-fifteen-banner-search">
                                        <ul>
                                            <li>
                                                <?php if ($keyword_field == 'show') {
                                                    if ($autofill_keyword == 'yes') {
                                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                                    } ?>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php
                                                $all_sectors = get_terms(array(
                                                    'taxonomy' => 'sector',
                                                    'hide_empty' => false,
                                                ));
                                                if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                                    <div class="careerfy-select-style">
                                                        <select name="sector_cat" class="selectize-select">
                                                            <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                            <?php
                                                            foreach ($all_sectors as $term_sector) { ?>
                                                                <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <li>
                                                <?php
                                                if ($autofill_location == 'yes') { ?>
                                                    <span class="loc-loader"></span>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <?php
                                                } ?>
                                            </li>
                                            <li><input type="submit"
                                                       value="<?php esc_html_e("Find Job", 'careerfy-frame') ?>"></li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } else if ($view == 'view11') { ?>
                <!-- Banner -->
                <div class="careerfy-fourteen-banner">
                    <span class="careerfy-fourteen-banner-transparent"></span>
                    <div class="careerfy-fourteen-caption">
                        <?php if ($srch_title != '') { ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                        <?php }
                        if ($srch_desc != '') { ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                        <?php } ?>
                        <form method="get" action="<?php echo(get_permalink($result_page)); ?>">
                            <?php if ($keyword_field == 'show') {
                            if ($autofill_keyword == 'yes') {
                                wp_enqueue_script('jobsearch-search-box-sugg');
                            }
                            ?>
                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                       name="search_title" data-type="job" type="text">
                                <span class="sugg-search-loader"></span>
                                <?php } ?>
                            </div>
                            <input type="submit" value="<?php echo esc_html_e('Find Jobs', 'careerfy-frame') ?>">
                            <?php if (get_permalink($result_page) != "") { ?>
                                <a href="<?php echo get_permalink($result_page) ?>"
                                   class="careerfy-fourteen-caption-btn"><?php esc_html_e('+ Advance Search', 'careerfy-frame') ?></a>
                            <?php } ?>
                        </form>

                        <?php
                        $adv_banner_images = $atts['adv_banner_images'];
                        if (!empty($adv_banner_images)) { ?>
                            <ul>
                                <?php
                                $_exf_counter = 0;
                                foreach ($adv_banner_images as $adv_banner_image) { ?>
                                    <li><a href="<?php echo $adv_banner_image['img_link'] ?>"><img
                                                    src="<?php echo $adv_banner_image['banner_img']['url'] ?>"
                                                    alt=""></a></li>
                                    <?php
                                    $_exf_counter++;
                                }
                                ?>
                            </ul>
                        <?php } ?>

                    </div>
                </div>
                <!-- Banner -->
            <?php } else if ($view == 'view10') { ?>
                <!-- Banner -->
                <div class="careerfy-thirteen-banner">
                    <span class="careerfy-thirteen-banner-transparent"></span>
                    <div class="careerfy-thirteen-banner-search">
                        <form method="get" action="<?php echo(get_permalink($result_page)); ?>">
                            <ul>
                                <?php
                                if ($keyword_field == 'show') {
                                    if ($autofill_keyword == 'yes') {
                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                    }
                                    ?>
                                    <li><i class="careerfy-icon careerfy-search"></i>
                                        <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                            <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                   name="search_title" data-type="job" type="text">
                                            <span class="sugg-search-loader"></span>
                                        </div>
                                    </li>
                                <?php }
                                $all_sectors = get_terms(array(
                                    'taxonomy' => 'sector',
                                    'hide_empty' => false,
                                ));
                                if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                    ?>
                                    <li>
                                        <div class="careerfy-select-style">
                                            <select name="sector_cat" class="selectize-select">
                                                <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                <?php
                                                foreach ($all_sectors as $term_sector) {
                                                    ?>
                                                    <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </li>
                                <?php } ?>
                                <li>
                                    <?php
                                    if ($autofill_location == 'yes') { ?>
                                        <i class="careerfy-icon careerfy-pin"></i>
                                        <span class="loc-loader"></span>
                                        <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                               autocomplete="off"
                                               class="jobsearch_search_location_field"
                                               type="text">
                                        <i class="careerfy-thirteen-banner-search-icon careerfy-icon careerfy-gps-o"></i>
                                        <input type="hidden"
                                               class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                               name="location">
                                    <?php } else { ?>
                                        <i class="careerfy-icon careerfy-pin"></i>
                                        <input placeholder="<?php esc_html_e('City State or zip', 'careerfy-frame') ?>"
                                               class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                               name="location" type="text">
                                        <i class="careerfy-thirteen-banner-search-icon careerfy-icon careerfy-gps"></i>
                                        <?php
                                    } ?>

                                </li>
                                <?php if ($radius_field == 'show') { ?>
                                    <li class="careerfy-thirteen-banner-radius">
                                        <input name="loc_radius"
                                               placeholder="<?php esc_html_e('Radius', 'careerfy-frame') ?>" value=""
                                               type="text">
                                    </li>
                                <?php } ?>
                                <li><input type="submit" value="<?php esc_html_e("Search Job", 'careerfy-frame') ?>">
                                    <a href="<?php echo(get_permalink($result_page)); ?>"><?php esc_html_e("+ Advance Search", 'careerfy-frame') ?></a>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
                <!-- Banner -->
            <?php } else if ($view == 'view9') { ?>
                <!-- Banner -->
                <div class="careerfy-banner-eleven">
                    <div class="careerfy-banner-eleven-tabs">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        ?>
                        <ul class="careerfy-banner-eleven-tabs-nav">
                            <li class="active"><a data-toggle="tab"
                                                  href="#home"><?php esc_html_e("Jobs", 'careerfy-frame') ?></a>
                            </li>
                            <li><a data-toggle="tab"
                                   href="#menu1"><?php esc_html_e("Employers", 'careerfy-frame') ?></a></li>
                            <li><a data-toggle="tab"
                                   href="#menu2"><?php esc_html_e("Candidates", 'careerfy-frame') ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <div class="careerfy-banner-eleven-search">
                                    <form method="get"
                                          action="<?php echo(get_permalink($result_page)); ?>">
                                        <ul>
                                            <?php
                                            if ($keyword_field == 'show') {
                                                if ($autofill_keyword == 'yes') {
                                                    wp_enqueue_script('jobsearch-search-box-sugg');
                                                }
                                                ?>
                                                <li><i class="careerfy-icon careerfy-search"></i>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                </li>
                                                <?php
                                            } ?>
                                            <li>
                                                <?php
                                                if ($autofill_location == 'yes') { ?>
                                                    <span class="loc-loader"></span>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') { ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-gps"></i></a>
                                                <?php } ?>
                                            </li>
                                            <li><input type="submit"
                                                       value="<?php esc_html_e("Find Jobs", 'careerfy-frame') ?>">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                <?php if ($txt_below_forms_1 != '') { ?>
                                    <div class="careerfy-fileupload-banner">
                                        <span><i class="careerfy-icon careerfy-upload"></i><?php echo $txt_below_forms_1 ?> </span>
                                        <input class="careerfy-upload">
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <div class="careerfy-banner-eleven-search">
                                    <form method="get"
                                          action="<?php echo(get_permalink($result_page_2)); ?>">
                                        <ul>
                                            <?php
                                            if ($keyword_field == 'show') {
                                                if ($autofill_keyword == 'yes') {
                                                    wp_enqueue_script('jobsearch-search-box-sugg');
                                                }
                                                ?>
                                                <li><i class="careerfy-icon careerfy-search"></i>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                </li>
                                                <?php
                                            } ?>
                                            <li>
                                                <?php
                                                if ($autofill_location == 'yes') { ?>
                                                    <span class="loc-loader"></span>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') { ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-gps"></i></a>
                                                <?php } ?>
                                            </li>
                                            <li><input type="submit"
                                                       value="<?php esc_html_e("Find Jobs", 'careerfy-frame') ?>">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                <?php if ($txt_below_forms_2 != '') { ?>
                                    <div class="careerfy-fileupload-banner">
                                        <span><i class="careerfy-icon careerfy-upload"></i><?php echo $txt_below_forms_2 ?> </span>
                                        <input class="careerfy-upload">
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <div class="careerfy-banner-eleven-search">
                                    <form method="get"
                                          action="<?php echo(get_permalink($result_page_3)); ?>">
                                        <ul>
                                            <?php
                                            if ($keyword_field == 'show') {
                                                if ($autofill_keyword == 'yes') {
                                                    wp_enqueue_script('jobsearch-search-box-sugg');
                                                }
                                                ?>
                                                <li><i class="careerfy-icon careerfy-search"></i>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                </li>
                                                <?php
                                            } ?>
                                            <li>
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <i class="careerfy-icon careerfy-gps"></i>
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') { ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-gps"></i></a>
                                                <?php } ?>
                                            </li>
                                            <li><input type="submit"
                                                       value="<?php esc_html_e("Find Jobs", 'careerfy-frame') ?>">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                                <?php if ($txt_below_forms_3 != '') { ?>
                                    <div class="careerfy-fileupload-banner">
                                        <span><i class="careerfy-icon careerfy-upload"></i><?php echo $txt_below_forms_3 ?> </span>
                                        <input class="careerfy-upload">
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- Banner -->
            <?php } else if ($view == 'view8') { ?>
                <!-- Main Section -->
                <div class="careerfy-main-section careerfy-search-ten-full">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="careerfy-search-ten">
                                <?php
                                if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                                    ?>
                                    <form class="careerfy-banner-search-ten" method="get"
                                          action="<?php echo(get_permalink($result_page)); ?>">
                                        <ul class="careerfy-search-ten-grid">
                                            <?php
                                            if ($keyword_field == 'show') {
                                                if ($autofill_keyword == 'yes') {
                                                    wp_enqueue_script('jobsearch-search-box-sugg');
                                                }
                                                ?>
                                                <li>
                                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                        <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                               name="search_title" data-type="job" type="text">
                                                        <i class="careerfy-icon careerfy-edit"></i>
                                                        <span class="sugg-search-loader"></span>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            if ($location_field == 'show') {
                                                if ($autofill_location == 'yes') {
                                                    wp_enqueue_script('jobsearch-google-map');
                                                    wp_enqueue_script('jobsearch-location-autocomplete');
                                                }
                                                ?>
                                                <li>
                                                    <div class="jobsearch_searchloc_div">
                                                        <?php
                                                        if ($autofill_location == 'yes') {
                                                            ?>
                                                            <span class="loc-loader"></span>
                                                            <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                                   autocomplete="off"
                                                                   class="jobsearch_search_location_field"
                                                                   type="text">
                                                            <i class="careerfy-icon careerfy-gps"></i>
                                                            <input type="hidden"
                                                                   class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                                   name="location">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                                   class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                                   name="location" type="text">
                                                            <i class="careerfy-icon careerfy-gps"></i>
                                                            <?php
                                                        }
                                                        //
                                                        if ($auto_geo_location == 'yes') {
                                                            ?>
                                                            <a href="javascript:void(0);" class="geolction-btn"
                                                               onclick="JobsearchGetClientLocation()"><i
                                                                        class="careerfy-icon careerfy-gps"></i></a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            $all_sectors = get_terms(array(
                                                'taxonomy' => 'sector',
                                                'hide_empty' => false,
                                            ));

                                            if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                                ?>
                                                <li>
                                                    <div class="careerfy-select-style">
                                                        <select name="sector_cat" class="selectize-select">
                                                            <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                            <?php
                                                            foreach ($all_sectors as $term_sector) {
                                                                ?>
                                                                <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <?php
                                            } ?>

                                            <li>
                                                <label>
                                                    <i class="careerfy-icon careerfy-search"></i>
                                                    <input type="submit"
                                                           value="<?php esc_html_e("Search Job", 'careerfy-frame') ?>">
                                                </label>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                        <?php
                                        $top_sectors = $wpdb->get_col($wpdb->prepare("SELECT terms.term_id FROM $wpdb->terms AS terms"
                                            . " LEFT JOIN $wpdb->term_taxonomy AS term_tax ON(terms.term_id = term_tax.term_id) "
                                            . " LEFT JOIN $wpdb->termmeta AS term_meta ON(terms.term_id = term_meta.term_id) "
                                            . " WHERE term_tax.taxonomy=%s AND term_meta.meta_key=%s"
                                            . " ORDER BY cast(term_meta.meta_value as unsigned) DESC LIMIT 4", 'sector', 'active_jobs_count'));

                                        if (!empty($top_sectors) && !is_wp_error($top_sectors)) {
                                            ?>
                                            <ul class="careerfy-search-ten-list">
                                                <li<?php echo($adv_search_paragraph_color) ?>><?php esc_html_e('Top Sectors :', 'careerfy-frame') ?></li>
                                                <?php
                                                foreach ($top_sectors as $term_id) {
                                                    $term_sector = get_term_by('id', $term_id, 'sector');
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo add_query_arg(array('sector' => $term_sector->slug), get_permalink($result_page)); ?>"<?php echo($adv_search_link_color) ?>><?php echo($term_sector->name) ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <?php
                                        }
                                        ?>
                                        <a href="<?php echo(get_permalink($result_page)); ?>"
                                           class="careerfy-search-ten-list-btn"><?php echo esc_html__('Advance Search', 'careerfy-frame') ?></a>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Main Section -->

            <?php } else if ($view == 'view7') {
                ?>
                <div class="careerfy-search-eight-wrap <?php echo($design_css_class) ?>">
                    <div class="careerfy-adv-wrap">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h2<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h2>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        ?>
                        <div class="careerfy-banner-btn">
                            <?php
                            if ($btn1_txt != '') {
                                ?>
                                <a href="<?php echo($btn1_url) ?>"
                                   class="careerfy-bgcolorhover"<?php echo($button_style) ?>><?php echo($btn_1_icon != '' ? '<i class="' . $btn_1_icon . '"></i>' : '') ?><?php echo($btn1_txt) ?></a>
                                <?php
                            }
                            if ($btn2_txt != '') {
                                ?>
                                <a href="<?php echo($btn2_url) ?>"
                                   class="careerfy-bgcolorhover"<?php echo($button_style) ?><?php echo($adv_search_btn_bg_color) ?>><?php echo($btn_2_icon != '' ? '<i class="' . $btn_2_icon . '"></i>' : '') ?><?php echo($btn2_txt) ?></a>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>

                            <form class="careerfy-banner-search-eight" method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <ul>
                                    <?php
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }

                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><input type="submit" value="<?php esc_html_e("Let's Go", 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                        ?>

                    </div>

                </div>
                <?php
            } elseif ($view == 'view6') {
                ?>
                <div class="careerfy-search-seven-wrap <?php echo($design_css_class) ?>">

                    <div class="careerfy-adv-wrap">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h2<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h2>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        ?>
                        <?php
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>

                            <form class="careerfy-banner-search-seven" <?php echo $transparent_bg_color; ?> method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><i class="careerfy-icon careerfy-search"></i><input type="submit" value=""></li>
                                </ul>
                            </form>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php
            } elseif ($view == 'view5') {
                ?>
                <div class="careerfy-search-six-wrap <?php echo($design_css_class) ?>">

                    <div class="careerfy-adv-wrap">
                        <?php
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>
                            <form class="careerfy-banner-search-six" <?php echo $transparent_bg_color; ?> method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <?php
                                if ($srch_title != '') {
                                    ?>
                                    <h2<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h2>
                                    <?php
                                }
                                if ($srch_desc != '') {
                                    ?>
                                    <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                                    <?php
                                }
                                ?>
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><i class="careerfy-icon careerfy-search"></i><input type="submit"
                                                                                            value="<?php esc_html_e('Search Jobs', 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                        ?>

                    </div>


                </div>
                <?php
            } elseif ($view == 'view4') {
                ?>
                <div class="careerfy-search-four-wrap <?php echo($design_css_class) ?>">

                    <div class="careerfy-adv-wrap">
                        <?php
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>
                            <form class="careerfy-banner-search-four" method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <?php
                                if ($srch_title != '') {
                                    ?>
                                    <h2<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h2>
                                    <?php
                                }
                                if ($srch_desc != '') {
                                    ?>
                                    <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                                    <?php
                                }
                                ?>
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><input type="submit"
                                               value="<?php esc_html_e('Search Jobs', 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                        $top_sectors = $wpdb->get_col($wpdb->prepare("SELECT terms.term_id FROM $wpdb->terms AS terms"
                            . " LEFT JOIN $wpdb->term_taxonomy AS term_tax ON(terms.term_id = term_tax.term_id) "
                            . " LEFT JOIN $wpdb->termmeta AS term_meta ON(terms.term_id = term_meta.term_id) "
                            . " WHERE term_tax.taxonomy=%s AND term_meta.meta_key=%s"
                            . " ORDER BY cast(term_meta.meta_value as unsigned) DESC LIMIT 4", 'sector', 'active_jobs_count'));

                        if (!empty($top_sectors) && !is_wp_error($top_sectors)) {
                            ?>
                            <ul class="careerfy-search-categories">
                                <li<?php echo($adv_search_paragraph_color) ?>><?php esc_html_e('Top Sectors :', 'careerfy-frame') ?></li>
                                <?php
                                foreach ($top_sectors as $term_id) {
                                    $term_sector = get_term_by('id', $term_id, 'sector');
                                    ?>
                                    <li>
                                        <a href="<?php echo add_query_arg(array('sector' => $term_sector->slug), get_permalink($result_page)); ?>"<?php echo($adv_search_link_color) ?>><?php echo($term_sector->name) ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            <?php } else if ($view == 'view3') { ?>
                <div class="careerfy-banner-three careerfy-typo-wrap <?php echo($design_css_class) ?>">
                    <div class="careerfy-bannerthree-caption">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        ?>
                        <div class="clearfix"></div>
                        <?php
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>
                            <form class="careerfy-banner-search-three" method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><i class="careerfy-icon careerfy-search"></i> <input type="submit"
                                                                                             value="<?php esc_html_e('Find', 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                        if ($btn1_txt != '') {
                            ?>
                            <a href="<?php echo($btn1_url); ?>"
                               class="careerfy-upload-cvbtn"<?php echo($button_style) ?>><?php echo($btn_1_icon != '' ? '<i class="' . $btn_1_icon . '"></i>' : '') ?><?php echo($btn1_txt); ?></a>
                            <?php
                        }
                        $top_sectors = $wpdb->get_col($wpdb->prepare("SELECT terms.term_id FROM $wpdb->terms AS terms"
                            . " LEFT JOIN $wpdb->term_taxonomy AS term_tax ON(terms.term_id = term_tax.term_id) "
                            . " LEFT JOIN $wpdb->termmeta AS term_meta ON(terms.term_id = term_meta.term_id) "
                            . " WHERE term_tax.taxonomy=%s AND term_meta.meta_key=%s"
                            . " ORDER BY cast(term_meta.meta_value as unsigned) DESC LIMIT 4", 'sector', 'active_jobs_count'));

                        if (!empty($top_sectors) && !is_wp_error($top_sectors)) {
                            ?>
                            <ul class="careerfy-search-categories">
                                <li<?php echo($adv_search_paragraph_color) ?>><?php esc_html_e('Top Sectors :', 'careerfy-frame') ?></li>
                                <?php
                                foreach ($top_sectors as $term_id) {
                                    $term_sector = get_term_by('id', $term_id, 'sector');
                                    ?>
                                    <li>
                                        <a href="<?php echo add_query_arg(array('sector_cat' => $term_sector->slug), get_permalink($result_page)); ?>"<?php echo($adv_search_link_color) ?>><?php echo($term_sector->name) ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            } else if ($view == 'view2') {
                ?>
                <div class="careerfy-banner-two careerfy-typo-wrap <?php echo($design_css_class) ?>">
                    <div class="careerfy-banner-caption">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        //
                        if ($btn1_txt != '') {
                            ?>
                            <div class="clearfix"></div>
                            <a href="<?php echo($btn1_url); ?>"
                               class="careerfy-banner-two-btn"<?php echo($button_style) ?>><?php echo($btn_1_icon != '' ? '<i class="' . $btn_1_icon . '"></i>' : '') ?><?php echo($btn1_txt); ?></a>
                            <?php
                        }
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>
                            <div class="clearfix"></div>
                            <form class="careerfy-banner-search-two" method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>
                                        <li>
                                            <i class="careerfy-icon careerfy-search"></i>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ?>
                                        <li>
                                            <i class="careerfy-icon careerfy-folder"></i>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><input type="submit"
                                               value="<?php esc_html_e('Search Jobs', 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="careerfy-banner careerfy-typo-wrap <?php echo($design_css_class) ?>">
                    <div class="careerfy-banner-caption">
                        <?php
                        if ($srch_title != '') {
                            ?>
                            <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                            <?php
                        }
                        if ($srch_desc != '') {
                            ?>
                            <p<?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></p>
                            <?php
                        }
                        if ($keyword_field == 'show' || $location_field == 'show' || $category_field == 'show') {
                            ?>
                            <form class="careerfy-banner-search" method="get"
                                  action="<?php echo(get_permalink($result_page)); ?>">
                                <ul>
                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ob_start();
                                        ?>
                                        <li>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Job Title, Keywords, or Phrase', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>
                                        </li>
                                        <?php
                                        $srchfield_html = ob_get_clean();
                                        echo apply_filters('jobsearch_careerfy_advance_search_sh_frmtitle', $srchfield_html);
                                    }
                                    if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            wp_enqueue_script('jobsearch-google-map');
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ob_start();
                                        ?>
                                        <li>
                                            <div class="jobsearch_searchloc_div">
                                                <?php
                                                if ($autofill_location == 'yes') {
                                                    ?>
                                                    <span class="loc-loader"></span>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           autocomplete="off" class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <input placeholder="<?php esc_html_e('City, State or ZIP', 'careerfy-frame') ?>"
                                                           class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location" type="text">
                                                    <?php
                                                }
                                                //
                                                if ($auto_geo_location == 'yes') {
                                                    ?>
                                                    <a href="javascript:void(0);" class="geolction-btn"
                                                       onclick="JobsearchGetClientLocation()"><i
                                                                class="careerfy-icon careerfy-location"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </li>
                                        <?php
                                        $srchfield_html = ob_get_clean();
                                        echo apply_filters('jobsearch_careerfy_advance_search_sh_frmloc', $srchfield_html);
                                    }
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));

                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') {
                                        ob_start(); ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Select Sector', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) {
                                                        ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </li>
                                        <?php
                                        $srchfield_html = ob_get_clean();
                                        echo apply_filters('jobsearch_careerfy_advance_search_sh_frmcat', $srchfield_html, $all_sectors);
                                    }
                                    ?>
                                    <li class="careerfy-banner-submit"><input type="submit" value=""> <i
                                                class="careerfy-icon careerfy-search"></i></li>
                                </ul>
                            </form>
                        <?php } ?>
                        <div class="clearfix"></div>
                        <div class="careerfy-banner-btn">
                            <?php
                            ob_start();
                            if ($btn1_txt != '') {
                                ?>
                                <a href="<?php echo($btn1_url) ?>"
                                   class="careerfy-bgcolorhover"<?php echo($button_style) ?>><?php echo($btn_1_icon != '' ? '<i class="' . $btn_1_icon . '"></i>' : '') ?><?php echo($btn1_txt) ?></a>
                                <?php
                            }
                            if ($btn2_txt != '') {
                                ?>
                                <a href="<?php echo($btn2_url) ?>"
                                   class="careerfy-bgcolorhover"<?php echo($button_style) ?><?php echo($adv_search_btn_bg_color) ?>><?php echo($btn_2_icon != '' ? '<i class="' . $btn_2_icon . '"></i>' : '') ?><?php echo($btn2_txt) ?></a>
                                <?php
                            }
                            $btns_html = ob_get_clean();
                            echo apply_filters('jobsearch_advance_search_actbtns_html', $btns_html, $btn1_txt, $btn2_txt, $button_style, $adv_search_btn_bg_color);
                            ?>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
        $html = ob_get_clean();
        echo $html;
    }

    protected function _content_template()
    {

    }
}