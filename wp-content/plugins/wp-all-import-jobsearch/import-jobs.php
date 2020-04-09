<?php

$jobsearch_jobs = new RapidAddon('Wp Jobsearch', 'jobsearch_jobs');

$jobsearch__options = get_option('jobsearch_plugin_options');

$jobsearch_jobs->add_field('job_act_id', 'Job ID', 'text');
$jobsearch_jobs->add_field('job_employer_email', 'Employer Email', 'text');
$jobsearch_jobs->add_field('job_publish_date', 'Publish Date', 'text');
$jobsearch_jobs->add_field('job_expiry_date', 'Expiry Date', 'text');
$jobsearch_jobs->add_field('application_deadline_date', 'Application Deadline Date', 'text');
$jobsearch_jobs->add_field('job_apply_type', 'Apply Type', 'radio', array('internal' => 'Internal', 'external' => 'External URL', 'with_email' => 'By Email'));
$jobsearch_jobs->add_field('ext_url_apply_job', 'External URL for Apply Job', 'text');
$jobsearch_jobs->add_field('job_apply_email', 'Job Apply Email', 'text');
$jobsearch_jobs->add_field('job_min_salary', 'Minimum Salary', 'text');
$jobsearch_jobs->add_field('job_max_salary', 'Maximum Salary', 'text');

$job_salary_types = isset($jobsearch__options['job-salary-types']) ? $jobsearch__options['job-salary-types'] : '';
if (!empty($job_salary_types)) {
    $salary_types = array();
    $slar_type_count = 1;
    foreach ($job_salary_types as $job_salary_type) {
        $salary_types['type_' . $slar_type_count] = $job_salary_type;
        $slar_type_count++;
    }
    $jobsearch_jobs->add_field('job_salary_type', 'Salary Type', 'radio', $salary_types);
} else {
    $jobsearch_jobs->add_field('job_salary_type', 'Salary Type', 'text');
}
$jobsearch_jobs->add_field('featured_job', 'Featured Job', 'radio', array('off' => 'No', 'on' => 'Yes'));
$jobsearch_jobs->add_field('urgent_job', 'Urgent Job', 'radio', array('off' => 'No', 'on' => 'Yes'));
$jobsearch_jobs->add_field('filled_job', 'Filled Job', 'radio', array('off' => 'No', 'on' => 'Yes'));
$jobsearch_jobs->add_field('job_posted_by', 'Job Posted by (Employer ID)', 'text');
$jobsearch_jobs->add_field('job_status', 'Job Status', 'radio', array('approved' => 'Approved', 'admin-review' => 'Admin Review', 'pending' => 'Pending', 'canceled' => 'Canceled'));

$job_custom_fields_saved_data = get_option('jobsearch_custom_field_job');
if (is_array($job_custom_fields_saved_data) && sizeof($job_custom_fields_saved_data) > 0) {
    $field_names_counter = 0;
    foreach ($job_custom_fields_saved_data as $f_key => $custom_field_saved_data) {
        $cusfield_type = isset($custom_field_saved_data['type']) ? $custom_field_saved_data['type'] : '';
        $cusfield_label = isset($custom_field_saved_data['label']) ? $custom_field_saved_data['label'] : '';
        $cusfield_name = isset($custom_field_saved_data['name']) ? $custom_field_saved_data['name'] : '';
        if ($cusfield_label != '' && $cusfield_name != '') {
            if ($cusfield_type == 'dropdown') {
                $field_post_multi = isset($custom_field_saved_data['post-multi']) ? $custom_field_saved_data['post-multi'] : '';
                $dropdown_field_options = isset($custom_field_saved_data['options']) ? $custom_field_saved_data['options'] : '';
                $dropdown_opts = wp_jobsearch_drpdwn_options_arr($dropdown_field_options);
                if (!empty($dropdown_opts)) {
                    $jobsearch_jobs->add_field('cus_field_' . $cusfield_name, $cusfield_label, 'radio', $dropdown_opts);
                } else {
                    $jobsearch_jobs->add_field('cus_field_' . $cusfield_name, $cusfield_label, 'text');
                }
            } else {
                $jobsearch_jobs->add_field('cus_field_' . $cusfield_name, $cusfield_label, 'text');
            }
        }
    }
}
$jobsearch_jobs->add_field('job_loc_contry', 'Country', 'text');
$jobsearch_jobs->add_field('job_loc_state', 'State', 'text');
$jobsearch_jobs->add_field('job_loc_city', 'City', 'text');
$jobsearch_jobs->add_field('job_loc_address', 'Full Address', 'text');
$jobsearch_jobs->add_field('job_loclat', 'Latitude', 'text');
$jobsearch_jobs->add_field('job_loclng', 'Longitude', 'text');

$jobsearch_jobs->set_import_function('wp_jobsearch_jobs_import');
// admin notice if WPAI and/or Wp Jobsearch isn't installed

if (function_exists('is_plugin_active')) {

    // display this notice if neither the free or pro version of the Wp Jobsearch plugin is active.
    if (!is_plugin_active("wp-jobsearch/wp-jobsearch.php")) {

        // Specify a custom admin notice.
        $jobsearch_jobs->admin_notice(
                'The Wp Jobsearch requires WP All Import <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a> and the <a href="#">Wp Jobsearch</a> plugin.'
        );
    }

    // only run this add-on if the free or pro version of the Wp Jobsearch plugin is active.
    if (is_plugin_active("wp-jobsearch/wp-jobsearch.php")) {

        $jobsearch_jobs->run(
                array(
                    "post_types" => array("job")
                )
        );
    }
}

function wp_jobsearch_jobs_import($post_id, $data, $import_options) {

    global $jobsearch_jobs;

    $jobsearch__options = get_option('jobsearch_plugin_options');

    if ($jobsearch_jobs->can_update_meta('job_publish_date', $import_options)) {
        $publish_date = $data['job_publish_date'];
        if ($publish_date == '') {
            $publish_date = date('d-m-Y H:i:s');
        }
        $publish_date = strtotime($publish_date);
        update_post_meta($post_id, 'jobsearch_field_job_publish_date', $publish_date);
    }
    if ($jobsearch_jobs->can_update_meta('job_expiry_date', $import_options)) {
        $expiry_date = $data['job_expiry_date'];
        if ($expiry_date == '') {
            $job_expiry_days = isset($jobsearch__options['free-job-post-expiry']) ? $jobsearch__options['free-job-post-expiry'] : '';
            // job expiry time
            if ($job_expiry_days > 0) {
                $expiry_date = strtotime("+" . $job_expiry_days . " day", strtotime(current_time('d-m-Y H:i:s')));
            } else {
                $expiry_date = date('d-m-Y H:i:s');
            }
        }
        $expiry_date = strtotime($expiry_date);
        update_post_meta($post_id, 'jobsearch_field_job_expiry_date', $expiry_date);
    }
    if ($jobsearch_jobs->can_update_meta('application_deadline_date', $import_options)) {
        $deadline_date = $data['application_deadline_date'];
        if ($deadline_date != '') {
            $deadline_date = strtotime($deadline_date);
            update_post_meta($post_id, 'jobsearch_field_job_application_deadline_date', $deadline_date);
        } else {
            update_post_meta($post_id, 'jobsearch_field_job_application_deadline_date', '');
        }
    }
    if ($jobsearch_jobs->can_update_meta('job_apply_type', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_apply_type', $data['job_apply_type']);
    }
    if ($jobsearch_jobs->can_update_meta('ext_url_apply_job', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_apply_url', $data['ext_url_apply_job']);
    }
    if ($jobsearch_jobs->can_update_meta('job_apply_email', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_apply_email', $data['job_apply_email']);
    }
    if ($jobsearch_jobs->can_update_meta('job_min_salary', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_salary', $data['job_min_salary']);
    }
    if ($jobsearch_jobs->can_update_meta('job_max_salary', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_max_salary', $data['job_max_salary']);
    }
    if ($jobsearch_jobs->can_update_meta('job_salary_type', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_salary_type', $data['job_salary_type']);
    }
    if ($jobsearch_jobs->can_update_meta('featured_job', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_featured', $data['featured_job']);
    }
    if ($jobsearch_jobs->can_update_meta('urgent_job', $import_options)) {
        $is_urgent_job = $data['urgent_job'];
        if ($is_urgent_job == 'on') {
            update_post_meta($post_id, '_urgent_job_frmadmin', 'yes');
            update_post_meta($post_id, 'jobsearch_field_urgent_job', 'on');
            update_post_meta($post_id, 'cusjob_urgent_fbckend', 'on');
        } else {
            update_post_meta($post_id, '_urgent_job_frmadmin', 'no');
            update_post_meta($post_id, 'jobsearch_field_urgent_job', 'off');
        }
    }
    if ($jobsearch_jobs->can_update_meta('filled_job', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_filled', $data['filled_job']);
    }
    if ($jobsearch_jobs->can_update_meta('job_posted_by', $import_options)) {
        if ($data['job_posted_by'] > 0) {
            update_post_meta($post_id, 'jobsearch_field_job_posted_by', $data['job_posted_by']);
        }
    }
    if ($jobsearch_jobs->can_update_meta('job_employer_email', $import_options)) {
        $job_employer_email = $data['job_employer_email'];
        if ($job_employer_email != '' && filter_var($job_employer_email, FILTER_VALIDATE_EMAIL)) {
            if (email_exists($job_employer_email)) {
                $em_user_obj = get_user_by('email', $job_employer_email);
                $em_user_id = $em_user_obj->ID;
                $user_is_employer = wp_jobsearch_user_is_emp($em_user_id);

                if ($user_is_employer) {
                    $user_employer_id = wp_jobsearch_get_user_emp_id($em_user_id);
                    update_post_meta($post_id, 'jobsearch_field_job_posted_by', $user_employer_id);
                }
            }
        }
    }
    if ($jobsearch_jobs->can_update_meta('job_status', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_job_status', $data['job_status']);
    }
    $job_custom_fields_saved_data = get_option('jobsearch_custom_field_job');
    if (is_array($job_custom_fields_saved_data) && sizeof($job_custom_fields_saved_data) > 0) {
        $field_names_counter = 0;
        foreach ($job_custom_fields_saved_data as $f_key => $custom_field_saved_data) {
            $cusfield_type = isset($custom_field_saved_data['type']) ? $custom_field_saved_data['type'] : '';
            $cusfield_label = isset($custom_field_saved_data['label']) ? $custom_field_saved_data['label'] : '';
            $cusfield_name = isset($custom_field_saved_data['name']) ? $custom_field_saved_data['name'] : '';
            if ($cusfield_label != '' && $cusfield_name != '') {
                if ($jobsearch_jobs->can_update_meta('cus_field_' . $cusfield_name, $import_options)) {
                    update_post_meta($post_id, $cusfield_name, $data['cus_field_' . $cusfield_name]);
                }
            }
        }
    }
    if ($jobsearch_jobs->can_update_meta('job_loc_contry', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_location1', $data['job_loc_contry']);
    }
    if ($jobsearch_jobs->can_update_meta('job_loc_state', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_location2', $data['job_loc_state']);
    }
    if ($jobsearch_jobs->can_update_meta('job_loc_city', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_location3', $data['job_loc_city']);
    }
    if ($jobsearch_jobs->can_update_meta('job_loc_address', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_address', $data['job_loc_address']);
    }
    if ($jobsearch_jobs->can_update_meta('job_loclat', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_lat', $data['job_loclat']);
    }
    if ($jobsearch_jobs->can_update_meta('job_loclng', $import_options)) {
        update_post_meta($post_id, 'jobsearch_field_location_lng', $data['job_loclng']);
    }
}
