<?php
/*
Plugin Name: Mechanic Post Hits Counter
Plugin URI: http://www.adityasubawa.com/blog/63/install-mechanic-post-hits-counter-wordpress.html
Description: Display Hits View Counter in below Posting Article and anywhere you want it. Including page, single post, or as a widgets using shortcode method and easy to install.
Version: 1.1
Author: Aditya Subawa
Author URI: http://www.adityasubawa.com
*/
global $wpdb;
define('HC_TABLE_NAME', $wpdb->prefix . 'adit_hitcount');
define('HC_PATH', ABSPATH . 'wp-content/plugins/hitssmechanic');
require_once(ABSPATH . '/wp-includes/pluggable.php');

function adit_install(){
global $wpdb;
if ( $wpdb->get_var('SHOW TABLES LIKE "' . HC_TABLE_NAME . '"') != HC_TABLE_NAME )
{
$sql = "CREATE TABLE IF NOT EXISTS `". HC_TABLE_NAME . "` (";
$sql .= "`SN` BIGINT NOT NULL AUTO_INCREMENT,";
$sql .= "`name` VARCHAR( 1000 ) NOT NULL,";
$sql .= "`hit` BIGINT NOT NULL DEFAULT '1',";
$sql .= "PRIMARY KEY ( `SN` )";
$sql .= ") ENGINE = MYISAM;";
$wpdb->query($sql);
 }
}
	 
function adit_uninstall(){
global $wpdb;
$sql = "DROP TABLE `". HC_TABLE_NAME . "`;";
$wpdb->query($sql);
}
register_activation_hook(__FILE__, 'adit_install');
register_deactivation_hook(__FILE__, 'adit_uninstall');?>
<?php
function get_HitsMechanic(){
$url = getenv("HTTP_REFERER");
	$url = str_replace ("http://",'',$url);
	$url = str_replace ("www.",'',$url);
    if ($url != "")
		{
			$query = "Select hit from `". HC_TABLE_NAME . "` where name = '$url'"; 
			$result = mysql_query($query);
			if (!$result) 
			{
    			die('Invalid query: ' . mysql_error());
			}
			if (mysql_affected_rows()==0)
			{
				$query = "Insert into `". HC_TABLE_NAME . "` (name) values ('$url')";
				$result = mysql_query($query);
				echo " Hits: 1 ";
				if (!$result) 
				{
    				die('Invalid query: ' . mysql_error());
				}
			}
			else
			{
				$hitcount = mysql_result($result, 0);
				$hitcount++;
				echo " Hits: $hitcount ";
				$query = "Update `". HC_TABLE_NAME . "` set hit = $hitcount where name = '$url'";
				$result = mysql_query($query);
				if (!$result) 
				{
    				die('Invalid query: ' . mysql_error());
				}
			}
		}?>
<?php }
//admin setting
add_action('admin_menu', 'hitsmechanic_menu');
function hitsmechanic_menu() {
add_options_page('Plugin Hits Mechanic', 'Hits Mechanic Options', 1, 'plugin_hitsmechanic_menu', 'hitsmechanic_options');
}
function hitsmechanic_options() {
if (!current_user_can(1))  {
wp_die( __('You do not have sufficient permissions to access this page.') );
}
echo '
<div class="wrap">';
echo '<h2>Plugin Options Mechanic Post Hits Counter</h2><br/>';
echo 'Join our mailing list for tips, tricks, and Website secrets.';
echo '<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open("http://feedburner.google.com/fb/a/mailverify?uri=adityasubawa", "popupwindow", "scrollbars=yes,width=550,height=520");return true">
  <p>Enter your email address: 
    <input type="text" style="width:140px" name="email"/> 
    <input type="hidden" value="adityasubawa" name="uri"/>
    <input type="hidden" name="loc" value="en_US"/>
    <input type="submit" value="Subscribe" />
  </p>
  </form>';
echo '<h3>Enable Mechanic Post Hits Counter</h3>';
echo 'Just insert the following shortcode anywhere in your blog (for use in a widget: use the text-widget and insert the shortcode there) <br/>';
echo '<pre>&lt;?php get_HitsMechanic();?&gt;</pre>';
echo 'If you like and helped with our plugins, please donate to the developer. how much your nominal will help developers to develop these plugins. Also, dont forget to follow us on <a href="http://www.twitter.com/adityasubawa" target="_blank">Twitter</a>.<br/>';
echo '<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZMEZEYTRBZP5N&lc=ID&item_name=Aditya%20Subawa&item_number=426267&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" alt="Donate" /></a></p>';
echo '</div>';
}
?>