<?php
global $jobsearch_plugin_options;

$output = '';
//echo '<pre>'; print_r($args_count); echo '</pre>';
$left_filter_count_switch = 'no';

$filters_op_sort = isset($jobsearch_plugin_options['emp_srch_filtrs_sort']) ? $jobsearch_plugin_options['emp_srch_filtrs_sort'] : '';

$filters_op_sort = isset($filters_op_sort['fields']) ? $filters_op_sort['fields'] : '';
?>
<div class="jobsearch-column-3 jobsearch-typo-wrap">
    <?php
    $sh_atts = isset($employer_arg['atts']) ? $employer_arg['atts'] : '';
    if (isset($sh_atts['employer_filters_count']) && $sh_atts['employer_filters_count'] == 'yes') {
        $left_filter_count_switch = 'yes';
    }

    $mobile_view_flag = false;

    if (jobsearch_is_mobile()) {
        $mobile_view_flag = true;
    }
    if ($mobile_view_flag) {
    ?>

    <div class="jobsearch-mobile-wrap">

        <a href="javascript:void(0);"
           class="jobsearch-mobile-btn"><?php echo esc_html__('Filter Sorting', 'wp-jobsearch'); ?><i
                    class="careerfy-icon careerfy-up-arrow"></i></a>

        <div class="jobsearch-mobile-section" style="display: none;">
            <?php
            }
            if (isset($filters_op_sort['date_posted'])) {
                foreach ($filters_op_sort as $filter_sort_key => $filter_sort_val) {
                    if ($filter_sort_key == 'date_posted') {
                        $output .= apply_filters('jobsearch_employer_filter_date_posted_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else if ($filter_sort_key == 'location') {
                        $output .= apply_filters('jobsearch_employer_filter_location_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else if ($filter_sort_key == 'sector') {
                        $output .= apply_filters('jobsearch_employer_filter_sector_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else if ($filter_sort_key == 'job_type') {
                        $output .= apply_filters('jobsearch_employer_filter_employertype_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else if ($filter_sort_key == 'team_size') {
                        $output .= apply_filters('jobsearch_team_size_filter_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else if ($filter_sort_key == 'custom_fields') {
                        $output .= apply_filters('jobsearch_custom_fields_filter_box_html', '', 'employer', $global_rand_id, $args_count, $left_filter_count_switch, 'jobsearch_employer_content_load');
                    } else if ($filter_sort_key == 'ads') {
                        $filter_ads_code = isset($jobsearch_plugin_options['emps_filter_adcode']) ? $jobsearch_plugin_options['emps_filter_adcode'] : '';
                        if ($filter_ads_code != '') {
                            ob_start();
                            echo do_shortcode($filter_ads_code);
                            $the_ad_code = ob_get_clean();
                            $output .= '<div class="jobsearch-filter-responsive-wrap"><div class="filter-ads-wrap">' . $the_ad_code . '</div></div>';
                        }
                    }
                }
            } else {
                /*
                 * add filter box for job locations filter 
                 */
                $output .= apply_filters('jobsearch_employer_filter_location_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);

                //$output .= apply_filters('jobsearch_location_field_filter_box_html', '', 'employer', $global_rand_id, $args_count, 'jobsearch_employer_content_load');
                /*
                 * add filter box for date posted filter 
                 */
                $output .= apply_filters('jobsearch_employer_filter_date_posted_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                /*
                 * add filter box for employer types filter 
                 */
                $output .= apply_filters('jobsearch_employer_filter_employertype_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                /*
                 * add filter box for sectors filter 
                 */
                $output .= apply_filters('jobsearch_employer_filter_sector_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                /*
                 * add filter box for custom fields filter 
                 */
                $output .= apply_filters('jobsearch_custom_fields_filter_box_html', '', 'employer', $global_rand_id, $args_count, $left_filter_count_switch, 'jobsearch_employer_content_load');
                /*
                 * add filter box for team fields filter 
                 */
                $output .= apply_filters('jobsearch_team_size_filter_box_html', '', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
            }
            echo force_balance_tags($output);
            if ($mobile_view_flag) { ?>
        </div>
    </div>
<?php
}
?>
</div>
