<?php /** @noinspection DuplicatedCode */
global $value_border_colour;

/**
 * Template Name: TJG KidKit PDF
 * Version: 1.0
 * Description: Create a printout of the user's account information and all submitted forms.
 * Author: Tyler Karle
 * Author URI: https://thejohnson.group/
 * Group: Custom TJG Templates
 * License: GPL
 * Required PDF Version: 6.0
 * Tags: Header, Footer, Background, Optional HTML Fields, Optional Page Fields, Container Background Color
 */

/**
 * All Gravity PDF v4/v5/v6 templates have access to the following variables:
 *
 * @var array $form The current Gravity Forms array
 * @var array $entry The raw entry data
 * @var array $form_data The processed entry data stored in an array
 * @var object $settings The current PDF configuration
 * @var array $fields An array of Gravity Forms fields which can be accessed with their ID number
 * @var array $config The initialised template config class – eg. /config/zadani.php
 */

/* Prevent direct access to the template (always good to include this) */
if (!class_exists('GFForms')) {
    return;
}

/*
 * Load our core-specific styles from our PDF settings which will be passed to the PDF template $config array
 */
$show_form_title = ($settings['show_form_title'] ?? '') === 'Yes';
$show_page_names = ($settings['show_page_names'] ?? '') === 'Yes';
$show_html = ($settings['show_html'] ?? '') === 'Yes';
$show_section_content = ($settings['show_section_content'] ?? '') === 'Yes';
$enable_conditional = ($settings['enable_conditional'] ?? '') === 'Yes';
$show_empty = ($settings['show_empty'] ?? '') === 'Yes';

/**
 * Set up our configuration array to control what is and is not shown in the generated PDF
 */
$html_config = [
    'settings' => $settings,
    'meta' => [
        'echo' => false,
        /* whether to output the HTML or return it */
        'exclude' => true,
        /* whether we should exclude fields with a CSS value of 'exclude'. Default to true */
        'empty' => $show_empty,
        /* whether to show empty fields or not. Default is false */
        'conditional' => $enable_conditional,
        /* whether we should skip fields hidden with conditional logic. Default to true. */
        'show_title' => $show_form_title,
        /* whether we should show the form title. Default to true */
        'section_content' => $show_section_content,
        /* whether we should include a section breaks content. Default to false */
        'page_names' => $show_page_names,
        /* whether we should show the form's page names. Default to false */
        'html_field' => $show_html,
        /* whether we should show the form's html fields. Default to false */
        'individual_products' => false,
        /* Whether to show individual fields in the entry. Default to false - they are grouped together at the end of the form */
        'enable_css_ready_classes' => true,
        /* Whether to enable or disable Gravity Forms CSS Ready Class support in your PDF */
    ],
];

$primary_form_id = 112;
$nested_form_id = 113;
$nested_form_entry_id_string = $form_data['field'][9];
$nested_form_entry_ids = explode(',', $nested_form_entry_id_string);

$user_full_name = ucwords($form_data['field'][3]['first'] . ' ' . $form_data['field'][3]['last']);
$email = $form_data['field']['Email'];
$phone = $form_data['field']['Phone'];
$subheading = "<a href='mailto:$email'>$email</a> | <a href='tel:$phone'>$phone</a>";

$street_one = $form_data['field']['Address']['street'] ?? '';
$street_two = $form_data['field']['Address']['street2'] ?? '';
$city = $form_data['field']['Address']['city'] ?? '';
$state = $form_data['field']['Address']['state'] ?? '';
$zip = $form_data['field']['Address']['zip'] ?? '';
$country = $form_data['field']['Address']['country'] ?? '';

$user_address = <<<EOT
<ul style="list-style-type: none">
    <li>$street_one</li>
    <li>$street_two</li>
    <li>$city, $state $zip</li>
    <li>$country</li>
</ul>
EOT;

//echo "Nested form entry IDs: $nested_form_entry_ids";

?>
<!-- Any PDF CSS styles can be placed in the style tag below -->
<!-- Include styles needed for the PDF -->
<!--suppress CssUnusedSymbol, CssReplaceWithShorthandSafely -->
<style xmlns="http://www.w3.org/1999/html">
    /* Handle Gravity Forms CSS Ready Classes */
    .row-separator {
        clear: both;
        padding: 1.25mm 0;
    }

    /* Handle GF2.5+ Columns */
    .grid {
        float: <?php echo ($settings['rtl'] ?? 'No') === 'Yes' ? 'right' : 'left'; ?>;
    }

    .grid .inner-container {
        width: 95%;
    }

    .grid-3 {
        width: 25%;
    }

    .grid-4 {
        width: 33.33%;
    }

    .grid-5 {
        width: 41.66%;
    }

    .grid-6 {
        width: 50%;
    }

    .grid-7 {
        width: 58.33%;
    }

    .grid-8 {
        width: 66.66%;
    }

    .grid-9 {
        width: 75%
    }

    .grid-10 {
        width: 83.33%;
    }

    .grid-11 {
        width: 91.66%;
    }

    .grid-12,
    .grid-12 .inner-container {
        width: 100%;
    }

    /* Handle Legacy Columns */
    .gf_left_half,
    .gf_left_third,
    .gf_middle_third,
    .gf_first_quarter,
    .gf_second_quarter,
    .gf_third_quarter,
    .gf_list_2col li,
    .gf_list_3col li,
    .gf_list_4col li,
    .gf_list_5col li {
        float: left;
    }

    .gf_right_half,
    .gf_right_third,
    .gf_fourth_quarter {
        float: right;
    }

    .gf_left_half,
    .gf_right_half,
    .gf_list_2col li {
        width: 49%;
    }

    .gf_left_third,
    .gf_middle_third,
    .gf_right_third,
    .gf_list_3col li {
        width: 32.3%;
    }

    .gf_first_quarter,
    .gf_second_quarter,
    .gf_third_quarter,
    .gf_fourth_quarter {
        width: 24%;
    }

    .gf_list_4col li {
        width: 24%;
    }

    .gf_list_5col li {
        width: 19%;
    }

    .gf_left_half,
    .gf_right_half {
        padding-right: 1%;
    }

    .gf_left_third,
    .gf_middle_third,
    .gf_right_third {
        padding-right: 1.505%;
    }

    .gf_first_quarter,
    .gf_second_quarter,
    .gf_third_quarter,
    .gf_fourth_quarter {
        padding-right: 1.333%;
    }

    .gf_right_half,
    .gf_right_third,
    .gf_fourth_quarter {
        padding-right: 0;
    }

    /* Don't double float the list items if already floated (mPDF does not support this ) */
    .gf_left_half li,
    .gf_right_half li,
    .gf_left_third li,
    .gf_middle_third li,
    .gf_right_third li {
        width: 100% !important;
        float: none !important;
    }

    /*
	 * Headings
	 */
    h3 {
        margin: 1.5mm 0 0.5mm;
        padding: 0;
    }

    /*
	 * Quiz Style Support
	 */
    .gquiz-field {
        color: #666;
    }

    .gquiz-correct-choice {
        font-weight: bold;
        color: black;
    }

    .gf-quiz-img {
        padding-left: 5px !important;
        vertical-align: middle;
    }

    /*
	 * Survey Style Support
	 */
    .gsurvey-likert-choice-label {
        padding: 4px;
    }

    .gsurvey-likert-choice,
    .gsurvey-likert-choice-label {
        text-align: center;
    }

    /*
	 * Terms of Service (Gravity Perks) Support
	 */
    .terms-of-service-agreement {
        padding-top: 3px;
        font-weight: bold;
    }

    .terms-of-service-tick {
        font-size: 150%;
    }

    /*
	 * List Support
	 */
    ul,
    ol {
        margin: 0;
        padding-left: 1mm;
        padding-right: 1mm;
    }

    li {
        margin: 0;
        padding: 0;
        list-style-position: inside;
    }

    /*
	 * Header / Footer
	 */
    .alignleft {
        float: left;
    }

    .alignright {
        float: right;
    }

    .aligncenter {
        text-align: center;
    }

    p.alignleft {
        text-align: left;
        float: none;
    }

    p.alignright {
        text-align: right;
        float: none;
    }

    /*
	 * Independent Template Styles
	 */
    .gfpdf-field .label {
        text-transform: uppercase;
        font-size: 90%;
    }

    .gfpdf-field .value {
        border: 1px solid #000;
        border-color: <?php echo esc_html($value_border_colour); ?>;
        padding: 1.5mm 2mm;
    }

    .products-title-container,
    .products-container {
        padding: 0;
    }

    .products-title-container h3 {
        margin-bottom: -0.5mm;
    }
</style>

<!-- The PDF content should be placed in here -->
<div style="
    background-size: cover;
    background: url('https://thejohnson.group/wp-content/uploads/2023/06/myChildFrontPage.jpg') no-repeat;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    display:flex;
">
    <p style="font-size: 3em; font-weight: bold; color: #30A24D; margin-top: 90%; margin-bottom: 0; text-align: center; width: 100%;">
        <?php echo $user_full_name; ?> <br/>
    </p>
</div>

<!--suppress CheckEmptyScriptTag -->
<pagebreak/>

<div style="display: grid">
    <div style="grid-column: 1; grid-row: 1; background-color: #30A24D; color: #fff; padding: 1em; font-size: 1.5em; font-weight: bold; text-align: center;">
        <p style="margin: 0;">Your Information</p>
    </div>
    <!--   content block -->
    <div style="grid-column: 1; grid-row: 2; background-color: #fff; color: #000; padding: 1em; font-size: 1em;display: inline-grid">
        <div style="border-radius: 12px; border: 1px solid #30A24D; padding: 1em; font-size: 1.5em;  text-align: center;">
            <?php echo $user_full_name; ?>
            <br/>
            <?php echo $user_address; ?>
            <br/>
            <?php echo $subheading; ?>

        </div>
    </div>
</div>

<?php
foreach ($nested_form_entry_ids as $entry_id) {
    // begin list of fields to display
    $e = GFAPI::get_entry($entry_id);

    $first_name = ucwords($e['3.3']) ?? '';
    $last_name = ucwords($e['3.6']) ?? '';
    $nickname = ucwords($e['4']) ?? '';
    $gender = ucwords($e['5']) ?? '';
    $date_of_birth = $e['6'] ?? '';

    $photos_field = $e['21'];
    $fingerprints_field = $e['35'];

    $address = ($e['8'] == 'Yes') ? $form_data['field'][6] : '';
    $pdf = GPDFAPI::get_pdf_class();
    $output = $pdf->process_html_structure($e, GPDFAPI::get_pdf_class('model'), $html_config);

    $page = <<<PAGE
    <pagebreak/>
    <div style="display: grid">
    <div style="grid-column: 1; grid-row: 1; background-color: #30A24D; color: #fff; padding: 1em; font-size: 1.5em; font-weight: bold; text-align: center;">
        <p style="margin: 0;">$first_name $last_name</p>
    </div>
    <!-- content -->
    <div>
        $output
    </div>
    
   <!-- <div style="grid-column: 2; grid-row: 3; background-color: #30A24D; color: #fff; padding: 1em; font-size: 1.5em; font-weight: bold; text-align: center;">
        <p style="margin: 0;">Medical Information</p>
    </div> -->
PAGE;

    echo $page; // ???

    echo processImages($photos_field);
}

function processImages(string $urlString): string
{
    $urlArray = explode(',', $urlString);
    $output = '';

    foreach ($urlArray as $url) {
        // strip special characters and quotes and brackets
        $url = str_replace('"', '', $url);
        $url = stripslashes($url);
        $url = str_replace('[', '', $url);
        $url = str_replace(']', '', $url);
        $url = str_replace(home_url('/'), ABSPATH, $url);
        $output .= '<img alt="" title="" src="' . $url . '" style="width: 100%; height: auto;"/><pagebreak/>';
    }

    return $output;
}

?>
