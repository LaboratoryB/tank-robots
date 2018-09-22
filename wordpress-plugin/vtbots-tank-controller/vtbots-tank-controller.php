<?php if(!defined('ABSPATH')) { die(); }
/*
Plugin Name: VTBots Tank Controller
Plugin URI: https://vtbots.com/tank-battle/
Description: Provides a control interface and user management for Laser Tag Tanks
Version: 1.0.0
Author: Laboratory B
Author URI: https://laboratoryb.org
*/


if( !class_exists('VTBTank') ) {
	class VTBTank {
		protected $use_fast_ajax = true;

		protected $teams = array();
		protected $commands = array( 'stop', 'forwards', 'backwards', 'left', 'right', 'fire' );
		protected $resolution = array(
			'initial' => 0.75,
			'min'     => 0.25,
			'max'     => 1.50,
			'step'    => 0.25
		);
		protected $resolution_commands = array( '-', '|', '+' );
		protected $reset_delay = 30;
		protected $event_max_age = 10;

		protected $ajax_url;

		protected $command_table_name = 'vtbots_tank_commands';
		protected $hit_table_name = 'vtbots_tank_hits';
		protected $event_table_name = 'vtbots_tank_events';

		// fill in to block local IP from viewing stream
		protected $event_local_ip = "";

		public static function Instance() {
			static $instance = null;
			if ($instance === null) {
				$instance = new self();
			}
			return $instance;
		}

		protected function __construct() {
			$this->teams = parse_ini_file ( 'config.ini.php', true );
			if( !defined('SHORTINIT') || !SHORTINIT ) {
				if( $this->use_fast_ajax ) {
					$this->ajax_url = plugin_dir_url( __FILE__ ).'fast-ajax.php';
				} else {
					$this->ajax_url = admin_url( 'admin-ajax.php' );
				}
				register_activation_hook( __FILE__, array( $this, 'activate' ) );
				add_shortcode( 'vtbots-tanks', array( $this, 'shortcode'  ) );
				// query from tank
				add_action( 'wp_ajax_vtbots_tank_get_command', array( $this, 'get_command' ) );
				add_action( 'wp_ajax_nopriv_vtbots_tank_get_command', array( $this, 'get_command' ) );
				add_action( 'wp_ajax_vtbots_tank_register_hit', array( $this, 'register_hit' ) );
				add_action( 'wp_ajax_nopriv_vtbots_tank_register_hit', array( $this, 'register_hit' ) );
				// query from controller
				add_action( 'wp_ajax_vtbots_tank_set_team', array( $this, 'set_team' ) );
				add_action( 'wp_ajax_nopriv_vtbots_tank_set_team', array( $this, 'set_team' ) );
				add_action( 'wp_ajax_vtbots_tank_send_command', array( $this, 'send_command' ) );
				add_action( 'wp_ajax_nopriv_vtbots_tank_send_command', array( $this, 'send_command' ) );
				add_action( 'wp_ajax_vtbots_tank_get_score', array( $this, 'get_score' ) );
				add_action( 'wp_ajax_nopriv_vtbots_tank_get_score', array( $this, 'get_score' ) );
			}
		}

		public function activate() {
			global $wpdb;

			$command_table_name = $wpdb->prefix . $this->command_table_name;
			$hit_table_name = $wpdb->prefix . $this->hit_table_name;
			$event_table_name = $wpdb->prefix . $this->event_table_name;

			$wpdb_collate = $wpdb->collate;

			$sql_command_table =
			"CREATE TABLE {$command_table_name} (
			user_id bigint(20) unsigned NOT NULL UNIQUE ,
			team varchar(255) NULL,
			command varchar(255) NULL,
			resolution varchar(255) NULL,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (user_id),
			KEY team (team),
			KEY timestamp (timestamp)
			)
			COLLATE {$wpdb_collate}";

			$sql_hit_table =
			"CREATE TABLE {$hit_table_name} (
			hit_id bigint(20) unsigned NOT NULL auto_increment ,
			team varchar(255) NULL,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (hit_id),
			KEY team (team),
			KEY timestamp (timestamp)
			)
			COLLATE {$wpdb_collate}";

			$sql_event_table =
			"CREATE TABLE {$event_table_name} (
			event_id bigint(20) unsigned NOT NULL auto_increment ,
			team varchar(255) NULL,
			message varchar(255) NULL,
			timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (event_id),
			KEY team (team),
			KEY timestamp (timestamp)
			)
			COLLATE {$wpdb_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql_command_table );
			dbDelta( $sql_hit_table );
			dbDelta( $sql_event_table );
		}

		public function shortcode( $atts, $content ) {
			ob_start();
			echo '<div class="vtbots-tanks">';
			$user = wp_get_current_user();
			if( !empty( $_GET['scoreboard'] ) ) {
				include('templates/scoreboard.php');
			} elseif( !$user->exists() ) {
				include('templates/login-register.php');
			} else {
				$team = get_user_meta( $user->ID, '_vtbots_tank_team', true );
				if( !( $team && isset( $this->teams[$team] ) ) || !empty($_GET['teamchange']) ) {
					// Pick your team, puny mortal
					include('templates/select-team.php');
				} else {
					// Let's Play a game!
					$team_data = $this->teams[$team];
					include('templates/controller.php');
				}

			}
			echo '</div>';
			return ob_get_clean();
		}

		public function get_command() {
			$response = array();
			$team = '';
			$api_key = '';
			if( !empty( $_GET['hostname'] ) ) {
				$team = $this->get_team_from_hostname( $_GET['hostname'] );
			}
			if( !empty( $_GET['apikey'] ) ) {
				$api_key = $_GET['apikey'];
			}

			if( !$team || !$this->validate_api_key( $team, $api_key ) ) {
				echo error;
				die();
			}
			$resolution = get_option('vtbots_tank_' . $team . '_resolution', $this->resolution['initial'] );
			$last_move  = get_option('vtbots_tank_' . $team . '_last_move', '0' );
			$command = $this->get_game_command( $team, 'anarchy', $resolution, $last_move );

			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			echo json_encode( $command );

			update_option('vtbots_tank_' . $team . '_resolution', $command['resolution'] );
			update_option('vtbots_tank_' . $team . '_last_move', time() );


			die();
		}

		public function register_hit() {
			$response = array();
			$team = '';
			$api_key = '';
			if( !empty( $_GET['hostname'] ) ) {
				$team = $this->get_team_from_hostname( $_GET['hostname'] );
			}
			if( !empty( $_GET['apikey'] ) ) {
				$api_key = $_GET['apikey'];
			}

			if( !$team || !$this->validate_api_key( $team, $api_key ) ) {
				echo error;
				die();
			}
			$this->push_hit( $team );
			$this->trigger_hit_events( $team );
		}

		public function set_team() {
			$team = "";
			if( isset($_POST['team']) && isset( $this->teams[$_POST['team']] ) ) {
				$team = $_POST['team'];
			}
			$user = wp_get_current_user();
			if( $user->exists() && $team ) {
				update_user_meta( $user->ID, '_vtbots_tank_team', $team );
				echo "set team to: " . $team;
			} else {
				echo "error";
			}
			die();
		}

		public function send_command() {
			$command = "";
			if( isset($_POST['command']) ) {
				$command = $_POST['command'];
			}
			$user = wp_get_current_user();
			if( $user->exists() && ( in_array( $command, $this->commands ) || in_array( $command, $this->resolution_commands ) ) ) {
				$team = get_user_meta( $user->ID, '_vtbots_tank_team', true );
				if( $team && isset( $this->teams[$team] ) ) {
					$this->push_command( $user->ID, $team, $command );
					echo 'command pushed';
				} else {
					echo 'error';
				}
			} else {
				echo "error";
			}
			die();
		}

		public function get_score() {
			$scores = $this->get_team_scores();
			$event  = $this->get_event();
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			echo json_encode( array(
				'scores' => $scores,
				'event'  => $event
			) );
			die();
		}

		public function viewer_is_local_ip() {
			if( $this->get_client_IP() == $this->event_local_ip ) {
				return true;
			}
			return false;
		}

		protected function get_team_from_hostname( $hostname ) {
			foreach( $this->teams as $team => $details ) {
				if( $hostname == $details['hostname'] ) {
					return $team;
				}
			}
			return '';
		}

		protected function validate_api_key( $team, $api_key ) {
			if( isset( $this->teams[$team] ) ) {
				$team_data = $this->teams[$team];
				$team_api_key = $team_data['api_key'];
				if( $api_key == $team_api_key ) {
					return true;
				}
			}
			return false;
		}

		protected function push_hit( $team ) {
			global $wpdb;
			$hit_table_name = $wpdb->prefix . $this->hit_table_name;

			if( isset( $this->teams[$team] ) ) {
				$team_data = $this->teams[$team];
				$query = "INSERT INTO " . $hit_table_name . " (`team`) VALUES (%s)";
				$args = array( $team );
				$wpdb->query( $wpdb->prepare( $query, $args ) );
				return true;
			}
		}

		protected function get_team_scores( $team = '') {
			global $wpdb;
			$hit_table_name = $wpdb->prefix . $this->hit_table_name;

			if( $team ) {
				if( is_array( $team ) ) {
					$teams = $team;
				} else {
					$teams = array( $team );
				}
			} else {
				$teams = array_keys( $this->teams );
			}
			$game_start = get_option( 'vtbots_tank_game_start', 0 );
			$timestamp = date("Y-m-d H:i:s", $game_start );
			$scores = array();
			foreach( $teams as $team ) {
				if( empty( $this->teams[$team] ) ) {
					continue;
				}
				$query = "SELECT COUNT(*) FROM " . $hit_table_name . " WHERE `team`=%s AND `timestamp` > %s";
				$args  = array( $team, $timestamp );
				$hits  = $wpdb->get_var( $wpdb->prepare( $query, $args ) );
				$scores[$team] = $hits;
			}
			if( $team && !is_array( $team ) && count( $scores ) == 1 && isset( $scores[$team] ) ) {
				return $scores[$team];
			}
			return $scores;
		}

		protected function get_event() {
			global $wpdb;
			$event_table_name = $wpdb->prefix . $this->event_table_name;

			$user = wp_get_current_user();
			$event_cutoff = date("Y-m-d H:i:s", time() - $this->event_max_age );
			$now = date("Y-m-d H:i:s", time() );
			if( empty( $_GET['scoreboard'] ) && $user->exists() ) {
				$team = get_user_meta( $user->ID, '_vtbots_tank_team', true );
				if( $team && isset( $this->teams[$team] ) ) {
					$sql   = "SELECT * FROM " . $event_table_name . " WHERE (`team`=%s OR `team`='') AND `timestamp` > %s AND `timestamp` <= %s  ORDER BY `timestamp` DESC LIMIT 1";
					$args  = array( $team, $event_cutoff, $now );
					$event = $wpdb->get_row( $wpdb->prepare( $sql, $args ) );
					return $event;
				}
			} else {
				$sql   = "SELECT * FROM " . $event_table_name . " WHERE (`team`='') AND `timestamp` > %s  AND `timestamp` <= %s ORDER BY `timestamp` DESC LIMIT 1";
				$args  = array( $event_cutoff, $now );
				$event = $wpdb->get_row( $wpdb->prepare( $sql, $args ) );
				return $event;
			}
			return "";
		}

		protected function trigger_hit_events( $team ) {
			$team_data = $this->teams[$team];
			$score = $this->get_team_scores( $team );
			if( $score >= $team_data['health'] ) {
				// Round has ended
				update_option( 'vtbots_tank_game_start', time() + $this->reset_delay, true );

				$i = 0;
				while ( $i < $this->reset_delay ) {
					$message = '';
					if( $i < 10 ) {
						$message .= $team_data['name'] . " Loses! ";
					}
					$message .= "Game will restart in approximately " . ( $this->reset_delay - $i ) . " seconds.";
					$this->send_event( null, $message, time()+$i );
					$i += 5;
				}
				$message = "New Game Starting NOW!";
				$this->send_event( null, $message, time()+$i );
			} elseif( $score ) {
				foreach( $this->teams as $cur_team => $cur_team_data ) {
					if( $team == $cur_team ) {
						$message = "Your tank has been hit! Take evasive action!";
					} else {
						$message = $team_data['name'] . " has been hit! Finish them!";
					}
					$this->send_event( $cur_team, $message );
				}
			}
		}

		protected function send_event( $team = null, $message = "", $time = false ) {
			global $wpdb;
			$event_table_name = $wpdb->prefix . $this->event_table_name;

			if( ( $team && !isset( $this->teams[$team] ) ) || !$message ) {
				return;
			}
			if( !$team ) {
				$team = '';
			}
			if( !$time ) {
				$time = time();
			}
			$ts = date("Y-m-d H:i:s", $time );

			$sql = "INSERT INTO " . $event_table_name . "(`team`, `message`, `timestamp`) VALUES (%s, %s, %s)";
			$args = array( $team, $message, $ts );
			$wpdb->query( $wpdb->prepare( $sql, $args ) );
		}

		protected function push_command( $id, $team, $command ) {
			global $wpdb;
			$command_table_name = $wpdb->prefix . $this->command_table_name;

			if( isset( $this->teams[$team] ) ) {
				if( $command == '+' || $command == '-' || $command == '|' ) {
					$query = "INSERT INTO " . $command_table_name . " (`user_id`,`team`,`command`,`resolution`) VALUES (%d,%s,'stop',%s) ON DUPLICATE KEY UPDATE team=%s,resolution=%s,timestamp=%s";
				} elseif( in_array( $command, $this->commands ) ) {
					$query = "INSERT INTO " . $command_table_name . " (`user_id`,`team`,`command`,`resolution`) VALUES (%d,%s,%s,'|') ON DUPLICATE KEY UPDATE team=%s,command=%s,timestamp=%s";
				} else {
					return false;
				}
				$args = array( $id, $team, $command, $team, $command, date("Y-m-d H:i:s", time()) );
				$wpdb->query( $wpdb->prepare( $query, $args ) );
				return true;
			}
		}

		protected function get_active_player_count($team) {
			global $wpdb;
			$command_table_name = $wpdb->prefix . $this->command_table_name;

			if( !isset( $this->teams[$team] ) ) {
				return 0;
			}
			$timestamp = date("Y-m-d H:i:s", time() - 2*60 ); // last 2 minutes
			$query = "SELECT COUNT(*) FROM " . $command_table_name . " WHERE `team`=%s AND `timestamp` > %s";
			$args = array($team, $timestamp);
			$query = $wpdb->prepare( $query, $args );
			$var = $wpdb->get_var( $query );
			return intval($var);
		}

		protected function get_game_command( $team, $mode, $resolution, $last_move ) {
			$timestamp = date("Y-m-d H:i:s", $last_move);
			switch( $mode ) {
				case 'anarchy':
					return $this->get_game_command_anarchy($team, $resolution, $timestamp);
				default :
					return array(
						'resolution' => $resolution,
						'command' => 'stop'
					);
			}

		}

		protected function update_resolution($resolution, $change) {
			if( $change == '+' ) {
				$resolution += $this->resolution['step'];
			} elseif( $change == '-' ) {
				$resolution -= $this->resolution['step'];
			}
			if( $resolution >= $this->resolution['max'] ) {
				$resolution = $this->resolution['max'];
			} elseif( $resolution <= $this->resolution['min'] ) {
				$resolution = $this->resolution['min'];
			}
			return $resolution;
		}

		protected function get_game_command_anarchy( $team, $resolution, $timestamp ) {
			global $wpdb;
			$command_table_name = $wpdb->prefix . $this->command_table_name;

			$retval = array(
				'resolution' => $resolution,
				'command' => 'stop',
				'mode' => 'anarchy'
			);

			$query = "SELECT * FROM " . $command_table_name . " WHERE `team`=%s AND `timestamp` > %s ORDER BY RAND() LIMIT 1";
			$args = array($team, $timestamp);
			$query = $wpdb->prepare( $query, $args );
			$row = $wpdb->get_row( $query );
			if( !$row ) {
				return $retval;
			}
			// resolution
			if( in_array( $row->resolution, $this->resolution_commands ) ) {
				$retval['resolution'] = $this->update_resolution($resolution, $row->resolution );
			}
			$command = 'stop';
			if( in_array( $row->command, $this->commands ) ) {
				$retval['command'] = $row->command;
			}

			return $retval;
		}

		protected function get_client_IP() {
			if( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) ){
				return  $_SERVER["HTTP_X_FORWARDED_FOR"];
			} elseif( array_key_exists( 'REMOTE_ADDR', $_SERVER ) ) {
				return $_SERVER["REMOTE_ADDR"];
			} elseif( array_key_exists( 'HTTP_CLIENT_IP', $_SERVER ) ) {
				return $_SERVER["HTTP_CLIENT_IP"];
			}

			return '';
		}
	}
	VTBTank::Instance();
}
