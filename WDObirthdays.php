<?php
/*
Plugin Name: WDO Birthdays
Plugin URI: http://www.webdevsonline.com
Description: Displays birthdays for the current day via a widget, users can hide their birthday if they wish to. For more information, or if you need help with the plugin, or to request an update, email us at contact@webdevsonline.com.
Version: 1.1.3
Author: Web Devs Online
Author URI: http://www.webdevsonline.com

For more information, email us at contact@webdevsonline.com.

Copyright 2012 Web Devs Online

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

function WDObirthdaystyle(){

$pluginurl = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
echo '<link rel="stylesheet" type="text/css" href="'.$pluginurl.'WDObirthdaystyle.css">';
}
add_action( 'wp_head', 'WDObirthdaystyle' );

function list_birthdays() {

    ?>
    <h3><?php _e('Birthday Info', 'your_textdomain'); ?></h3>
    <table class="form-table">
    <tr>
    <th>
    <label for="address"><?php _e('Birthday', 'your_textdomain'); ?>
    </label></th>
    <td>
	<?php 
	global $profileuser;
	$user_id = $profileuser->ID;
	global $wpdb;
	$prefix = $wpdb->prefix;
	
	$sql = mysql_query('select meta_value from '.$prefix.'usermeta where meta_key = "birthday_day" AND user_id ='.$user_id.'');
	$day = mysql_fetch_array($sql);
	$sql2 = mysql_query('select meta_value from '.$prefix.'usermeta where meta_key = "birthday_month" AND user_id ='.$user_id.'');
	$month = mysql_fetch_array($sql2);
	$sql3 = mysql_query('select meta_value from '.$prefix.'usermeta where meta_key = "birthday_year" AND user_id ='.$user_id.'');
	$yeardb = mysql_fetch_array($sql3);
	?>
    <select name="day">
<?php
	for ($i=1; $i<32; $i++){
	if ($i == $day[0]){
	echo '<option value="'.$i.'" selected="selected">'. $i .' </option>';
	}
	else {
	echo '<option value="'.$i.'">'. $i .' </option>';
	}
	}
	?>
</select>
<select name="month">
<?php
	for ($i=1; $i<13; $i++){
	$zero = 0;
	if($i < 10) {
		if ($i == $month[0]){
	echo '<option value="'.$zero . $i.'" selected="selected">'. $zero . $i .' </option>';
	}
	else {
	echo '<option value="'.$zero . $i.'">'. $zero . $i .' </option>';
	}
	}
	else {
	if ($i == $month[0]){
	echo '<option value="'.$i.'" selected="selected">'.$i.' </option>';
	}
	else {
	echo '<option value="'.$i.'">'.$i.' </option>';
	}
	}
}
	?>
</select>
<select name="year">
<?php
	$year = date(Y);
	for ($i=$year; $i>=1920; $i--){
	if ($i == $yeardb[0]){
	echo '<option value="'.$i.'" selected="selected">'. $i .' </option>';
	}
	else {
	echo '<option value="'.$i.'">'. $i .' </option>';
	}
	}
	?>
</select>
    </td>
    </tr>
	<tr>
	<td>Current Birthday:</td>
	<td>
	<?php 
	if (!empty($day[0])){
	$slash = '/';
	}
	echo $day[0]; 
	echo $slash;
	echo $month[0];
	echo $slash;
	echo $yeardb[0];
	?>
	</td>
	</tr>
	<tr>
	<td>Show Birthday:</td>
	<td>
	<?php 
	$sql2 = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'birthday_display' AND user_id ='.$user_id.'");
	$test2 = mysql_num_rows($sql2);
	if (empty($test2))
	{
	$auto = 'checked="checked"';
	}
	if (!empty($sql2))
	{
	$test = mysql_fetch_array($sql2);
	}
	if ($test[0] == 'y'){
	$y = 'checked="checked"';
	}
	if ($test[0] == 'n'){
	$n = 'checked="checked"';
	}
	?>
	<input type="radio" value="y" name="birthdisplay" <?php echo $auto; echo $y; ?>/> Yes 
	<input type="radio" value="n" name="birthdisplay" <?php echo $n; ?>/> No 
	</td>
	</tr>
    </table>
    <?php }
	
    function fb_save_custom_user_profile_fields( $user_id ) {
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	
    if ( !current_user_can( 'edit_user', $user_id ) )
    return FALSE;
    update_user_meta( $user_id, 'birthday_display', $_POST['birthdisplay'] );
	update_user_meta( $user_id, 'birthday_day', $day );
	update_user_meta( $user_id, 'birthday_month', $month );
	update_user_meta( $user_id, 'birthday_year', $year );
    }
    add_action( 'show_user_profile', 'list_birthdays' );
    add_action( 'edit_user_profile', 'list_birthdays' );
    add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
    add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );

function list_birthdays_widget() {

	global $wpdb;
	$prefix = $wpdb->prefix;
	
	$thismonth = date(M);
	$thismonthw = date(m);
	$thisday = date(dS);
	$thisdayw = date(d);
	$thisyear = date(Y);

	echo 'Today is '. $thisday . ' ' . $thismonth . ' ' . $thisyear;
	echo '<br />';
	echo '<hr />';
	echo '<strong> Birthdays today: </strong>';

	echo '<br />';
			$sqlw = mysql_query('SELECT `user_id` FROM '.$prefix.'usermeta WHERE `meta_key` = "birthday_month" AND `meta_value` = "'.$thismonthw.'"');

			while ($user = mysql_fetch_array($sqlw)){
			$sqlw2 = mysql_query('SELECT `meta_value` FROM '.$prefix.'usermeta WHERE `meta_key` = "birthday_day" AND `user_id` = "'.$user[0].'"');

			while ($dayd = mysql_fetch_array($sqlw2)){
			$sqlw4 = mysql_query('SELECT `meta_value` FROM '.$prefix.'usermeta WHERE `meta_key` = "birthday_display" AND `user_id` = "'.$user[0].'"');

			while ($display = mysql_fetch_array($sqlw4)){
	
			if ($dayd[0] == $thisdayw && $display[0] == 'y'){
			$getuser = mysql_query('select user_login from '.$prefix.'users where ID = '.$user[0].'');
			while ($birthdays = mysql_fetch_array($getuser)){
			$sqlw3 = mysql_query('select meta_value from '.$prefix.'usermeta where meta_key = "birthday_year" AND user_id ="'.$user[0].'"');
			$yearw = mysql_fetch_array($sqlw3);
			$age = intval($thisyear) - $yearw[0];
			echo $birthdays[0] . ' ' . $age . '<br />';

			}
			}
			}
			}
			}
}

function widget_birthdays($args) {
    extract($args);

echo $before_widget; 
echo $before_title . 'Birthdays' . $after_title; 
echo list_birthdays_widget();
echo $after_widget; 

}
register_sidebar_widget('Birthdays',
    'widget_birthdays');

?>