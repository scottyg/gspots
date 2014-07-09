<?php
class GspotSettingsPage
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
			'Gspot', 
			'manage_options', 
			'gspot-setting-admin', 
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
			<h2>Gspot Settings</h2>		   
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'api_key_group' );   
				do_settings_sections( 'gspot-setting-admin' );
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
			'gspot-setting-admin' // Page
		);  

		add_settings_field(
			'key', // ID
			'API Key', // Title 
			array( $this, 'api_key_callback' ), // Callback
			'gspot-setting-admin', // Page
			'setting_section_id' // Section		   
		);	  
		/*
		add_settings_field(
			'title', 
			'Title', 
			array( $this, 'title_callback' ), 
			'gspot-setting-admin', 
			'setting_section_id'
		);
		*/	  
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		//$new_input = array();
		//if( isset( $input['api_key'] ) )
		//	$new_input['api_key'] = absint( $input['api_key'] );

		//if( isset( $input['title'] ) )
		//$new_input['title'] = sanitize_text_field( $input['title'] );

		return $input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Enter your settings below:';
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function api_key_callback()
	{
		printf(
			'<input type="text" id="key" name="api[key]" value="%s" />',
			isset( $this->options['key'] ) ? esc_attr( $this->options['key']) : ''
		);
	}

	/** 
	 * Get the settings option array and print one of its values
	 */
	public function title_callback()
	{
		/*
		printf(
			'<input type="text" id="title" name="api[title]" value="%s" />',
			isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
		);
		*/
	}
}

if( is_admin() )
	$my_settings_page = new GspotSettingsPage();