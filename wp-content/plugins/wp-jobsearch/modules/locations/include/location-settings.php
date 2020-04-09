<?php
if (!defined('ABSPATH')) {
    die;
}
global $jobsearch_gdapi_allocation;
if (!class_exists('jobsearch_allocation_settings_handle')) {

    class jobsearch_allocation_settings_handle
    {
        public $auto_load_files;

        // hook things up
        public function __construct()
        {
            $this->auto_load_files = false;

            add_action('init', array($this, 'load_locfiles_init'), 1);

            $this->save_locsettings();

            add_action('admin_menu', array($this, 'jobsearch_loc_settings_create_menu'));
            add_action('admin_footer', array($this, 'load_locations_js'));
            add_action('wp_footer', array($this, 'load_locations_js'));
            add_action('wp_head', array($this, 'global_variables_init'), 1);
            add_action('admin_head', array($this, 'global_variables_init'), 1);
            add_action('admin_enqueue_scripts', array($this, 'load_locations_script'));
            add_action('wp_ajax_jobsearch_locations_download', array($this, 'jobsearch_locations_download_callback'), 1);

            add_action('wp_ajax_jobsearch_update_country', array($this, 'jobsearch_update_country_callback'));

            add_action('wp_ajax_jobsearch_add_new_states', array($this, 'jobsearch_add_new_states_callback'));

            add_action('wp_ajax_jobsearch_add_new_cities', array($this, 'jobsearch_add_new_cities_callback'));

            add_action('wp_ajax_jobsearch_check_state_dir', array($this, 'jobsearch_check_state_dir_callback'));
        }

        public function jobsearch_locations_path($path)
        {
            global $jobsearch_uploding_resume, $jobsearch_uploding_resume;
            $jobsearch_uploding_resume = false;
            $jobsearch_uploding_candimg = false;
            return 'jobsearch-locations/';
        }

        public function load_locfiles_init()
        {
            if (defined('WP_JOBSEARCH_VERSION')) {
                $jobsearch_version = WP_JOBSEARCH_VERSION;
                if ($jobsearch_version <= '1.3.5') {
                    $this->auto_load_files = true;
                }
            }
        }

        public function global_variables_init()
        { ?>
            <script type="text/javascript">
                var jobsearch_sloc_country = '', jobsearch_sloc_state = '', jobsearch_sloc_city = '',
                    jobsearch_sloc_type = '', jobsearch_is_admin = '', jobsearch_is_post_page = '',
                    jobsearch_is_loc_editor = '';
            </script>
        <?php }

        public function load_locations_script()
        {
            wp_register_script('jobsearch-location-editor', jobsearch_plugin_get_url('modules/locations/js/jobsearch-inline-editor.js'), array('jquery'), '', true);

        }

        public static function get_countries()
        {
            global $jobsearch_download_locations;
            $jobsearch_download_locations = true;

            add_filter('jobsearch_locations_upload_dir', 'jobsearch_locations_path', 10, 1);
            $wp_upload_dir = wp_upload_dir();
            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }
            remove_filter('jobsearch_locations_upload_dir', 'jobsearch_locations_path', 10, 1);
            $jobsearch_download_locations = false;

            $contries_list = '';
            $countries = read_location_file('countries.json');

            if (file_exists($upload_file_path . "/countries")) {
                $cont_list = json_decode($countries, true);
                if (isset($cont_list) && !empty($cont_list) && is_array($cont_list)) {
                    $contries_list = $cont_list;
                    update_option('jobsearch_apiloc_countires_list', $contries_list);
                }
            }
            return $contries_list;

        }

        public static function get_states($contry_code)
        {
            $states = read_location_file('countries/' . $contry_code . '/' . $contry_code . '-states.json');
            $cont_list = json_decode($states, true);
            if (isset($cont_list) && !empty($cont_list) && is_array($cont_list)) {
                $statets_list = $cont_list;
            }
            return $statets_list;
        }

        public static function get_cities($contry_code, $state_name)
        {
            $cities = read_cities_file('countries/' . $contry_code . '/' . $state_name . '/' . $contry_code . '-' . $state_name . '-cities.json');
            $cont_list = json_decode($cities, true);
            if (isset($cont_list['result']) && !empty($cont_list['result']) && is_array($cont_list['result'])) {
                $cities_list = $cont_list['result'];
            }
            return $cities_list;
        }

        public function jobsearch_loc_settings_create_menu()
        {
            // create new top-level menu
            add_menu_page(esc_html__('Location Manager', 'wp-jobsearch'), esc_html__('Location Manager', 'wp-jobsearch'), 'administrator', 'jobsearch-location-sett', function () {
                global $jobsearch_download_locations;
                $jobsearch_download_locations = true;
                add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
                $wp_upload_dir = wp_upload_dir();

                $upload_file_path = $wp_upload_dir['path'];

                if (!file_exists($upload_file_path . "/countries")) {
                    $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
                }

                $upload_file_url = $wp_upload_dir['url'];
                remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
                $jobsearch_download_locations = false;


                if (!file_exists($upload_file_path . "/countries")) {
                    self::download_files();
                    die();
                }

                $rand_id = rand(10000000, 99999999);
                wp_enqueue_script('jobsearch-gdlocation-api');
                wp_enqueue_script('jobsearch-selectize');

                $api_contries_list = self::get_countries();
                $jobsearch_locsetin_options = get_option('jobsearch_locsetin_options');

                $loc_required_fields = isset($jobsearch_locsetin_options['loc_required_fields']) ? $jobsearch_locsetin_options['loc_required_fields'] : '';
                $loc_optionstype = isset($jobsearch_locsetin_options['loc_optionstype']) ? $jobsearch_locsetin_options['loc_optionstype'] : '';
                $contry_singl_contry = isset($jobsearch_locsetin_options['contry_singl_contry']) ? $jobsearch_locsetin_options['contry_singl_contry'] : '';

                $contry_order = isset($jobsearch_locsetin_options['contry_order']) ? $jobsearch_locsetin_options['contry_order'] : '';
                $contry_order = $contry_order != '' ? $contry_order : 'alpha';
                $contry_filtring = isset($jobsearch_locsetin_options['contry_filtring']) ? $jobsearch_locsetin_options['contry_filtring'] : '';
                $contry_filtring = $contry_filtring != '' ? $contry_filtring : 'none';
                $contry_filtr_limreslts = isset($jobsearch_locsetin_options['contry_filtr_limreslts']) ? $jobsearch_locsetin_options['contry_filtr_limreslts'] : '';
                $contry_filtr_limreslts = $contry_filtr_limreslts <= 0 ? 1000000 : $contry_filtr_limreslts;
                $contry_filtrinc_contries = isset($jobsearch_locsetin_options['contry_filtrinc_contries']) ? $jobsearch_locsetin_options['contry_filtrinc_contries'] : '';

                $contry_filtrexc_contries = isset($jobsearch_locsetin_options['contry_filtrexc_contries']) ? $jobsearch_locsetin_options['contry_filtrexc_contries'] : '';
                $contry_preselct = isset($jobsearch_locsetin_options['contry_preselct']) ? $jobsearch_locsetin_options['contry_preselct'] : '';
                $contry_preselct = $contry_preselct != '' ? $contry_preselct : 'none';
                $contry_presel_contry = isset($jobsearch_locsetin_options['contry_presel_contry']) ? $jobsearch_locsetin_options['contry_presel_contry'] : '';
                $autoload_files = $this->auto_load_files;
                //var_dump($autoload_files); die;
                ?>

                <div class="jobsearch-allocssett-holder">
                    <script>

                        jQuery(document).ready(function () {

                            jQuery('.selectiz-locfield').selectize({
                                plugins: ['remove_button'],
                            });
                        });
                        jQuery(document).on('click', '.jobsearch-locsve-btn', function () {
                            jQuery('#allocs-settings-form').submit();
                        });
                        jQuery(document).on('click', '.panl-title > a', function () {
                            var _this = jQuery(this);
                            var main_acholder = jQuery('#panl-filter-options');
                            main_acholder.find('.panl-opened').removeClass('panl-opened').addClass('panl-closed');
                            main_acholder.find('.panel-body-opened').removeClass('panel-body-opened').addClass('panel-body-closed');
                            main_acholder.find('.panel-body-closed').hide();
                            //
                            _this.parents('.loc-panl-sec').find('.panl-closed').removeClass('panl-closed').addClass('panl-opened');
                            _this.parents('.loc-panl-sec').find('.panel-body-closed').removeClass('panel-body-closed').addClass('panel-body-opened');
                            _this.parents('.loc-panl-sec').find('.panel-body-opened').slideDown();
                        });
                        jQuery(document).on('change', 'select[name="loc_optionstype"]', function () {
                            if (jQuery(this).val() == '0' || jQuery(this).val() == '1') {
                                jQuery('#panl-filter-options').show(500);
                                jQuery('.setingsave-btncon').show(500);
                                jQuery('.allocs-contdrpdwn-selt').slideUp();

                            } else {
                                jQuery('.setingsave-btncon').hide(500);
                                jQuery('#panl-filter-options').hide(500);
                                jQuery('#contry-presel-none-<?php echo($rand_id) ?>').prop('checked', true);
                                jQuery('#contry-order-alpha-<?php echo($rand_id) ?>').prop('checked', true);
                                jQuery('#contry-filtr-none-<?php echo($rand_id) ?>').prop('checked', true);
                                jQuery('#contry-filtrinc-cont-<?php echo($rand_id) ?>').hide();
                                jQuery('#contry-filtrexc-cont-<?php echo($rand_id) ?>').hide();
                                jQuery('.allocs-contdrpdwn-selt').slideDown();
                            }
                        });
                        jQuery(document).on('change', 'input[name="contry_filtring"]', function () {
                            if (jQuery(this).val() == 'inc_contries') {
                                jQuery('#contry-filtrinc-cont-<?php echo($rand_id) ?>').slideDown();
                                jQuery('#contry-filtrexc-cont-<?php echo($rand_id) ?>').slideUp();
                            } else if (jQuery(this).val() == 'exc_contries') {
                                jQuery('#contry-filtrexc-cont-<?php echo($rand_id) ?>').slideDown();
                                jQuery('#contry-filtrinc-cont-<?php echo($rand_id) ?>').slideUp();
                            } else {
                                jQuery('#contry-filtrexc-cont-<?php echo($rand_id) ?>').slideUp();
                                jQuery('#contry-filtrinc-cont-<?php echo($rand_id) ?>').slideUp();
                            }
                        });
                        jQuery(document).on('change', 'input[name="contry_preselct"]', function () {
                            if (jQuery(this).val() == 'by_contry') {
                                jQuery('#contry-presel-contry-<?php echo($rand_id) ?>').slideDown();
                            } else {
                                jQuery('#contry-presel-contry-<?php echo($rand_id) ?>').slideUp();
                            }
                        });
                        //
                        jQuery(document).on('change', 'input[type="checkbox"][name="continent_group"]', function () {
                            if (jQuery(this).is(":checked")) {
                                jQuery('.contint-group-options').slideDown();
                            } else {
                                jQuery('.contint-group-options').slideUp();
                            }
                        });
                    </script>
                    <div class="allocs-sett-label">
                        <h1><?php esc_html_e('Preview Example', 'wp-jobsearch') ?></h1>
                    </div>
                    <div class="allocs-sett-view">
                        <div class="preview-loc-exmphdin">
                            <h3><?php esc_html_e('Select Country', 'wp-jobsearch') ?></h3></div>
                        <?php
                        if ($loc_optionstype == '0' || $loc_optionstype == '1') { ?>
                            <select name="country" class="countries " id="countryId"
                                    data-placeholder="<?php echo esc_html_e('Select Country', 'wp-jobsearch') ?>">
                                <option value=""><?php echo esc_html_e('Select Country', 'wp-jobsearch') ?></option>
                            </select>
                        <?php } else { ?>

                            <input type="hidden" name="country" id="countryId"
                                   value="<?php echo($contry_singl_contry) ?>"/>
                        <?php } ?>
                        <select name="state" class="states" id="stateId">
                            <option value=""><?php esc_html_e('Select State', 'wp-jobsearch') ?></option>
                        </select>
                        <?php
                        if ($loc_optionstype == '1' || $loc_optionstype == '2') {
                            ?>
                            <select name="city" class="cities" id="cityId">
                                <option value="0"><?php esc_html_e('Select City', 'wp-jobsearch') ?></option>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="allocs-sett-filtrs">
                        <div class="allocs-configdrpdwn-sett">
                            <span><?php esc_html_e('Configure your dropdowns', 'wp-jobsearch') ?></span>
                            <a href="javascript:void(0);"
                               class="jobsearch-locsve-btn button button-primary"><?php esc_html_e('Generate Settings', 'wp-jobsearch') ?></a>
                        </div>
                        <form id="allocs-settings-form" method="post">
                            <div class="allocs-configdrpdwn-sett">
                                <span><?php esc_html_e('Required Location Fields', 'wp-jobsearch') ?></span>
                                <select class="drpdwn-type-control" name="loc_required_fields">
                                    <option <?php echo($loc_required_fields == 'no' ? 'selected="selected"' : '') ?>
                                            value="no">
                                        <?php esc_html_e('No', 'wp-jobsearch') ?>
                                    </option>
                                    <option <?php echo($loc_required_fields == 'yes' ? 'selected="selected"' : '') ?>
                                            value="yes">
                                        <?php esc_html_e('Yes', 'wp-jobsearch') ?>
                                    </option>
                                </select>
                                <input type="hidden" name="jobsearch_allocs_setingsubmit" value="1">
                            </div>
                            <div class="allocs-configdrpdwn-sett">
                                <span><?php esc_html_e('Dropdown Sequence', 'wp-jobsearch') ?></span>
                                <select class="drpdwn-type-control" name="loc_optionstype">
                                    <option <?php echo($loc_optionstype == '0' ? 'selected="selected"' : '') ?>
                                            value="0">
                                        <?php esc_html_e('Country - State', 'wp-jobsearch') ?>
                                    </option>
                                    <option <?php echo($loc_optionstype == '1' || $loc_optionstype == '' ? 'selected="selected"' : '') ?>
                                            value="1">
                                        <?php esc_html_e('Country - State - City', 'wp-jobsearch') ?>
                                    </option>
                                    <option <?php echo($loc_optionstype == '2' ? 'selected="selected"' : '') ?>
                                            value="2">
                                        <?php esc_html_e('State - City (Single country)', 'wp-jobsearch') ?>
                                    </option>
                                    <option <?php echo($loc_optionstype == '3' ? 'selected="selected"' : '') ?>
                                            value="3">
                                        <?php esc_html_e('State (Single country)', 'wp-jobsearch') ?>
                                    </option>
                                </select>
                                <input type="hidden" name="jobsearch_allocs_setingsubmit" value="1">
                            </div>

                            <div class="allocs-contdrpdwn-selt"
                                 style="display: <?php echo($loc_optionstype == '0' || $loc_optionstype == '1' ? 'none' : 'block') ?>;">
                                <label for="contry-singl-contry-<?php echo($rand_id) ?>"><?php esc_html_e('Select Country', 'wp-jobsearch') ?></label>
                                <select id="contry-singl-contry-<?php echo($rand_id) ?>" name="contry_singl_contry">
                                    <?php
                                    if (!empty($api_contries_list)) {
                                        foreach ($api_contries_list as $contry_key => $contry_title) { ?>
                                            <option value="<?php echo($contry_title['code']) ?>" <?php echo($contry_singl_contry == $contry_title['code'] ? 'selected="selected"' : '') ?>><?php echo($contry_title['name']) ?></option>
                                            <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                            <?php
                            $display = '';
                            if ($loc_optionstype == 2 || $loc_optionstype == 3) {
                                $display = 'style="display: none" ';
                            } ?>
                            <div id="panl-filter-options" class="jobsearch-filtr-options" <?php echo $display ?>>
                                <div class="loc-panl-sec">
                                    <div class="panl-heading">
                                        <h4 class="panl-title">
                                            <a href="javascript:void(0);" class="panl-opened">
                                                <?php esc_html_e('Country Options', 'wp-jobsearch') ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body-opened">
                                        <div class="panl-body">
                                            <div class="filtr-chks-box ordering">
                                                <span><?php esc_html_e('Ordering', 'wp-jobsearch') ?></span>
                                                <ul>
                                                    <li>
                                                        <input id="contry-order-alpha-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_order"
                                                               value="alpha" <?php echo($contry_order == 'alpha' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-order-alpha-<?php echo($rand_id) ?>"><?php esc_html_e('Alphabetical', 'wp-jobsearch') ?></label>
                                                    </li>
                                                    <li>
                                                        <input id="contry-order-bypop-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_order"
                                                               value="by_population" <?php echo($contry_order == 'by_population' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-order-bypop-<?php echo($rand_id) ?>"><?php esc_html_e('By Population', 'wp-jobsearch') ?></label>
                                                    </li>
                                                    <li>
                                                        <input id="contry-order-randm-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_order"
                                                               value="random" <?php echo($contry_order == 'random' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-order-randm-<?php echo($rand_id) ?>"><?php esc_html_e('Random', 'wp-jobsearch') ?></label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="filtr-chks-box filtering">
                                                <span><?php esc_html_e('Filtering', 'wp-jobsearch') ?></span>
                                                <ul>
                                                    <li>
                                                        <input id="contry-filtr-none-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_filtring"
                                                               value="none" <?php echo($contry_filtring == 'none' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-filtr-none-<?php echo($rand_id) ?>"><?php esc_html_e('None', 'wp-jobsearch') ?></label>
                                                    </li>
                                                    <li class="with-frm-fields">
                                                        <div class="orig-radio-field">
                                                            <input id="contry-filtr-inclist-<?php echo($rand_id) ?>"
                                                                   type="radio" name="contry_filtring"
                                                                   value="inc_contries" <?php echo($contry_filtring == 'inc_contries' ? 'checked="checked"' : '') ?>>
                                                            <label for="contry-filtr-inclist-<?php echo($rand_id) ?>"><?php esc_html_e('Include only countries selected', 'wp-jobsearch') ?></label>
                                                        </div>

                                                        <div id="contry-filtrinc-cont-<?php echo($rand_id) ?>"
                                                             class="filtrs-select-field multiseltc"
                                                             style="display: <?php echo($contry_filtring == 'inc_contries' ? 'block' : 'none') ?>;">

                                                            <select multiple="multiple"
                                                                    name="contry_filtrinc_contries[]">
                                                                <?php

                                                                if (!empty($api_contries_list)) {
                                                                    if ($contry_order == 'by_population') {
                                                                        $api_contries_list = self::sortByPopulation($api_contries_list);
                                                                    } else if ($contry_order == 'random') {
                                                                        $api_contries_list = self::shuffleArray($api_contries_list);
                                                                    } else {
                                                                        $api_contries_list = $api_contries_list;
                                                                    }

                                                                    $api_contries_list = $contry_order == 'by_population' ? self::sortByPopulation($api_contries_list) : $api_contries_list;
                                                                    foreach ($api_contries_list as $contry_key => $contry_info) { ?>
                                                                        <option value="<?php echo($contry_info['name']) ?>" <?php echo(!empty($contry_filtrinc_contries) && is_array($contry_filtrinc_contries) && in_array($contry_info['name'], $contry_filtrinc_contries) ? 'selected="selected"' : '') ?>><?php echo($contry_info['name']) ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </li>
                                                    <li class="with-frm-fields">
                                                        <div class="orig-radio-field">
                                                            <input id="contry-filtr-exclist-<?php echo($rand_id) ?>"
                                                                   type="radio" name="contry_filtring"
                                                                   value="exc_contries" <?php echo($contry_filtring == 'exc_contries' ? 'checked="checked"' : '') ?>>
                                                            <label for="contry-filtr-exclist-<?php echo($rand_id) ?>"><?php esc_html_e('Exclude countries selected', 'wp-jobsearch') ?></label>
                                                        </div>
                                                        <div id="contry-filtrexc-cont-<?php echo($rand_id) ?>"
                                                             class="filtrs-select-field multiseltc"
                                                             style="display: <?php echo($contry_filtring == 'exc_contries' ? 'block' : 'none') ?>;">

                                                            <select multiple="multiple"
                                                                    name="contry_filtrexc_contries[]">
                                                                <?php
                                                                if (!empty($api_contries_list)) {
                                                                    foreach ($api_contries_list as $contry_key => $contry_info) { ?>
                                                                        <option value="<?php echo($contry_info['name']) ?>" <?php echo(!empty($contry_filtrexc_contries) && is_array($contry_filtrexc_contries) && in_array($contry_info['name'], $contry_filtrexc_contries) ? 'selected="selected"' : '') ?>><?php echo($contry_info['name']) ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="filtr-chks-box pre-select">
                                                <span><?php esc_html_e('Preselect Country', 'wp-jobsearch') ?></span>
                                                <ul>
                                                    <li>
                                                        <input id="contry-presel-none-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_preselct"
                                                               value="none" <?php echo($contry_preselct == 'none' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-presel-none-<?php echo($rand_id) ?>"><?php esc_html_e('None', 'wp-jobsearch') ?></label>
                                                    </li>
                                                    <li class="with-frm-fields">
                                                        <div class="orig-radio-field">
                                                            <input id="contry-presel-bycontry-<?php echo($rand_id) ?>"
                                                                   type="radio" name="contry_preselct"
                                                                   value="by_contry" <?php echo($contry_preselct == 'by_contry' ? 'checked="checked"' : '') ?>>
                                                            <label for="contry-presel-bycontry-<?php echo($rand_id) ?>"><?php esc_html_e('Choose country', 'wp-jobsearch') ?></label>
                                                        </div>
                                                        <div id="contry-presel-contry-<?php echo($rand_id) ?>"
                                                             class="filtrs-select-field"
                                                             style="display: <?php echo($contry_preselct == 'by_contry' ? 'block' : 'none') ?>;">
                                                            <?php if (!empty($contry_filtrinc_contries) && $contry_filtring == 'inc_contries') {
                                                                $api_contries_list = $contry_filtrinc_contries;
                                                            } else if (!empty($contry_filtrexc_contries) && $contry_filtring == 'exc_contries') {
                                                                $api_contries_list = self::getExcludeCountriesList($contry_filtrexc_contries);
                                                            }
                                                            ?>
                                                            <select name="contry_presel_contry">
                                                                <?php
                                                                if (!empty($api_contries_list)) {
                                                                    foreach ($api_contries_list as $key => $contry_info) {
                                                                        if (!empty($contry_filtrinc_contries) && $contry_filtring == 'inc_contries') { ?>
                                                                            <option value="<?php echo(self::getCountryCode($contry_info)) ?>" <?php echo($contry_presel_contry == self::getCountryCode($contry_info) ? 'selected="selected"' : '') ?>><?php echo($contry_info) ?></option>
                                                                        <?php } else if (!empty($contry_filtrexc_contries) && $contry_filtring == 'exc_contries') { ?>
                                                                            <option value="<?php echo($contry_info->code) ?>" <?php echo($contry_presel_contry == $contry_info->code ? 'selected="selected"' : '') ?>><?php echo($contry_info->name) ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo($contry_info['code']) ?>" <?php echo($contry_presel_contry == $contry_info['code'] ? 'selected="selected"' : '') ?>><?php echo($contry_info['name']) ?></option>
                                                                        <?php }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <input id="contry-presel-byip-<?php echo($rand_id) ?>"
                                                               type="radio" name="contry_preselct"
                                                               value="by_user_ip" <?php echo($contry_preselct == 'by_user_ip' ? 'checked="checked"' : '') ?>>
                                                        <label for="contry-presel-byip-<?php echo($rand_id) ?>"><?php esc_html_e('Predict by user IP', 'wp-jobsearch') ?></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="setingsave-btncon" <?php echo $display ?>>
                            <a href="javascript:void(0);"
                               class="jobsearch-locsve-btn button button-primary"><?php esc_html_e('Generate Settings', 'wp-jobsearch') ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }, '', 30);
            add_submenu_page(
                'jobsearch-location-sett',
                esc_html__('Location Settings Editor', 'wp-jobsearch'), //page title
                esc_html__('Location Settings Editor', 'wp-jobsearch'), //menu title
                'administrator',
                'jobsearch-location-sett-editor',
                array($this, 'locations_settings_editor')
            );
        }

        public static function getExcludeCountriesList($contry_filtrexc_contries)
        {
            $total_countries = read_location_file('countries.json');
            $countries_list = [];
            foreach (json_decode($total_countries) as $key => $val) {
                if (in_array($val->name, $contry_filtrexc_contries)) {
                    continue;
                }
                $countries_list[] = $val;
            }
            return $countries_list;
        }

        public static function getCountryCode($name)
        {
            $countries = read_location_file('countries.json');
            $country_code = '';
            foreach (json_decode($countries) as $key => $val) {
                if ($val->name == $name) {
                    $country_code = $val->code;
                }
            }
            return $country_code;
        }

        public static function shuffleArray($list)
        {
            if (!is_array($list)) return $list;
            $keys = array_keys($list);
            shuffle($keys);
            $random = array();
            foreach ($keys as $key)
                $random[$key] = $list[$key];
            return $random;
        }

        public static function sortByPopulation($api_contries_list)
        {
            $arr = $api_contries_list;
            $sort = array();
            foreach ($arr as $k => $v) {
                $population = str_replace(',', '', $v['population']);
                $sort['population'][$k] = $population;
            }
            array_multisort($sort['population'], SORT_DESC, $arr);
            return $arr;
        }

        public static function download_files()
        { ?>
            <script>
                jQuery(document).ready(function () {
                    var request = jQuery.ajax({
                        url: ajaxurl,
                        method: "POST",
                        data: {
                            action: 'jobsearch_locations_download',
                        },
                        dataType: "json"
                    });
                    request.done(function (response) {
                        if ('undefined' !== typeof response.status && response.status == 'data_downloaded') {
                            setTimeout(function () {
                                location.reload();
                            }, 1000)
                        } else {
                            jQuery(document).find(".dnload-btn-wrapper").find("h3").text(" ");
                            jQuery(document).find(".dnload-btn-wrapper").find("h3").text("The destination folder for streaming files does not exist or cannot be written to.");
                        }
                    });
                    request.fail(function (jqXHR, textStatus) {
                        alert(textStatus)
                    });
                });
            </script>
            <div class="allocs-sett-filtrs">
                <div class="dnload-btn-wrapper">
                    <h3><?php echo esc_html('Please wait while your files are being downloaded.', 'wp-jobsearch') ?></h3>
                    <span class="location-loader"><i class="fa fa-refresh fa-spin"></i></span>
                </div>
            </div>
            <?php
        }

        function jobsearch_locations_download_callback()
        {
            global $wp_filesystem, $jobsearch_download_locations;
            $jobsearch_download_locations = true;
            $url = wp_nonce_url("options-general.php?page=demo", "filesystem-nonce");
            $form_fields = array("file-data");
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();
            $upload_file_path = $wp_upload_dir['path'];

            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }

            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;

            if (!file_exists($upload_file_path . "/countries")) {
                if (connect_fs($url, "", $upload_file_path, $form_fields)) {

                    $dir = $wp_filesystem->find_folder($upload_file_path);
                    $file = trailingslashit($dir) . "locations.zip";
                    $wp_filesystem->put_contents($file, '', FS_CHMOD_FILE);
                } else {
                    return new WP_Error("filesystem_error", "Cannot initialize filesystem");
                }

                $zipFile = $upload_file_path . "/locations.zip"; // Local Zip File Path

                $response = wp_remote_get(
                    'https://careerfy.net/download-plugins/locations.zip',
                    array(
                        'timeout' => 300,
                        'stream' => true,
                        'filename' => $upload_file_path . '/locations.zip',
                    )
                );

                if (empty($response->error->http_request_failed)) {
                    unzip_file($zipFile, $upload_file_path);
                    unlink($zipFile);
                    echo json_encode(array('status' => 'data_downloaded'));
                    wp_die();
                } else {
                    echo json_encode(array('status' => 'data_not_downloaded'));
                    wp_die();
                }
            }
        }


        public function locations_settings_editor()
        {
            global $jobsearch_download_locations;
            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }

            $upload_file_url = $wp_upload_dir['url'];
            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;

            if (!file_exists($upload_file_path . "/countries")) {
                self::download_files();
                die();
            }

            wp_enqueue_script('jobsearch-location-editor');

            $jobsearch_location_common_text = array(
                'sav_contry' => esc_html__('Save Countries', 'wp-jobsearch'),
                'save_states' => esc_html__('Save States', 'wp-jobsearch'),
                'sav_city' => esc_html__('Save City', 'wp-jobsearch'),
                'pls_wait' => esc_html__('Please Wait...', 'wp-jobsearch'),
                'req_state' => esc_html__('Please Enter Any State Name', 'wp-jobsearch'),
                'req_city' => esc_html__('Please Enter Any City Name', 'wp-jobsearch'),
                'req_cntry' => esc_html__('Please Enter Country Code', 'wp-jobsearch'),
                'req_cntry_code_uppercase' => esc_html__('Country Code should be in uppercase.', 'wp-jobsearch'),
                'req_num' => esc_html__('Country Code cannot be numeric.', 'wp-jobsearch'),
                'req_chars' => esc_html__('Country code cannot be more than 3 alphabets.', 'wp-jobsearch'),
                'req_poplation' => esc_html__('Population can only be in numbers.', 'wp-jobsearch'),
                'cntry_success' => esc_html__('Country Saved Successfully.', 'wp-jobsearch'),
                'state_success' => esc_html__('State Saved successfully.', 'wp-jobsearch'),
                'city_success' => esc_html__('City Saved successfully.', 'wp-jobsearch'),
            );

            wp_localize_script('jobsearch-location-editor', 'jobsearch_location_common_text', $jobsearch_location_common_text);
            $query_var = '';
            if (isset($_GET['page'])) {
                $query_var = $_GET['page'];
            }
            ?>

            <div class="jobsearch-allocssett-holder">
                <div class="allocs-sett-label"><h1><?php esc_html_e('Location Editor', 'wp-jobsearch') ?></h1>
                </div>
                <div class="allocs-sett-view">
                    <div class="preview-loc-exmphdin">
                        <h3><?php esc_html_e('Select Country', 'wp-jobsearch') ?></h3></div>
                    <div class="locations-wrapper">
                        <select name="country" class="countries" id="countryId"
                                placeholder="<?php echo esc_html_e('Select Country', 'wp-jobsearch') ?>">
                            <option value=""><?php esc_html_e('Select Country', 'wp-jobsearch') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="card-wrapper country-table">
                            <div class="card-body">
                                <table class="table country-table-detail" id="makeEditableCountries" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><?php echo esc_html_e('Country', 'wp-jobsearch'); ?></th>
                                        <th><?php echo esc_html_e('Country Code', 'wp-jobsearch'); ?></th>
                                        <th><?php echo esc_html_e('Population', 'wp-jobsearch'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="loc-error">
                                        <td><?php echo esc_html_e('Enter Country Name', 'wp-jobsearch'); ?></td>
                                        <td><?php echo esc_html_e('Enter Code', 'wp-jobsearch'); ?></td>
                                        <td><?php echo esc_html_e('Enter Population', 'wp-jobsearch'); ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="jobsearch-table-cta-btn-wrapper">
                                    <button id="submit_country_detail"
                                            class="cta-submit loc-disabled"
                                            disabled="disabled"><?php echo esc_html_e('Save Countries', 'wp-jobsearch'); ?></button>
                                    <button id="add_country"><?php echo esc_html_e('Add New Country', 'wp-jobsearch'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="allocs-sett-view state-wrapper loc-hidden">
                    <div class="preview-loc-exmphdin jobsearch-load-state-name">
                        <h3><?php esc_html_e('Country States', 'wp-jobsearch') ?></h3></div>
                    <div class="col-md-12">
                        <div class="card-wrapper state-table">
                            <div class="card-body">
                                <table class="table state-table-detail" id="makeEditableStates" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><?php echo esc_html_e('States', 'wp-jobsearch'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div class="jobsearch-table-cta-btn-wrapper">
                                    <button id="submit_states_detail"
                                            class="cta-submit loc-disabled"
                                            disabled="disabled"><?php echo esc_html_e('Save States', 'wp-jobsearch'); ?></button>
                                    <button id="add_state"><?php echo esc_html_e('Add New State', 'wp-jobsearch'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="allocs-sett-view cities-wrapper loc-hidden">
                    <div class="preview-loc-exmphdin jobsearch-load-states-cities-name">
                        <h3></h3>
                    </div>
                    <div class="locations-wrapper">
                        <select name="state" class="states" id="stateId">
                            <option value=""><?php esc_html_e('Select State', 'wp-jobsearch') ?></option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="card-wrapper cities-table">
                            <div class="card-body">
                                <table class="table cities-table-detail" id="makeEditableCities" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th><?php echo esc_html_e('Cities', 'wp-jobsearch'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <div class="jobsearch-table-cta-btn-wrapper">
                                    <button id="submit_cities_detail"
                                            class="cta-submit loc-disabled"
                                            disabled="disabled"><?php echo esc_html_e('Save Cities', 'wp-jobsearch'); ?></button>
                                    <button id="add_cities"><?php echo esc_html_e('Add New City', 'wp-jobsearch'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">

                var country_json_file = '<?php echo $upload_file_url ?>/countries.json?param=<?php echo rand(10, 100) ?>',
                    country_json_files_loc = '<?php echo $upload_file_url ?>/countries/';

                <?php if($query_var != ''){ ?>
                var jobsearch_is_loc_editor = '<?php echo $query_var; ?>';
                <?php } ?>

            </script>
            <script type="text/javascript">
                var $ = jQuery;
                $(function () {

                    var $ = jQuery, upload_file_url = '<?php echo $upload_file_url ?>';

                    jQuery('#countryId').on('change', function () {
                        var $this = $(this);

                        if ($this.val() != 0) {

                            var country_code = $this.find('option:selected').attr("code");

                            jQuery(".cities-table-detail").find('tbody').html('');
                            readsingleCountryData(country_json_file, country_code);
                            jQuery(".state-wrapper").removeClass('loc-hidden');
                            jQuery(".cities-wrapper").removeClass('loc-hidden');
                            jQuery("#stateId").removeClass('loc-hidden');
                            jQuery(".jobsearch-load-states-cities-name").find('h3').text('');
                            readSingleCityStateFile(upload_file_url + '/countries/' + country_code + '/' + country_code + '-states.json?param=<?php echo(rand(10, 100)) ?>', $('.state-table'));
                            jQuery('.jobsearch-load-state-name').find('h3').html($this.val() + ", <?php echo esc_html_e('States', 'wp-jobsearch'); ?>");

                        } else {
                            jQuery(".jobsearch-load-state-name").find('h3').text('');
                            jQuery(".jobsearch-load-states-cities-name").find('h3').text('');
                            jQuery(".country-table-detail").find('tbody').html('');
                            jQuery(".state-table-detail").find('tbody').html('');
                            jQuery(".cities-table").find('tbody').html('');
                            jQuery("#stateId").addClass('loc-hidden');
                        }
                    });

                    jQuery(document).on('change', '#stateId', function () {
                        $this = $(this);
                        if ($this.val() != 0 && $this.val() != undefined) {
                            var country_code = jQuery("#countryId option:selected").attr('code');
                            var filename = country_code + '-' + $this.val() + '-cities.json?param=<?php  echo(rand(10, 100)) ?>';
                            readSingleCityStateFile(upload_file_url + '/countries/' + country_code + '/' + $this.val() + '/' + filename, $('.cities-table'));
                            jQuery('.jobsearch-load-states-cities-name').find('h3').html($this.val() + ", <?php echo esc_html_e('Cities', 'wp-jobsearch'); ?>")
                        } else {
                            jQuery(".jobsearch-load-states-cities-name").find('h3').text('');
                            jQuery(".cities-table-detail").find('tbody').html('');
                        }
                    });

                })
            </script>
            <?php
        }

        public function jobsearch_check_state_dir_callback()
        {
            global $wp_filesystem, $jobsearch_download_locations;
            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }

            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;

            if (file_exists($upload_file_path . "/countries/" . $_POST['country_code'])) {
                echo json_encode(array('country_code' => $_POST['country_code']));
                wp_die();
            }
        }

        public function jobsearch_add_new_cities_callback()
        {
            global $wp_filesystem, $jobsearch_download_locations;
            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];

            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }
            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;

            $url = wp_nonce_url("post.php");
            $form_fields = array("file-data");
            $updated_cities = $_POST['updated_cities'];

            $new_cities = array();
            foreach ($updated_cities as $key => $val) {
                if (!empty($val['cities_name'])) {
                    $new_cities['result'][] = $val['cities_name'];
                }
            }

            $result = $new_cities;

            if (connect_fs($url, "", $upload_file_path, $form_fields)) {

                $dir = $wp_filesystem->find_folder($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $_POST['states_name'] . "/" . $_POST['country_code'] . "-" . $_POST['states_name'] . "-cities.json");
                $file = rtrim($dir, '/');

                $wp_filesystem->put_contents($file, json_encode($result), FS_CHMOD_FILE);
                echo json_encode(array('status' => 'data_updated'));
                wp_die();

            } else {
                echo json_encode(array('status' => new WP_Error("filesystem_error", "Cannot initialize filesystem")));
                wp_die();
            }
        }

        public function stripslashes_deep($value)
        {
            $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

            return $value;
        }

        public function jobsearch_add_new_states_callback()
        {
            global $wp_filesystem, $jobsearch_download_locations;

            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }
            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;

            $url = wp_nonce_url("post.php");
            $form_fields = array("file-data");

            $updated_states = $_POST['updated_states'];
            $current_states = $_POST['current_states'];
            $default_state_text = '';
            if (isset($current_states['result'][0])) {
                $default_state_text = $current_states['result'][0];
            }

            $flag = false;
            if (isset($updated_states[0]['state_name']) && empty($updated_states[0]['state_name'])) {
                unset($current_states['result'][0]);
                $flag = true;
            }

            if (!empty($updated_states[0]['state_name'])) {
                $new_states = array();
                foreach ($updated_states as $key => $val) {
                    if (!empty($val['state_name'])) {
                        $new_states['result'][] = $val['state_name'];
                    }
                }
            }

            if (!empty($updated_states[0]['state_name'])) {

                $result = array_merge($current_states, $new_states);

            } else {

                $result = $current_states['result'];
            }


            if (connect_fs($url, "", $upload_file_path, $form_fields)) {

                if ($flag == false) {
                    foreach ($result['result'] as $val) {

                        if ((!file_exists($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $val) && count($current_states['result']) != count($result['result'])) || $default_state_text == trim("Enter Any State")) {

                            mkdir($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $val);
                            $wp_filesystem->put_contents($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $val . "/" . $_POST['country_code'] . "-" . $val . "-cities.json", '', FS_CHMOD_FILE);

                            $dummy_data = array();
                            $dummy_data['result'][] = "Enter Any City";
                            $wp_filesystem->put_contents($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $val . "/" . $_POST['country_code'] . "-" . $val . "-cities.json", json_encode($dummy_data), FS_CHMOD_FILE);
                        }
                    }
                }

                $dir = $wp_filesystem->find_folder($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $_POST['country_code'] . "-states.json");
                $file = rtrim($dir, '/');

                if ($flag == true) {
                    $current_states['result'][0] = "Enter Any State";
                    $wp_filesystem->put_contents($file, json_encode($current_states), FS_CHMOD_FILE);
                } else {
                    $wp_filesystem->put_contents($file, json_encode($this->stripslashes_deep($result)), FS_CHMOD_FILE);
                }

                $total_states_file = read_location_file('/countries/' . $_POST['country_code'] . "/" . $_POST['country_code'] . "-states.json");
                $total_states = json_decode($total_states_file);
                /*
                 * This code will execute the renaming state folder process.
                 * if the existing state name is modified then it will rename the folder.
                 * */
                if ($flag == false) {

                    if (count($current_states['result']) == count($result['result']) && $default_state_text != trim("Enter Any State")) {
                        $states_folders = glob($upload_file_path . "/countries/" . $_POST['country_code'] . "/*", GLOB_ONLYDIR);

                        foreach ($total_states->result as $key => $info_states) {
                            if (!file_exists($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $info_states)) {

                                rename($states_folders[$key], $upload_file_path . "/countries/" . $_POST['country_code'] . "/" . $info_states);
                                $city_File = scandir($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . trim($info_states) . "/");
                                $all_files = array_values(array_diff($city_File, array('.', '..')));

                                rename($upload_file_path . "/countries/" . $_POST['country_code'] . "/" . trim($info_states) . "/" . trim($all_files[0]), $upload_file_path . "/countries/" . $_POST['country_code'] . "/" . trim($info_states) . "/" . $_POST['country_code'] . "-" . trim($info_states) . "-cities.json");
                            }
                        }
                    }
                }

                echo json_encode(array('status' => 'data_updated'));
                wp_die();
            } else {
                echo json_encode(array('status' => new WP_Error("filesystem_error", "Cannot initialize filesystem")));
                wp_die();
            }
        }

        public static function searchForValue($name, $array)
        {
            foreach ($array as $key => $val) {
                if ($val['name'] === $name) {
                    return true;
                }
            }
            return null;
        }

        public function jobsearch_update_Country_Callback()
        {
            global $wp_filesystem, $jobsearch_download_locations;
            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }
            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;
            $url = wp_nonce_url("post.php");
            $form_fields = array("file-data");
            $updated_countries = $_POST['updated_countries'];
            $country_to_update_index = $_POST['country_to_update'];
            $current_countries = $_POST['current_countries'];
            $single_country_code = $_POST['single_country_code'];
            $new_countries = array();

            /*
             * code will search for existing country
             * */

            foreach ($updated_countries as $key => $val) {
                if (!empty($val['name'])) {
                    if (empty(self::searchForValue($val['name'], $current_countries))) {
                        $new_countries[] = $val;
                    }
                }
            }
            /*
             * End
             * */

            /*
             * Code will delete the Country
             * */

            if (empty($new_countries) && $updated_countries[0]['name'] == "") {
                $to_delete_array = [];
                foreach ($current_countries as $key => $prsnt_countries) {
                    if ($prsnt_countries['code'] == $single_country_code) {
                        $to_delete_array[] = $key;
                    }
                }
                unset($current_countries[$to_delete_array[0]]);
            }
            /*
             * End
             * */

            /*
             * Code will update the selected Country
             * */

            if ($country_to_update_index != "" && file_exists($upload_file_path . "/countries/" . $updated_countries[0]['code']) && $updated_countries[0]['name'] != "") {
                $current_countries[$country_to_update_index] = array(
                    'name' => $new_countries[0]['name'],
                    'code' => $new_countries[0]['code'],
                    'population' => $new_countries[0]['population'],
                );
            }

            /*
             * End
             * */

            if (!empty($new_countries) && $country_to_update_index == "") {
                $result = array_merge($current_countries, $new_countries);
            } else {
                $result = array_values($current_countries);
            }

            if (connect_fs($url, "", $upload_file_path, $form_fields)) {

                foreach ($new_countries as $countries) {

                    if (!file_exists($upload_file_path . "/countries/" . strtoupper($countries['code']))) {
                        mkdir($upload_file_path . "/countries/" . strtoupper($countries['code']));

                        $dummy_data = array();
                        $dummy_data['result'][] = "Enter Any State";
                        /*
                         * create file
                         * */
                        $wp_filesystem->put_contents($upload_file_path . "/countries/" . strtoupper($countries['code']) . "/" . strtoupper($countries['code']) . "-states.json", '', FS_CHMOD_FILE);
                        /*
                         * Write file
                         * */
                        $wp_filesystem->put_contents($upload_file_path . "/countries/" . strtoupper($countries['code']) . "/" . strtoupper($countries['code']) . "-states.json", json_encode($dummy_data), FS_CHMOD_FILE);
                    }
                }

                $dir = $wp_filesystem->find_folder($upload_file_path . "/countries.json");
                $file = rtrim($dir, '/');

                $wp_filesystem->put_contents($file, json_encode($result), FS_CHMOD_FILE);
                if (!empty($new_countries)) {
                    echo json_encode(array('status' => 'data_updated'));
                } else {
                    echo json_encode(array('status' => 'data_deleted'));
                }
                wp_die();
            } else {
                echo json_encode(array('status' => new WP_Error("filesystem_error", "Cannot initialize filesystem")));
                wp_die();
            }
        }

        public function save_locsettings()
        {
            if (isset($_POST['jobsearch_allocs_setingsubmit']) && $_POST['jobsearch_allocs_setingsubmit'] == '1') {
                $data_arr_list = array();
                foreach ($_POST as $post_key => $post_val) {
                    $data_arr_list[$post_key] = $post_val;
                }
                update_option('jobsearch_locsetin_options', $data_arr_list);
            }
        }


        public function load_locations_js($force_flag = false, $ajax_flag = false)
        {
            global $pagenow, $jobsearch_plugin_options, $jobsearch_download_locations, $jobsearch_uploding_candimg, $jobsearch_uploding_resume;
            $jobsearch_uploding_resume = false;
            $jobsearch_uploding_candimg = false;

            $jobsearch_download_locations = true;
            add_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $wp_upload_dir = wp_upload_dir();

            $upload_file_path = $wp_upload_dir['path'];
            if (!file_exists($upload_file_path . "/countries")) {
                $upload_file_path = $wp_upload_dir['basedir'] . '/jobsearch-locations';
            }
            $upload_file_url = $wp_upload_dir['url'];

            remove_filter('jobsearch_locations_upload_dir', array($this, 'jobsearch_locations_path'), 10, 1);
            $jobsearch_download_locations = false;


            if (!file_exists($upload_file_path . "/countries")) {
                return;
            }

            $page_id = $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
            $page_id = $user_dashboard_page = jobsearch__get_post_id($user_dashboard_page, 'page');
            $loc_flag = false;

            if ($force_flag == true) {
                $loc_flag = true;
            }

            if (is_page($page_id)) {

                $loc_flag = true;
                $state_param_name = 'jobsearch_field_location_location2';
                $city_param_name = 'jobsearch_field_location_location3';
            } else {
                $state_param_name = 'location_location2';
                $city_param_name = 'location_location3';
            }

            if ((isset($_GET['page']) && $_GET['page'] == 'jobsearch-location-sett')) {
                $loc_flag = true;
            }

            if ((isset($_GET['page']) && $_GET['page'] == 'jobsearch-location-sett-editor')) {
                $loc_flag = true;
            }

            if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
                $loc_flag = true;
            }
            if ($loc_flag === false) {
                return false;
            }

            $jobsearch_locsetin_options = get_option('jobsearch_locsetin_options');
            $loc_optionstype = isset($jobsearch_locsetin_options['loc_optionstype']) ? $jobsearch_locsetin_options['loc_optionstype'] : '';

            $contry_order = isset($jobsearch_locsetin_options['contry_order']) ? $jobsearch_locsetin_options['contry_order'] : '';
            $contry_order = $contry_order != '' ? $contry_order : 'alpha';
            $contry_filtrinc_contries = isset($jobsearch_locsetin_options['contry_filtrinc_contries']) ? $jobsearch_locsetin_options['contry_filtrinc_contries'] : '';
            $contry_filtring = isset($jobsearch_locsetin_options['contry_filtring']) ? $jobsearch_locsetin_options['contry_filtring'] : '';
            $contry_filtrexc_contries = isset($jobsearch_locsetin_options['contry_filtrexc_contries']) ? $jobsearch_locsetin_options['contry_filtrexc_contries'] : '';
            $contry_preselct = isset($jobsearch_locsetin_options['contry_preselct']) ? $jobsearch_locsetin_options['contry_preselct'] : '';
            $contry_preselct = $contry_preselct != '' ? $contry_preselct : 'none';
            $contry_presel_contry = isset($jobsearch_locsetin_options['contry_presel_contry']) ? $jobsearch_locsetin_options['contry_presel_contry'] : '';

            $query_var = '';
            if (isset($_GET['page'])) {
                $query_var = $_GET['page'];
            }
            ?>
            <script>
                var jobsearch_sloc_type = '<?php echo $loc_optionstype ?>',
                    jobsearch_is_admin = '<?php echo is_admin(); ?>',
                    jobsearch_is_post_page = '<?php echo $pagenow; ?>';

                <?php if($query_var != ''){ ?>
                var jobsearch_is_loc_editor = '<?php echo $query_var; ?>';
                <?php } ?>

                var $ = jQuery, filename, rawFile, selector, inc_countries = "",
                    exec_countries = "", index, contry_presel_contry, $this, ajax_flag = false,
                    contry_preselct = '<?php echo $contry_preselct ?>',
                    random_num = '<?php echo rand(999, 1000) ?>',
                    contry_presel_contry_code = '<?php echo $contry_presel_contry ?>';

                <?php
                if($ajax_flag == false){ ?>

                $(document).ready(function () {
                    <?php } ?>

                    var $ = jQuery;
                    var _single_country_code = '';
                    if (jobsearch_sloc_type == '2' || jobsearch_sloc_type == '3') {
                        _single_country_code = $("#countryId").val();
                    } else {
                        if (jobsearch_sloc_country != 0) {
                            _single_country_code = api_scrapper.getCountryCode(jobsearch_sloc_country);
                        }
                    }

                    <?php if($ajax_flag == true){ ?>
                    ajax_flag = '<?php echo $ajax_flag ?>';

                    api_scrapper.readCountryFile('<?php echo $upload_file_url ?>/countries.json', $('#countryId'), jobsearch_sloc_country);
                    <?php } ?>

                    <?php if($contry_preselct != 'by_user_ip' || $query_var == 'jobsearch-location-sett-editor'){ ?>

                    api_scrapper.readCountryFile('<?php echo $upload_file_url ?>/countries.json', $('#countryId'), '');

                    <?php } ?>

                    if (jobsearch_sloc_state != "") {

                        api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + _single_country_code + '/' + _single_country_code + '-states.json', $("#stateId"))
                    }

                    if ((jobsearch_sloc_type == 2 || jobsearch_sloc_type == 3) && jobsearch_is_loc_editor != 'jobsearch-location-sett-editor') {

                        api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + _single_country_code + '/' + _single_country_code + '-states.json', $("#stateId"))
                    }

                    if (jobsearch_sloc_city != "" && jobsearch_is_post_page != 'post.php' && jobsearch_is_post_page != 'post-new.php') {
                        setTimeout(function () {
                            $('#stateId').trigger('change');
                        }, 1000)
                    }

                    <?php

                    if($contry_preselct == 'by_user_ip' && $query_var != 'jobsearch-location-sett-editor'){ ?>

                    if (jobsearch_sloc_country == 0 || jobsearch_sloc_state == 0) {

                        api_scrapper.predictByIP();

                    } else {

                        api_scrapper.readCountryFile('<?php echo $upload_file_url ?>/countries.json', $('#countryId'), _single_country_code);
                        api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + _single_country_code + '/' + _single_country_code + '-states.json', $("#stateId"));
                    }
                    <?php } ?>
                    /*
                    * Pre select Country
                    * */

                    <?php
                    if ($contry_preselct == 'by_contry' && $contry_presel_contry != "" && $query_var != 'jobsearch-location-sett-editor') { ?>
                    if ($("#countryId").val() != "") {

                        _single_country_code = jobsearch_is_admin == 1 && $("select[name=contry_presel_contry]").val() != undefined ? $("select[name=contry_presel_contry]").val() : api_scrapper.getCountryCode($("#countryId").val());
                    } else {

                        _single_country_code = '<?php echo $contry_presel_contry ?>';

                    }
                    api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + _single_country_code + '/' + _single_country_code + '-states.json', $("#stateId"));
                    <?php } ?>
                    <?php if($ajax_flag == false){ ?>
                });
                <?php } ?>

                /*
                 *countries change event
                 **/


                $(document).on('change', '.countries', function () {
                    var $ = jQuery;
                    $this = $(this);
                    if ($this.val() != 0 && $this.val() != undefined) {
                        var _country_code = '';
                        if (inc_countries != "") {
                            _country_code = api_scrapper.getCountryCode($this.val());
                        } else {
                            _country_code = jobsearch_is_admin == 1 && $this.find('option:selected').attr("code") != undefined ? $this.find('option:selected').attr("code") : api_scrapper.getCountryCode($this.val());
                        }
                        api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + _country_code + '/' + _country_code + '-states.json', $("#stateId"));
                    }
                });

                /*
                * state change events
                * */

                $(document).on('change', '#stateId', function () {
                    var $ = jQuery, country_code = '';
                    $this = $(this);

                    if ($this.val() != 0 && $this.val() != undefined) {

                        country_code = $("#countryId").find('option:selected').attr("code") != undefined ? $("#countryId").find('option:selected').attr("code") : $("#countryId").val();
                        if (country_code.length > 2) {
                            country_code = api_scrapper.getCountryCode($("#countryId").val())
                        }

                        var filename = country_code + '-' + $this.val() + '-cities.json?param=<?php echo(rand(10, 100)) ?>';
                        api_scrapper.readCityOnlyFiles('<?php echo $upload_file_url ?>/countries/' + country_code + '/' + $this.val() + '/' + filename, $('#cityId'));
                    }
                });
                /*
                * Scrapper Events
                * */

                var api_scrapper = {
                    getCountryCode: function (jobsearch_sloc_country) {
                        var $ = jQuery;
                        var _country_code = '';
                        rawFile = new XMLHttpRequest();
                        rawFile.open("GET", '<?php echo $upload_file_url ?>/countries.json', false);
                        rawFile.onreadystatechange = function () {
                            if (rawFile.readyState === 4) {
                                if (rawFile.status === 200 || rawFile.status == 0) {

                                    var _result_coutries = JSON.parse(rawFile.responseText);
                                    var results = _result_coutries.filter(function (item) {
                                        return item.name.indexOf(api_scrapper.stripslashes(jobsearch_sloc_country)) > -1;
                                    });

                                    if (results.length != 0) {
                                        _country_code = results[0].code;
                                    }
                                }
                            }
                        };
                        rawFile.send(null);
                        return _country_code;
                    },
                    readCityOnlyFiles: function (file, selector) {

                        var $ = jQuery;
                        if (jobsearch_is_admin == '' || ajax_flag == true) {
                            jQuery('#jobsearch-gdapilocs-citycon').empty();
                            jQuery('#jobsearch-gdapilocs-citycon').append('<select placeholder="<?php echo esc_html__('Select City', 'wp-jobsearch'); ?>" name="<?php echo $city_param_name; ?>" class="cities" id="cityId"><option value="0"><?php echo esc_html__("Select City", "wp-jobsearch") ?></option></select>');
                            selector = $(document).find("#cityId");
                        }

                        rawFile = new XMLHttpRequest();
                        rawFile.open("GET", file, false);
                        rawFile.onreadystatechange = function () {
                            if (rawFile.readyState === 4) {
                                if (rawFile.status === 200 || rawFile.status == 0) {
                                    selector.html('');
                                    selector.append('<option value="pls_wait"><?php echo esc_html_e('Please Wait...', 'wp-jobsearch') ?></option>');
                                    setTimeout(function () {
                                        selector.html('');
                                        var _result_cities = JSON.parse(rawFile.responseText);

                                        //if (jobsearch_is_admin == 1 && ajax_flag == false) {
                                        selector.append('<option value=""><?php echo esc_html_e('Select City', 'wp-jobsearch') ?></option>');
                                        // }
                                        var _option_select = '';
                                        $.each(_result_cities.result, function (index, element) {
                                            if ($.trim(element) != "Enter Any City") {
                                                if (jobsearch_sloc_city != "") {
                                                    _option_select = jobsearch_sloc_city == element ? 'selected' : '';
                                                }
                                                if (_option_select != "") {
                                                    selector.append($("<option></option>").attr("value", element).attr("selected", "selected").text(element));

                                                } else {

                                                    selector.append($("<option></option>").attr("value", element).text(element));
                                                }
                                            }
                                        });

                                    }, 300);
                                    if (jobsearch_is_admin == '' || ajax_flag == true) {
                                        setTimeout(function () {
                                            selector.selectize({
                                                sortField: 'text'
                                            });
                                        }, 300)
                                    }
                                }
                            }
                        };
                        rawFile.send(null);
                    },
                    readStateFile: function (file, selector) {

                        var $ = jQuery;
                        if (jobsearch_is_admin == '' || ajax_flag == true) {
                            $('#jobsearch-gdapilocs-statecon').empty();
                            $('#jobsearch-gdapilocs-statecon').append('<select placeholder="<?php echo esc_html__("Select State", "wp-jobsearch") ?>"  class="states" id="stateId" name="<?php echo $state_param_name; ?>"><option value=""><?php echo esc_html__("Select State", "wp-jobsearch") ?></option></select>');
                            selector = $(document).find("#stateId");
                        }

                        rawFile = new XMLHttpRequest();
                        rawFile.open("GET", file, false);
                        rawFile.onreadystatechange = function () {
                            if (rawFile.readyState === 4) {
                                if (rawFile.status === 200 || rawFile.status == 0) {
                                    selector.html('');
                                    selector.append('<option value="pls_wait"><?php echo esc_html_e('Please Wait...', 'wp-jobsearch') ?></option>');
                                    setTimeout(function () {
                                        selector.html('');
                                        var _result_states = JSON.parse(rawFile.responseText);

                                        //if (jobsearch_is_admin == 1 && ajax_flag == false) {
                                        selector.append('<option value=""><?php echo esc_html_e('Select State', 'wp-jobsearch') ?></option>');
                                        //}
                                        var _option_select = '';
                                        $.each(_result_states.result, function (index, element) {

                                            if ($.trim(element) != "Enter Any State") {
                                                if (jobsearch_sloc_state != "") {
                                                    _option_select = jobsearch_sloc_state == element ? 'selected' : '';
                                                }


                                                if (_option_select != "") {

                                                    selector.append($("<option></option>").attr("value", element).attr("selected", "selected").text(element));

                                                    // selector.append(jQuery('<option>', {
                                                    //     value: element,
                                                    //     text: element,
                                                    //     selected: _option_select,
                                                    // }));

                                                } else {

                                                    selector.append($("<option></option>").attr("value", element).text(element));

                                                    // selector.append(jQuery('<option>', {
                                                    //     value: element,
                                                    //     text: element,
                                                    // }));

                                                }
                                            }
                                        });
                                    }, 300);
                                    /*
                                    * Initialize Selectize
                                    * */
                                    if (jobsearch_is_admin == '' || ajax_flag == true) {
                                        setTimeout(function () {
                                            selector.selectize({
                                                sortField: 'text'
                                            });
                                        }, 300)
                                    }
                                    //
                                }
                            }
                        };
                        rawFile.send(null);
                    },
                    stripslashes: function (str) {
                        return str.replace(/\\/g, '');
                    },
                    readCountryFile: function (file, selector, country) {
                        var rand_num = Math.random();
                        var $ = jQuery;
                        rawFile = new XMLHttpRequest();
                        rawFile.open("GET", file + "?param=" + rand_num, false);
                        rawFile.onreadystatechange = function () {
                            if (rawFile.readyState === 4) {
                                if (rawFile.status === 200 || rawFile.status == 0) {
                                    var _result_countries = JSON.parse(rawFile.responseText);

                                    selector.html('');
                                    if (jobsearch_is_admin == 1 && ajax_flag == true) {

                                        selector.append('<option value=""><?php echo esc_html_e('Select Country', 'wp-jobsearch') ?></option>');
                                    } else {

                                        var $opt = $('<option>');
                                        $opt.val('').text('<?php echo esc_html_e('Select Country', 'wp-jobsearch') ?>');
                                        $opt.appendTo(selector);
                                    }
                                    /*
                                    * Alphabetic countries
                                    * */

                                    <?php if($contry_order == 'alpha'){ ?>
                                    _result_countries.sort(function (a, b) {
                                        return api_scrapper.compareStrings(a.name, b.name);
                                    });
                                    <?php } ?>
                                    /*
                                    * Code will execute if Exclude only countries option will be selected.
                                    * */

                                    <?php if($contry_filtring == 'inc_contries'){ ?>
                                    inc_countries = <?php echo json_encode($contry_filtrinc_contries);
                                    } ?>;

                                    /*
                                    * Code will execute if Exclude only countries option will be selected.
                                    * */

                                    <?php if($contry_filtring == 'exc_contries'){ ?>
                                    exec_countries = <?php echo json_encode($contry_filtrexc_contries);

                                    } ?>;
                                    /*
                                    * Code will execute if Random countries option will be selected.
                                    * */

                                    <?php if($contry_order == 'random'){ ?>
                                    _result_countries = api_scrapper.shuffleArray(_result_countries);
                                    <?php }
                                    ////////// countries by population ////////////
                                    if($contry_order == 'by_population'){ ?>
                                    _result_countries.sort(function (a, b) {
                                        return parseFloat(b.population) - parseFloat(a.population);
                                    });
                                    <?php } ?>
                                    /*
                                    * Include only countries
                                    * */
                                    if (inc_countries != "" && jobsearch_is_loc_editor != 'jobsearch-location-sett-editor') {

                                        var _inc_flag = false;
                                        $.each(_result_countries, function (i, element) {
                                            if (i < inc_countries.length) {

                                                if (jobsearch_sloc_country == inc_countries[i]) {

                                                    selector.append($("<option></option>")
                                                        .attr("data-index", i)
                                                        .attr("code", api_scrapper.getCountryCode(inc_countries[i]))
                                                        .attr("selected", "selected")
                                                        .attr("value", inc_countries[i])
                                                        .text(inc_countries[i]));
                                                    _inc_flag = true;

                                                } else if (contry_preselct == 'by_contry' && contry_presel_contry_code == api_scrapper.getCountryCode(inc_countries[i]) && ajax_flag == false && _inc_flag == false) {
                                                    selector.append($("<option></option>")
                                                        .attr("data-index", i)
                                                        .attr("code", api_scrapper.getCountryCode(inc_countries[i]))
                                                        .attr("selected", "selected")
                                                        .attr("value", inc_countries[i])
                                                        .text(inc_countries[i]));

                                                } else {
                                                    selector.append($("<option></option>")
                                                        .attr("data-index", i)
                                                        .attr("code", api_scrapper.getCountryCode(inc_countries[i]))
                                                        .attr("value", inc_countries[i])
                                                        .text(inc_countries[i]));
                                                }
                                            }
                                        })

                                    } else if (exec_countries != '' && jobsearch_is_loc_editor != 'jobsearch-location-sett-editor') {
                                        /*
                                        * code will execute if "Exclude countries selected" filter option will be selected
                                        * */

                                        var _exec_flag = false;
                                        $.each(_result_countries, function (index, element) {
                                            if (element != "") {
                                                if (exec_countries.indexOf(element.name) == -1) {
                                                    /*
                                                    * code will execute if Country Name is from save in metavalue
                                                    * */

                                                    if (jobsearch_sloc_country == element.name) {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("selected", "selected")
                                                            .attr("value", element.name)
                                                            .text(element.name));
                                                        _exec_flag = true;
                                                    } else if (contry_preselct == 'by_contry' && contry_presel_contry_code == api_scrapper.getCountryCode(element.name) && ajax_flag == false && _exec_flag == false) {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("selected", "selected")
                                                            .attr("value", element.name)
                                                            .text(element.name));

                                                    } else {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("value", element.name)
                                                            .text(element.name));
                                                    }
                                                }
                                            }
                                        })
                                    } else {

                                        $.each(_result_countries, function (index, element) {

                                            if (element != "") {
                                                /*
                                                * code will execute if Preselect Country option will be selected
                                                * */

                                                <?php if ($contry_preselct == 'by_contry' && $contry_presel_contry != "" && $query_var != 'jobsearch-location-sett-editor') { ?>

                                                contry_presel_contry = "<?php echo $contry_presel_contry ?>";
                                                var _option_select = contry_presel_contry == element.code ? 'selected' : '';
                                                /////////////////// code will execute on the front end//////////////
                                                if (jobsearch_is_admin == '' || ajax_flag == true) {

                                                    if (contry_presel_contry == element.code) {
                                                        selector.append($('<option>', {
                                                            value: element.name,
                                                            text: element.name,
                                                            selected: 'selected',
                                                        }));
                                                    } else {
                                                        selector.append($('<option>', {
                                                            value: element.name,
                                                            text: element.name,
                                                        }));
                                                    }
                                                    ///////////////////end//////////////
                                                } else {

                                                    if (jobsearch_sloc_country != "") {
                                                        if (api_scrapper.getCountryCode(jobsearch_sloc_country) == element.code) {

                                                            selector.append($("<option></option>")
                                                                .attr("data-index", index)
                                                                .attr("code", element.code)
                                                                .attr("selected", "selected")
                                                                .attr("value", element.name)
                                                                .text(element.name));
                                                        } else {
                                                            selector.append($("<option></option>")
                                                                .attr("data-index", index)
                                                                .attr("code", element.code)
                                                                .attr("value", element.name)
                                                                .text(element.name));
                                                        }

                                                    } else {

                                                        if (contry_presel_contry == element.code) {
                                                            selector.append($("<option></option>")
                                                                .attr("data-index", index)
                                                                .attr("code", element.code)
                                                                .attr("selected", "selected")
                                                                .attr("value", element.name)
                                                                .text(element.name));
                                                        } else {
                                                            selector.append($("<option></option>")
                                                                .attr("data-index", index)
                                                                .attr("code", element.code)
                                                                .attr("value", element.name)
                                                                .text(element.name));
                                                        }
                                                    }

                                                }
                                                /*
                                                * Countries by IP
                                                * */

                                                <?php } else if($contry_preselct == 'by_user_ip'){ ?>


                                                if (jobsearch_is_admin == '' || ajax_flag == true) {
                                                    if (country == element.code) {
                                                        selector.append($('<option>', {
                                                            value: element.name,
                                                            text: element.name,
                                                            selected: 'selected',
                                                        }));
                                                    } else {
                                                        selector.append($('<option>', {
                                                            value: element.name,
                                                            text: element.name,
                                                        }));
                                                    }
                                                } else {
                                                    var _option_select = country == element.code ? 'selected' : '';
                                                    if (country == element.code) {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("selected", "selected")
                                                            .attr("value", element.name)
                                                            .text(element.name));
                                                    } else {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("value", element.name)
                                                            .text(element.name));
                                                    }
                                                }

                                                <?php } else { ?>

                                                console.info(api_scrapper.stripslashes(jobsearch_sloc_country));
                                                var _option_select = '';
                                                if (jobsearch_sloc_country != '') {
                                                    if (api_scrapper.stripslashes(jobsearch_sloc_country) == api_scrapper.stripslashes(element.name)) {
                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("selected", "selected")
                                                            .attr("value", element.name)
                                                            .text(api_scrapper.stripslashes(element.name)));


                                                    } else {

                                                        selector.append($("<option></option>")
                                                            .attr("data-index", index)
                                                            .attr("code", element.code)
                                                            .attr("value", element.name)
                                                            .text(api_scrapper.stripslashes(element.name)));
                                                    }

                                                } else {

                                                    selector.append($("<option></option>")
                                                        .attr("data-index", index)
                                                        .attr("code", element.code)
                                                        .attr("value", element.name)
                                                        .text(api_scrapper.stripslashes(element.name)));
                                                }

                                                <?php } ?>
                                            }
                                        });
                                    }
                                    /*
                                    * Initialize Selectize
                                    * */

                                    if (jobsearch_is_admin == '' || ajax_flag == true) {
                                        if (jobsearch_sloc_type == 0 || jobsearch_sloc_type == 1) {
                                            selector.selectize({
                                                placeholder: '<?php echo esc_html_e('Select Country', 'wp-jobsearch') ?>',
                                            });
                                        }
                                    }
                                }
                            }
                        };
                        rawFile.send(null);
                    }, predictByIP: function () {
                        var $ = jQuery;
                        var request = $.ajax({
                            url: "https://ipinfo.io/json",
                            dataType: "json"
                        });
                        request.done(function (result) {

                            if (result != '') {
                                api_scrapper.readCountryFile('<?php echo $upload_file_url ?>/countries.json', $('#countryId'), result.country);
                                api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + result.country + '/' + result.country + '-states.json', $('#stateId'))
                            } else {
                                /*
                                * Second Request will be sent if first request will fail.
                                * */
                                api_scrapper.apiSecondRequest();
                            }
                        });
                        request.fail(function (jqXHR, textStatus) {
                            alert(textStatus)
                        });
                    }, shuffleArray: function (a) {
                        var j, x, i;
                        for (i = a.length - 1; i > 0; i--) {
                            j = Math.floor(Math.random() * (i + 1));
                            x = a[i];
                            a[i] = a[j];
                            a[j] = x;
                        }
                        return a;
                    }, apiSecondRequest: function () {
                        var request = $.ajax({
                            url: "http://ip-api.com/json",
                            dataType: "json"
                        });
                        request.done(function (result) {
                            api_scrapper.readCountryFile('<?php echo $upload_file_url ?>/countries.json', $('#countryId'), result.countryCode);
                            api_scrapper.readStateFile('<?php echo $upload_file_url ?>/countries/' + result.countryCode + '/' + result.countryCode + '-states.json', $('#stateId'))
                        });
                        request.fail(function (jqXHR, textStatus) {
                            alert(textStatus)()
                        });
                    }, compareStrings: function (a, b) {
                        a = a.toLowerCase();
                        b = b.toLowerCase();
                        return (a < b) ? -1 : (a > b) ? 1 : 0;
                    }
                }
            </script>
            <?php
        }

    }

    global $jobsearch_gdapi_allocation;
    $jobsearch_gdapi_allocation = new jobsearch_allocation_settings_handle();
}
