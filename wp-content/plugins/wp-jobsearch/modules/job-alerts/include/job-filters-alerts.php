<?php
// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

class JobSearch_Job_Alerts_Job_Filters {

    public function __construct() {

        add_filter('jobsearch_job_alerts_filters_html', array($this, 'job_alerts_filters_html'), 10, 4);
        add_action('wp_ajax_jobsearch_alrtmodal_popup_openhtml', array($this, 'job_alerts_filters_html'));
        add_action('wp_ajax_nopriv_jobsearch_alrtmodal_popup_openhtml', array($this, 'job_alerts_filters_html'));
    }

    public function keyword_filter_html($global_rand_id, $sh_atts) {

        $keyword_val = '';
        if (isset($_REQUEST['search_title']) && $_REQUEST['search_title'] != '') {
            $keyword_val = $_REQUEST['search_title'];
        }

        ob_start();
        ?>
        <div class="jobsearch-column-6">
            <div class="jobalert-filter-item">
                <label><?php esc_html_e('Keyword', 'wp-jobsearch') ?></label>
                <div class="filter-item-text">
                    <input type="text" name="search_title" class="chagn-keywords-field" value="<?php echo ($keyword_val) ?>">
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public function location_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts) {

        global $jobsearch_plugin_options;
        
        $location_map_type = isset($jobsearch_plugin_options['location_map_type']) ? $jobsearch_plugin_options['location_map_type'] : '';
        if ($location_map_type == 'mapbox') {
            wp_enqueue_script('jobsearch-mapbox');
            wp_enqueue_script('jobsearch-mapbox-geocoder');
            wp_enqueue_script('mapbox-geocoder-polyfill');
            wp_enqueue_script('mapbox-geocoder-polyfillauto');
        } else {
            wp_enqueue_script('jobsearch-google-map');
        }
        wp_enqueue_script('jobsearch-location-autocomplete');
        
        $loc_val = '';
        if (isset($_REQUEST['location']) && $_REQUEST['location'] != '') {
            $loc_val = $_REQUEST['location'];
        }
        if (isset($_REQUEST['location_location1']) && $_REQUEST['location_location1'] != '') {
            $loc_val = $_REQUEST['location_location1'];
            if (isset($_REQUEST['location_location2']) && $_REQUEST['location_location2'] != '') {
                $loc_val = $_REQUEST['location_location2'] . ', ' . $loc_val;
            }
            if (isset($_REQUEST['location_location3']) && $_REQUEST['location_location3'] != '') {
                $loc_val = $_REQUEST['location_location3'] . ', ' . $loc_val;
            }
        }

        $job_loc_filter = isset($sh_atts['job_filters_loc']) ? $sh_atts['job_filters_loc'] : '';

        ob_start();
        ?>
        <div class="jobsearch-column-6">
            <div class="jobalert-filter-item">
                <label><?php esc_html_e('Location', 'wp-jobsearch') ?></label>
                <div class="filter-item-text">
                    <div class="jobsearch_searchloc_div">
                        <span class="loc-loader"></span>
                        <input type="text" name="location" autocomplete="off" class="jobsearch_search_location_field" value="<?php echo ($loc_val) ?>">
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery('.jobsearch_search_location_field').cityAutocomplete();
        jQuery(document).on('click', '.jobsearch_searchloc_div', function () {
            jQuery('.jobsearch_search_location_field').prop('disabled', false);
        });
        jQuery(document).on('click', 'form', function () {
            var src_loc_val = jQuery(this).find('.jobsearch_search_location_field');
            src_loc_val.next('.loc_search_keyword').val(src_loc_val.val());
        });
        </script>
        <?php
        $html = ob_get_clean();

        return $html;
    }

    public function sector_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts) {

        $job_sector = '';
        if (isset($_REQUEST['sector_cat']) && $_REQUEST['sector_cat'] != '') {
            $job_sector = $_REQUEST['sector_cat'];
        }

        $job_sector_filter = isset($sh_atts['job_filters_sector']) ? $sh_atts['job_filters_sector'] : '';

        ob_start();
        $sector_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'fields' => 'all',
            'hide_empty' => false,
            'slug' => '',
            'parent' => 0,
        );
        $all_sector = get_terms('sector', $sector_args);
        if ($all_sector != '') {
            ?>
            <div class="jobsearch-column-6">
                <div class="jobalert-filter-item">
                    <label><?php esc_html_e('Sector', 'wp-jobsearch') ?></label>
                    <div class="filter-item-dropdown">
                        <select name="sector_cat">
                            <option value=""><?php esc_html_e('Select Job Sector', 'wp-jobsearch') ?></option>
                            <?php
                            foreach ($all_sector as $job_sectitem) {
                                $job_sect_selected = '';
                                if ($job_sector == $job_sectitem->slug) {
                                    $job_sect_selected = ' selected="selected"';
                                }
                                ?>
                                <option value="<?php echo ($job_sectitem->slug) ?>"<?php echo ($job_sect_selected) ?>><?php echo ($job_sectitem->name) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php
        }
        $html = ob_get_clean();

        if ($job_sector_filter == 'no') {
            $html = '';
        }

        return $html;
    }

    public function type_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts) {
        global $sitepress;
        
        $job_type = '';
        if (isset($_REQUEST['job_type']) && $_REQUEST['job_type'] != '') {
            $job_type = $_REQUEST['job_type'];
        }

        $job_type_filter = isset($sh_atts['job_filters_type']) ? $sh_atts['job_filters_type'] : '';

        ob_start();
        $typs_args = array(
            'taxonomy' => 'jobtype',
            'hide_empty' => false,
        );
        $typs_args = apply_filters('jobsearch_listing_jobtypes_filters_args', $typs_args);
        $all_job_type = get_terms($typs_args);
        if (empty($all_job_type) && function_exists('icl_object_id')) {
            $sitepress_def_lang = $sitepress->get_default_language();
            $sitepress_curr_lang = $sitepress->get_current_language();
            $sitepress->switch_lang($sitepress_def_lang, true);
            //
            $typs_args = array(
                'taxonomy' => 'jobtype',
                'hide_empty' => false,
            );
            $typs_args = apply_filters('jobsearch_listing_jobtypes_filters_args', $typs_args);
            $all_job_type = get_terms($typs_args);
            //
            $sitepress->switch_lang($sitepress_curr_lang, true);
        }
        if ($all_job_type != '') {
            ?>
            <div class="jobsearch-column-6">
                <div class="jobalert-filter-item">
                    <label><?php esc_html_e('Job Type', 'wp-jobsearch') ?></label>
                    <div class="filter-item-dropdown">
                        <select name="job_type">
                            <option value=""><?php esc_html_e('Select Job Type', 'wp-jobsearch') ?></option>
                            <?php
                            foreach ($all_job_type as $job_typeitem) {
                                $job_type_selected = '';
                                if ($job_type == $job_typeitem->slug) {
                                    $job_type_selected = ' selected="selected"';
                                }
                                ?>
                                <option value="<?php echo ($job_typeitem->slug) ?>"<?php echo ($job_type_selected) ?>><?php echo ($job_typeitem->name) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php
        }
        $html = ob_get_clean();

        if ($job_type_filter == 'no') {
            $html = '';
        }

        return $html;
    }

    public function custom_fields_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts) {

        global $jobsearch_plugin_options, $sitepress;

        $lang_code = '';
        if (function_exists('icl_object_id') && function_exists('wpml_init_language_switcher')) {
            $lang_code = $sitepress->get_current_language();
        }

        $salary_onoff_switch = isset($jobsearch_plugin_options['salary_onoff_switch']) ? $jobsearch_plugin_options['salary_onoff_switch'] : ''; // for job salary check

        $job_cus_fields = get_option("jobsearch_custom_field_job");
        ob_start();
        if (!empty($job_cus_fields)) {
            foreach ($job_cus_fields as $cus_fieldvar => $cus_field) {
                $all_item_empty = 0;
                if (isset($cus_field['options']['value']) && is_array($cus_field['options']['value'])) {
                    foreach ($cus_field['options']['value'] as $cus_field_options_value) {

                        if ($cus_field_options_value != '') {
                            $all_item_empty = 0;
                            break;
                        } else {
                            $all_item_empty = 1;
                        }
                    }
                }
                if ($cus_field['type'] == 'salary') {
                    $cus_field['enable-search'] = 'yes';
                }
                if (isset($cus_field['enable-search']) && $cus_field['enable-search'] == 'yes' && ($all_item_empty == 0)) {
                    if ($cus_field['type'] == 'salary') {
                        $query_str_var_name = 'jobsearch_field_job_salary';
                        $str_salary_type_name = 'job_salary_type';
                    } else {
                        $query_str_var_name = isset($cus_field['name']) ? $cus_field['name'] : '';
                    }

                    $cus_field_label_arr = isset($cus_field['label']) ? $cus_field['label'] : '';
                    $type = isset($cus_field['type']) ? $cus_field['type'] : '';

                    if ($type == 'text') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Text Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'email') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Email Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'number') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Number Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'date') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Date Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'dropdown') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Dropdown Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'range') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Range Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'textarea') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Textarea Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'heading') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Heading Field Label - ' . $cus_field_label_arr, $lang_code);
                    } else if ($type == 'salary') {
                        $cus_field_label_arr = apply_filters('wpml_translate_single_string', $cus_field_label_arr, 'Custom Fields', 'Salary Label - ' . $cus_field_label_arr, $lang_code);
                    }

                    $custom_field_placeholder = isset($cus_field['placeholder']) ? $cus_field['placeholder'] : '';

                    if ($cus_field['type'] == 'dropdown') {
                        if (isset($cus_field['options']['value']) && !empty($cus_field['options']['value'])) {
                            $cut_field_flag = 0;
                            $dropdwn_is_multi = isset($cus_field['multi']) ? $cus_field['multi'] : '';
                            ?>
                            <div class="jobsearch-column-6">
                                <div class="jobalert-filter-item">
                                    <label><?php echo esc_html(stripslashes($cus_field_label_arr)); ?></label>
                                    <div class="filter-item-dropdown">
                                        <select name="<?php echo esc_html($query_str_var_name) . ($dropdwn_is_multi == 'yes' ? '[]' : ''); ?>" <?php echo ($dropdwn_is_multi == 'yes' ? 'multiple' : '') ?>>
                                            <?php
                                            if ($dropdwn_is_multi != 'yes') {
                                                ?>
                                                <option value=""><?php echo ($custom_field_placeholder != '' ? $custom_field_placeholder : esc_html__('Select', 'wp-jobsearch')) ?></option>
                                                <?php
                                            }
                                            foreach ($cus_field['options']['value'] as $cus_field_options_value) {
                                                $custom_dropdown_selected = '';
                                                if ($dropdwn_is_multi) {
                                                    $request_val = isset($_REQUEST[$query_str_var_name]) ? $_REQUEST[$query_str_var_name] : '';
                                                    $request_val_arr = explode(",", $request_val);
                                                    if (!empty($request_val_arr) && in_array($cus_field_options_value, $request_val_arr)) {
                                                        $custom_dropdown_selected = ' selected="selected"';
                                                    }
                                                } else {
                                                    if (isset($_REQUEST[$query_str_var_name]) && $_REQUEST[$query_str_var_name] == $cus_field_options_value) {
                                                        $custom_dropdown_selected = ' selected="selected"';
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo esc_html($cus_field_options_value); ?>"<?php echo ($custom_dropdown_selected) ?>><?php echo(apply_filters('wpml_translate_single_string', $cus_field['options']['label'][$cut_field_flag], 'Custom Fields', 'Dropdown Option Label - ' . $cus_field['options']['label'][$cut_field_flag], $lang_code)); ?></option>
                                                <?php
                                                $cut_field_flag++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else if ($cus_field['type'] == 'salary') {
                        $job_salary_types = isset($jobsearch_plugin_options['job-salary-types']) ? $jobsearch_plugin_options['job-salary-types'] : '';

                        if ($salary_onoff_switch != 'off') {

                            $salary_min = $cus_field['min'];
                            $salary_laps = $cus_field['laps'];
                            $salary_interval = $cus_field['interval'];
                            $salary_field_type = isset($cus_field['field-style']) ? $cus_field['field-style'] : 'simple'; //input, slider, input_slider

                            if (strpos($salary_field_type, '-') !== FALSE) {
                                $salary_field_type_arr = explode("_", $salary_field_type);
                            } else {
                                $salary_field_type_arr[0] = $salary_field_type;
                            }
                            ?>
                            <div class="jobsearch-column-6">
                                <div class="jobalert-filter-item">
                                    <label><?php echo esc_html(stripslashes($cus_field_label_arr)); ?></label>
                                    <?php
                                    // Salary Types
                                    if (!empty($job_salary_types)) {
                                        $slar_type_count = 1;
                                        ?>
                                        <div class="jobsearch-salary-types-filter">
                                            <ul>
                                                <?php
                                                foreach ($job_salary_types as $job_salary_type) {
                                                    $salary_countr = rand(100000, 9999999);
                                                    $job_salary_type = apply_filters('wpml_translate_single_string', $job_salary_type, 'JobSearch Options', 'Salary Type - ' . $job_salary_type, $lang_code);
                                                    $slalary_type_selected = '';
                                                    if (isset($_REQUEST[$str_salary_type_name]) && $_REQUEST[$str_salary_type_name] == 'type_' . $slar_type_count) {
                                                        $slalary_type_selected = ' checked="checked"';
                                                    }
                                                    ?>
                                                    <li class="salary-type-radio">
                                                        <input type="radio"
                                                               id="salary_type_<?php echo($slar_type_count . '-' . $salary_countr) ?>"
                                                               name="<?php echo($str_salary_type_name) ?>"
                                                               class="job_salary_type"<?php echo($slalary_type_selected) ?>
                                                               value="type_<?php echo($slar_type_count) ?>">
                                                        <label for="salary_type_<?php echo($slar_type_count . '-' . $salary_countr) ?>">
                                                            <span></span>
                                                            <small><?php echo($job_salary_type) ?></small>
                                                        </label>
                                                    </li>
                                                    <?php
                                                    $slar_type_count++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    //
                                    ?>
                                    <div class="filter-item-dropdown">
                                        <?php
                                        $salary_flag = 0;
                                        while (count($salary_field_type_arr) > $salary_flag) {
                                            $filter_more_counter = 1;
                                            ?>
                                            <select name="<?php echo esc_html($query_str_var_name); ?>">
                                                <option value=""><?php esc_html_e('Select', 'wp-jobsearch') ?></option>
                                                <?php
                                                $loop_flag = 1;
                                                while ($loop_flag <= $salary_laps) {
                                                    $custom_slider_selected = '';
                                                    if (isset($_REQUEST[$query_str_var_name]) && $_REQUEST[$query_str_var_name] == (($salary_min + 1) . "-" . ($salary_min + $salary_interval))) {
                                                        $custom_slider_selected = ' selected="selected"';
                                                    }
                                                    $salary_from = ($salary_min + 1);
                                                    $salary_upto = ($salary_min + $salary_interval);
                                                    ?>
                                                    <option value="<?php echo esc_html((($salary_min + 1) . "-" . ($salary_min + $salary_interval))); ?>" <?php echo esc_html($custom_slider_selected); ?>><?php echo((($salary_from) . " - " . ($salary_upto))); ?></option>
                                                    <?php
                                                    $salary_min = $salary_min + $salary_interval;
                                                    $loop_flag++;
                                                    $filter_more_counter++;
                                                }
                                                ?>
                                            </select>
                                            <?php
                                            $salary_flag++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        $custom_field_val = '';
                        if (isset($_REQUEST[$query_str_var_name]) && $_REQUEST[$query_str_var_name] == $cus_field_options_value) {
                            $custom_field_val = $_REQUEST[$query_str_var_name];
                        }
                        ?>
                        <div class="jobsearch-column-6">
                            <div class="jobalert-filter-item">
                                <label><?php echo esc_html(stripslashes($cus_field_label_arr)); ?></label>
                                <div class="filter-item-text">
                                    <input type="text" name="<?php echo esc_html($query_str_var_name) ?>" value="<?php echo ($custom_field_val) ?>">
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        }

        $html = ob_get_clean();

        return $html;
    }

    public function job_alerts_filters_html($html = '', $global_rand_id = 0, $left_filter_count_switch = '', $sh_atts = array()) {

        global $jobsearch_plugin_options;
        
        if (isset($_POST['job_shatts_str']) && $_POST['job_shatts_str'] != '') {
            $sh_atts = stripslashes($_POST['job_shatts_str']);
            $sh_atts = json_decode($sh_atts, true);
            //
            $global_rand_id = isset($_POST['sh_globrnd_id']) ? $_POST['sh_globrnd_id'] : '';
            $left_filter_count_switch = isset($sh_atts['job_filters_count']) ? $sh_atts['job_filters_count'] : '';
        }
        
        //
        $job_alfiltr_sectr = isset($jobsearch_plugin_options['job_alerts_filtr_sectr']) ? $jobsearch_plugin_options['job_alerts_filtr_sectr'] : '';
        $job_alfiltr_jobtype = isset($jobsearch_plugin_options['job_alerts_filtr_jobtype']) ? $jobsearch_plugin_options['job_alerts_filtr_jobtype'] : '';
        $job_alfiltr_loc = isset($jobsearch_plugin_options['job_alerts_filtr_location']) ? $jobsearch_plugin_options['job_alerts_filtr_location'] : '';
        $job_alfiltr_cusfields = isset($jobsearch_plugin_options['job_alerts_filtr_cusfield']) ? $jobsearch_plugin_options['job_alerts_filtr_cusfield'] : '';
        //

        $job_types_switch = isset($jobsearch_plugin_options['job_types_switch']) ? $jobsearch_plugin_options['job_types_switch'] : '';

        $filters_op_sort = isset($jobsearch_plugin_options['jobs_srch_filtrs_sort']) ? $jobsearch_plugin_options['jobs_srch_filtrs_sort'] : '';

        $filters_op_sort = isset($filters_op_sort['fields']) ? $filters_op_sort['fields'] : '';

        if (isset($filters_op_sort['date_posted'])) {
            
            $html .= '<div class="jobsearch-row">';
            $html .= $this->keyword_filter_html($global_rand_id, $sh_atts);
            foreach ($filters_op_sort as $filter_sort_key => $filter_sort_val) {
                if ($filter_sort_key == 'location' && $job_alfiltr_loc == 'on') {
                    $html .= $this->location_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts);
                } else if ($filter_sort_key == 'sector' && $job_alfiltr_sectr == 'on') {
                    $html .= $this->sector_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts);
                } else if ($filter_sort_key == 'job_type' && $job_alfiltr_jobtype == 'on') {
                    if ($job_types_switch == 'on') {
                        $html .= $this->type_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts);
                    }
                } else if ($filter_sort_key == 'custom_fields' && $job_alfiltr_cusfields == 'on') {
                    $html .= $this->custom_fields_filter_html($global_rand_id, $left_filter_count_switch, $sh_atts);
                }
            }
            $html .= '</div>';
            $html .= '<input type="hidden" name="alert_frequency" value="' . (isset($_POST['alert_frequency']) ? $_POST['alert_frequency'] : '') . '">';
            $html .= '<span class="jobsearch-job-shatts" data-id="' . $global_rand_id . '" style="display:none;">' . json_encode($sh_atts) . '</span>';
        }

        if (isset($_POST['job_shatts_str'])) {
            echo json_encode(array('pop_html' => $html));
            die;
        } else {
            return $html;
        }
    }

}

new JobSearch_Job_Alerts_Job_Filters();
