<?php
/**
 * Adds Settings Page
 */
class gspotsSettingsPage
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin', 
			'Gspots', 
			'manage_options', 
			'gspots-setting-admin', 
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'api' );
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Gspots Settings</h2>		   
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'api_key_group' );   
				do_settings_sections( 'gspots-setting-admin' );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{		
		register_setting(
			'api_key_group', // Option group
			'api', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'API Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'gspots-setting-admin' // Page
		);  

		add_settings_field(
			'key', // ID
			'API Key', // Title 
			array( $this, 'api_key_callback' ), // Callback
			'gspots-setting-admin', // Page
			'setting_section_id' // Section		   
		);		  
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['key'] ) )
			$new_input['key'] = sanitize_text_field( $input['key'] );

		return $new_input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your Google Maps Javascript v3 <a href="https://code.google.com/apis/console/" target="_blank">API key</a> below:<br/>';
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function api_key_callback()
	{
		printf(
			'<input type="text" id="key" name="api[key]" value="%s" class="regular-text" />',
			isset( $this->options['key'] ) ? esc_attr( $this->options['key']) : ''
		);
	}

}

if( is_admin() )
	$gspots_settings_page = new gspotsSettingsPage();