<?php
/**
 * Plugin Name: HMLaserLLC Core Functionality
 * Description: This contains all your site's core functionality so that it is theme independent. Customized by capwebsolutions.com for HMLASERLLC.com
 * Version: 1.0.0
 * Author: Cap Web Solutions
 * Author URI: https://capwebsolutions.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Plugin Directory
define( 'CWS_DIR', dirname( __FILE__ ) );

// Post Types
//include_once( CWS_DIR . '/lib/functions/post-types.php' );

// Taxonomies
//include_once( CWS_DIR . '/lib/functions/taxonomies.php' );

// Metaboxes
//include_once( CWS_DIR . '/lib/functions/metaboxes.php' );

// Widgets
//include_once( CWS_DIR . '/lib/widgets/widget-social.php' );

// Editor Style Refresh
include_once( CWS_DIR . '/lib/functions/editor-style-refresh.php' );

// General
include_once( CWS_DIR . '/lib/functions/general.php' );
