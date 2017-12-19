<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	    <form action="options.php" method="post">
	        <?php
	            settings_fields( $this->plugin_name );
	            do_settings_sections( $this->plugin_name );
	            submit_button();
	        ?>
	    </form>
	</div>

	<div class="testdiv">
		<?php
		function writeText($par) {
			echo "<script>console.log(\"".$par."\");</script>";
		}
		?>
				<?php
					if(isset($_POST["setup"])) {
						$l_caps = array('edit_vk_event','read_vk_event','delete_vk_events','edit_vk_events','edit_others_vk_events','publish_vk_events','read_private_vk_events','create_vk_events','edit_daycare','read_daycare','delete_daycare','edit_daycares','edit_others_daycares','publish_daycares','read_private_daycares','create_daycares');
						Vuosikello_Utils::create_capabilities($l_caps);
						Vuosikello_Utils::create_common_group();
						//add all capabilities to admin
						$l_caps = array('edit_vk_event','read_vk_event','delete_vk_events','edit_vk_events','edit_others_vk_events','publish_vk_events','read_private_vk_events','create_vk_events','edit_daycare','read_daycare','delete_daycare','edit_daycares','edit_others_daycares','publish_daycares','read_private_daycares','create_daycares');
						$l_adminz = Groups_Group::read_by_name("admin");
						if($l_adminz) {
							$l_adminz = $l_adminz->group_id;
						}
						else {
							$l_adminz = Groups_Group::create(array('name' => "admin"));
						}
						echo "<br>".$l_adminz;
						foreach($l_caps as $l_cap) {
							$l_cap_id = Groups_Capability::create(array('capability' => $l_cap));
							if(!$l_cap_id) {
								$l_cap_id = Groups_Capability::read_by_capability($l_cap)->capability_id;
							}
							echo "<br>"."Created $l_cap_id";
							$succ = Groups_Group_Capability::create(array('group_id' => $l_adminz, 'capability_id' => $l_cap_id));
							echo "<br>"."Combined $l_adminz and $l_cap_id: $succ";
						}
					}
				?>
			</script>
			<?php
				if(isset($_POST["parsables"])) {
					submit_daycares();
				}

				function submit_daycares() {
					$url = wp_login_url();
					$lines = explode("|", $_POST["parsables"]);
					foreach($lines as $line) {
						$split_lines = explode("=", $line);
						if(count($split_lines) < 2) {
							continue;
						}
						$daycare = substr($split_lines[1], 0, strpos($split_lines[1], ";"));
						if(!Groups_Group::read_by_name($daycare)) {
							Vuosikello_Utils::create_daycare($daycare);
							$users_arr = explode(",", substr($split_lines[2], 0, strpos($split_lines[2], ";")));
							$mods_arr = explode(",", substr($split_lines[3], 0, strpos($split_lines[3], ";")));
							foreach($users_arr as $user) {
								if(!empty($user)) {
									$username = substr($user, 0, strpos($user, "@"));
									$password = wp_generate_password();
									$user_id = wp_create_user($username, $password);
									$res = Vuosikello_Utils::add_vk_user($daycare, $user_id);
									$subject = "Vuosikello tunnukset";
									$message = "Hei,\n Teille on luotu tunnukset ja annettu lukijaoikeudet utu vuosikelloon. Käyttäjätunnuksenne on $username ja salasananne $password.\nVoitte vaihtaa salasananne ja asettaa muita asetuksia osoitteessa $url";
									echo "<br>"."$user mailed:".wp_mail($user, $subject, $message);
								}
							}
							foreach($mods_arr as $mod) {
								if(!empty($mod)) {
									$username = substr($mod, 0, strpos($mod, "@"));
									$password = wp_generate_password();
									$user_id = wp_create_user($username, $password);
									$res = Vuosikello_Utils::add_vk_moderator($daycare, $user_id);
									$subject = "Vuosikello tunnukset";
									$message = "Hei,\n Teille on luotu tunnukset ja annettu kirjoitusoikeudet utu vuosikelloon. Käyttäjätunnuksenne on $username ja salasananne $password.\nVoitte vaihtaa salasananne ja asettaa muita asetuksia osoitteessa $url";
									echo "<br>"."$mod mailed:".wp_mail($mod, $subject, $message);
								}
							}
						}
					}

				}
			?>
		<form action="" method="post">
			<input type="hidden" name="setup" value="setup">
			<input type="submit" value="Setup">
		</form>
		<form action="" method="post">
			<br><h3>Daycare:</h3>
			<br>Format: daycare=name;users=email1,emailN;mods=modemail1,modemailN;|<br>
			Strings to parse: <br><textarea name="parsables" cols="50" rows="20"></textarea><br>
			<input type="submit" value="Create daycare">
		</form>
	</div>
