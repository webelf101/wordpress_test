<?php

namespace CareerfyElementor\Widgets;

use WP_Jobsearch\Candidate_Profile_Restriction;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;


/**
 * @since 1.1.0
 */
class CandidateSlider extends Widget_Base
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
        return 'candidate-slider';
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
        return __('Candidate Slider', 'careerfy-frame');
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
        return 'fa fa-picture-o';
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
        $categories = get_terms(array(
            'taxonomy' => 'sector',
            'hide_empty' => false,
        ));

        $cate_array = array(esc_html__("Select Sector", "careerfy-frame") => '');
        if (is_array($categories) && sizeof($categories) > 0) {
            foreach ($categories as $category) {
                $cate_array[$category->name] = $category->slug;
            }
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Candidate Slider Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_style',
            [
                'label' => __('Style', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'default' => 'style1',
                'options' => [
                    'style1' => __('Style 1', 'careerfy-frame'),
                    'style2' => __('Style 2', 'careerfy-frame'),
                    'style3' => __('Style 3', 'careerfy-frame'),
                    'style4' => __('Style 4', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'candidate_cat',
            [
                'label' => __('Sector', 'careerfy-frame'),
                'type' => Controls_Manager::SELECT2,
                'options' => $cate_array,
                'description' => esc_html__("Select Sector.", "careerfy-frame")
            ]
        );

        $this->add_control(
            'candidate_nums', [
                'label' => __('Number of Candidates', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'first_btn_color', [
                'label' => __('First Button Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'second_btn_color', [
                'label' => __('Second Button Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {
        $atts = $this->get_settings_for_display();
        global $jobsearch_plugin_options;
        extract(shortcode_atts(array(
            'candidate_cat' => '',
            'candidate_nums' => '',
            'first_btn_color' => '',
            'second_btn_color' => '',
            'slider_style' => 'style1',
        ), $atts));

        $rand_num = rand(10000000, 99909999);
        $cand_profile_restrict = new Candidate_Profile_Restriction;
        $candidate_nums = isset($candidate_nums) && $candidate_nums != "" ? $candidate_nums : '-1';
        $candidates_reviews = isset($jobsearch_plugin_options['candidate_reviews_switch']) ? $jobsearch_plugin_options['candidate_reviews_switch'] : '';

        $candidate_listing_percent = isset($jobsearch_plugin_options['jobsearch_candidate_skills']) ? $jobsearch_plugin_options['jobsearch_candidate_skills'] : '';
        $candmin_listing_percent = isset($jobsearch_plugin_options['cand_min_listpecent']) ? $jobsearch_plugin_options['cand_min_listpecent'] : '';
        $candmin_listing_percent = absint($candmin_listing_percent);

        $element_filter_arr = array();
        $element_filter_arr[] = array(
            'key' => 'jobsearch_field_candidate_approved',
            'value' => 'on',
            'compare' => '=',
        );
        $element_filter_arr[] = array(
            'key' => 'cuscand_feature_fbckend',
            'value' => 'on',
            'compare' => '=',
        );
        if ($candidate_listing_percent == 'on' && $candmin_listing_percent > 0) {
            $element_filter_arr[] = array(
                'key' => 'overall_skills_percentage',
                'value' => $candmin_listing_percent,
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }

        $args_count = array(
            'posts_per_page' => $candidate_nums,
            'post_type' => 'candidate',
            'post_status' => 'publish',
            'meta_query' => array(
                $element_filter_arr,
            ),
            'fields' => 'ids'
        );
        if ($candidate_cat != "") {
            $args_count['tax_query'][] = array(
                'taxonomy' => 'sector',
                'field' => 'slug',
                'terms' => $candidate_cat
            );
        }
        $query = new \WP_Query($args_count);

        $html = '';
        ob_start();
        if ($query->found_posts != 0) { ?>

        <div id="careerfy-slidmaintop-<?php echo($rand_num) ?>" style="position: relative; float: left; width: 100%;">
            <div id="careerfy-slidloder-<?php echo($rand_num) ?>" class="careerfy-slidloder-section">
                <div class="ball-scale-multiple">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <?php if ($slider_style == 'style3') { ?>
            <div id="careerfy-popcands-<?php echo($rand_num) ?>" class="careerfy-popular-candidates-style14-slider">
                <?php } else if ($slider_style == 'style1') { ?>
                <div id="careerfy-popcands-<?php echo($rand_num) ?>" class="careerfy-popular-candidates">
                    <?php } else if ($slider_style == 'style4') { ?>
                    <div id="careerfy-popcands-<?php echo($rand_num) ?>" class="careerfy-sixteen-candidate-slider">
                        <div class="careerfy-sixteen-candidate-layer">
                            <div class="careerfy-sixteen-candidate-grid">
                                <ul class="row">
                                    <?php } else { ?>

                                    <div id="careerfy-popcands-<?php echo($rand_num) ?>"
                                         class="careerfy-candidates-style11-slider">
                                        <?php }

                                        $first_btn_color = $first_btn_color != "" ? 'style="background-color: ' . $first_btn_color . '"' : '';
                                        $second_btn_color = $second_btn_color != "" ? 'style="background-color: ' . $second_btn_color . '"' : '';
                                        $count = 0;
                                        ///////////Counter is for slider only which is started from 1 ////////////
                                        $slider_counter = 1;
                                        foreach ($query->posts as $post_id) {

                                        $candidate_expertise_skills = "";
                                        $inopt_resm_skills = isset($jobsearch_plugin_options['cand_resm_skills']) ? $jobsearch_plugin_options['cand_resm_skills'] : '';
                                        $careerfy_theme_color = isset($careerfy__options['careerfy-main-color']) && $careerfy__options['careerfy-main-color'] != '' ? $careerfy__options['careerfy-main-color'] : '#13b5ea';

                                        if (!$cand_profile_restrict::cand_field_is_locked('expertise_defields', 'detail_page')) {
                                            if ($inopt_resm_skills != 'off') {

                                                $exfield_list = get_post_meta($post_id, 'jobsearch_field_skill_title', true);
                                                $skill_percentagefield_list = get_post_meta($post_id, 'jobsearch_field_skill_percentage', true);
                                                if (is_array($exfield_list) && sizeof($exfield_list) > 0) {
                                                    $exfield_counter = 0;
                                                    $total_skills_percent = 0;
                                                    foreach ($exfield_list as $exfield) {

                                                        $skill_percentagefield_val = isset($skill_percentagefield_list[$exfield_counter]) ? absint($skill_percentagefield_list[$exfield_counter]) : '';
                                                        $skill_percentagefield_val = $skill_percentagefield_val > 100 ? 100 : $skill_percentagefield_val;
                                                        $total_skills_percent += $skill_percentagefield_val;

                                                        $exfield_counter++;
                                                    }
                                                    $candidate_expertise_skills = ($total_skills_percent / $exfield_counter);
                                                }

                                            }
                                        }
                                        //$do_shortlist = do_action('jobsearch_add_employer_resume_to_list_btn', array('id' => $post_id, 'style' => 'style1'));
                                        $candidate_rank = get_post_meta($post_id, 'jobsearch_field_candidate_jobtitle', true);
                                        $candidate_salary = get_post_meta($post_id, 'jobsearch_field_candidate_salary', true);
                                        $candidate_salary = isset($candidate_salary) && $candidate_salary != "" ? '<span>' . esc_html__('$', 'careerfy-frame') . ' ' . $candidate_salary . ' ' . esc_html__('/ hr', 'careerfy-frame') . ' </span>' : esc_html__('No Salary exist', 'careerfy-frame');
                                        $no_rating_class = isset($candidates_reviews) && $candidates_reviews == 'off' ? 'no-candidate-rating' : '';
                                        $oveall_review_avg_rating = '';
                                        if ($candidates_reviews == 'on') {
                                            $oveall_review_avg_rating = get_post_meta($post_id, 'oveall_review_avg_rating', true);
                                            $over_all_avg_rting_perc = 0;
                                            if ($oveall_review_avg_rating > 0) {
                                                $over_all_avg_rting_perc = ($oveall_review_avg_rating / 5) * 100;
                                            }
                                        } else {
                                            $over_all_avg_rting_perc = '';
                                        }


                                        $get_item_city = get_post_meta($post_id, 'jobsearch_field_location_location3', true);
                                        if ($get_item_city != '') {
                                            $get_item_city . ',';
                                        }
                                        $get_item_state = get_post_meta($post_id, 'jobsearch_field_location_location2', true);

                                        if ($get_item_state != '') {
                                            $get_item_state . ',';
                                        }

                                        $get_item_country = get_post_meta($post_id, 'jobsearch_field_location_location1', true);
                                        $user_facebook_url = get_post_meta($post_id, 'jobsearch_field_user_facebook_url', true);
                                        $user_twitter_url = get_post_meta($post_id, 'jobsearch_field_user_twitter_url', true);
                                        $user_google_plus_url = get_post_meta($post_id, 'jobsearch_field_user_google_plus_url', true);
                                        $user_linkedin_url = get_post_meta($post_id, 'jobsearch_field_user_linkedin_url', true);

                                        $post_thumbnail_id = jobsearch_candidate_get_profile_image($post_id);
                                        $post_thumbnail_image = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
                                        $post_thumbnail_src = isset($post_thumbnail_image[0]) && esc_url($post_thumbnail_image[0]) != '' ? $post_thumbnail_image[0] : '';
                                        $post_thumbnail_src = $post_thumbnail_src == '' ? jobsearch_candidate_image_placeholder() : $post_thumbnail_src;
                                        $final_color = '';
                                        $candidate_skills = isset($jobsearch_plugin_options['jobsearch_candidate_skills']) ? $jobsearch_plugin_options['jobsearch_candidate_skills'] : '';
                                        if ($candidate_skills == 'on') {

                                            $low_skills_clr = isset($jobsearch_plugin_options['skill_low_set_color']) && $jobsearch_plugin_options['skill_low_set_color'] != '' ? $jobsearch_plugin_options['skill_low_set_color'] : '';
                                            $med_skills_clr = isset($jobsearch_plugin_options['skill_med_set_color']) && $jobsearch_plugin_options['skill_med_set_color'] != '' ? $jobsearch_plugin_options['skill_med_set_color'] : '';
                                            $high_skills_clr = isset($jobsearch_plugin_options['skill_high_set_color']) && $jobsearch_plugin_options['skill_high_set_color'] != '' ? $jobsearch_plugin_options['skill_high_set_color'] : '';
                                            $comp_skills_clr = isset($jobsearch_plugin_options['skill_ahigh_set_color']) && $jobsearch_plugin_options['skill_ahigh_set_color'] != '' ? $jobsearch_plugin_options['skill_ahigh_set_color'] : '';
                                            $jobsearch_sectors = wp_get_post_terms($post_id, 'sector', array("fields" => "all"));

                                            $cand_user_id = jobsearch_get_candidate_user_id($post_id);
                                            $all_skill_msgs = jobsearch_candidate_skill_percent_count($cand_user_id, 'msgs');
                                            preg_match_all('!\d+!', @$all_skill_msgs[$count], $matches);
                                            $overall_candidate_skills = get_post_meta($post_id, 'overall_skills_percentage', true);
                                            if ($overall_candidate_skills <= 25 && $low_skills_clr != '') {
                                                $final_color = 'style="background-color: ' . $low_skills_clr . ';"';
                                            } else if ($overall_candidate_skills > 25 && $overall_candidate_skills <= 50 && $med_skills_clr != '') {
                                                $final_color = 'style="background-color: ' . $med_skills_clr . ';"';
                                            } else if ($overall_candidate_skills > 50 && $overall_candidate_skills <= 75 && $high_skills_clr != '') {
                                                $final_color = 'style="background-color: ' . $high_skills_clr . ';"';
                                            } else if ($overall_candidate_skills > 75 && $comp_skills_clr != '') {
                                                $final_color = 'style="background-color: ' . $comp_skills_clr . ';"';
                                            }
                                        } ?>

                                        <?php if ($slider_style == 'style4') { ?>
                                            <li class="col-md-4">
                                                <div class="careerfy-sixteen-candidate-grid-inner">
                                                    <figure>
                                                        <a href="<?php echo get_permalink($post_id) ?>"><img
                                                                    src="<?php echo $post_thumbnail_src ?>"
                                                                    alt=""></a>
                                                        <figcaption>
                                                            <?php
                                                            echo $candidate_salary;
                                                            do_action('jobsearch_add_employer_resume_to_list_btn', array('id' => $post_id, 'style' => 'style4'));
                                                            ?>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="careerfy-sixteen-candidate-grid-text">
                                                        <h2>
                                                            <a href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id) ?> </a>
                                                        </h2>
                                                        <span><?php echo $candidate_rank ?></span>
                                                        <div class="careerfy-sixteen-candidate-grid-bottom  <?php echo $no_rating_class ?>  ">

                                                            <?php
                                                            if ($candidates_reviews == 'on') {
                                                                if ($oveall_review_avg_rating != "") { ?>
                                                                    <div class="careerfy-featured-rating"><span
                                                                                class="careerfy-featured-rating-box"
                                                                                style="width:<?php echo($over_all_avg_rting_perc) ?>%;"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="no-rating-text"><p>No Rating Yet</p>
                                                                    </div>
                                                                <?php }
                                                            } ?>

                                                            <?php if ($get_item_country != "") { ?>
                                                                <span class="careerfy-featured-candidates-loc"><i
                                                                            class="fa fa-map-marker"></i> <?php echo $get_item_country ?></span>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <a <?php echo $first_btn_color ?>
                                                        href="<?php echo esc_url(get_permalink($post_id)) ?>"
                                                        class="careerfy-sixteen-candidate-grid-btn active"><?php echo esc_html__('View Profile', 'careerfy-frame') ?></a>
                                                <a <?php echo $second_btn_color ?>
                                                        href="<?php echo esc_url(get_permalink($post_id)) ?>"
                                                        class="careerfy-sixteen-candidate-grid-btn"><?php echo esc_html__('Hire Me', 'careerfy-frame') ?></a>
                                            </li>

                                        <?php } else if ($slider_style == 'style3') { ?>
                                            <div class="careerfy-popular-candidates-style14">
                                                <div class="careerfy-popular-candidates-style14-inner">
                                                    <figure><a href="<?php echo get_permalink($post_id) ?>"><img
                                                                    src="<?php echo $post_thumbnail_src ?>" alt=""></a>
                                                    </figure>
                                                    <h2>
                                                        <a href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id) ?></a>
                                                    </h2>
                                                    <span<?php echo $candidate_rank ?></span>
                                                    <small><?php echo esc_html__('Location:', 'careerfy-frame') ?><?php echo $get_item_state ?><?php echo $get_item_country ?></small>

                                                    <?php if (!empty($jobsearch_sectors)) { ?>
                                                        <small><?php echo esc_html__('Sector:', 'careerfy-frame') ?><?php echo $jobsearch_sectors[0]->name ?></small>
                                                    <?php } ?>

                                                    <ul class="careerfy-popular-candidates-style14-social">
                                                        <?php if (!empty($user_facebook_url)) { ?>
                                                            <li><a href="<?php echo $user_facebook_url ?>"
                                                                   class="careerfy-icon careerfy-facebook"></a></li>
                                                        <?php }
                                                        if (!empty($user_twitter_url)) { ?>
                                                            <li><a href="<?php echo $user_twitter_url ?>"
                                                                   class="careerfy-icon careerfy-twitter"></a></li>
                                                        <?php }
                                                        if (!empty($user_google_plus_url)) { ?>
                                                            <li><a href="<?php echo $user_google_plus_url ?>"
                                                                   class="careerfy-icon careerfy-google-plus"></a></li>
                                                        <?php }

                                                        if (!empty($user_linkedin_url)) { ?>
                                                            <li><a href="<?php echo $user_linkedin_url ?>"
                                                                   class="careerfy-icon careerfy-linkedin"></a></li>
                                                        <?php } ?>

                                                    </ul>
                                                </div>
                                                <strong><?php echo esc_html__('Salary', 'careerfy-frame') ?><?php echo $candidate_salary ?></strong>
                                            </div>

                                        <?php } else if ($slider_style == 'style1') { ?>

                                            <div class="careerfy-popular-candidates-layer">
                                                <figure><a href="<?php echo get_permalink($post_id) ?>"><img
                                                                src="<?php echo $post_thumbnail_src ?>"
                                                                alt=""><span <?php echo $final_color ?>></span> </a>
                                                </figure>
                                                <div class="careerfy-popular-candidates-text">
                                                    <h2>
                                                        <a href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id) ?></a>
                                                    </h2>
                                                    <span><?php echo $candidate_rank ?></span>
                                                    <?php echo $candidate_salary ?>
                                                </div>
                                            </div>
                                        <?php } else {
                                            wp_enqueue_script('careerfy-progressbar-two');
                                            ?>

                                            <div class="careerfy-candidates-style11-slider-layer">
                                                <div class="careerfy-candidates-style11">
                                                    <ul class="row">
                                                        <li class="col-md-12">
                                                            <div class="careerfy-candidates-style11-inner">
                                                                <div class="careerfy-candidates-style11-top">
                                                                    <?php if ($candidate_skills == 'on') { ?>
                                                                        <span><?php echo esc_html__('Score ', 'careerfy-frame') ?> <?php echo $overall_candidate_skills ?>%</span>
                                                                    <?php } ?>
                                                                    <?php if (!empty($candidate_expertise_skills)) { ?>
                                                                        <span><?php echo esc_html__('Skills', 'careerfy-frame') ?> <?php echo floor($candidate_expertise_skills) ?>%</span>
                                                                    <?php } ?>
                                                                </div>
                                                                <figure>
                                                                    <a href="<?php echo get_permalink($post_id) ?>"><img
                                                                                src="<?php echo $post_thumbnail_src ?>"
                                                                                alt=""></a>
                                                                    <figcaption>
                                                                        <h2>
                                                                            <a href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id) ?></a>
                                                                        </h2>
                                                                        <span><?php echo $candidate_rank ?></span>
                                                                        <small><?php echo $get_item_city ?><?php echo $get_item_state ?><?php echo $get_item_country ?></small>
                                                                        <div class="careerfy-candidates-style11-progress">
                                                                            <ul>
                                                                                <li><?php echo esc_html__('25', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('50', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('75', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('100', 'careerfy-frame') ?></li>
                                                                            </ul>
                                                                            <div id="single_candidate_progress"
                                                                                 data-width="<?php echo floor($candidate_expertise_skills) ?>"></div>
                                                                            <?php if (!is_admin()) { ?>
                                                                                <script>
                                                                                    jQuery(document).ready(function ($) {
                                                                                        jQuery('#single_candidate_progress').progressBar({
                                                                                            percentage: true,
                                                                                            animation: true,
                                                                                            height: "24",
                                                                                        });
                                                                                    });
                                                                                </script>
                                                                            <?php } ?>
                                                                            <ul>
                                                                                <li><?php echo esc_html__('Poor', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('Fair', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('Good', 'careerfy-frame') ?></li>
                                                                                <li><?php echo esc_html__('Excellent', 'careerfy-frame') ?></li>
                                                                            </ul>
                                                                        </div>
                                                                    </figcaption>
                                                                </figure>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php }

                                        if ($slider_counter % 3 === 0 && $slider_style == 'style4' && $slider_counter != $candidate_nums) { ?>
                                </ul>
                            </div>
                        </div>

                        <div class="careerfy-sixteen-candidate-layer">
                            <div class="careerfy-sixteen-candidate-grid">
                                <ul class="row">
                                    <?php }
                                    $count++;
                                    ///////////Counter is for slider only which is started from 1 ////////////
                                    $slider_counter++;
                                    }

                                    if ($slider_style == 'style4') { ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function () {

                    jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').css({'height': 'auto'});
                    jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').find('.careerfy-popular-candidates-layer').css({'display': 'inline-block'});

                    <?php if($slider_style == 'style3'){ ?>
                    jQuery('#careerfy-popcands-<?php echo($rand_num) ?>').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class='slick-arrow-left'><i class='careerfy-icon careerfy-next-long'></i><?php echo esc_html__('BACK', 'careerfy-frame') ?> </span>",
                        nextArrow: "<span class='slick-arrow-right'><?php echo esc_html__('NEXT', 'careerfy-frame') ?><i class='careerfy-icon careerfy-next-long'></i></span>",
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                    infinite: true,
                                }
                            },
                            {
                                breakpoint: 800,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 400,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                    <?php } else if($slider_style == 'style1'){ ?>
                    jQuery('#careerfy-popcands-<?php echo($rand_num) ?>').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class='slick-arrow-left'><i class='careerfy-icon careerfy-arrow-right-light'></i></span>",
                        nextArrow: "<span class='slick-arrow-right'><i class='careerfy-icon careerfy-arrow-right-light'></i></span>",
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 1,
                                    infinite: true,
                                }
                            },
                            {
                                breakpoint: 800,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 400,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                    <?php } else if($slider_style == 'style4'){ ?>
                    jQuery('.careerfy-sixteen-candidate-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        infinite: true,
                        dots: true,
                        arrows: false,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    infinite: true,
                                }
                            },
                            {
                                breakpoint: 800,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 400,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                    <?php } else { ?>
                    jQuery('#careerfy-popcands-<?php echo($rand_num) ?>').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class='slick-arrow-left'><i class='careerfy-icon careerfy-next'></i></span>",
                        nextArrow: "<span class='slick-arrow-right'><i class='careerfy-icon careerfy-next'></i></span>",
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                    infinite: true,
                                }
                            },
                            {
                                breakpoint: 800,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 400,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });

                    <?php }?>
                    var remSlidrLodrInt<?php echo($rand_num) ?> = setInterval(function () {
                        jQuery('#careerfy-slidloder-<?php echo($rand_num) ?>').remove();
                        clearInterval(remSlidrLodrInt<?php echo($rand_num) ?>);
                    }, 1500);
                    //

                    var slidrHightInt<?php echo($rand_num) ?> = setInterval(function () {
                        jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').css({'height': 'auto'});
                        jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').find('.careerfy-popular-candidates-layer').css({'display': 'inline-block'});

                        var slider_act_height_<?php echo($rand_num) ?> = jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').height();

                        var filtr_cname_<?php echo($rand_num) ?> = 'careerfy_popcands_slidr_lheight';
                        var c_date_<?php echo($rand_num) ?> = new Date();
                        c_date_<?php echo($rand_num) ?>.setTime(c_date_<?php echo($rand_num) ?>.getTime() + (60 * 60 * 1000));
                        var c_expires_<?php echo($rand_num) ?> = "; c_expires=" + c_date_<?php echo($rand_num) ?>.toGMTString();
                        document.cookie = filtr_cname_<?php echo($rand_num) ?> + "=" + slider_act_height_<?php echo($rand_num) ?> + c_expires_<?php echo($rand_num) ?> + "; path=/";

                        clearInterval(slidrHightInt<?php echo($rand_num) ?>);
                    }, 2500);
                });
                jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').find('.careerfy-popular-candidates-layer').css({'display': 'none'});

                var slider_height_<?php echo($rand_num) ?> = '<?php echo(isset($_COOKIE['careerfy_popcands_slidr_lheight']) && $_COOKIE['careerfy_popcands_slidr_lheight'] != '' ? $_COOKIE['careerfy_popcands_slidr_lheight'] . 'px' : '300px') ?>';
                jQuery('#careerfy-slidmaintop-<?php echo($rand_num) ?>').css({'height': slider_height_<?php echo($rand_num) ?>});
            </script>
        <?php } else {
            $html = esc_html__('No Record exist', 'careerfy-frame');
        }
        $html = ob_get_clean();
        echo $html;
    }

    protected function _content_template()
    {
    }
}