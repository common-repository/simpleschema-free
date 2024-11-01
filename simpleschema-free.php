<?php
/*
Plugin Name: SimpleSchema Free
Plugin URI: http://www.sergiomico.com/simpleschema-free/
Description: This microdata insertion shortcode tool prevents the WordPress built-in TinyMCE content editor removing microdata when you change post content editor view from code text to visual. LocalBusiness Schema Widget available since 1.6 version. LocalBusiness Services Schema Widget available since 1.7 version.
Version: 1.7.6.9
Author: dev@sergiomico.com
Author URI: http://hire.sergiomico.com
License: GPLv2 or later
*/

/**
 * normalize_empty_atts, for using when needed.
 *
 * @since 1.0
 */

if (!function_exists('normalize_empty_atts')) {
    function normalize_empty_atts ($atts) {
        foreach ($atts as $attribute => $value) {
            if (is_int($attribute)) {
                $atts[strtolower($value)] = true;
                unset($atts[$attribute]);
            }
        }
        return $atts;
    }
}


/**
 * insertSchema [itemscope] shortcode.
 *
 * @since 1.0
 */

function insertSchema ( $atts, $content = null ) {

    // Attributes
    extract( shortcode_atts(
        array(

            'itemprop' => '', // cuando este esquema es hijo de otro esquema superior
            'itemtype' => '', // LocalBusiness, PostalAddress, Product
            'tag' => 'div',
            'start' => '',
            'end' => '',

        ), $atts )
           );

    $output = '';

    $START = true;
    $END = true;

    //if ( $itemtype == '' )
    //    return '';

    if ( in_array( 'start', $atts ) && !in_array( 'end', $atts ) ) $END = false;
    if ( in_array( 'end', $atts ) && !in_array( 'start', $atts ) ) $START = false;

    if ( $START ) {
        $output = '<'.$tag.' itemscope ';

        if ( $itemtype == 'PostalAddress' ) $itemprop = 'address';
        if ( $itemtype == 'Product' ) $itemprop = 'owns';

        if ( $itemprop !== '' ) $output .= 'itemprop="'.$itemprop.'" ';

        $output .= 'itemtype="http://schema.org/'.$itemtype.'">';
    };

    if ( $START && $END ) {
        $output .= do_shortcode( $content );
    };

    if ( $END ) {
        $output .= '</'.$tag.'>';
    }

    return $output;
};
add_shortcode( 'itemscope', 'insertSchema' );


/**
 * insertSchema [is] shortcode.
 *
 * @since 1.1
 */

add_shortcode( 'is', 'insertSchema' );



/**
 * insertProperty [itemprop] shortcode.
 *
 * @since 1.0
 */

function insertProperty ( $atts, $contents = null ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'name' => '',
            'tag' => 'meta',
            'content' => $contents,
        ), $atts )
           );

    $output = '';

    if ( $tag == 'meta' ) {
        $output .= '<'.$tag.' itemprop="'.$name.'" content="'.do_shortcode( $contents ).'"/>';
    }
    else {
        $output = '<'.$tag.' itemprop="'.$name.'">';
        $output .= do_shortcode( $contents );
        $output .= '</'.$tag.'>';
    };

    return $output;
};
add_shortcode( 'itemprop', 'insertProperty' );


/**
 * insertSchema [ip] shortcode.
 *
 * @since 1.1
 */

add_shortcode( 'ip', 'insertProperty' );




/**
 * insertKeywords [keywords] shortcode.
 *
 * @since 1.0
 */

function insertKeywords ( $atts, $contents = null ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'name' => 'keywords',
            'tag' => 'strong',
            'content' => $contents,
        ), $atts )
           );

    $output = '';

    if ( $tag == 'meta' ) {
        $output .= '<'.$tag.' itemprop="'.$name.'" content="'.do_shortcode( $contents ).'"/>';
    }
    else {
        $output = '<'.$tag.' itemprop="'.$name.'">';
        $output .= do_shortcode( $contents );
        $output .= '</'.$tag.'>';
    };

    return $output;
};
add_shortcode( 'keywords', 'insertKeywords' );


/**
 * insertKeywords [kw] shortcode.
 *
 * @since 1.1
 */

add_shortcode( 'kw', 'insertKeywords' );





/**
 * insertBlogPostingMetas [meta_blog_posting] shortcode.
 *
 * @since 0.5
 */

function insertBlogPostingMetas ( $atts, $content = null ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'itemtype' => 'blogPosting',
            'tag' => 'div',
            'headline' => '',
            'datePublished' => '',
            'image' => '',
            'only_image' => false,
            'no_wrapper' => false,
        ), $atts )
           );

    $itemtype = 'blogPosting';
    $output = '';

    if ( is_array( $atts ) && in_array( 'no_wrapper', $atts ) ) $no_wrapper = true;
    else                                                        $no_wrapper = false;

    if ( is_array( $atts ) && in_array( 'only_image', $atts ) ) $only_image = true;
    else                                                        $only_image = false;

    if ( is_single() && $itemtype == 'blogPosting' ) {

        if ( $headline == '' )
            $headline = get_the_title();

        if ( $datePublished == '' )
            $datePublished = get_the_date('Y-m-d');

        if ( $image == '' )
            $image = wp_get_attachment_thumb_url( get_post_thumbnail_id( get_the_ID() ) );

        if ( $tag == 'meta' )   $tag = 'div';

        if ( $tag == 'div' )    $tag = '';
        else                    $tag = ' tag="'.$tag.'" ';

        if ( $no_wrapper === false ) $output .= do_shortcode('[is '.$tag.' itemtype="'.$itemtype.'" start /]');
        if ( $only_image === false ) $output .= do_shortcode('[ip name="headline"]'.$headline.'[/ip]');
        if ( $only_image === false ) $output .= do_shortcode('[ip name="datePublished"]'.$datePublished.'[/ip]');
        $output .= do_shortcode('[ip name="image"]'.$image.'[/ip]');
        $output .= do_shortcode( $content );
        if ( $no_wrapper === false ) $output .= do_shortcode('[is '.$tag.' end /]');

    };

    return $output;

};
add_shortcode( 'meta_blog_posting', 'insertBlogPostingMetas' );





/**
 * insertGeoCoordinatesMetas [meta_geo_coordinates] shortcode.
 *
 * @since 0.5
 */

function insertGeoCoordinatesMetas ( $atts, $content = null ) {

    // Attributes
    extract( shortcode_atts(
        array(
            'itemtype' => 'GeoCoordinates',
            'tag' => 'div',
            'lat' => '',
            'lon' => '',
            'region' => '',
            'placename' => '',
            'no_wrapper' => false,
        ), $atts )
           );

    $itemtype = 'GeoCoordinates';
    $output = '';

    if ( is_array( $atts ) && in_array( 'no_wrapper', $atts ) ) $no_wrapper = true;
    else                                                        $no_wrapper = false;

    if ( $itemtype == 'GeoCoordinates' ) {

        if ( $lat == '' || $lon == '' )
            return;

        if ( $tag == 'meta' )   $tag = 'div';

        if ( $tag == 'div' )    $tag = '';
        else                    $tag = ' tag="'.$tag.'" ';

        if ( $no_wrapper === false ) $output .= do_shortcode( '[is '.$tag.' itemtype="'.$itemtype.'" itemprop="geo" start /]' );

        $output .= do_shortcode( '[ip name="lat"]'.$lat.'[/ip]' );
        $output .= do_shortcode( '[ip name="lon"]'.$lon.'[/ip]' );
        $output .= do_shortcode( '[ip name="ICBM"]'.$lat.','.$lon.'[/ip]');
        $output .= do_shortcode( '[ip name="geo.position"]'.$lat.';'.$lon.'[/ip]');

        if ( $region !== '' )
            $output .= do_shortcode( '[ip name="geo.region"]'.$region.'[/ip]');
        if ( $placename !== '' )
            $output .= do_shortcode( '[ip name="geo.placename"]'.$placename.'[/ip]');

        $output .= do_shortcode( $content );
        if ( $no_wrapper === false ) $output .= do_shortcode( '[is '.$tag.' end /]' );

    };

    return $output;

};
add_shortcode( 'meta_geo_coordinates', 'insertGeoCoordinatesMetas' );




/**
 * itemscope_mce_shortcode
 *
 * @since 1.3
 */

class itemscope_mce_shortcode {
    /**
     * $shortcode_tag
     * holds the name of the shortcode tag
     * @var string
     */
    public $shortcode_tag = 'ss_is';

    /**
     * __construct
     * class constructor will set the needed filter and action hooks
     *
     * @param array $args
     */
    function __construct($args = array()){
        //add shortcode
        add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );

        if ( is_admin() ){
            add_action('admin_head', array( $this, 'admin_head') );
            add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
        }
    }

    /**
     * shortcode_handler
     * @param  array  $atts shortcode attributes
     * @param  string $content shortcode content
     * @return string
     */
    function shortcode_handler($atts , $content = null){
        // Attributes
        /*extract( shortcode_atts(
            array(

                'itemprop' => '', // cuando este esquema es hijo de otro esquema superior
                'itemtype' => '', // LocalBusiness, PostalAddress, Product
                'tag' => 'div',
                'start' => '',
                'end' => '',

            ), $atts )
               );

        $output = '';

        $output .= do_shortcode('[itemscope tag="'.$tag.'" itemprop="'.$itemprop.'" itemtype="'.$itemtype.'" start /]');
        $output .= do_shortcode('[itemscope tag="'.$tag.'" end /]');
        return $output;*/
        return insertSchema( $atts, $content );
    }
    //add_shortcode( 'ss_ip', 'shortcode_handler' );

    /**
     * admin_head
     * calls your functions into the correct filters
     * @return [type] [description]
     */
    function admin_head() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }

        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
        }
    }

    /**
     * mce_external_plugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->shortcode_tag] = plugins_url( 'js/simpleschema-free-mce-itemscope.js' , __FILE__ );
        return $plugin_array;
    }

    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }

    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    function admin_enqueue_scripts(){
        wp_enqueue_style('itemscope_mce_shortcode', plugins_url( 'css/simpleschema-free-mce-itemscope.css' , __FILE__ ) );
    }
}//end class
new itemscope_mce_shortcode();

/**
 * itemprop_mce_shortcode
 *
 * @since 1.3
 */

class itemprop_mce_shortcode { 
    /**
     * $shortcode_tag
     * holds the name of the shortcode tag
     * @var string
     */
    public $shortcode_tag = 'ss_ip';

    /**
     * __construct
     * class constructor will set the needed filter and action hooks
     *
     * @param array $args
     */
    function __construct($args = array()){
        //add shortcode
        add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );

        if ( is_admin() ){
            add_action('admin_head', array( $this, 'admin_head') );
            add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
        }
    }

    /**
     * shortcode_handler
     * @param  array  $atts shortcode attributes
     * @param  string $content shortcode content
     * @return string
     */
    function shortcode_handler($atts , $content = null){
        return insertProperty( $atts, $content );
    }
    //add_shortcode( 'ss_ip', 'shortcode_handler' );

    /**
     * admin_head
     * calls your functions into the correct filters
     * @return [type] [description]
     */
    function admin_head() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }

        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
        }
    }

    /**
     * mce_external_plugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->shortcode_tag] = plugins_url( 'js/simpleschema-free-mce-itemprop.js' , __FILE__ );
        return $plugin_array;
    }

    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }

    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    function admin_enqueue_scripts(){
        wp_enqueue_style('itemprop_mce_shortcode', plugins_url( 'css/simpleschema-free-mce-itemprop.css' , __FILE__ ) );
    }
}//end class
new itemprop_mce_shortcode();


/**
 * keywords_mce_shortcode
 *
 * @since 1.3
 */

class keywords_mce_shortcode {
    /**
     * $shortcode_tag
     * holds the name of the shortcode tag
     * @var string
     */
    public $shortcode_tag = 'ss_kw';

    /**
     * __construct
     * class constructor will set the needed filter and action hooks
     *
     * @param array $args
     */
    function __construct($args = array()){
        //add shortcode
        add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );

        if ( is_admin() ){
            add_action('admin_head', array( $this, 'admin_head') );
            add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
        }
    }

    /**
     * shortcode_handler
     * @param  array  $atts shortcode attributes
     * @param  string $content shortcode content
     * @return string
     */
    function shortcode_handler($atts , $content = null){
        return insertKeywords( $atts, $content );
    }
    //add_shortcode( 'ss_kw', 'shortcode_handler' );

    /**
     * admin_head
     * calls your functions into the correct filters
     * @return [type] [description]
     */
    function admin_head() {
        // check user permissions
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }

        // check if WYSIWYG is enabled
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
            add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
        }
    }

    /**
     * mce_external_plugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
        $plugin_array[$this->shortcode_tag] = plugins_url( 'js/simpleschema-free-mce-keywords.js' , __FILE__ );
        return $plugin_array;
    }

    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
        array_push( $buttons, $this->shortcode_tag );
        return $buttons;
    }

    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    function admin_enqueue_scripts(){
        wp_enqueue_style('keywords_mce_shortcode', plugins_url( 'css/simpleschema-free-mce-keywords.css' , __FILE__ ) );
    }
}//end class
new keywords_mce_shortcode();


/**
 * LocalBusiness Schema Widget
 *
 * @since 1.6
 */

$LocalBusiness_Schema_fields = array(

    array('name' => 'namePrefix', 'default' => 'Our', 'inputType' => 'text' ),
    array('name' => 'name', 'default' => 'LocalBusiness', 'inputType' => 'text' ),
    array('name' => 'nameSuffix', 'default' => 'Offices', 'inputType' => 'text' ),
    array('name' => 'legalName', 'default' => '', 'inputType' => 'text' ),

    // addres = PostalAddress Schema
    array('name' => 'streetAddress', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'postalCode', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'addressLocality', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'addressRegion', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'addressCountry', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'postOfficeBoxNumber', 'default' => '', 'inputType' => 'text' ),

    // geo = GeoCoordinates Schema
    array('name' => 'latitude', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'longitude', 'default' => '', 'inputType' => 'text' ),

    // contactPoint = ContactPoint Schema
    array('name' => 'telephoneCountryPrefix', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'telephone', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'faxNumber', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'email', 'default' => '', 'inputType' => 'text' ),

    // Others:
    array('name' => 'url', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'openingHours_label', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'openingHours', 'default' => '', 'inputType' => 'text' ), //<time itemprop="openingHours" datetime="Mo,Tu,We,Th,Fr,Sa,Su 09:00-14:00">daily 9am-2pm</time>  ),
    /** http://schema.org/openingHours:
     *
     * The opening hours for a business can be specified as a weekly time range, starting with days, then times per day. Multiple days can be listed with commas ',' separating each day. Day or time ranges are specified using a hyphen '-'.
     * - Days are specified using the following two-letter combinations: Mo, Tu, We, Th, Fr, Sa, Su.
     * - Times are specified using 24:00 time. For example, 3pm is specified as 15:00.
     * - Here is an example: <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
     * - If a business is open 7 days a week, then it can be specified as <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.
     */

    /**
     * Considering properties for next versions:
     *
     * // LocalBusiness --> openingHoursSpecification = OpeningHoursSpecification Schema
     * // contactPoint --> ContactPoint hoursAvailable = OpeningHoursSpecification Schema
     * 'dayOfWeek' => '',
     * 'opens' => '',
     * 'closes' => '',
     */

    array('name' => 'block_id', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'block_class', 'default' => '', 'inputType' => 'text' ),
    array('name' => 'block_css', 'default' => '', 'inputType' => 'textarea' ),

);

class LocalBusiness_Schema_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'localbusiness_schema_widget',

            // name of the widget
            'SimpleSchema LocalBusiness Schema',

            // widget options
            array (
                'description' => 'This widget will turn your inserted local business data into a Schema.org microformats block.'
            )

        );
    }

    function form( $instance ) {

        global $LocalBusiness_Schema_fields;
        $fields = $LocalBusiness_Schema_fields;

        $defaults = array();
        foreach( $fields as $field ){
            $defaults[ $field[ 'name' ] ] = $field[ 'default' ];
        };

        //$depth = $instance[ 'depth' ];
        extract( $defaults );
        foreach( $fields as $field ){
            $this->$field[ 'name' ] = $instance[ $field[ 'name' ] ];
        };

        // markup for form

        foreach( $fields as $field ){

            $field_name = $field[ 'name' ];
            $friendly_name = strtoupper( substr( $field_name, 0, 1 ) ) . substr( $field_name, 1, strlen( $field_name ) - 1 );
            $field_default = $field[ 'default' ];
            $field_type = $field[ 'inputType' ];

            $placeholder = $field_default;
            if( $field_name == "openingHours" )
                $placeholder = 'Example: Mo,Tu,We,Th,Fr,Sa,Su 09:30-13:30 16:30-20:30';
            if( $field_name == "latitude" || $field_name == "longitude" )
                $placeholder = 'Only numeric, like 39.654987 or -0.321654';

?>
<p><?php if( $field_name == "block_id" || $field_name == "telephoneCountryPrefix" || $field_name == "streetAddress" ) echo '<br><br>'; ?>

    <label for="<?php echo $field_name; ?>"><?php echo $friendly_name; ?>:</label>

    <?php if ( $field_type !== 'textarea' ) : ?>
    <input class="widefat" type="<?php echo $field_type; ?>" placeholder="<?php echo $placeholder; ?>" id="<?php echo $this->get_field_id( $field_name ); ?>" name="<?php echo $this->get_field_name( $field_name );; ?>" value="<?php echo esc_attr( $this->$field_name ); ?>">
    <?php endif;?>

    <?php if ( $field_type == 'textarea' ) : ?>
    <textarea class="widefat" placeholder="<?php echo $placeholder; ?>" id="<?php echo $this->get_field_id( $field_name ); ?>" name="<?php echo $this->get_field_name( $field_name );; ?>" value="<?php echo esc_attr( $this->$field_name ); ?>"><?php echo esc_attr( $this->$field_name ); ?></textarea>
    <?php endif;?>

</p>
<?php
        };

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance[ 'block_id' ] = strip_tags( $new_instance[ 'block_id' ] );
        $instance[ 'block_class' ] = strip_tags( $new_instance[ 'block_class' ] );
        $instance[ 'block_css' ] = strip_tags( $new_instance[ 'block_css' ] );

        $instance[ 'namePrefix' ] = strip_tags( $new_instance[ 'namePrefix' ] );
        $instance[ 'name' ] = strip_tags( $new_instance[ 'name' ] );
        $instance[ 'nameSuffix' ] = strip_tags( $new_instance[ 'nameSuffix' ] );
        $instance[ 'legalName' ] = strip_tags( $new_instance[ 'legalName' ] );

        // addres = PostalAddress Schema
        $instance[ 'streetAddress' ] = strip_tags( $new_instance[ 'streetAddress' ] );
        $instance[ 'postalCode' ] = strip_tags( $new_instance[ 'postalCode' ] );
        $instance[ 'addressLocality' ] = strip_tags( $new_instance[ 'addressLocality' ] );
        $instance[ 'addressRegion' ] = strip_tags( $new_instance[ 'addressRegion' ] );
        $instance[ 'addressCountry' ] = strip_tags( $new_instance[ 'addressCountry' ] );
        $instance[ 'postOfficeBoxNumber' ] = strip_tags( $new_instance[ 'postOfficeBoxNumber' ] );

        // geo = GeoCoordinates Schema
        $instance[ 'latitude' ] = strip_tags( $new_instance[ 'latitude' ] );
        $instance[ 'longitude' ] = strip_tags( $new_instance[ 'longitude' ] );

        // contactPoint = ContactPoint Schema
        $instance[ 'email' ] = strip_tags( $new_instance[ 'email' ] );
        $instance[ 'telephoneCountryPrefix' ] = strip_tags( $new_instance[ 'telephoneCountryPrefix' ] );
        $instance[ 'telephone' ] = strip_tags( $new_instance[ 'telephone' ] );
        $instance[ 'faxNumber' ] = strip_tags( $new_instance[ 'faxNumber' ] );

        // Others:
        $instance[ 'url' ] = strip_tags( $new_instance[ 'url' ] );
        $instance[ 'openingHours_label' ] = strip_tags( $new_instance[ 'openingHours_label' ] );
        $instance[ 'openingHours' ] = strip_tags( $new_instance[ 'openingHours' ] );

        return $instance;

    }

    function widget( $args, $instance ) {

        extract( $args );
        $output = '';
        $output .= $before_widget;
        $output .= $before_title . '' . $after_title;

        $output .= '<div id="' . $instance[ 'block_id' ] . '" class="' . $instance[ 'block_class' ] . '" itemscope itemType="http://schema.org/LocalBusiness">';
        if( $instance[ 'block_css' ] !== '' )
            $output .= '<style>' . $instance[ 'block_css' ] . '</style>';

        if( $instance[ 'name' ] !== '' || $instance[ 'legalName' ] !== '' ) {
            $output .= '<p class="ss_name_p">';
            $output .= $instance[ 'namePrefix' ] . ' <span itemprop="name">' . $instance[ 'name' ] . '</span> ' . $instance[ 'nameSuffix' ];
            if( $instance[ 'legalName' ] !== '' ) {
                if( $instance[ 'name' ] !== '' ){
                    if ( $instance[ 'name' ] == $instance[ 'legalName' ] )
                        $output .= '<meta itemprop="legalName" content="' . $instance[ 'legalName' ] . '"/>';
                    else 
                        $output .= '<br><small itemprop="legalName">' . $instance[ 'legalName' ] . '</small>';
                }
                else
                    $output .= '<p itemprop="legalName">' . $instance[ 'legalName' ] . '</p>';
            }
            $output .= '</p>';
        }

        // address = PostalAddress Schema
        $output .= '<div class="ss_postaladdress" itemprop="address" itemscope itemType="http://schema.org/PostalAddress">';
        if( $instance[ 'streetAddress' ] !== '' )
            $output .= '<p class="ss_postaladdress_p" itemprop="streetAddress">' . $instance[ 'streetAddress' ] . '</p>';
        if( $instance[ 'postalCode' ] !== '' || $instance[ 'addressLocality' ] !== '') {
            $output .= '<p class="ss_postaladdress_p">';
            if( $instance[ 'postalCode' ] !== '' )
                $output .= '<span itemprop="postalCode">' . $instance[ 'postalCode' ] . '</span> ';
            if( $instance[ 'addressLocality' ] !== '' )
                $output .= '<span itemprop="addressLocality">' . $instance[ 'addressLocality' ] . '</span>';
            $output .= '</p>';
        };
        if( $instance[ 'addressRegion' ] !== '' || $instance[ 'addressCountry' ] !== '') {
            $output .= '<p class="ss_postaladdress_p">';
            if( $instance[ 'addressRegion' ] !== '' )
                $output .= '<span itemprop="addressRegion">' . $instance[ 'addressRegion' ] . '</span> ';
            if( $instance[ 'addressCountry' ] !== '' )
                $output .= '<span itemprop="addressCountry">(' . $instance[ 'addressCountry' ] . ')</span>';
            $output .= '</p>';
        };
        if( $instance[ 'postOfficeBoxNumber' ] !== '' )
            $output .= '<p class="ss_postaladdress_p" itemprop="postOfficeBoxNumber">' . $instance[ 'postOfficeBoxNumber' ] . '</p>';
        $output .= '</div>';

        if( $instance[ 'openingHours' ] !== '' )
            $output .= '<p><time itemprop="openingHours" datetime=' . $instance[ 'openingHours' ] . '">' . $instance[ 'openingHours_label' ] . '</time></p>';

        // geo = GeoCoordinates Schema
        if( $instance[ 'latitude' ] !== '' && $instance[ 'longitude' ] !== '') {
            $output .= '<div itemprop="geo" itemscope itemType="http://schema.org/GeoCoordinates">';
            if( $instance[ 'latitude' ] !== '' )
                $output .= '<meta itemprop="latitude" content="' . $instance[ 'latitude' ] . '"/>';
            if( $instance[ 'longitude' ] !== '' )
                $output .= '<meta itemprop="longitude" content="' . $instance[ 'longitude' ] . '"/>';
            $output .= '</div>';
        };

        // contactPoint = ContactPoint Schema
        $output .= '<div class="ss_contactpoint">';// itemprop="contactPoint" itemscope itemType="http://schema.org/ContactPoint">';
        $output .= '<meta itemprop="contactType" content="Atención al cliente"/>';
        if( $instance[ 'telephone' ] !== '' ) {

            $phone_number = $instance[ 'telephone' ];
            $phone_number = str_replace( ' ', '-', $phone_number );
            $phone_prefix = $instance[ 'telephoneCountryPrefix' ];
            $phone_prefix = ( strpos( $phone_prefix, '+' ) !== false && strpos( $phone_prefix, '+' ) === 0 )? $phone_prefix : '+'.$phone_prefix;

            $output .= '<p><a itemprop="telephone" href="tel:'.$phone_prefix.'-'.$phone_number.'">(' . $phone_prefix . ') ' . $instance[ 'telephone' ] . '</a></p>';
        }
        if( $instance[ 'faxNumber' ] !== ''  )
            $output .= '<p itemprop="faxNumber">(' . $instance[ 'telephoneCountryPrefix' ] . ') ' . $instance[ 'faxNumber' ] . '</p>';
        if( $instance[ 'email' ] !== '' )
            $output .= '<p><a itemprop="email" href="mailto:' . $instance[ 'email' ] . '" title="Mail to ' . $instance[ 'name' ] . '">' . $instance[ 'email' ] . '</a></p>';
        $output .= '</div>';

        // Others:
        if( $instance[ 'url' ] !== '' ) {
            $url = $instance[ 'url' ];
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            if ( strpos( $url, 'http' ) === false || strpos( $url, 'http' ) > 0 ) $url = $protocol . $url;
            $output .= '<p class="ss_url_p"><a itemprop="url" href="' . $url . '" title="' . $instance[ 'name' ] . '">' . $instance[ 'url' ] . '</a></p>';
        };

        $output .= '</div>';
        $output .= $after_widget;
        return $output;

    }

}

function register_localbusiness_schema_widget() {

    register_widget( 'LocalBusiness_Schema_Widget' );

}
add_action( 'widgets_init', 'register_localbusiness_schema_widget' );



/**
 * LocalBusiness Services Schema Widget
 *
 * @since 1.7
 */


class Services_Schema_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(

            // base ID of the widget
            'services_schema_widget',

            // name of the widget
            'SimpleSchema Services Schema',

            // widget options
            array (
                'description' => 'This widget will turn your inserted services data into a Schema.org microformats block.'
            )

        );
    }

    function form( $instance ) {

        $defaults = array(
            'title' => '',
            'block_id' => '',
            'block_class' => '',
            'block_css' => '',
            'serviceType' => '',
            'provider_name' => '',
            'services_data' => '',
            'areas_data' => '',
            'areas_scope' => 'City',
        );

        $serviceType = $instance['serviceType'];
        $provider_name = $instance['provider_name'];
        $services_data = $instance['services_data'];
        $areas_data = $instance['areas_data'];
        $areas_scope = $instance['areas_scope'];

        $title = $instance[ 'title' ];
        $block_id = $instance[ 'block_id' ];
        $block_class = $instance[ 'block_class' ];
        $block_css = $instance[ 'block_css' ];

        try{
?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'serviceType' ); ?>">Service type: <abbr title="Mandatory: without this parameter, the block will make no sense.">[?]</abbr></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'serviceType' ); ?>" name="<?php echo $this->get_field_name( 'serviceType' ); ?>" value="<?php echo esc_attr( $serviceType ); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'provider_name' ); ?>">(LocalBusiness) Provider name: <abbr title="Mandatory: without this parameter, the block will make no sense.">[?]</abbr></label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'provider_name' ); ?>" name="<?php echo $this->get_field_name( 'provider_name' ); ?>" value="<?php echo esc_attr( $provider_name ); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'services_data' ); ?>">Services list:</label>
    <br><small><em>service1_name|service1_url<br>service2_name|service2_url<br>...</em></small>
    <textarea placeholder="Optional if Areas served list is not empty." class="widefat" id="<?php echo $this->get_field_id( 'services_data' ); ?>" name="<?php echo $this->get_field_name( 'services_data' ); ?>"><?php echo $services_data; ?></textarea>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'areas_data' ); ?>">Areas served list:</label>
    <br><small><em>area1_name|area1_url<br>area2_name|area2_url<br>...</em></small>
    <textarea placeholder="Optional if Services list is not empty." class="widefat" id="<?php echo $this->get_field_id( 'areas_data' ); ?>" name="<?php echo $this->get_field_name( 'areas_data' ); ?>"><?php echo $areas_data; ?></textarea>
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'areas_scope' ); ?>">Areas served scope: <abbr title='This parameter applies to all area served. Otherwise, you can add an extraordinary "|scope" parameter for customized scope for each area served, like this example: "area1_name|area1_url|State".'>[?]</abbr></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'areas_scope' ); ?>" name="<?php echo $this->get_field_name( 'areas_scope' ); ?>" >
        <option value="City" <?php echo ($areas_scope=='City') ? 'selected':''; ?>>City</option>
        <option value="State" <?php echo ($areas_scope=='State') ? 'selected':''; ?>>State</option>
        <option value="Country" <?php echo ($areas_scope=='Country') ? 'selected':''; ?>>Country</option>
    </select>
</p>
<br>
<p>
    <label for="<?php echo $this->get_field_id( 'block_id' ); ?>">Block Id:</label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'block_id' ); ?>" name="<?php echo $this->get_field_name( 'block_id' ); ?>" value="<?php echo esc_attr( $block_id ); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'block_class' ); ?>">Block Class:</label>
    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'block_class' ); ?>" name="<?php echo $this->get_field_name( 'block_class' ); ?>" value="<?php echo esc_attr( $block_class ); ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'block_css' ); ?>">Block CSS:</label>
    <textarea class="widefat" id="<?php echo $this->get_field_id( 'block_css' ); ?>" name="<?php echo $this->get_field_name( 'block_css' ); ?>"><?php echo $block_css; ?></textarea>
</p>

<?php
           }catch (Exception $ex){ echo '<p>Error</p>'; }

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        //$instance[ 'areas_scope' ] = ( ! empty( $new_instance['areas_scope'] ) ) ? strip_tags( $new_instance['areas_scope'] ) : ''; // $new_instance[ 'areas_scope' ];

        $instance['serviceType'] = $new_instance['serviceType'];
        $instance['provider_name'] = $new_instance['provider_name'];
        $instance['services_data'] = $new_instance['services_data'];
        $instance['areas_data'] = $new_instance['areas_data'];
        $instance['areas_scope'] = $new_instance['areas_scope'];

        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'block_id' ] = strip_tags( $new_instance[ 'block_id' ] );
        $instance[ 'block_class' ] = strip_tags( $new_instance[ 'block_class' ] );
        $instance[ 'block_css' ] = strip_tags( $new_instance[ 'block_css' ] );

        return $instance;

    }

    function widget( $args, $instance ) {

        extract( $args );
        $output = '';
        $output .= $before_widget;
        $output .= $before_title . $instance[ 'title' ] . $after_title;

        //$output .= '<h3>' . $instance[ 'title' ] . '</h3>';
        $output .= '<div id="' . $instance[ 'block_id' ] . '" class="' . $instance[ 'block_class' ] . '" itemscope itemType="http://schema.org/Service">';

        if( $instance[ 'block_css' ] !== '' )
            $output .= '<style>' . $instance[ 'block_css' ] . '</style>';

        $output .= '<meta itemprop="serviceType" content="'.$instance['serviceType'].'" />';
        $output .= '<div itemprop="provider" itemscope itemtype="http://schema.org/LocalBusiness">';
        $output .= '<meta itemprop="name" content="'.$instance['provider_name'].'"/>';
        $output .= '</div>';

        $services_data = preg_split('/\r\n|[\r\n]/', $instance['services_data'] );

        if ( !empty( $services_data ) && $services_data[0] !== "" ) {
            $output .= '<ul class="ss-services-list" itemprop="hasOfferCatalog" itemscope itemtype="http://schema.org/OfferCatalog">';
            foreach($services_data as $service_data) {
                $service_data = explode( '|', trim( $service_data ) );
                $output .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/Offer" data-count="'.count($service_data).'">';
                $output .= '<div itemprop="itemOffered" itemscope itemtype="http://schema.org/Service">';
                if ( count($service_data) > 1 ) $output .= '<a href="'.$service_data[1].'" itemprop="url">';
                $output .= '<span itemprop="name">'.$service_data[0].'</span>';
                if ( count($service_data) > 1 ) $output .= '</a>';
                $output .= '</div>';
                $output .= '</li>';
            }
            $output .= '</ul>';

        };


        $areas_data = preg_split('/\r\n|[\r\n]/', $instance['areas_data'] );

        if ( !empty( $areas_data ) && $areas_data[0] !== "" ) {

            $output .= '<ul class="ss-areas-served-list">';
            foreach( $areas_data as $area_data ) {
                $this_area_data = explode( '|', trim( $area_data ) );
                $this_area_scope = $instance['areas_scope'];
                if ( isset( $this_area_data[2] ) ) $this_area_scope = $this_area_data[2];
                $output .= '<div itemprop="areaServed" itemscope itemtype="http://schema.org/'.$this_area_scope.'">
                    <a href="'.$this_area_data[1].'" itemprop="url">
                        <span itemprop="name">'.$this_area_data[0].'</span></a></div>';
            }
            $output .= '</ul>';

        };


        $output .= '</div>';
        $output .= $after_widget;
        return $output;

    }

}

function register_services_schema_widget() {

    register_widget( 'Services_Schema_Widget' );

}
add_action( 'widgets_init', 'register_services_schema_widget' );

?>
