<?php

function parabola_color_sanitize( $color ) {
    if ( '' === $color ) return '';
	$color = trim(wp_kses_data($color));
 
    if ( preg_match( '/^#?([A-Fa-f0-9]{3}){1,2}$/', $color ) ) {
        return '#' . preg_replace( '/#/i', '', $color );
    }	
	return '';
} // parabola_color_sanitize()


function parabola_theme_settings_placeholder() { 
	if (function_exists('parabola_theme_settings_restore') ):
			parabola_theme_settings_restore();
	else:
?>
   <div id="parabola-settings-placeholder">
		<h3>Where are the theme settings?</h3>
		<p>According to the <a href="https://make.wordpress.org/themes/2015/04/21/this-weeks-meeting-important-information-regarding-theme-options/" target="_blank">Wordpress Theme Review Guidelines</a>, starting with Parabola v1.6 we had to remove the settings page from the theme and transfer all the settings to the <a href="http://codex.wordpress.org/Appearance_Customize_Screen" target="_blank">Customizer</a> interface.</p>
		<p>However, we feel that the Customizer interface does not provide the right medium (in space of terms and usability) for our existing theme options. We've created our settings with a certain layout that is not really compatible with the Customizer interface.</p>
		<p>As an alternative solution that allows us to keep updating and improving our theme we have moved the settings functionality to the separate <a href="https://wordpress.org/plugins/cryout-theme-settings/" target="_blank">Cryout Serious Theme Settings</a> plugin. To restore the theme settings page to previous functionality, all you need to do is install this free plugin with a couple of clicks.</p>
		<h3>How do I restore the settings?</h3>
		<p><strong>Navigate <a href="themes.php?page=parabola-extra-plugins">to this page</a> to install and activate the Cryout Serious Theme Settings plugin, then return here to find the settings page in all its past glory.</strong></p>
		<p>The plugin is compatible with all our themes that are affected by this change and only needs to be installed once.</p>
   </div>
<?php
	endif;
} // parabola_theme_settings_placeholder()

/**
 * Export Parabola settings to file
 */

function parabola_export_options(){

    if (ob_get_contents()) ob_clean();

	/* Check authorisation */
	$authorised = true;
	// Check nonce
	if ( ! wp_verify_nonce( $_POST['parabola-export'], 'parabola-export' ) ) {
		$authorised = false;
	}
	// Check permissions
	if ( ! current_user_can( 'edit_theme_options' ) ){
		$authorised = false;
	}

	if ( $authorised) {
        global $parabolas;
        date_default_timezone_set('UTC');
        $name = 'parabolasettings-'.preg_replace("/[^a-z0-9-_]/i",'',str_replace("http://","",get_option('siteurl'))).'-'.date('Ymd-His').'.txt';

		$data = $parabolas;
		$data = json_encode( $data );
		$size = strlen( $data );

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="'.$name.'"' );
		header( "Content-Transfer-Encoding: binary" );
		header( 'Accept-Ranges: bytes' );

		/* The three lines below basically make the download non-cacheable */
		header( "Cache-control: private" );
		header( 'Pragma: private' );
		header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );

		header( "Content-Length: " . $size);
		print( $data );
}
die();
}

if ( isset( $_POST['parabola_export'] ) ){
	add_action( 'init', 'parabola_export_options' );
}

/**
 * This file manages the theme settings uploading and import operations.
 * Uses the theme page to create a new form for uplaoding the settings
 * Uses WP_Filesystem
*/
function parabola_import_form(){

    $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
    $size = size_format( $bytes );
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) :
        ?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'parabola'); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
    else :
    ?>

    <div class="wrap">
		<div style="width:400px;display:block;margin-left:30px;">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Parabola Theme Options', 'parabola' );?></h2>
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="">
        	<p><?php _e('Hi! This is where you import the  Parabola settings.<i> Please remember that this is still an experimental feature.</i>', 'parabola'); ?></p>
            <p>
                <label for="upload"><strong><?php _e('Just choose a file from your computer:', 'parabola'); ?> </strong><i>(parabola-settings.txt)</i></label>
		       <input type="file" id="upload" name="import" size="25"  />
				<span style="font-size:10px;">(<?php  printf( __( 'Maximum size: %s', 'parabola' ), $size ); ?> )</span>
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                <?php wp_nonce_field('parabola-import', 'parabola-import'); ?>
                <input type="hidden" name="parabola_import_confirmed" value="true" />
            </p>
            <input type="submit" class="button" value="<?php _e('And import!', 'parabola'); ?>" />
        </form>
	</div>
    </div> <!-- end wrap -->
    <?php
    endif;
} // Closes the parabola_import_form() function definition


/**
 * This actual import of the options from the file to the settings array.
*/
function parabola_import_file() {
    global $parabolas;

    /* Check authorisation */
    $authorised = true;
    // Check nonce
    if (!wp_verify_nonce($_POST['parabola-import'], 'parabola-import')) {$authorised = false;}
    // Check permissions
    if (!current_user_can('edit_theme_options')){ $authorised = false; }

    // If the user is authorised, import the theme's options to the database
    if ($authorised) {?>
        <?php
        // make sure there is an import file uploaded
        if ( (isset($_FILES["import"]["size"]) &&  ($_FILES["import"]["size"] > 0) ) ) {

			$form_fields = array('import');
			$method = '';

			$url = wp_nonce_url('themes.php?page=parabola-page', 'parabola-import');

			// Get file writing credentials
			if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
				return true;
			}

			if ( ! WP_Filesystem($creds) ) {
				// our credentials were no good, ask the user for them again
				request_filesystem_credentials($url, $method, true, false, $form_fields);
				return true;
			}

			// Write the file if credentials are good
			$upload_dir = wp_upload_dir();
			$filename = trailingslashit($upload_dir['path']).'parabolas.txt';

			// by this point, the $wp_filesystem global should be working, so let's use it to create a file
			global $wp_filesystem;
			if ( ! $wp_filesystem->move($_FILES['import']['tmp_name'], $filename, true) ) {
				echo 'Error saving file!';
				return;
			}

			$file = $_FILES['import'];

			if ($file['type'] == 'text/plain') {
				$data = $wp_filesystem->get_contents($filename);
				// try to read the file
				if ($data !== FALSE){
					$settings = json_decode($data, true);
					// try to read the settings array
					if (isset($settings['parabola_db'])){ ?>
        <div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Parabola Theme Options ', 'parabola' );?></h2> <?php
						$settings = array_merge($parabolas, $settings);
						update_option('parabola_settings', $settings);
						echo '<div class="updated fade"><p>'. __('Great! The options have been imported!', 'parabola').'<br />';
						echo '<a href="themes.php?page=parabola-page">'.__('Go back to the Parabola options page and check them out!', 'parabola').'<a></p></div>';
					}
					else { // else: try to read the settings array
						echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'parabola').'</strong><br />';
						echo __('The uploaded file does not contain valid Parabola options. Make sure the file is exported from the Parabola Options page.', 'parabola').'</p></div>';
						parabola_import_form();
					}
				}
				else { // else: try to read the file
					echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'parabola').'</strong><br />';
					echo __('The uploaded file could not be read.', 'parabola').'</p></div>';
					parabola_import_form();
				}
			}
			else { // else: make sure the file uploaded was a plain text file
				echo '<div class="error"><p><strong>'.__('Oops, there\'s a small problem.', 'parabola').'</strong><br />';
				echo __('The uploaded file is not supported. Make sure the file was exported from the Parabola page and that it is a text file.', 'parabola').'</p></div>';
				parabola_import_form();
			}

			// Delete the file after we're done
			$wp_filesystem->delete($filename);

        }
        else { // else: make sure there is an import file uploaded
            echo '<div class="error"><p>'.__( 'Oops! The file is empty or there was no file. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.', 'parabola' ).'</p></div>';
			parabola_import_form();
        }
        echo '</div> <!-- end wrap -->';
    }
    else {
        wp_die(__('ERROR: You are not authorised to perform that operation', 'parabola'));
    }
} // Closes the parabola_import_file() function definition



function parabola_presets(){
?>
<script type="text/javascript">
var scheme_confirmation = '<?php echo esc_html__('Are you sure you want to load a new color scheme? \nAll current saved settings under Text and Color Settings will be lost.','parabola'); ?>'; 
</script>
    <div class="wrap">
		<div id="admin_header"><img src="<?php echo get_template_directory_uri() . '/admin/images/colorschemes-logo.png' ?>" /> </div>
		<div style="display:block;margin-left:30px;clear:both;float:none;">
			<p><em><?php echo _e("Select one of the preset color schemes and press the Load button.<br> <b> CAUTION! </b> When loading a color scheme, the Parabola theme settings under Text and Color Settings will be overriden. All other settings will remain intact.<br> <u>SUGGESTION:</u> It's always better to export your current theme settings before loading a color scheme." , "parabola"); ?></em></p>
			<br>
			<form name="parabola_form" action="options.php" method="post" enctype="multipart/form-data">

	<?php
	settings_fields('parabola_settings');

	global $parabolas;
	global $parabola_colorschemes_array;
	$items = $parabola_colorschemes_array;

	foreach($items as $key=>$item) {
		$id = preg_replace('/[^a-z0-9]/i', '',$key);
		$checkedClass = ($parabolas['parabola_colorschemes']==$item) ? ' checkedClass' : '';
		echo " <label id='$id' for='$id$id' class='images presets $checkedClass'><input ";
			checked($parabolas['parabola_colorschemes'],$item);
		echo " value='$key' id='$id$id' onClick=\"changeBorder('$id','images');\" name='parabola_settings[parabola_colorschemes]' type='radio' /><img class='$id'  src='".get_template_directory_uri()."/admin/images/schemes/{$key}.png'/><p>{$key}</p></label>";
	}

	?>

			<div id="submitDiv" style="width:400px;display:block;margin:0 auto;">
				<br>
				<input type="hidden" value="true" name="parabola_presets_loaded" />
				<input class="button" name="parabola_settings[parabola_schemessubmit]" type="submit" id="load-color-scheme" style="width:400px;height:40px;display:block;text-align:center;" value="<?php _e('Load Color Scheme','parabola'); ?>" />
			</div>
			</form>
		</div>
    </div> <!-- end wrap -->
	<br>
    <?php
} // Closes the parabola_import_form() function definition


// Truncate function for use in the Admin RSS feed
function parabola_truncate_words($string,$words=20, $ellipsis=' ...') {
 $new = preg_replace('/((\w+\W+\'*){'.($words-1).'}(\w+))(.*)/', '${1}', $string);
 return $new.$ellipsis;
}

// FIN