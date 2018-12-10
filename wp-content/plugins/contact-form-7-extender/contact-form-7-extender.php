<?php
/**
 * Plugin Name: Contact Form 7 Extender Server
 * Description: Contact form 7 aggregation plugin for Server
 * Version: 1.0
 * Author: virginiawinefest
 * Author URI: http://virginiawinefest.com/
 */

add_action( 'admin_menu', 'cf7e_setup_pages' );
add_action( 'wpcf7_before_send_mail', 'cf7_before_send_custom' );
add_action( 'admin_init', 'cf7e_settings_init' );
add_action( 'rest_api_init', 'cf7e_save_form_data' );
register_activation_hook( __FILE__, 'cf7e_create_database_table' );
add_action( 'admin_enqueue_scripts', 'cf7e_load_admin_style' );

/*
 *
 * Create aggregation table
 *
 * */
function cf7e_create_database_table() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;
	$tableName = 'cf7e_aggregation_data';
	$table     = $wpdb->prefix . $tableName;
	if ( ! $wpdb->get_var( "show tables like '$table'" ) ) {
		$sql = "CREATE TABLE " . $table . " ( ";
		$sql .= "  `id` INT NOT NULL AUTO_INCREMENT , 
			`created_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			`name` TEXT NOT NULL , 
			`email` VARCHAR(255) NOT NULL , 
			`phone` VARCHAR(255) NOT NULL , 
			`subject` VARCHAR(255) NOT NULL , 
			`message` TEXT NOT NULL , 
			`from_site` VARCHAR(255) NOT NULL , 
			PRIMARY KEY (`id`)";
		$sql .= ");";
		dbDelta( $sql );
	} else {
//		$mod_result = cf7e_modify_database_table( $table, 'subject', 'phone' );
	}
}


/**
 * Modify aggregation table
 *
 * @param string $table database table name
 * @param string $col_name new column name
 * @param string $after position of the new column
 *
 * @return array $result
 */
function cf7e_modify_database_table( $table, $col_name = 'subject', $after = 'phone' ) {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;

	$result = array(
		'success' => false,
		'message' => ''
	);

	if ( empty( $table ) ) {
		$tableName = 'cf7e_aggregation_data';
		$table     = $wpdb->prefix . $tableName;
	}

	$col = $wpdb->query( "SELECT $col_name FROM $table;" );

	if ( ! $col ) {
		$query_result = $wpdb->query( "ALTER TABLE $table ADD $col_name VARCHAR(255) NOT NULL AFTER $after;" );

		if ( ! $query_result ) {
			$result = array(
				'success' => false,
				'message' => "Something went wrong during adding column '$col_name' to the database."
			);
		} else {
			$result = array(
				'success' => true,
				'message' => "Column '$col_name' has been added to the database."
			);
		}
	} else {
		$result['message'] = "Column '$col_name' is already exists.";
	}

	return $result;
}

/*
 *
 * Load Style for aggregation page
 * */
function cf7e_load_admin_style() {
	wp_enqueue_style( 'fontawesome', plugin_dir_url( __FILE__ ) . 'assets/fontawesome/css/font-awesome.min.css', false, '1.0.0' );
	wp_enqueue_style( 'admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css', false, '1.0.0' );
	wp_enqueue_script( 'cf7e_admin', plugin_dir_url( __FILE__ ) . 'assets/js/cf7e_admin.js', false, '1.0.0' );
}

/*
 *
 * Setup plugin pages
 *
 */
function cf7e_setup_pages() {
	add_submenu_page( 'tools.php', 'CF7 Extender Settings Page', 'Contact Form 7 Extender Settings', 'manage_options', 'contact-form-7-extender-settings', 'cf7e_settings_page' );
	add_menu_page( 'CF7 Extender Aggregation Page', 'Contact Form 7 Aggregation', 'manage_options', 'contact-form-7-extender', 'cf7e_aggregation_page' );
}

/**
 * Register settings section
 */
function cf7e_settings_init() {
	register_setting( 'pluginPage', 'cf7e_settings' );
	add_settings_section(
		'cf7e_pluginPage_section',
		__( 'API key and endpoint url ', 'contact form 7 extender' ),
		'cf7e_settings_section_callback',
		'pluginPage'
	);
	add_settings_field(
		'cf7e_text_field_0',
		__( 'Api key', 'contact form 7 extender' ),
		'cf7e_text_field_0_render',
		'pluginPage',
		'cf7e_pluginPage_section'
	);
	add_settings_field(
		'cf7e_text_field_1',
		__( 'Endpoint Url', 'contact form 7 extender' ),
		'cf7e_text_field_1_render',
		'pluginPage',
		'cf7e_pluginPage_section'
	);
}

/**
 *
 * Render settings fields
 *
 */
function cf7e_text_field_0_render() {
	$options = get_option( 'cf7e_settings' );
	?>
	<input type='text' name='cf7e_settings[cf7e_text_field_0]' value='<?php echo $options['cf7e_text_field_0']; ?>'>
	<?php
}

/**
 *
 * Render settings fields
 *
 */
function cf7e_text_field_1_render() {
	$options = get_option( 'cf7e_settings' );
	?>
	<input type='text' name='cf7e_settings[cf7e_text_field_1]' disabled
	       value='<?php echo get_site_url(); ?>/wp-json/cfesave/v1/route'>
	<?php

}

/**
 * Contact Form 7 before send hook. Send data to api endpoint
 *
 * @param object $cf7
 *
 */
function cf7_before_send_custom( $cf7 ) {
	$cf7eSettings = get_option( 'cf7e_settings' );
	if ( ! empty( $cf7eSettings ) ) {
		$postData = [
			'name'     => ( isset( $_POST['name'] ) ) ? $_POST['name'] : $_POST['your-name'],
			'email'    => ( isset( $_POST['email'] ) ) ? $_POST['email'] : $_POST['your-email'],
			'phone'    => ( isset( $_POST['phone'] ) ) ? $_POST['phone'] : $_POST['your-phone'],
			'subject'  => ( isset( $_POST['subject'] ) ) ? $_POST['subject'] : $_POST['your-subject'],
			'message'  => ( isset( $_POST['message'] ) ) ? $_POST['message'] : $_POST['your-message'],
			'site_url' => get_site_url(),
			'api_key'  => $cf7eSettings['cf7e_text_field_0']
		];

		$endPointUrl = $cf7eSettings['cf7e_text_field_1'];
		$ch          = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
		curl_setopt( $ch, CURLOPT_URL, $endPointUrl );
		$result = curl_exec( $ch );
	}

}

/**
 *
 * Section description callback
 *
 */
function cf7e_settings_section_callback() {
	echo __( 'Contact Form 7 Settings Page', 'contact form 7 extender' );
}

/**
 *
 * Render settings page
 *
 */
function cf7e_settings_page() {
	if ( isset( $_POST['submit'] ) && ! empty( $_POST['cf7e_settings'] ) ) {
		if ( ! get_option( 'cf7e_settings' ) ) {
			add_option( 'cf7e_settings', $_POST['cf7e_settings'] );
		} else {
			update_option( 'cf7e_settings', $_POST['cf7e_settings'] );
		}
	}
	if ( isset( $_POST['submit'] ) && ! empty( $_POST['api_key'] ) ) {
		$cf7Settings = [
			'cf7e_text_field_0' => $_POST['api_key'],
			'cf7e_text_field_1' => get_site_url() . '/wp-json/cfesave/v1/route'
		];
		if ( ! get_option( 'cf7e_settings' ) ) {
			add_option( 'cf7e_settings', $cf7Settings );
		} else {
			update_option( 'cf7e_settings', $cf7Settings );
		}
	}
	echo "<h1>" . __( 'Contact Form 7 Settings Page' ) . "</h1>";
	echo "<hr />";

	echo "<form action='' method='post'>";
	settings_fields( 'pluginPage' );
	do_settings_sections( 'pluginPage' );
	submit_button();
	echo "</form>";
	echo "<hr />";
	echo "<form action='' method='post'>";
	?>
	<legend>Generate new API key</legend>
	<input type="hidden" name="api_key" value="<?php echo generateRandomString( 25 ); ?>">
	<?php
	submit_button();
	echo "</form>";
}

/*
 *
 * Generate random string for api key
 *
 * */
function generateRandomString( $length = 10 ) {
	$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-|/!@%*';
	$charactersLength = strlen( $characters );
	$randomString     = '';
	for ( $i = 0; $i < $length; $i ++ ) {
		$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
	}

	return $randomString;
}

/*
 *
 * Register custom endpoint
 *
 * */
function cf7e_save_form_data() {
	$version   = 1;
	$namespace = 'cfesave/v' . $version;
	$base      = 'route';
	register_rest_route( $namespace, '/' . $base, [
		'methods'  => WP_REST_Server::CREATABLE,
		'callback' => 'cf7e_save_data'
	] );
}

/**
 * Save data to aggregation page
 *
 * @param WP_REST_Request $request
 *
 * @return string
 */
function cf7e_save_data( WP_REST_Request $request ) {
	$storeApiKey = get_option( 'cf7e_settings' );
	if ( $storeApiKey['cf7e_text_field_0'] == $request->get_param( 'api_key' ) ) {
		$params = [
			'name'      => $request->get_param( 'name' ),
			'email'     => $request->get_param( 'email' ),
			'phone'     => $request->get_param( 'phone' ),
			'subject'   => $request->get_param( 'subject' ),
			'message'   => $request->get_param( 'message' ),
			'from_site' => $request->get_param( 'site_url' ),
		];
		global $wpdb;
		$table = $wpdb->prefix . 'cf7e_aggregation_data';
		if ( $wpdb->insert( $table, $params ) ) {
			return 'Success';
		} else {
			return 'Error';
		}
	} else {
		return 'You not authorizated to access this point';
	}

}

/**
 * @param array $data
 *
 * @return array
 */
function cf7e_aggregation_filter_render( $data = [] ) {
	global $wpdb;
	$sort = ( isset( $data['sort'] ) ) ? $data['sort'] : "DESC";
	if ( isset( $_GET['sort'] ) ) {
		$sort = $_GET['sort'];
	}
	$conditions         = '';
	$aggregationResults = [];

	if ( isset( $data['message-limit'] ) && $data['message-limit'] != 'all' ) {
		$limit = $data['message-limit'];
	} elseif ( isset( $data['message-limit'] ) && $data['message-limit'] == 'all' ) {
		$limit = 0;
	} elseif ( isset( $_GET['limit'] ) ) {
		$limit = $_GET['limit'];
	} else {
		$limit = 10;
	}

	if ( isset( $_GET['site_url'] ) && ! empty( $_GET['site_url'] ) ) {
		$data['site_url'] = urldecode( $_GET['site_url'] );
	}
	$start = ( isset( $_GET['page_n'] ) ) ? ( $_GET['page_n'] - 1 ) * $limit : 0;
	if ( isset( $data['site_url'] ) && ! empty( $data['site_url'] ) && $data['site_url'] != get_site_url() ) {
		$conditions                                                    .= ' from_site LIKE "%' . $data['site_url'] . '%" AND';
		$aggregationResults[ $wpdb->prefix . 'cf7e_aggregation_data' ] = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "cf7e_aggregation_data WHERE " . $conditions . " from_site NOT LIKE '" . get_site_url() . "'  ORDER BY created_on $sort" );

		if ( stripos( get_site_url(), $data['site_url'] ) !== false ) {
			$list = $wpdb->get_results( "SELECT CFDBA_tbl_name as name FROM SaveContactForm7_lookup" );
			foreach ( $list as $table ) {
				$aggregationResults[ $table->name ] = $wpdb->get_results( "SELECT your_name as name, your_message as message, your_email as email, your_phone as phone, your_subject as subject, created_on FROM $table->name ORDER BY created_on $sort" );
			}
		}
	} elseif ( isset( $data['site_url'] ) && $data['site_url'] == get_site_url() ) {
		$list = $wpdb->get_results( "SELECT CFDBA_tbl_name as name FROM SaveContactForm7_lookup" );
		foreach ( $list as $table ) {
			$aggregationResults[ $table->name ] = $wpdb->get_results( "SELECT your_name as name, your_message as message, your_email as email, your_phone as phone, your_subject as subject, created_on FROM $table->name ORDER BY created_on $sort" );
		}
	} elseif ( ! isset( $data['site_url'] ) || empty( $data['site_url'] ) ) {
		$list = $wpdb->get_results( "SELECT CFDBA_tbl_name as name FROM SaveContactForm7_lookup" );
		foreach ( $list as $table ) {
			$aggregationResults[ $table->name ] = $wpdb->get_results( "SELECT your_name as name, your_message as message, your_email as email, your_phone as phone, your_subject as subject, created_on FROM $table->name ORDER BY created_on $sort" );
		}
		$aggregationResults[ $wpdb->prefix . 'cf7e_aggregation_data' ] = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "cf7e_aggregation_data WHERE from_site NOT LIKE '" . get_site_url() . "'  ORDER BY created_on $sort" );
	}

	foreach ( $aggregationResults as $key => $result ) {
		foreach ( $result as $single ) {
			if ( ! isset( $single->from_site ) ) {
				$single->from_site = get_site_url();
			}
			$single->table   = $key;
			$prepareResult[] = $single;
		}
	}
	if ( $sort == 'ASC' ) {
		usort( $prepareResult, function ( $item1, $item2 ) {
			return $item1->created_on >= $item2->created_on;
		} );
	} else {
		usort( $prepareResult, function ( $item1, $item2 ) {
			return $item1->created_on <= $item2->created_on;
		} );
	}
	$count = count( $prepareResult );
	if ( $limit > 0 ) {

		$prepareResult = array_slice( $prepareResult, $start, $limit );
	}

	return [
		'sort'          => $sort,
		'count'         => $count,
		'limit'         => $limit,
		'prepareResult' => $prepareResult
	];
}

/**
 *
 * Render aggregation page
 *
 */
function cf7e_aggregation_page() {
	global $wpdb;
	$_page = ( isset( $_GET['page_n'] ) ) ? $_GET['page_n'] : 1;

	$siteSelected = ( isset( $_POST['site_url'] ) ) ? $_POST['site_url'] : '';
	if ( isset( $_GET['site_url'] ) && ! empty( $_GET['site_url'] ) ) {
		$siteSelected = urldecode( $_GET['site_url'] );
	}
	$siteList          = $wpdb->get_results( "SELECT DISTINCT from_site FROM " . $wpdb->prefix . "cf7e_aggregation_data WHERE from_site NOT LIKE '" . get_site_url() . "'" );
	$aggregationResult = cf7e_aggregation_filter_render( $_POST );
	$sort              = ( isset( $aggregationResult['sort'] ) ) ? $aggregationResult['sort'] : "DESC";
	?>
	<div class="aggregation">
		<h1>Contact Forms Messages</h1>
		<hr/>
		<form action="admin.php?page=contact-form-7-extender" method="post" id="cf7e-filter">
			<fieldset>
				<legend><strong><?php echo __( "Sort By Date" ); ?></strong></legend>
				<p>
					<label for="datetime_asc"><?php echo __( "ASC" ); ?></label>
					<input type="radio" id="datetime_asc" <?php echo ( $sort == 'ASC' ) ? 'checked' : ''; ?> name="sort"
					       value="ASC">
				</p>
				<p>
					<label for="datetime_desc"><?php echo __( "DESC" ); ?></label>
					<input type="radio" id="datetime_desc" <?php echo ( $sort == 'DESC' ) ? 'checked' : ''; ?> name="sort"
					       value="DESC">
				</p>
			</fieldset>
			<fieldset>
				<legend><strong><?php echo __( "Filter By Site Url" ); ?></strong></legend>
				<label for="site_url"><?php echo __( "Input Site Url" ); ?></label>
				<?php
				$selected = '';
				if ( isset( $_POST['site_url'] ) && $_POST['site_url'] == get_site_url() || ( isset( $_GET['site_url'] ) && $_GET['site_url'] == get_site_url() ) ) :
					$selected = 'selected';
				endif
				?>
				<select name="site_url" id="site_url">
					<option value="" selected><?php echo __( "Select all" ) ?></option>
					<option <?php echo $selected; ?> value="<?php echo get_site_url(); ?>"><?php echo get_site_url(); ?></option>
					<?php
					$selected = '';
					foreach ( $siteList as $site ) {

						if ( isset( $_POST['site_url'] ) && $_POST['site_url'] == $site->from_site || ( isset( $_GET['site_url'] ) && $_GET['site_url'] == $site->from_site ) ) :
							$selected = 'selected';
						endif;
						?>
						<option <?php echo $selected; ?>
							value="<?php echo $site->from_site ?>"><?php echo $site->from_site ?></option>
						<?php

					}
					?>
				</select>


			</fieldset>
			<fieldset>
				<legend><strong><?php echo __( "Set view count" ) ?>:</strong></legend>
				<select name="message-limit">
					<option value="10" <?php if ( $aggregationResult['limit'] == 10 ) {
						echo "selected";
					} ?>>10
					</option>
					<option value="25" <?php if ( $aggregationResult['limit'] == 25 ) {
						echo "selected";
					} ?>>25
					</option>
					<option value="50" <?php if ( $aggregationResult['limit'] == 50 ) {
						echo "selected";
					} ?>>50
					</option>
					<option value="all" <?php if ( $aggregationResult['limit'] == 0 ) {
						echo "selected";
					} ?>>All
					</option>
				</select>
				<?php submit_button( __( "Apply" ) ); ?>
			</fieldset>
			<fieldset>

			</fieldset>
			<fieldset>
				<legend><strong><?php echo __( "Data export" ); ?></strong></legend>
				<a id="export" href="<?php echo admin_url( 'admin-ajax.php' ) ?>" data-sort="<?php echo $sort; ?>"
				   data-site="<?php echo $siteSelected ?>">
					<i class="fa fa-table" aria-hidden="true"></i>
					<?php echo __( 'Export to csv' ) ?>
				</a>
				<div class="loader">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/ajax-loader.gif'; ?>">
				</div>
				<div class="clearfix"></div>
				<div class="responce"></div>
			</fieldset>
			<div class="clearfix"></div>
		</form>
		<hr/>
		<div class="flash-message">

		</div>
		<div class="contact-form-7-wrapper">

			<?php if ( ! empty( $aggregationResult['prepareResult'] ) ) : ?>
				<?php $widthDivider = $aggregationResult['count']; ?>
				<div class="aggregation-header">
					<div class="left">
						<input type="button" name="Delete" id="delete-selected"
						       data-url="<?php echo admin_url( 'admin-ajax.php' ) ?>" value="<?php echo __( "Delete Selected" ) ?>">
						<input type="button" name="Delete" id="select-all" value="<?php echo __( "Select All" ) ?>">
						<input type="button" name="Delete" id="unselect-all" value="<?php echo __( "Unselect All" ) ?>">
						<input type="hidden" name="select-all" value="0">
					</div>
					<div class="right">

					</div>
					<div class="clearfix"></div>
				</div>
				<?php foreach ( $aggregationResult['prepareResult'] as $result ) : ?>

					<div class="single" data-id="<?php echo $result->table ?>_<?php echo $result->id ?>">
						<div class="loader">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/ajax-loader.gif'; ?>">
						</div>
						<div class="delete-wrap">
							<input type="checkbox" name="<?php echo $result->table ?>" value="<?php echo $result->id ?>">
						</div>
						<div class="body">
							<a href="javascript:void(0);" class="title">
								<span><strong><?php echo __( 'From site:' ) . '</strong> ' . $result->from_site; ?></span>
								<span><strong><?php echo __( 'Created on:' ) . '</strong> ' . $result->created_on; ?></span>
								<span><strong><?php echo __( 'Name:' ) . '</strong> ' . $result->name; ?></span>
								<span><strong><?php echo __( 'Email:' ) . '</strong> ' . $result->email; ?></span>
							</a>
							<div class="collapse">
								<div class="meta">
									<p><strong><?php echo __( 'Created on:' ) . '</strong> ' . $result->created_on; ?></p>
									<p><strong><?php echo __( 'Name:' ) . '</strong> ' . $result->name; ?></p>
									<p>
										<strong>
											<?php echo __( 'Email:' ); ?>
										</strong>
										<a href="mailto:<?php echo $result->email ?> ">
											<?php echo $result->email; ?>
										</a>
									</p>
									<p><strong><?php echo __( 'Phone:' ) . '</strong> ' . $result->phone; ?></strong></p>
									<p><strong><?php echo __( 'Subject:' ) . '</strong> ' . $result->subject; ?></strong></p>
									<?php if ( isset( $result->from_site ) ) : ?>
										<p><strong><?php echo __( 'From:' ) . '</strong> ' . $result->from_site; ?></strong></p>
									<?php else : ?>
										<p><strong><?php echo __( 'From:' ) . '</strong> ' . get_site_url(); ?></strong></p>
									<?php endif ?>
								</div>
								<div class="message">
									<p><?php echo nl2br( stripslashes( $result->message ) ); ?></p>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>

				<?php endforeach; ?>
			<?php endif ?>
			<div class="clearfix"></div>
		</div>
		<hr/>
		<div class="pagination">
			<?php
			if ( $aggregationResult['count'] > 0 && $aggregationResult['limit'] > 0 ) {
				$_total = $aggregationResult['count'];
				$_limit = $aggregationResult['limit'];
				$last   = ceil( $_total / $_limit );
				$links  = 7;
				$start  = ( ( $_page - $links ) > 0 ) ? $_page - $links : 1;
				$end    = ( ( $_page + $links ) < $last ) ? $_page + $links : $last;
				$html   = '<ul class="">';
				$class  = ( $_page == 1 ) ? "disabled" : "";
				$html   .= '<li class="' . $class . '"><a href="admin.php?page=contact-form-7-extender&limit=' . $_limit . '&page_n=' . ( $_page - 1 ) . '&sort=' . $sort . '&site_url=' . $siteSelected . '">&laquo;</a></li>';

				if ( $start > 1 ) {
					$html .= '<li><a href="admin.php?page=contact-form-7-extender&limit=' . $_limit . '&page_n=1&sort=' . $sort . '&site_url=' . $siteSelected . '">1</a></li>';
					$html .= '<li class="disabled"><span>...</span></li>';
				}

				for ( $i = $start; $i <= $end; $i ++ ) {
					$class = ( $_page == $i ) ? "active" : "";
					$html  .= '<li  class="' . $class . '"><a href="admin.php?page=contact-form-7-extender&limit=' . $_limit . '&page_n=' . $i . '&sort=' . $sort . '&site_url=' . $siteSelected . '">' . $i . '</a></li>';
				}

				if ( $end < $last ) {
					$html .= '<li class="disabled"><span>...</span></li>';
					$html .= '<li><a href="admin.php?page=contact-form-7-extender&limit=' . $_limit . '&page_n=' . $last . '&sort=' . $sort . '&site_url=' . $siteSelected . '">' . $last . '</a></li>';
				}

				$class = ( $_page == $last ) ? "disabled" : "";
				$html  .= '<li class="' . $class . '"><a href="admin.php?page=contact-form-7-extender&limit=' . $_limit . '&page_n=' . ( $_page + 1 ) . '&sort=' . $sort . '&site_url=' . $siteSelected . '">&raquo;</a></li>';

				$html .= '</ul>';
				echo $html;
			}
			?>
		</div>
	</div>
	<?php
}

add_action( 'wp_ajax_cf7e_export_csv', 'cf7e_export_csv' );
add_action( 'wp_ajax_cf7e_delete_record', 'cf7e_delete_record' );
function cf7e_export_csv() {

	if ( ! file_exists( ABSPATH . 'wp-content/uploads/csv/' ) ) {
		mkdir( ABSPATH . 'wp-content/uploads/csv/', 0777, true );
	}

	$exportData = cf7e_aggregation_filter_render( $_POST );
	if ( ! empty( $exportData ) ) {

		$name    = "exportMessage-" . date( 'YmdHis', time() ) . ".csv";
		$csvName = "wp-content/uploads/csv/" . $name;
		$csvFile = fopen( ABSPATH . $csvName, "w" );
		foreach ( $exportData['prepareResult'] as $line ) {
			$data = [
				$line->created_on,
				$line->from_site,
				$line->name,
				$line->email,
				$line->phone,
				$line->subject,
				preg_replace( '/(?<!,)"|"(?!,)/', '', str_ireplace( array(
					"\r",
					"\n",
					'\r',
					'\n'
				), '', stripslashes( $line->message ) ) )
			];
			fputcsv( $csvFile, $data, ',', '"' );
		}
		fclose( $csvFile );
		$html = "<a href='" . admin_url( 'admin-ajax.php' ) . "' data-name='" . $name . "' data-href='" . get_site_url() . '/' . $csvName . "' >" . __( "Download CSV" ) . "</a>";
		echo $html;
	}

	die();
}

function cf7e_delete_record() {
	if ( isset( $_POST['deleteArr'] ) && ! empty( $_POST['deleteArr'] ) ) {
		global $wpdb;
		if ( $_POST['all'] == '1' ) {
			$aggregationResults = [];
			$list               = $wpdb->get_results( "SELECT CFDBA_tbl_name as name FROM SaveContactForm7_lookup" );
			foreach ( $list as $table ) {
				$aggregationResults[ $table->name ] = $wpdb->get_results( "SELECT id FROM $table->name" );
			}
			$aggregationResults[ $wpdb->prefix . 'cf7e_aggregation_data' ] = $wpdb->get_results( "SELECT id FROM " . $wpdb->prefix . "cf7e_aggregation_data" );
			foreach ( $aggregationResults as $key => $result ) {
				foreach ( $result as $single ) {
					$wpdb->delete( $key, [ 'id' => $single->id ] );
				}
			}

		} else {
			foreach ( $_POST['deleteArr'] as $key => $value ) {
				$wpdb->delete( $value[0], [ 'id' => $value[1] ] );
			}
		}
		echo __( "Items succefully deleted" );
	}
	die();
}