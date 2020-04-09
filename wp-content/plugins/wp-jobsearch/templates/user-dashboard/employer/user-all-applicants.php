<?php
if (!defined('ABSPATH')) {
    die;
}

global $jobsearch_plugin_options, $empall_applicants_handle, $empemail_applicants_handle;

$email_applicants = isset($jobsearch_plugin_options['emp_dash_email_applics']) ? $jobsearch_plugin_options['emp_dash_email_applics'] : '';

if (isset($_GET['view']) && $_GET['view'] == 'email-applicants' && $email_applicants == 'on') {
    $empemail_applicants_handle->applicants_list();
} else {
    $empall_applicants_handle->all_applicants_list();
}
