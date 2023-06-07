<?php

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

	/* Prevent direct access to the template (always good to include this) */
	if ( ! class_exists( 'GFForms' ) ) {
		return;
	}

	/**
	 * All Gravity PDF v4/v5/v6 templates have access to the following variables:
	 *
	 * @var array  $form      The current Gravity Forms array
	 * @var array  $entry     The raw entry data
	 * @var array  $form_data The processed entry data stored in an array
	 * @var object $settings  The current PDF configuration
	 * @var array  $fields    An array of Gravity Forms fields which can be accessed with their ID number
	 * @var array  $config    The initialised template config class – eg. /config/zadani.php
	 */

    $nested_form_id = 113;
    $nested_form_entry_id_string = $form_data['field'][9];
    $nested_form_entry_ids = explode(',', $nested_form_entry_id_string);

    foreach ( $nested_form_entry_ids as $entry_id ) {
    $entry = GFAPI::get_entry( $entry_id );
    echo "Entry: ";
    echo print_r($entry, true);
    }

    echo "Nested form entry IDs: $nested_form_entry_ids";

?>

<!-- Any PDF CSS styles can be placed in the style tag below -->
<style>

</style>
<!-- The PDF content should be placed in here -->

<pre>
	<?php
		echo print_r($form_data, true);
	?>
</pre>

<h5 style="text-align: center">End of File</h5>

<?php echo <<<EOF
    <h1>Test</h1>    
EOF;
?>