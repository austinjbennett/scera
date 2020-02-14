<?php
class accorssSettingsPage
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
		add_action('admin_enqueue_scripts', array( $this, 'accorss_admin_scripts' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
		add_menu_page(
			"Accorss: Horizontal Accordian Slider", 
			"Accorss Slider", 
			"manage_options", 
			"accorss-setting-admin", 
			array( $this, 'create_admin_page' )
			);
    }

	public function accorss_admin_scripts() 
	{
		if (isset($_GET['page']) && $_GET['page'] == 'accorss-setting-admin') {
			wp_enqueue_media();
			wp_register_script('accorss-admin-js', WP_PLUGIN_URL.'/accorss/admin.js', array('jquery'));
			wp_register_style( 'accorss_admin_style', plugins_url('admin.css', __FILE__) );
			
			wp_enqueue_script('accorss-admin-js');
			wp_enqueue_style('accorss_admin_style');
		}
	}

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'accorss_options' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Accorss Slider Settings Page</h2>           
            <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'accorss_options' );   
                do_settings_sections( 'accorss-setting-admin' );
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
        register_setting(				// Options Group Registration
            'accorss_options', 						// Option group
            'accorss_options', 						// Option name
            array( $this, 'sanitize' ) 				// Sanitize
        );
	//First Slide
        add_settings_section( 			//Section - First Slide
            'first_slide', 							// ID
            '<hr>First Slide', 						// Title
            array( $this, 'print_section_info' ), 	// Callback
            'accorss-setting-admin' 				// Page
        );  
        add_settings_field(				//Field - Banner
            'fs_banner', 							// ID
            'Banner', 								// Title 
            array( $this, 'file_callback' ), 		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_banner'								// Callback function Parameters
        );
		add_settings_field(				//Field - Background Image
            'fs_background', 						// ID
            'Background Image', 					// Title 
            array( $this, 'file_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_background'							// Callback function Parameters
        );
		add_settings_field(				//Field - Primary Text 
            'fs_primary', 							// ID	
            'Primary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_primary'							// Callback function Parameters
        );
		add_settings_field(				//Field - Secondary Text
            'fs_secondary', 						// ID
            'Secondary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_secondary'							// Callback function Parameters
        );
		add_settings_field(				//Field - First Button
            'fs_button_one', 						// ID
            'First Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_button_one'							// Callback function Parameters
        );
		add_settings_field(				//Field - Second Button
            'fs_button_two', 						// ID
            'Second Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'first_slide', 							// Section   
			'fs_button_two'							// Callback function Parameters
        );
		
	//Second Slide
        add_settings_section( 			//Section - First Slide
            'second_slide', 						// ID
            '<hr>Second Slide', 					// Title
            array( $this, 'print_section_info' ), 	// Callback
            'accorss-setting-admin' 				// Page
        );  
        add_settings_field(				//Field - Banner
            'ss_banner', 							// ID
            'Banner', 								// Title 
            array( $this, 'file_callback' ), 		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_banner'								// Callback function Parameters
        );
		add_settings_field(				//Field - Background Image
            'ss_background', 						// ID
            'Background Image', 					// Title 
            array( $this, 'file_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_background'							// Callback function Parameters
        );
		add_settings_field(				//Field - Primary Text 
            'ss_primary', 							// ID	
            'Primary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_primary'							// Callback function Parameters
        );
		add_settings_field(				//Field - Secondary Text
            'ss_secondary', 						// ID
            'Secondary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_secondary'							// Callback function Parameters
        );
		add_settings_field(				//Field - First Button
            'ss_button_one', 						// ID
            'First Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_button_one'							// Callback function Parameters
        );
		add_settings_field(				//Field - Second Button
            'ss_button_two', 						// ID
            'Second Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'second_slide', 						// Section   
			'ss_button_two'							// Callback function Parameters
        );		
		
	//Third Slide
        add_settings_section( 			//Section - First Slide
            'third_slide', 							// ID
            '<hr>Third Slide', 						// Title
            array( $this, 'print_section_info' ), 	// Callback
            'accorss-setting-admin' 				// Page
        );  
        add_settings_field(				//Field - Banner
            'ts_banner', 							// ID
            'Banner', 								// Title 
            array( $this, 'file_callback' ), 		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_banner'								// Callback function Parameters
        );
		add_settings_field(				//Field - Background Image
            'ts_background', 						// ID
            'Background Image', 					// Title 
            array( $this, 'file_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_background'							// Callback function Parameters
        );
		add_settings_field(				//Field - Primary Text 
            'ts_primary', 							// ID	
            'Primary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_primary'							// Callback function Parameters
        );
		add_settings_field(				//Field - Secondary Text
            'ts_secondary', 						// ID
            'Secondary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_secondary'							// Callback function Parameters
        );
		add_settings_field(				//Field - First Button
            'ts_button_one', 						// ID
            'First Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_button_one'							// Callback function Parameters
        );
		add_settings_field(				//Field - Second Button
            'ts_button_two', 						// ID
            'Second Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'third_slide', 							// Section   
			'ts_button_two'							// Callback function Parameters
        );
		
	//Fourth Slide
        add_settings_section( 			//Section - First Slide
            'fourth_slide', 						// ID
            '<hr>Fourth Slide', 					// Title
            array( $this, 'print_section_info' ), 	// Callback
            'accorss-setting-admin' 				// Page
        );  
        add_settings_field(				//Field - Banner
            'rs_banner', 							// ID
            'Banner', 								// Title 
            array( $this, 'file_callback' ), 		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_banner'								// Callback function Parameters
        );
		add_settings_field(				//Field - Background Image
            'rs_background', 						// ID
            'Background Image', 					// Title 
            array( $this, 'file_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_background'							// Callback function Parameters
        );
		add_settings_field(				//Field - Primary Text 
            'rs_primary', 							// ID	
            'Primary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_primary'							// Callback function Parameters
        );
		add_settings_field(				//Field - Secondary Text
            'rs_secondary', 						// ID
            'Secondary Text', 						// Title 
            array( $this, 'text_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_secondary'							// Callback function Parameters
        );
		add_settings_field(				//Field - First Button
            'rs_button_one', 						// ID
            'First Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_button_one'							// Callback function Parameters
        );
		add_settings_field(				//Field - Second Button
            'rs_button_two', 						// ID
            'Second Button', 						// Title 
            array( $this, 'button_callback' ),		// Callback
            'accorss-setting-admin', 				// Page
            'fourth_slide', 						// Section   
			'rs_button_two'							// Callback function Parameters
        );		
		
    }


    //Sanitize each setting field as needed
    //@param array $input Contains all settings fields as array keys
    public function sanitize( $input )
    {
		global $_FILES;
        $new_input = array();
	//First Slide
        if( isset( $input['fs_banner'] ) )
            $new_input['fs_banner'] = sanitize_text_field( $input['fs_banner'] );

		if( isset( $input['fs_background'] ) )
            $new_input['fs_background'] = sanitize_text_field( $input['fs_background'] );
		
		if( isset( $input['fs_primary'] ) )
            $new_input['fs_primary'] = sanitize_text_field( $input['fs_primary'] );

		if( isset( $input['fs_secondary'] ) )
            $new_input['fs_secondary'] = sanitize_text_field( $input['fs_secondary'] );
		
		if( isset( $input['fs_button_one'] ) )
			$new_input['fs_button_one'] = $input['fs_button_one'];

		if( isset( $input['fs_button_two'] ) )
			$new_input['fs_button_two'] = $input['fs_button_two'];
	//Second Slide		
		if( isset( $input['ss_banner'] ) )
            $new_input['ss_banner'] = sanitize_text_field( $input['ss_banner'] );

		if( isset( $input['ss_background'] ) )
            $new_input['ss_background'] = sanitize_text_field( $input['ss_background'] );
		
		if( isset( $input['ss_primary'] ) )
            $new_input['ss_primary'] = sanitize_text_field( $input['ss_primary'] );

		if( isset( $input['ss_secondary'] ) )
            $new_input['ss_secondary'] = sanitize_text_field( $input['ss_secondary'] );
		
		if( isset( $input['ss_button_one'] ) )
			$new_input['ss_button_one'] = $input['ss_button_one'];

		if( isset( $input['ss_button_two'] ) )
			$new_input['ss_button_two'] = $input['ss_button_two'];
	//Third Slide	
		if( isset( $input['ts_banner'] ) )
            $new_input['ts_banner'] = sanitize_text_field( $input['ts_banner'] );

		if( isset( $input['ts_background'] ) )
            $new_input['ts_background'] = sanitize_text_field( $input['ts_background'] );
		
		if( isset( $input['ts_primary'] ) )
            $new_input['ts_primary'] = sanitize_text_field( $input['ts_primary'] );

		if( isset( $input['ts_secondary'] ) )
            $new_input['ts_secondary'] = sanitize_text_field( $input['ts_secondary'] );
		
		if( isset( $input['ts_button_one'] ) )
			$new_input['ts_button_one'] = $input['ts_button_one'];

		if( isset( $input['ts_button_two'] ) )
			$new_input['ts_button_two'] = $input['ts_button_two'];
	//Fourth Slide	
		if( isset( $input['rs_banner'] ) )
            $new_input['rs_banner'] = sanitize_text_field( $input['rs_banner'] );

		if( isset( $input['rs_background'] ) )
            $new_input['rs_background'] = sanitize_text_field( $input['rs_background'] );
		
		if( isset( $input['rs_primary'] ) )
            $new_input['rs_primary'] = sanitize_text_field( $input['rs_primary'] );

		if( isset( $input['rs_secondary'] ) )
            $new_input['rs_secondary'] = sanitize_text_field( $input['rs_secondary'] );
		
		if( isset( $input['rs_button_one'] ) )
			$new_input['rs_button_one'] = $input['rs_button_one'];

		if( isset( $input['rs_button_two'] ) )
			$new_input['rs_button_two'] = $input['rs_button_two'];
		
        return $new_input;
    }
	

    //Print the Section text
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }
	
	// Text input callback
    public function text_callback( $which_field )
    {
		printf(
            '<input type="text" id="'.$which_field.'" name="accorss_options['.$which_field.']" size="25" value="%s" />',
            isset( $this->options[$which_field] ) ? esc_attr( $this->options[$which_field]) : ''
        ); 
    }
	
	// file input callback
    public function file_callback( $which_field )
    {
		printf( 
			'<img class="'.$which_field.'_display" style="max-width:200px; max-height:150px;" src="%s"><br>',
			isset( $this->options[$which_field] ) ? esc_attr( $this->options[$which_field]) : ''
		); 
		printf(
            '<input type="text" id="'.$which_field.'" name="accorss_options['.$which_field.']" value="%s" />',
            isset( $this->options[$which_field] ) ? esc_attr( $this->options[$which_field]) : ''
        ); 
		echo '<input id="upload_image_button_'.$which_field.'" class="button upload_image_button" name="'.$which_field.'" type="button" value="Choose Image" />';
		
    }

	// Button input callback
    public function button_callback( $which_field )
    {
		printf(
            '<label for="#'.$which_field.'_main">Show button: </label><input others="'.$which_field.'_ot" class="check_boxer" type="checkbox" %s id="'.$which_field.'" name="accorss_options['.$which_field.'][main]" size="25" style="margin-right:15px;" />',
            isset( $this->options[$which_field]['main'] ) ? 'checked' : ''
        ); 
		printf(
            '<div class="'.$which_field.'_ot display-not-true"><label for="#'.$which_field.'_color">Background Color: </label><input type="text" id="'.$which_field.'_color" name="accorss_options['.$which_field.'][color]" size="25" value="%s" style="margin-right:15px;" />',
            isset( $this->options[$which_field]['color'] ) ? esc_attr( $this->options[$which_field]['color']) : ''
        ); 
		printf(
            '<label for="#'.$which_field.'_text_color">Text Color: </label><input type="text" id="'.$which_field.'_text_color" name="accorss_options['.$which_field.'][text_color]" size="25" value="%s" style="margin-right:15px;" />',
            isset( $this->options[$which_field]['text_color'] ) ? esc_attr( $this->options[$which_field]['text_color']) : ''
        ); 
		printf(
            '<label for="#'.$which_field.'_text">Text: </label><input type="text" id="'.$which_field.'_text" name="accorss_options['.$which_field.'][text]" size="25" value="%s" style="margin-right:15px;" />',
            isset( $this->options[$which_field]['text'] ) ? esc_attr( $this->options[$which_field]['text']) : ''
        ); 
		printf(
            '<label for="#'.$which_field.'_link">Link URL: </label><input type="text" id="'.$which_field.'_link" name="accorss_options['.$which_field.'][link]" size="25" value="%s" style="margin-right:15px;" /></div>',
            isset( $this->options[$which_field]['link'] ) ? esc_attr( $this->options[$which_field]['link']) : ''
        ); 

    }
	
}

if( is_admin() )
    $accorss_settings_page = new accorssSettingsPage();
