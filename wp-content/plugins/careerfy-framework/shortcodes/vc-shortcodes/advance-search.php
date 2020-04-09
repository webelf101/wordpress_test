<?php
/**
 * Advance Search Shortcode
 * @return html
 */
add_shortcode('careerfy_advance_search', 'careerfy_advance_search_shortcode');

function careerfy_advance_search_shortcode($atts, $content)
{
    global $wpdb, $jobsearch_plugin_options;
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
        'css' => '',
    ), $atts));

    $rand_num = rand();

    $design_css_class = '';
    if (function_exists('vc_shortcode_custom_css_class')) {
        $design_css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
    }
    $transparent_bg_color = '';
    if (isset($search_bg_color) && !empty($search_bg_color)) {
        $transparent_bg_color = 'style="background-color:' . $search_bg_color . '!important"';
    }
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

    $location_map_type = isset($jobsearch_plugin_options['location_map_type']) ? $jobsearch_plugin_options['location_map_type'] : '';

    if ($location_map_type == 'mapbox') {
        wp_enqueue_script('jobsearch-mapbox');
        wp_enqueue_script('jobsearch-mapbox-geocoder');
        wp_enqueue_script('mapbox-geocoder-polyfill');
        wp_enqueue_script('mapbox-geocoder-polyfillauto');
    }

    ob_start();
    if (class_exists('JobSearch_plugin')) {
        if ($view == 'view20') { ?>
            <div class="careerfy-twentytwo-search">

                <?php if (!empty($small_search_title)) { ?>
                    <small class="careerfy-twentytwo-search-tag" <?php echo($adv_search_title_color) ?>><?php echo($small_search_title) ?>
                        <img src="<?php echo trailingslashit(get_template_directory_uri()) . 'images/arrow-plane.png'; ?>"/>
                    </small>
                <?php }

                if ($content != '') {
                    echo force_balance_tags($content);
                }

                if ($srch_desc != '') { ?>
                    <span class="careerfy-twentytwo-search-description" <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                <?php } ?>
                <div class="careerfy-twentytwo-search-tabs">
                    <ul class="careerfy-search-twentytwo-tabs-nav">
                        <li class="active">
                            <a data-toggle="tab"
                               href="#home"><i
                                        class="fa fa-black-tie"></i><span><?php echo esc_html__("I am looking tutoring", "careerfy-frame") ?></span></a>
                        </li>

                        <li><a data-toggle="tab"
                               href="#menu1"><i
                                        class="fa fa-eye"></i><span><?php echo esc_html__("I am looking jobs", "careerfy-frame") ?></span></a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <form method="get" action="<?php echo(get_permalink($result_page)); ?>"
                                  class="careerfy-twentytwo-loc-search">

                                <?php
                                if ($keyword_field == 'show') {
                                    if ($autofill_keyword == 'yes') {
                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                    }
                                    ?>

                                    <i class="careerfy-icon careerfy-search"></i>
                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                               name="search_title" data-type="job" type="text">
                                        <span class="sugg-search-loader"></span>
                                    </div>

                                <?php } ?>
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
                                <?php if ($location_field == 'show') {
                                    if ($autofill_location == 'yes') {
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
                                        wp_enqueue_script('jobsearch-location-autocomplete');
                                    }
                                    ob_start();
                                    ?>
                                    <div class="jobsearch_searchloc_div">
                                        <?php
                                        if ($autofill_location == 'yes') {
                                            ?>
                                            <span class="loc-loader"></span>
                                            <i class="careerfy-icon careerfy-pin"></i>
                                            <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                   autocomplete="off"
                                                   class="jobsearch_search_location_field"
                                                   type="text">
                                            <input type="hidden"
                                                   class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                   name="location">
                                        <?php } else { ?>
                                            <i class="careerfy-icon careerfy-pin"></i>
                                            <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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

                                    <?php
                                    $srchfield_html = ob_get_clean();
                                    echo apply_filters('jobsearch_careerfy_advance_search_sh_frmloc', $srchfield_html);
                                } ?>

                                <input type="submit" value="<?php esc_html_e("", 'careerfy-frame') ?>">
                                <i class="careerfy-icon careerfy-search"></i>

                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <form method="get" action="<?php echo(get_permalink($result_page_3)); ?>"
                                  class="careerfy-twentytwo-loc-search">

                                <?php if ($keyword_field == 'show') { ?>


                                    <?php if ($autofill_keyword == 'yes') {
                                        wp_enqueue_script('jobsearch-search-box-sugg');
                                    } ?>
                                    <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                        <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                               name="search_title" data-type="job" type="text">
                                        <span class="sugg-search-loader"></span>
                                    </div>

                                <?php } ?>
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
                                <?php if ($location_field == 'show') {
                                    if ($autofill_location == 'yes') {
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
                                        wp_enqueue_script('jobsearch-location-autocomplete');
                                    }
                                    ob_start();
                                    ?>

                                    <div class="jobsearch_searchloc_div">
                                        <?php
                                        if ($autofill_location == 'yes') {
                                            ?>
                                            <span class="loc-loader"></span>
                                            <i class="careerfy-icon careerfy-pin"></i>
                                            <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                   autocomplete="off"
                                                   class="jobsearch_search_location_field"
                                                   type="text">
                                            <input type="hidden"
                                                   class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                   name="location">
                                        <?php } else { ?>
                                            <i class="careerfy-icon careerfy-pin"></i>
                                            <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                   class="<?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                   name="location" type="text">
                                        <?php }

                                        if ($auto_geo_location == 'yes') { ?>
                                            <a href="javascript:void(0);" class="geolction-btn"
                                               onclick="JobsearchGetClientLocation()"><i
                                                        class="careerfy-icon careerfy-location"></i></a>
                                        <?php } ?>
                                    </div>

                                    <?php
                                    $srchfield_html = ob_get_clean();
                                    echo apply_filters('jobsearch_careerfy_advance_search_sh_frmloc', $srchfield_html);
                                } ?>

                                <input type="submit" value="<?php esc_html_e("", 'careerfy-frame') ?>">
                                <i class="careerfy-icon careerfy-search"></i>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php } else if ($view == 'view19') { ?>

            <div class="careerfy-twentyone-search">
                <div class="careerfy-twentyone-search-inner">
                    <?php if (!empty($small_search_title)) { ?>
                        <small class="careerfy-twentyone-search-tag" <?php echo($adv_search_title_color) ?>><?php echo($small_search_title) ?></small>
                    <?php } ?>
                    <?php if ($content != '') { ?>
                        <h1<?php echo($adv_search_title_color) ?>><?php echo($content) ?> <img
                                    src="<?php echo trailingslashit(get_template_directory_uri()) . 'images/text-arrow.png'; ?>">
                        </h1>
                    <?php }
                    if ($srch_desc != '') { ?>
                        <span class="careerfy-twentyone-search-description" <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                    <?php } ?>
                    <div class="careerfy-twentyone-search-tabs">
                        <ul class="careerfy-search-twentyone-tabs-nav">
                            <li class="active">
                                <a data-toggle="tab"
                                   href="#home"><i
                                            class="fa fa-black-tie"></i><span><?php echo esc_html__("Find Help", "careerfy-frame") ?></span></a>
                            </li>
                            <?php
                            ob_start();
                            ?>
                            <li><a data-toggle="tab"
                                   href="#menu1"><i
                                            class="fa fa-eye"></i><span><?php echo esc_html__("Looking job", "careerfy-frame") ?></span></a>
                            </li>
                            <?php
                            $html = ob_get_clean();
                            echo apply_filters('careerfy_adv_srch_sh_view12_findcand_tab', $html);
                            ?>
                        </ul>
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <form method="get" action="<?php echo(get_permalink($result_page)); ?>"
                                      class="careerfy-twentyone-loc-search">

                                    <?php
                                    if ($keyword_field == 'show') {
                                        if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        }
                                        ?>

                                        <i class="careerfy-icon careerfy-search"></i>
                                        <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                            <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                   name="search_title" data-type="job" type="text">
                                            <span class="sugg-search-loader"></span>
                                        </div>

                                    <?php } ?>
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
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ob_start();
                                        ?>
                                        <div class="jobsearch_searchloc_div">
                                            <?php
                                            if ($autofill_location == 'yes') {
                                                ?>
                                                <span class="loc-loader"></span>
                                                <i class="careerfy-icon careerfy-pin"></i>
                                                <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                       autocomplete="off"
                                                       class="jobsearch_search_location_field"
                                                       type="text">
                                                <input type="hidden"
                                                       class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                       name="location">
                                            <?php } else { ?>
                                                <i class="careerfy-icon careerfy-pin"></i>
                                                <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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

                                        <?php
                                        $srchfield_html = ob_get_clean();
                                        echo apply_filters('jobsearch_careerfy_advance_search_sh_frmloc', $srchfield_html);
                                    } ?>

                                    <input type="submit" value="<?php esc_html_e("Search", 'careerfy-frame') ?>">


                                </form>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                                <form method="get" action="<?php echo(get_permalink($result_page_3)); ?>"
                                      class="careerfy-twentyone-loc-search">

                                    <?php if ($keyword_field == 'show') { ?>


                                        <?php if ($autofill_keyword == 'yes') {
                                            wp_enqueue_script('jobsearch-search-box-sugg');
                                        } ?>
                                        <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                            <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                   name="search_title" data-type="job" type="text">
                                            <span class="sugg-search-loader"></span>
                                        </div>

                                    <?php } ?>
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
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
                                            wp_enqueue_script('jobsearch-location-autocomplete');
                                        }
                                        ob_start();
                                        ?>

                                        <div class="jobsearch_searchloc_div">
                                            <?php
                                            if ($autofill_location == 'yes') {
                                                ?>
                                                <span class="loc-loader"></span>
                                                <i class="careerfy-icon careerfy-pin"></i>
                                                <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                       autocomplete="off"
                                                       class="jobsearch_search_location_field"
                                                       type="text">
                                                <input type="hidden"
                                                       class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                       name="location">
                                            <?php } else { ?>
                                                <i class="careerfy-icon careerfy-pin"></i>
                                                <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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

                                        <?php
                                        $srchfield_html = ob_get_clean();
                                        echo apply_filters('jobsearch_careerfy_advance_search_sh_frmloc', $srchfield_html);
                                    } ?>

                                    <input type="submit" value="<?php esc_html_e("Search", 'careerfy-frame') ?>">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else if ($view == 'view18') { ?>
            <div class="careerfy-twenty-search">
                <?php if (!empty($small_search_title)) { ?>
                    <small class="careerfy-twenty-search-tag"><?php echo($small_search_title) ?></small>
                <?php } ?>
                <?php if ($content != '') { ?>
                    <h1<?php echo($adv_search_title_color) ?>><?php echo($content) ?> <img
                                src="<?php echo trailingslashit(get_template_directory_uri()) . 'images/title-arrow.png'; ?>">
                    </h1>
                <?php }
                if ($srch_desc != '') { ?>
                    <span class="careerfy-twenty-search-description" <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                <?php } ?>
                <div class="careerfy-twenty-search-tabs">
                    <ul class="careerfy-search-twenty-tabs-nav">
                        <li class="active">
                            <a data-toggle="tab"
                               href="#home"><span><?php echo esc_html__("I'am looking for animal care", "careerfy-frame") ?></span></a>
                        </li>

                        <li><a data-toggle="tab"
                               href="#menu1"><span><?php echo esc_html__("I'am looking for a job", "careerfy-frame") ?></span></a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <form method="get" action="<?php echo(get_permalink($result_page)); ?>"
                                  class="careerfy-twenty-loc-search">
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
                                    <?php } ?>
                                    <?php
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));


                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) { ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
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
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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
                                    } ?>

                                    <li class="careerfy-twenty-loc-submit"><input type="submit"
                                                                                  value="<?php esc_html_e("", 'careerfy-frame') ?>">
                                        <i class="careerfy-icon careerfy-search"></i>
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <form method="get" action="<?php echo(get_permalink($result_page_3)); ?>"
                                  class="careerfy-twenty-loc-search">
                                <ul>
                                    <?php if ($keyword_field == 'show') { ?>
                                        <li>

                                            <?php if ($autofill_keyword == 'yes') {
                                                wp_enqueue_script('jobsearch-search-box-sugg');
                                            } ?>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));
                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) { ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
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
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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
                                    } ?>
                                    <li class="careerfy-twenty-loc-submit">
                                        <input type="submit" value="<?php esc_html_e("", 'careerfy-frame') ?>">
                                        <i class="careerfy-icon careerfy-search"></i>
                                    </li>

                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php } else if ($view == 'view17') { ?>

            <div class="careerfy-nineteen-search">
                <?php if ($srch_title != '') { ?>
                    <h1<?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
                <?php }
                if ($srch_desc != '') { ?>
                    <span class="careerfy-nineteen-search-description" <?php echo($adv_search_paragraph_color) ?>><?php echo($srch_desc) ?></span>
                <?php } ?>
                <div class="careerfy-nineteen-search-tabs">
                    <ul class="careerfy-search-nineteen-tabs-nav">
                        <li class="active">
                            <a data-toggle="tab"
                               href="#home"><i
                                        class="fa fa-black-tie"></i><span><?php echo esc_html__('Find Help', 'careerfy-frame') ?></span></a>
                        </li>

                        <li><a data-toggle="tab"
                               href="#menu1"><i
                                        class="fa fa-eye"></i><span><?php echo esc_html__('Looking Job', 'careerfy-frame') ?></span></a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <form method="get" action="<?php echo(get_permalink($result_page)); ?>"
                                  class="careerfy-nineteen-loc-search">
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
                                    <?php } ?>
                                    <?php
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));


                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) { ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
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
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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
                                    } ?>

                                    <li><input type="submit"
                                               value="<?php esc_html_e("Search Now", 'careerfy-frame') ?>">
                                    </li>
                                </ul>
                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <form method="get" action="<?php echo(get_permalink($result_page_3)); ?>"
                                  class="careerfy-nineteen-loc-search">
                                <ul>
                                    <?php if ($keyword_field == 'show') { ?>
                                        <li>

                                            <?php if ($autofill_keyword == 'yes') {
                                                wp_enqueue_script('jobsearch-search-box-sugg');
                                            } ?>
                                            <div class="<?php echo($autofill_keyword == 'yes' ? 'jobsearch-sugges-search' : '') ?>">
                                                <input placeholder="<?php esc_html_e('Keywords or Title', 'careerfy-frame') ?>"
                                                       name="search_title" data-type="job" type="text">
                                                <span class="sugg-search-loader"></span>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php
                                    $all_sectors = get_terms(array(
                                        'taxonomy' => 'sector',
                                        'hide_empty' => false,
                                    ));
                                    if (!empty($all_sectors) && !is_wp_error($all_sectors) && $category_field == 'show') { ?>
                                        <li>
                                            <div class="careerfy-select-style">
                                                <select name="sector_cat" class="selectize-select">
                                                    <option value=""><?php esc_html_e('Categories', 'careerfy-frame') ?></option>
                                                    <?php
                                                    foreach ($all_sectors as $term_sector) { ?>
                                                        <option value="<?php echo urldecode($term_sector->slug) ?>"><?php echo($term_sector->name) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </li>
                                    <?php } ?>
                                    <?php if ($location_field == 'show') {
                                        if ($autofill_location == 'yes') {
                                            if ($location_map_type == 'mapbox') {
                                                wp_enqueue_script('jobsearch-mapbox');
                                                wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                wp_enqueue_script('mapbox-geocoder-polyfill');
                                                wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                            } else {
                                                wp_enqueue_script('jobsearch-google-map');
                                            }
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
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
                                                           autocomplete="off"
                                                           class="jobsearch_search_location_field"
                                                           type="text">
                                                    <input type="hidden"
                                                           class="loc_search_keyword <?php echo($auto_geo_location == 'yes' ? 'srch_autogeo_location' : '') ?>"
                                                           name="location">
                                                <?php } else { ?>
                                                    <i class="careerfy-icon careerfy-pin"></i>
                                                    <input placeholder="<?php esc_html_e('Location', 'careerfy-frame') ?>"
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
                                    } ?>
                                    <li><input type="submit"
                                               value="<?php esc_html_e("Search Now", 'careerfy-frame') ?>"></li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="careerfy-nineteen-category-list">
                    <?php
                    $to_result_page = $result_page;
                    $top_sectors = $wpdb->get_col($wpdb->prepare("SELECT terms.term_id FROM $wpdb->terms AS terms"
                        . " LEFT JOIN $wpdb->term_taxonomy AS term_tax ON(terms.term_id = term_tax.term_id) "
                        . " LEFT JOIN $wpdb->termmeta AS term_meta ON(terms.term_id = term_meta.term_id) "
                        . " WHERE term_tax.taxonomy=%s AND term_meta.meta_key=%s"
                        . " ORDER BY cast(term_meta.meta_value as unsigned) DESC LIMIT 4", 'sector', 'active_jobs_count'));


                    if (!empty($top_sectors) && !is_wp_error($top_sectors)) { ?>
                        <ul>
                            <?php
                            foreach ($top_sectors as $term_id) {

                                $term_sector = get_term_by('id', $term_id, 'sector');
                                $term_fields = get_term_meta($term_sector->term_id, 'careerfy_frame_cat_fields', true);
                                $term_icon = isset($term_fields['icon']) ? $term_fields['icon'] : '';
                                $term_color = isset($term_fields['color']) ? $term_fields['color'] : '';
                                $term_image = isset($term_fields['image']) ? $term_fields['image'] : '';

                                $cat_goto_link = add_query_arg(array('sector_cat' => $term_sector->slug), get_permalink($to_result_page));
                                $cat_goto_link = apply_filters('jobsearch_job_sector_cat_result_link', $cat_goto_link, $term_sector->slug);

                                ?>
                                <li>
                                    <a href="<?php echo($cat_goto_link) ?>"><i
                                                class="<?php echo($term_icon) ?>"<?php echo($term_color != '' ? ' style="color: ' . $term_color . ';"' : '') ?>></i><span><?php echo($term_sector->name) ?></span>
                                    </a>

                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>


        <?php } else if ($view == 'view16') { ?>
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
                <div class="container">
                    <?php if ($srch_title != '') { ?>
                        <h1 <?php echo($adv_search_title_color) ?>><?php echo($srch_title) ?></h1>
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
            </div>
        <?php } else if ($view == 'view12') { ?>
            <style>
                .banner-bg-img-<?php echo $rand_num ?> {
                    background: url("<?php echo $bg_img ?>") no-repeat;
                }

            </style>
            <div class="careerfy-fifteen-banner">
                <div class="container">
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
                                                       value="<?php esc_html_e("Find Job", 'careerfy-frame') ?>">
                                            </li>
                                        </ul>
                                    </form>
                                </div>
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
                    <div class="container">
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
                        $adv_banner_images = vc_param_group_parse_atts($adv_banner_images);
                        if (!empty($adv_banner_images)) { ?>
                            <ul>
                                <?php
                                $_exf_counter = 0;
                                foreach ($adv_banner_images as $adv_banner_image) { ?>
                                    <li><a href="<?php echo $adv_banner_image['img_link'] ?>"><img
                                                    src="<?php echo $adv_banner_image['banner_img'] ?>" alt=""></a>
                                    </li>
                                    <?php
                                    $_exf_counter++;
                                }
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
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
                <div class="container">
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
                                                <?php } else { ?>
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
                                                if ($location_map_type == 'mapbox') {
                                                    wp_enqueue_script('jobsearch-mapbox');
                                                    wp_enqueue_script('jobsearch-mapbox-geocoder');
                                                    wp_enqueue_script('mapbox-geocoder-polyfill');
                                                    wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                                } else {
                                                    wp_enqueue_script('jobsearch-google-map');
                                                }
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
                        <?php
                    }
                    ?>

                </div>


            </div>
            <?php
        } else if ($view == 'view3') {
            ?>
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
                        <form class="careerfy-banner-search" method="get" action="<?php echo(get_permalink($result_page)); ?>">
                            <ul class="<?php echo apply_filters('careerfy_adv_srch_view1_ul_class', 'careerfy-jobs-srchul', $atts) ?>">
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
                                        if ($location_map_type == 'mapbox') {
                                            wp_enqueue_script('jobsearch-mapbox');
                                            wp_enqueue_script('jobsearch-mapbox-geocoder');
                                            wp_enqueue_script('mapbox-geocoder-polyfill');
                                            wp_enqueue_script('mapbox-geocoder-polyfillauto');
                                        } else {
                                            wp_enqueue_script('jobsearch-google-map');
                                        }
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
    return $html;
}
