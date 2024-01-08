<?php
/**
 * EXAMPLE PLUGIN CODE
 *
 * You will need to move the following code to your plugin.
 */

/**
 * Move the following defines to the root file of your plugin:
 */
//define( 'PLUGIN_FULL_FILEPATH', __FILE__ );
//define( 'PLUGIN_FILE', plugin_basename( __FILE__ ) );

namespace YourNameSpace\updates;

/**
 * Set the following array values to ones appropriate for your plugin:
 */
$plugin_update_details = [
  'endpoint'    => 'https://your-plugin-updates.com', // URL where you are hosting your plugin update endpoint
  'name'        => 'Name of Your Plugin',
  'slug'        => 'plugin-slug', // This should be the directory name of your plugin
  'filename'    => 'plugin-filename.php', // This is the "index" filename of your plugin
  'author_url'  => 'https://example.com',
  'expiration'  => 7200, // Duration of transient used to check if this plugin has an update
];

/**
 * Fetches remote data from our Plugin Update Server.
 *
 * @return     object  The remote data.
 */
function fetch_remote_data() {
  $remoteData = wp_remote_get(
    $plugin_update_details['endpoint'],
    [
      'timeout' => 10,
      'headers' => [
        'Accept' => 'application/json'
      ]
    ]
  );

  // Something went wrong!
  if (
    is_wp_error($remoteData) ||
    wp_remote_retrieve_response_code($remoteData) !== 200
  ) {
    return null;
  }

  $remoteData = json_decode( wp_remote_retrieve_body( $remoteData ) );
  //return $remoteData;

  //*
  return (object) [
      'name'          => $plugin_update_details['name'],
      'slug'          => $plugin_update_details['slug'],
      'plugin'        => $plugin_update_details['filename'],
      'new_version'   => $remoteData->version,
      'url'           => $plugin_update_details['author_url'],
      'package'       => $remoteData->package,
      'icons'         => [],
      'banners'       => [],
      'banners_rtl'   => [],
      'tested'        => '',
      'requires_php'  => '',
      'compatibility' => new \stdClass(),
      'banners'       => [
        'low' => 'There is a new version of ' . $plugin_update_details['name'] . '.',
      ],
  ];
  /**/
}

/**
 * Filters the `plugins_api` information for our plugin.
 *
 * @param      stdClass  $res     The resource
 * @param      <type>    $action  The action
 * @param      <type>    $args    The arguments
 *
 * @return     stdClass  The standard class.
 */
function filter_plugin_info( $res, $action, $args ){
  // do nothing if you're not getting plugin information right now
  if( 'plugin_information' !== $action ) {
    return $res;
  }

  // do nothing if it is not our plugin
  if( $plugin_update_details['slug'] !== $args->slug ) {
    return $res;
  }

  $remoteData = fetch_remote_data();
  if( ! $remoteData ) {
    return $res;
  }

  $res = new \stdClass();

  $res->name = $remoteData->name;
  $res->slug = $remoteData->slug;
  $res->version = $remoteData->new_version;
  //$res->tested = $remoteData->tested;
  //$res->requires = $remoteData->requires;
  //$res->author = $remoteData->author;
  //$res->author_profile = $remoteData->author_profile;
  //$res->download_link = $remoteData->download_url;
  //$res->trunk = $remoteData->download_url;
  //$res->requires_php = $remoteData->requires_php;
  //$res->last_updated = $remoteData->last_updated;

  $res->sections = array(
    'description' => 'Provides an interface for editing your site\'s sellers.json.',
    'installation' => null,
    'changelog' => '<strong>0.0.0</strong><ul><li>Changelog coming soon.</li></ul>'
  );

  if( ! empty( $remoteData->banners ) ) {
    $res->banners = array(
      'low' => $remoteData->banners['low'],
    );
  }

  return $res;
}
add_filter( 'plugins_api', __NAMESPACE__ . '\\filter_plugin_info', 20, 3 );

/**
 * Checks to see if our plugin has an update available.
 *
 * @param      object  $update_plugins  The update plugins object
 *
 * @return     object  The filtered update plugins object
 */
function filter_update_plugins( $update_plugins ){

  if( ! is_object( $update_plugins ) )
    return $update_plugins;

  if( ! isset( $update_plugins->response ) || ! is_array( $update_plugins->response ) )
    $update_plugins->response = [];

  $remoteData = get_transient( $plugin_update_details['slug'] . '_transient' );
  if( false === $remoteData ){
    $remoteData = fetch_remote_data();
    set_transient( $plugin_update_details['slug'] . '_transient', $remoteData, $plugin_update_details['expiration'] );
  }

  if( ! function_exists( 'get_plugin_data' ) )
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');

  $currentPluginData = get_plugin_data( PLUGIN_FULL_FILEPATH );
  define( 'PLUGIN_FILE', plugin_basename( __FILE__ ) );

  if( $remoteData ){
    $res = new \stdClass();
    $res->slug = $plugin_update_details['slug'];
    $res->plugin = PLUGIN_FILE;
    $res->new_version = $remoteData->new_version;
    $res->url = $plugin_update_details['author_url'];
    $res->package = $remoteData->package;
  }

  if( $remoteData && version_compare( $remoteData->new_version, $currentPluginData['Version'], '>' ) ){
    $update_plugins->response[ PLUGIN_FILE ] = $res;
  } else {
    $update_plugins->no_update[ PLUGIN_FILE ] = $res;
  }

  return $update_plugins;
}
add_filter( 'site_transient_update_plugins', __NAMESPACE__ . '\\filter_update_plugins' );