<?php

namespace CareerfyElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

/**
 * @since 1.1.0
 */
class testimonials extends Widget_Base
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
        return 'testimonials';
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
        return __('Testimonials', 'careerfy-frame');
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
        return 'fa fa-comment';
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
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Testimonials Settings', 'careerfy-frame'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'testi_view',
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
                    'view7' => __('Style 6', 'careerfy-frame'),
                    'view8' => __('Style 7', 'careerfy-frame'),
                    'view9' => __('Style 8', 'careerfy-frame'),
                    'view10' => __('Style 9', 'careerfy-frame'),
                    'view11' => __('Style 10', 'careerfy-frame'),
                    'view12' => __('Style 11', 'careerfy-frame'),
                ],
            ]
        );

        $this->add_control(
            'img', [
                'label' => __('Image', 'careerfy-frame'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
            ]
        );

        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'img', [
                'label' => __('Image', 'careerfy-frame'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'title', [
                'label' => __('Title', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'desc', [
                'label' => __('Description', 'careerfy-frame'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'position', [
                'label' => __('Position', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'date_txt', [
                'label' => __('Date Text', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                "description" => __('Date Text will be added when style 11 will be selected.', 'careerfy-frame')
            ]
        );

        $repeater->add_control(
            'testimonial_url', [
                'label' => __('URL', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                "description" => __('URL will be added when style 11 will be selected.', 'careerfy-frame')
            ]
        );

        $repeater->add_control(
            'location', [
                'label' => __('Location', 'careerfy-frame'),
                'type' => Controls_Manager::TEXT,
                "description" => __('Location will be added when style 7 will be selected.', 'careerfy-frame')
            ]
        );

        $repeater->add_control(
            'bg_color', [
                'label' => __('Choose Background Color', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                'description' => esc_html__("This Color will apply at 'Testimonial background'. and on style 7", "careerfy-frame"),
            ]
        );

        $repeater->add_control(
            'fb_url', [
                'label' => __('Facebook URL', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                "description" => __('Social link will be added when style 9 will be selected.')
            ]
        );

        $repeater->add_control(
            'twitter_url', [
                'label' => __('Twitter URL', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                "description" => __('Social link will be added when style 9 will be selected.')
            ]
        );

        $repeater->add_control(
            'linkedin_url', [
                'label' => __('linkedIn URL', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                "description" => __('Social link will be added when style 9 will be selected.')
            ]
        );

        $repeater->add_control(
            'link_btn_txt', [
                'label' => __('Link Button Text', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                "description" => __('Social link will be added when style 10 will be selected.')
            ]
        );

        $repeater->add_control(
            'link_btn_url', [
                'label' => __('Link Button URL', 'careerfy-frame'),
                'type' => Controls_Manager::COLOR,
                "description" => __('Social link will be added when style 10 will be selected.')
            ]
        );

        $this->add_control(
            'careerfy_testimonial_item',
            [
                'label' => __('Testimonials Content', 'careerfy-frame'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{title}}}',
            ]
        );
        $this->end_controls_section();
    }

    protected function careerfy_testimonial_item_shortcode()
    {
        global $testi_view;
        $atts = $this->get_settings_for_display();

        extract(shortcode_atts(array(
            'img' => '',
            'desc' => '',
            'title' => '',
            'position' => '',
            'location' => '',
            'bg_color' => '',
            'fb_url' => '',
            'twitter_url' => '',
            'linkedin_url' => '',
            'link_btn_txt' => '',
            'link_btn_url' => '',
            'date_txt' => '',
            'testimonial_url' => '',
        ), $atts));

        foreach ($atts['careerfy_testimonial_item'] as $info) {
           
            $img = $info['img'] != '' ? $info['img']['url'] : '';
            $desc = $info['desc'];
            $title = $info['title'];
            $position = $info['position'];
            $location = $info['location'];
            $bg_color = $info['bg_color'];
            $fb_url = $info['fb_url'];
            $twitter_url = $info['twitter_url'];
            $linkedin_url = $info['linkedin_url'];
            $link_btn_txt = $info['link_btn_txt'];
            $link_btn_url = $info['link_btn_url'];
            $date_txt = $info['date_txt'];
            $testimonial_url = $info['testimonial_url'];

            $bg_color = $bg_color != "" ? "style='background-color: $bg_color'" : "";
            if ($testi_view == 'view11') {

                $html = '<div class="col-md-6">
                   <div class="careerfy-seventeen-testimonial">
                   <figure>
                   <a href="' . $testimonial_url . '"><img src="' . ($img) . '" alt=""> <strong></strong> </a>
                   <figcaption>
                   <h2><a href="' . $testimonial_url . '">' . ($title) . '</a></h2>
                <span>' . $position . '</span>';
                if ($date_txt != "") {
                    $html .= '<small>' . $date_txt . '</small>';
                }
                $html .= '</figcaption>
                                </figure>
                                <p>' . ($desc) . '</p>
                            </div>
                        </div>';

            } else if ($testi_view == 'view10') {
                $html = '<div class="careerfy-testimonial-style14-layer">
                                <div class="careerfy-testimonial-style14-inner">
                                    <img src="' . ($img) . '" alt="">
                                    <h2>' . ($title) . '</h2>
                                    <span>' . $position . '</span>
                                    <p>' . ($desc) . '</p>
                                    <a href="' . $link_btn_url . '" class="careerfy-testimonial-style14-btn">' . $link_btn_txt . '</a>
                                </div>
                            </div>';
            } else if ($testi_view == 'view9') {

                $html = '<div class="careerfy-testimonails-thirteen-layer">
                                    <div class="careerfy-testimonails-thirteen-inner">
                                        <i class="careerfy-icon careerfy-phrase"></i>
                                        <div class="clearfix"></div>
                                        <p>' . ($desc) . '</p>
                                        <img src="' . ($img) . '" alt="">
                                        <div class="careerfy-testimonails-thirteen-text">
                                            <h2>' . ($title) . '</h2>
                                            <div class="clearfix"></div>';

                if ($fb_url != '') {
                    $html .= '<a href="' . $fb_url . '" class="fa fa-facebook"></a>';
                }

                if ($twitter_url != '') {
                    $html .= '<a href="' . $twitter_url . '" class="fa fa-twitter"></a>';
                }

                if ($linkedin_url != '') {
                    $html .= '<a href="' . $linkedin_url . '" class="fa fa-linkedin"></a>';
                }

                $html .= '</div>
                       </div>
                          </div>';

            } else if ($testi_view == 'view8') {
                $html = '<li class="col-md-4">
                   <div class="careerfy-testimonial-twelve-inner">
                     <i class="careerfy-icon careerfy-quote"></i>
                          <p>' . ($desc) . '</p>
                           <figure>
                           <img src="' . ($img) . '" alt="">
                           <figcaption>
                           <h2>' . ($title) . '</h2>
                           <span>' . ($position) . '</span>
                          </figcaption>
                     </figure>
                    </div>
                  </li>';
            } else if ($testi_view == 'view7') {
                $html = '<div class="careerfy-testimonial-style11-slider-layer" ' . $bg_color . '>
                    <i class="careerfy-icon careerfy-phrase"></i>
                    <p>' . ($desc) . '</p>
                     <figure>
                      <a href="#"><img src="' . ($img) . '" alt=""></a>
                      <figcaption>
                       <h2>' . ($title) . '</h2>
                       <span>' . $location . '</span>
                      </figcaption>
                    </figure>
                  </div>';
            } else if ($testi_view == 'view6') {
                $html = '<div class="careerfy-testimonial-style10-slider-layer">
                  <figure>';
                $img_headers = @get_headers($img);
                if (strpos($img_headers[0], '200') !== false) {
                    $html .= '<a href="#"><img src="' . ($img) . '" alt=""></a>';
                }
                $html .= '<figcaption>
                     <h2><a href="#">' . ($title) . '</a></h2>
                       <span>' . ($position) . '</span>
                     </figcaption>
                    </figure>
                    <p>' . ($desc) . '</p>
                    <i class="careerfy-icon careerfy-quote quote-icon-style"></i>
                </div>';
            } else if ($testi_view == 'view5') {
                $html = '
        <div class="careerfy-testimonial-slider-classic-layer">
          <div class="careerfy-testimonial-slider-classic-pera">
            <p> <i class="careerfy-icon careerfy-left-quote"></i>' . ($desc) . '</p>
         </div>
         <div class="careerfy-testimonial-slider-classic-text">
          <img src="' . ($img) . '" alt="">
           <h2>' . ($title) . '</h2>
           <span>' . ($position) . '</span>
           </div>
        </div>
        
        ';
            } else if ($testi_view == 'view4') {
                $html = '
        <div class="careerfy-testimonial-style4-layer">
            <img src="' . ($img) . '" alt="">
            <p>' . ($desc) . '</p>
            <span>' . ($title) . ' <small>' . ($position) . '</small> </span>
        </div>';
            } else if ($testi_view == 'view3') {
                $html = '
        <div class="careerfy-testimonial-slider-style3-layer">
            <div class="testimonial-slider-style3-text">
                <p>' . ($desc) . '</p>
                <span><i class="careerfy-icon careerfy-left-quote"></i> ' . ($position != '' ? '<small>' . $title . ',</small>' : '') . ' ' . ($position) . '</span>
            </div>
        </div>';
            } else if ($testi_view == 'view2') {
                $html = '
        <div class="careerfy-testimonial-styletwo-layer">
            <img src="' . ($img) . '" alt="">
            <p>' . ($desc) . '</p>
            <span>' . ($title) . '</span>
            <small>' . ($position) . '</small>
        </div>';
            } else {
                $html = '
        <div class="careerfy-testimonial-slide-layer">
            <div class="careerfy-testimonial-wrap">
                <p>' . ($desc) . '</p>
                <div class="careerfy-testimonial-text">
                    <h2>' . ($title) . '</h2>
                    <span>' . ($position) . '</span>
                </div>
            </div>
        </div>';
            }
            echo $html;
        }

    }

    protected function render()
    {
        global $testi_view;
        $atts = $this->get_settings_for_display();
        extract(shortcode_atts(array(
            'testi_view' => '',
            'img' => '',
        ), $atts));
        $rand_num = rand(1000, 9999);
        $img = $img != '' ? $img['url'] : '';

        wp_enqueue_script('careerfy-slick-slider');
        ob_start();
        if ($testi_view == 'view11') { ?>

            <div class="careerfy-seventeen-testimonial-full">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>


        <?php } else if ($testi_view == 'view10') { ?>

            <div class="careerfy-testimonial-style14-slider">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
            <script type="text/javascript">
                var $ = jQuery;
                $(document).ready(function () {
                    $('.careerfy-testimonial-style14-slider').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        infinite: true,
                        dots: false,
                        arrows: false,
                        centerMode: true,
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
                    })
                })
            </script>

        <?php } else if ($testi_view == 'view9') { ?>

            <div class="careerfy-testimonails-thirteen" id="testimonails-thirteen-<?php echo $rand_num ?>">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>

            <script type="text/javascript">
                var $ = jQuery;
                $(document).ready(function () {
                    jQuery("#testimonails-thirteen-'.$rand_num.'").slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        arrows: "false",
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
                });
            </script>

        <?php } else if ($testi_view == 'view8') { ?>

            <div class="careerfy-testimonial-twelve">
                <ul class="row">
                    <?php echo $this->careerfy_testimonial_item_shortcode() ?>
                </ul>
            </div>

        <?php } else if ($testi_view == 'view7') { ?>

            <div class="careerfy-testimonial-style11-slider">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
            <script type="text/javascript">
                var $ = jQuery;
                $(document).ready(function () {
                    $('.careerfy-testimonial-style11-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class=\'slick-arrow-left\'><i class=\'careerfy-icon careerfy-next-1\'></i></span>",
                        nextArrow: "<span class=\'slick-arrow-right\'><i class=\'careerfy-icon careerfy-next-1\'></i></span>",
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
                })
            </script>
        <?php } else if ($testi_view == 'view6') { ?>

            <div class="careerfy-testimonial-style10-slider">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
            <script type="text/javascript">
                var $ = jQuery;
                $(document).ready(function () {
                    $('.careerfy-testimonial-style10-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class=\'slick-arrow-left\'><i class=\'careerfy-icon careerfy-next-1\'></i></span>",
                        nextArrow: "<span class=\'slick-arrow-right\'><i class=\'careerfy-icon careerfy-next-1\'></i></span>",
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
                })
            </script>

        <?php } else if ($testi_view == 'view5') { ?>

            <div class="careerfy-testimonial-slider-classic">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
            <script type="text/javascript">
                var $ = jQuery;
                $(document).ready(function () {

                    $('.careerfy-testimonial-slider-classic').slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 5000,
                        infinite: true,
                        dots: false,
                        prevArrow: "<span class=\'slick-arrow-left\'><i class=\'careerfy-icon careerfy-arrow-right-light\'></i></span>",
                        nextArrow: "<span class=\'slick-arrow-right\'><i class=\'careerfy-icon careerfy-arrow-right-light\'></i></span>",
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                    infinite: true
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
                })
            </script>

        <?php } else if ($testi_view == 'view4') { ?>
            <div class="careerfy-testimonial-style4">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
        <?php } else if ($testi_view == 'view3') { ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="careerfy-testimonial-slider-style3-wrap">
                        <?php echo($img != '' ? '<div class="careerfy-plan-thumb"><img src="' . $img . '" alt=""></div>' : '') ?>
                        <div class="careerfy-testimonial-slider-style3">
                            <?php echo $this->careerfy_testimonial_item_shortcode() ?>
                        </div>
                        <ul class="careerfy-testimonial-nav">
                            <li class="careerfy-prev"><i class="careerfy-icon careerfy-right-arrow-long"></i></li>
                            <li class="careerfy-next"><i class="careerfy-icon careerfy-right-arrow-long"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } else if ($testi_view == 'view2') { ?>

            <div class="careerfy-testimonial-styletwo">
                <?php echo $this->careerfy_testimonial_item_shortcode() ?>
            </div>
        <?php } else { ?>

            <div class="careerfy-testimonial-section">
                <div class="row">
                    <?php echo($img != '' ? '<aside class="col-md-5"> <img src="' . $img . '" alt=""> </aside>' : '') ?>
                    <aside class="col-md-7">
                        <div class="careerfy-testimonial-slider">
                            <?php echo $this->careerfy_testimonial_item_shortcode() ?>
                        </div>
                    </aside>
                </div>
            </div>
        <?php }
        $html = ob_get_clean();
        echo $html;
    }

    protected function _content_template()
    {
    }
}