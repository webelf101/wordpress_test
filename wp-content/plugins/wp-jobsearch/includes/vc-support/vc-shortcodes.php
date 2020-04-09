<?php

/**
 * visual composer shortcodes mapping
 * @config
 */
/**
 * list all hooks adding
 * @return hooks
 */
add_action('vc_before_init', 'jobsearch_vc_user_job_shortcode');
add_action('vc_before_init', 'jobsearch_vc_banner_advertisement');

/**
 * adding user job shortcode
 * @return markup
 */
function jobsearch_vc_banner_advertisement() {
    global $jobsearch_plugin_options;
    $groups_value = isset($jobsearch_plugin_options['ad_banner_groups']) ? $jobsearch_plugin_options['ad_banner_groups'] : '';
    $sinle_value = isset($jobsearch_plugin_options['ad_banners_list']) ? $jobsearch_plugin_options['ad_banners_list'] : '';

    $group_add_arr = array(esc_html__('Select banner','wp-jobsearch')=>'');
    if (isset($groups_value) && !empty($groups_value) && is_array($groups_value)) {
        for ($ad = 0; $ad < count($groups_value['group_title']); $ad++) {
            $ad_title = $groups_value['group_title'][$ad];
            $ad_code = $groups_value['group_code'][$ad];
            $group_add_arr[$ad_title] = $ad_code;
        }
    }
    $single_add_arr = array(esc_html__('Select banner','wp-jobsearch')=>'');
    if (isset($sinle_value) && !empty($sinle_value) && is_array($sinle_value)) {
        for ($ad = 0; $ad < count($sinle_value['banner_title']); $ad++) {
            $ad_title = $sinle_value['banner_title'][$ad];
            $ad_code = $sinle_value['banner_code'][$ad];
            $single_add_arr[$ad_title] = $ad_code;
        }
    }
    $attributes = array(
        "name" => esc_html__("Banner Advertisement", "wp-jobsearch"),
        "base" => "jobsearch_banner_advertisement",
        "class" => "",
        "category" => esc_html__("Wp JobSearch", "wp-jobsearch"),
        "params" => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__("Banner Style", "wp-jobsearch"),
                'param_name' => 'banner_style',
                'value' => array(
                    esc_html__("Single Banner", "wp-jobsearch") => 'single_banner',
                    esc_html__("Group Banner ", "wp-jobsearch") => 'group_banner',
                ),
                'description' => ''
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__("Single Style", "wp-jobsearch"),
                'param_name' => 'banner_sinle_style',
                'value' => $single_add_arr,
                'description' => '',
                'dependency' => array('element' => 'banner_style', 'value' => array('single_banner'))
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__("Group Style", "wp-jobsearch"),
                'param_name' => 'banner_group_style',
                'value' => $group_add_arr,
                'description' => '',
                'dependency' => array('element' => 'banner_style', 'value' => array('group_banner'))
            ),
        )
    );

    if (function_exists('vc_map')) {
        vc_map($attributes);
    }
}

/**
 * adding user job shortcode
 * @return markup
 */
function jobsearch_vc_user_job_shortcode() {
    $attributes = array(
        "name" => esc_html__("Post New Job", "wp-jobsearch"),
        "base" => "jobsearch_user_job",
        "class" => "",
        "category" => esc_html__("Wp JobSearch", "wp-jobsearch"),
        "params" => array(
            array(
                'type' => 'textfield',
                'heading' => esc_html__("Title", "wp-jobsearch"),
                'param_name' => 'title',
                'value' => '',
                'description' => ''
            ),
        )
    );

    if (function_exists('vc_map')) {
        vc_map($attributes);
    }
}
