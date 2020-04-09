<?php
if (!defined('ABSPATH')) {
    die;
}

if (!class_exists('WooCommerce')) {
    return false;
}

class JobSearch_WC_Subscription {

    public function __construct() {
        //
        add_filter('jobsearch_emp_dash_pkgs_list_tab_mquery', array($this, 'dash_pkgs_meta_query'), 10, 1);
        add_filter('jobsearch_cand_dash_pkgs_list_tab_mquery', array($this, 'dash_pkgs_meta_query'), 10, 1);
        //
        add_filter('jobsearch_dashboard_tab_content_ext', array($this, 'emp_dash_subs_list'), 10, 2);
        //
        add_filter('jobsearch_dashboard_tab_content_ext', array($this, 'cand_dash_subs_list'), 10, 2);
    }

    public function dash_pkgs_meta_query($meta_query) {
        if (class_exists('WC_Subscription')) {
            $meta_query[] = array(
                'relation' => 'OR',
                array(
                    'key' => 'contains_subscription',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => 'contains_subscription',
                    'value' => 'true',
                    'compare' => '!=',
                )
            );
        }
        return $meta_query;
    }

    public static function order_subscription($order_id, $user_id = 0) {
        if ($user_id > 0) {
            $user_id = $user_id;
        } else {
            $user_id = get_current_user_id();
        }
        $args = array(
            'post_type' => 'shop_subscription',
            'post_status' => array('wc-active', 'wc-pending-cancel', 'wc-cancelled', 'wc-expired'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'DESC',
            'orderby' => 'ID',
            'meta_query' => array(
                array(
                    'key' => '_customer_user',
                    'value' => $user_id,
                    'compare' => '=',
                ),
            ),
        );

        $subscription_id = 0;
        
        $subs_query = new WP_Query($args);
        $subs_posts = $subs_query->posts;
        
        if (!empty($subs_posts)) {
            foreach ($subs_posts as $subs) {
                $subss_id = $subs;
                $subscription_obj = new WC_Subscription($subss_id);
                $_related_orders = $subscription_obj->get_related_orders();
                if (isset($_related_orders[$order_id]) && $_related_orders[$order_id] == $order_id) {
                    $subscription_id = $subss_id;
                }
            }
        }
        wp_reset_postdata();
        return $subscription_id;
    }

    public function emp_dash_subs_list($html, $get_tab) {
        global $jobsearch_plugin_options, $Jobsearch_User_Dashboard_Settings;
        if (class_exists('WC_Subscription') && $get_tab == 'user-subscriptions') {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                $user_obj = get_user_by('ID', $user_id);

                $page_id = $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $page_id = $user_dashboard_page = jobsearch__get_post_id($user_dashboard_page, 'page');
                $page_url = jobsearch_wpml_lang_page_permalink($page_id, 'page'); //get_permalink($page_id);

                $employer_id = jobsearch_get_user_employer_id($user_id);

                $reults_per_page = isset($jobsearch_plugin_options['user-dashboard-per-page']) && $jobsearch_plugin_options['user-dashboard-per-page'] > 0 ? $jobsearch_plugin_options['user-dashboard-per-page'] : 10;

                $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;

                ob_start();
                ?>
                <div class="jobsearch-employer-dasboard">
                    <div class="jobsearch-employer-box-section">

                        <div class="jobsearch-profile-title">
                            <h2><?php esc_html_e('Subscription', 'wp-jobsearch') ?></h2>
                        </div>
                        <?php
                        $args = array(
                            'post_type' => 'shop_order',
                            'posts_per_page' => $reults_per_page,
                            'paged' => $page_num,
                            'post_status' => 'wc-completed',
                            'order' => 'DESC',
                            'orderby' => 'ID',
                            'meta_query' => array(
                                array(
                                    'key' => 'jobsearch_order_attach_with',
                                    'value' => 'package',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'contains_subscription',
                                    'value' => 'true',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'package_type',
                                    'value' => array('job', 'featured_jobs', 'cv'),
                                    'compare' => 'IN',
                                ),
                                array(
                                    'key' => 'jobsearch_order_user',
                                    'value' => $user_id,
                                    'compare' => '=',
                                ),
                            ),
                        );

                        $pkgs_query = new WP_Query($args);
                        $total_pkgs = $pkgs_query->found_posts;
                        if ($pkgs_query->have_posts()) {
                            ?>
                            <div class="jobsearch-packages-list-holder jobsearch-recurings">
                                <div class="jobsearch-employer-packages">
                                    <div class="jobsearch-table-layer jobsearch-packages-thead">
                                        <div class="jobsearch-table-row">
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Package', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Total Jobs/CVs', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Used', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Remaining', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Status', 'wp-jobsearch') ?></div>
                                        </div>
                                    </div>
                                    <?php
                                    while ($pkgs_query->have_posts()) : $pkgs_query->the_post();
                                        $pkg_rand = rand(10000000, 99999999);
                                        $pkg_order_id = get_the_ID();
                                        //
                                        $order_obj = wc_get_order($pkg_order_id);
                                        $ordr_product_id = 0;
                                        $order_obj_arr = $order_obj->get_items();
                                        if (!empty($order_obj_arr)) {
                                            foreach ($order_obj->get_items() as $oitem_id => $oitem_product) {
                                                //Get the WC_Product object
                                                $oproduct = $oitem_product->get_product();

                                                if (is_object($oproduct)) {
                                                    $ordr_product_id = $oproduct->get_ID();
                                                }
                                            }
                                        }
                                        //

                                        $pkg_order_name = get_post_meta($pkg_order_id, 'package_name', true);

                                        //
                                        $pkg_type = get_post_meta($pkg_order_id, 'package_type', true);

                                        if ($pkg_type == 'cv') {
                                            $total_cvs = get_post_meta($pkg_order_id, 'num_of_cvs', true);

                                            $used_cvs = jobsearch_pckg_order_used_cvs($pkg_order_id);
                                            $remaining_cvs = jobsearch_pckg_order_remaining_cvs($pkg_order_id);
                                        } else if ($pkg_type == 'featured_jobs') {
                                            $total_jobs = get_post_meta($pkg_order_id, 'num_of_fjobs', true);

                                            $job_exp_dur = get_post_meta($pkg_order_id, 'fjob_expiry_time', true);
                                            $job_exp_dur_unit = get_post_meta($pkg_order_id, 'fjob_expiry_time_unit', true);

                                            $used_jobs = jobsearch_pckg_order_used_fjobs($pkg_order_id);
                                            $remaining_jobs = jobsearch_pckg_order_remaining_fjobs($pkg_order_id);
                                        } else {
                                            $total_jobs = get_post_meta($pkg_order_id, 'num_of_jobs', true);

                                            $job_exp_dur = get_post_meta($pkg_order_id, 'job_expiry_time', true);
                                            $job_exp_dur_unit = get_post_meta($pkg_order_id, 'job_expiry_time_unit', true);

                                            $used_jobs = jobsearch_pckg_order_used_jobs($pkg_order_id);
                                            $remaining_jobs = jobsearch_pckg_order_remaining_jobs($pkg_order_id);
                                        }
                                        $pkg_exp_dur = get_post_meta($pkg_order_id, 'package_expiry_time', true);
                                        $pkg_exp_dur_unit = get_post_meta($pkg_order_id, 'package_expiry_time_unit', true);

                                        $status_txt = esc_html__('Active', 'wp-jobsearch');
                                        $status_class = '';
                                        if ($pkg_type == 'cv') {
                                            if (jobsearch_cv_pckg_order_is_expired($pkg_order_id)) {
                                                $status_txt = esc_html__('Expired', 'wp-jobsearch');
                                                $status_class = 'jobsearch-packages-pending';
                                            }
                                        } else if ($pkg_type == 'featured_jobs') {
                                            if (jobsearch_fjobs_pckg_order_is_expired($pkg_order_id)) {
                                                $status_txt = esc_html__('Expired', 'wp-jobsearch');
                                                $status_class = 'jobsearch-packages-pending';
                                            }
                                        } else {
                                            if (jobsearch_pckg_order_is_expired($pkg_order_id)) {
                                                $status_txt = esc_html__('Expired', 'wp-jobsearch');
                                                $status_class = 'jobsearch-packages-pending';
                                            }
                                        }
                                        $order_subscription_id = $this->order_subscription($pkg_order_id);
                                        ?>
                                        <div class="jobsearch-table-layer jobsearch-packages-tbody">
                                            <div class="jobsearch-table-row">
                                                <div class="jobsearch-table-cell">
                                                    <?php
                                                    ob_start();
                                                    ?>
                                                    <span><?php echo ($pkg_order_name) ?></span>
                                                    <?php
                                                    $pkg_name_html = ob_get_clean();
                                                    echo apply_filters('jobsearch_emp_dashboard_pkgs_list_pkg_title', $pkg_name_html, $pkg_order_id);
                                                    ?>
                                                </div>
                                                <?php
                                                if ($pkg_type == 'cv') {
                                                    ?>
                                                    <div class="jobsearch-table-cell"><?php echo ($total_cvs) ?></div>
                                                    <div class="jobsearch-table-cell"><?php echo ($used_cvs) ?></div>
                                                    <div class="jobsearch-table-cell"><?php echo ($remaining_cvs) ?></div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="jobsearch-table-cell"><?php echo ($total_jobs) ?></div>
                                                    <div class="jobsearch-table-cell"><?php echo ($used_jobs) ?></div>
                                                    <div class="jobsearch-table-cell"><?php echo ($remaining_jobs) ?></div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="jobsearch-table-cell"><i class="fa fa-circle <?php echo ($status_class) ?>"></i> 
                                                    <?php
                                                    echo ('<span class="jobsearch-subs-status">' . $status_txt . '</span>');
                                                    if ($order_subscription_id > 0) {
                                                        echo '<a href="javascript:void(0);" class="jobsearch-subs-detail" data-rid="'. $pkg_rand . '">' . esc_html__('Detail', 'wp-jobsearch') . '</a>';
                                                    }
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($order_subscription_id > 0) {
                                            $subscription_obj = new WC_Subscription($order_subscription_id);
                                            ?>
                                            <div id="pkgs-table-subsc-<?php echo ($pkg_rand) ?>" class="pkgs-table-subscription-detail" style="display: none;">
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php esc_html_e('Order ID', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val">#<?php echo ($pkg_order_id); ?></span>
                                                </div>
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php esc_html_e('Subscription Status', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo esc_html(wcs_get_subscription_status_name($subscription_obj->get_status())); ?></span>
                                                </div>
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php echo esc_html_x('Start Date', 'table heading', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo esc_html($subscription_obj->get_date_to_display('date_created')); ?></span>
                                                </div>
                                                <?php
                                                foreach (array(
                                                'last_order_date_created' => _x('Last Order Date', 'admin subscription table header', 'wp-jobsearch'),
                                                'next_payment' => _x('Next Payment Date', 'admin subscription table header', 'wp-jobsearch'),
                                                'end' => _x('End Date', 'table heading', 'wp-jobsearch'),
                                                'trial_end' => _x('Trial End Date', 'admin subscription table header', 'wp-jobsearch'),
                                                ) as $date_type => $date_title) :
                                                    ?>
                                                    <?php $date = $subscription_obj->get_date($date_type); ?>
                                                    <?php if (!empty($date)) : ?>
                                                        <div class="subs-det-item">
                                                            <span class="subs-det-title"><?php echo esc_html($date_title); ?></span>
                                                            <span class="subs-det-val"><?php echo esc_html($subscription_obj->get_date_to_display($date_type)); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php echo esc_html_x('Expiry', 'table heading', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo absint($pkg_exp_dur) . ' ' . jobsearch_get_duration_unit_str($pkg_exp_dur_unit) ?></span>
                                                </div>
                                                <?php do_action('woocommerce_subscription_before_actions', $subscription_obj); ?>
                                                <?php $actions = wcs_get_all_user_actions_for_subscription($subscription_obj, $user_id); ?>
                                                <?php if (!empty($actions)) : ?>
                                                    <div class="subs-det-item">
                                                        <span class="subs-det-title"><?php esc_html_e('Actions', 'wp-jobsearch'); ?></span>
                                                        <span class="subs-det-val">
                                                            <?php
                                                            foreach ($actions as $key => $action) :
                                                                $s_action_url = $action['url'];
                                                                ?>
                                                                <a href="<?php echo esc_url($action['url']); ?>" class="button <?php echo sanitize_html_class($key) ?>"><?php echo esc_html($action['name']); ?></a>
                                                                <?php
                                                            endforeach;
                                                            ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php do_action('woocommerce_subscription_after_actions', $subscription_obj); ?>
                                                <?php
                                                if ($notes = $subscription_obj->get_customer_order_notes()) :
                                                    ?>
                                                    <div class="jobsearch-subslist-notes">
                                                        <h2><?php esc_html_e('Subscription Notes', 'wp-jobsearch'); ?></h2>
                                                        <ol class="jobsearch-commentlist">
                                                            <?php foreach ($notes as $note) : ?>
                                                                <li class="jobsearch-comment">
                                                                    <div class="comment-container">
                                                                        <div class="comment-text">
                                                                            <span class="subs-date"><?php echo esc_html(date_i18n(_x('l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'wp-jobsearch'), wcs_date_to_time($note->comment_date))); ?></span>
                                                                            <div class="subs-description">
                                                                                <?php echo wp_kses_post(wpautop(wptexturize($note->comment_content))); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ol>
                                                    </div>
                                                    <?php
                                                endif;
                                                ?>
                                            </div>
                                            <?php
                                        }
                                        //
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                            <?php
                            $total_pages = 1;
                            if ($total_pkgs > 0 && $reults_per_page > 0 && $total_pkgs > $reults_per_page) {
                                $total_pages = ceil($total_pkgs / $reults_per_page);
                                ?>
                                <div class="jobsearch-pagination-blog">
                                    <?php $Jobsearch_User_Dashboard_Settings->pagination($total_pages, $page_num, $page_url) ?>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <p><?php esc_html_e('No record found.', 'wp-jobsearch') ?></p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                $html .= ob_get_clean();
            }
        }
        echo $html;
    }

    public function cand_dash_subs_list($html, $get_tab) {
        global $jobsearch_plugin_options, $Jobsearch_User_Dashboard_Settings;
        if (class_exists('WC_Subscription') && $get_tab == 'user-subscriptions') {
            $user_id = get_current_user_id();
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                $user_obj = get_user_by('ID', $user_id);

                $page_id = $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $page_id = $user_dashboard_page = jobsearch__get_post_id($user_dashboard_page, 'page');
                $page_url = jobsearch_wpml_lang_page_permalink($page_id, 'page'); //get_permalink($page_id);

                $candidate_id = jobsearch_get_user_candidate_id($user_id);

                $reults_per_page = isset($jobsearch_plugin_options['user-dashboard-per-page']) && $jobsearch_plugin_options['user-dashboard-per-page'] > 0 ? $jobsearch_plugin_options['user-dashboard-per-page'] : 10;

                $page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;

                ob_start();
                ?>
                <div class="jobsearch-employer-dasboard">
                    <div class="jobsearch-employer-box-section">

                        <div class="jobsearch-profile-title">
                            <h2><?php esc_html_e('Subscriptions', 'wp-jobsearch') ?></h2>
                        </div>
                        <?php
                        $args = array(
                            'post_type' => 'shop_order',
                            'posts_per_page' => $reults_per_page,
                            'paged' => $page_num,
                            'post_status' => 'wc-completed',
                            'order' => 'DESC',
                            'orderby' => 'ID',
                            'meta_query' => array(
                                array(
                                    'key' => 'jobsearch_order_attach_with',
                                    'value' => 'package',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'contains_subscription',
                                    'value' => 'true',
                                    'compare' => '=',
                                ),
                                array(
                                    'key' => 'package_type',
                                    'value' => array('candidate'),
                                    'compare' => 'IN',
                                ),
                                array(
                                    'key' => 'jobsearch_order_user',
                                    'value' => $user_id,
                                    'compare' => '=',
                                ),
                            ),
                        );
                        $pkgs_query = new WP_Query($args);
                        $total_pkgs = $pkgs_query->found_posts;
                        if ($pkgs_query->have_posts()) {
                            ?>
                            <div class="jobsearch-packages-list-holder">
                                <div class="jobsearch-employer-packages jobsearch-recurings">
                                    <div class="jobsearch-table-layer jobsearch-packages-thead">
                                        <div class="jobsearch-table-row">
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Package', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Total Applications', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Used', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Remaining', 'wp-jobsearch') ?></div>
                                            <div class="jobsearch-table-cell"><?php esc_html_e('Status', 'wp-jobsearch') ?></div>
                                        </div>
                                    </div>
                                    <?php
                                    while ($pkgs_query->have_posts()) : $pkgs_query->the_post();
                                        $pkg_rand = rand(10000000, 99999999);
                                        $pkg_order_id = get_the_ID();
                                        $pkg_order_name = get_post_meta($pkg_order_id, 'package_name', true);
                                        //
                                        $order_obj = wc_get_order($pkg_order_id);
                                        $ordr_product_id = 0;
                                        $order_obj_arr = $order_obj->get_items();
                                        if (!empty($order_obj_arr)) {
                                            foreach ($order_obj->get_items() as $oitem_id => $oitem_product) {
                                                //Get the WC_Product object
                                                $oproduct = $oitem_product->get_product();

                                                if (is_object($oproduct)) {
                                                    $ordr_product_id = $oproduct->get_ID();
                                                }
                                            }
                                        }
                                        //

                                        //
                                        $pkg_type = get_post_meta($pkg_order_id, 'package_type', true);

                                        $total_apps = get_post_meta($pkg_order_id, 'num_of_apps', true);

                                        $used_apps = jobsearch_pckg_order_used_apps($pkg_order_id);
                                        $remaining_apps = jobsearch_pckg_order_remaining_apps($pkg_order_id);

                                        $pkg_exp_dur = get_post_meta($pkg_order_id, 'package_expiry_time', true);
                                        $pkg_exp_dur_unit = get_post_meta($pkg_order_id, 'package_expiry_time_unit', true);

                                        $status_txt = esc_html__('Active', 'wp-jobsearch');
                                        $status_class = '';

                                        if (jobsearch_app_pckg_order_is_expired($pkg_order_id)) {
                                            $status_txt = esc_html__('Expired', 'wp-jobsearch');
                                            $status_class = 'jobsearch-packages-pending';
                                        }
                                        
                                        $order_subscription_id = $this->order_subscription($pkg_order_id);
                                        ?>
                                        <div class="jobsearch-table-layer jobsearch-packages-tbody">
                                            <div class="jobsearch-table-row">
                                                <div class="jobsearch-table-cell"><span><?php echo ($pkg_order_name) ?></span></div>

                                                <div class="jobsearch-table-cell"><?php echo ($total_apps) ?></div>
                                                <div class="jobsearch-table-cell"><?php echo ($used_apps) ?></div>
                                                <div class="jobsearch-table-cell"><?php echo ($remaining_apps) ?></div>

                                                <div class="jobsearch-table-cell">
                                                    <i class="fa fa-circle <?php echo ($status_class) ?>"></i> 
                                                    <?php
                                                    echo ('<span class="jobsearch-subs-status">' . $status_txt . '</span>');
                                                    if ($order_subscription_id > 0) {
                                                        echo '<a href="javascript:void(0);" class="jobsearch-subs-detail" data-rid="'. $pkg_rand . '">' . esc_html__('Detail', 'wp-jobsearch') . '</a>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($order_subscription_id > 0) {
                                            $subscription_obj = new WC_Subscription($order_subscription_id);
                                            ?>
                                            <div id="pkgs-table-subsc-<?php echo ($pkg_rand) ?>" class="pkgs-table-subscription-detail" style="display: none;">
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php esc_html_e('Order ID', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val">#<?php echo ($pkg_order_id); ?></span>
                                                </div>
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php esc_html_e('Subscription Status', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo esc_html(wcs_get_subscription_status_name($subscription_obj->get_status())); ?></span>
                                                </div>
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php echo esc_html_x('Start Date', 'table heading', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo esc_html($subscription_obj->get_date_to_display('date_created')); ?></span>
                                                </div>
                                                <?php
                                                foreach (array(
                                                'last_order_date_created' => _x('Last Order Date', 'admin subscription table header', 'wp-jobsearch'),
                                                'next_payment' => _x('Next Payment Date', 'admin subscription table header', 'wp-jobsearch'),
                                                'end' => _x('End Date', 'table heading', 'wp-jobsearch'),
                                                'trial_end' => _x('Trial End Date', 'admin subscription table header', 'wp-jobsearch'),
                                                ) as $date_type => $date_title) :
                                                    ?>
                                                    <?php $date = $subscription_obj->get_date($date_type); ?>
                                                    <?php if (!empty($date)) : ?>
                                                        <div class="subs-det-item">
                                                            <span class="subs-det-title"><?php echo esc_html($date_title); ?></span>
                                                            <span class="subs-det-val"><?php echo esc_html($subscription_obj->get_date_to_display($date_type)); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                
                                                <div class="subs-det-item">
                                                    <span class="subs-det-title"><?php echo esc_html_x('Expiry', 'table heading', 'wp-jobsearch'); ?></span>
                                                    <span class="subs-det-val"><?php echo absint($pkg_exp_dur) . ' ' . jobsearch_get_duration_unit_str($pkg_exp_dur_unit) ?></span>
                                                </div>
                                                <?php do_action('woocommerce_subscription_before_actions', $subscription_obj); ?>
                                                <?php $actions = wcs_get_all_user_actions_for_subscription($subscription_obj, $user_id); ?>
                                                <?php if (!empty($actions)) : ?>
                                                    <div class="subs-det-item">
                                                        <span class="subs-det-title"><?php esc_html_e('Actions', 'wp-jobsearch'); ?></span>
                                                        <span class="subs-det-val">
                                                            <?php
                                                            foreach ($actions as $key => $action) :
                                                                $s_action_url = $action['url'];
                                                                ?>
                                                                <a href="<?php echo esc_url($action['url']); ?>" class="button <?php echo sanitize_html_class($key) ?>"><?php echo esc_html($action['name']); ?></a>
                                                                <?php
                                                            endforeach;
                                                            ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php do_action('woocommerce_subscription_after_actions', $subscription_obj); ?>
                                            </div>

                                            <?php
                                            if ($notes = $subscription_obj->get_customer_order_notes()) :
                                                ?>
                                                <div class="jobsearch-subslist-notes">
                                                    <h2><?php esc_html_e('Subscription Notes', 'wp-jobsearch'); ?></h2>
                                                    <ol class="jobsearch-commentlist">
                                                        <?php foreach ($notes as $note) : ?>
                                                            <li class="jobsearch-comment">
                                                                <div class="comment-container">
                                                                    <div class="comment-text">
                                                                        <span class="subs-date"><?php echo esc_html(date_i18n(_x('l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'wp-jobsearch'), wcs_date_to_time($note->comment_date))); ?></span>
                                                                        <div class="subs-description">
                                                                            <?php echo wp_kses_post(wpautop(wptexturize($note->comment_content))); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ol>
                                                </div>
                                                <?php
                                            endif;
                                        }
                                        ?>
                                        <?php
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                            <?php
                            $total_pages = 1;
                            if ($total_pkgs > 0 && $reults_per_page > 0 && $total_pkgs > $reults_per_page) {
                                $total_pages = ceil($total_pkgs / $reults_per_page);
                                ?>
                                <div class="jobsearch-pagination-blog">
                                    <?php $Jobsearch_User_Dashboard_Settings->pagination($total_pages, $page_num, $page_url) ?>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <p><?php esc_html_e('No record found.', 'wp-jobsearch') ?></p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                $html .= ob_get_clean();
            }
        }
        return $html;
    }

}

return new JobSearch_WC_Subscription();
