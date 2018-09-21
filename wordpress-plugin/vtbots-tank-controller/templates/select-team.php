<?php if(!defined('ABSPATH')) { die(); } ?>
<style>
body {
	background-color: #666;
	color: #fff;
}
.vtbots-tank-battle.select-team,
.vtbots-tank-battle.select-team *,
.vtbots-tank-battle.select-team *:before,
.vtbots-tank-battle.select-team *:after {
	box-sizing: border-box;
	line-height: 1.45;
}
.vtbots-tank-battle.select-team {
	padding: 15px;
	color: #fff;
}
.vtbots-tank-battle.select-team h1 {
	text-align: center;
	color: inherit;
}
.vtbots-tank-battle.select-team .note {
	text-align: center;
	margin: 10px auto;
	max-width: 500px;
}
.vtbots-tank-battle.select-team .team-wrap {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: center;
	margin: 0 -15px;
}
.team-box {
	display: block;
	position: relative;
	margin: 15px;
	padding: 15px 15px 130px;
	width: 300px;
	max-width: calc(50% - 30px);
	color: #fff;
	border-radius: 15px;
	overflow: hidden;
}

.team-box:hover {
	color: #fff;
}
.team-box .imgwrap {
	position: relative;
	margin: -15px -15px 15px;
	background-color: inherit;
}
.team-box .imgwrap:before {
	display: block;
	position: absolute;
	content: ' ';
	background-color: inherit;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	opacity: 0.4;
	mix-blend-mode: color;

}
.team-box h2 {
	color: inherit;
	text-align: center;
}

@media( max-width: 380px ) {
	.team-box {
		margin: 5px;
		padding: 10px 10px 130px;
		max-width: calc(50% - 10px);
	}
	.team-box h2 {
		font-size: 16px;
	}
}

.team-box p {
	max-height: 20vh;
	overflow: auto;
}
.team-box .cta {
	position: absolute;
	bottom: 50px;
	left: 0;
	right: 0;
	margin: 20px;
	padding: 15px;
	background-color: rgba(0,0,0,0.3);
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
}
.team-box .cta:hover {
	background-color: rgba(0,0,0,0.4);
}
.team-box .active-players {
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	text-align: center;
	font-style: italic;
	padding: 15px;
	background-color: rgba(0,0,0,0.2);
}
</style>
<div class="vtbots-tank-battle select-team">
	<h1>Select your team</h1>
	<p class="note">
		Keep in mind that by joining the team with fewer players your commands will have a better chance of being selected by the robot.
	</p>
	<div class="team-wrap">
		<?php foreach($this->teams as $team => $data ) { ?>
		<a href="#<?php echo esc_attr($team); ?>" class="team-box team-<?php echo esc_attr($team); ?>" style="background-color: <?php echo esc_attr( $data['color'] ); ?> ;">
			<div class="imgwrap"><?php echo wp_get_attachment_image( $data['picture'], 'full'); ?></div>
			<h2><?php _e($data['name']); ?></h2>
			<p><?php _e($data['bio']); ?></p>
			<div class="cta">Join Team</div>
			<div class="active-players"><?php printf( '%d', $this->get_active_player_count( $team ) ); ?> active players</div>
		</a>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
	jQuery( function($) {
		var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
		$('.vtbots-tank-battle .team-box').click(function(e) {
			e.preventDefault();
			var team = $(this).attr('href').replace('#', '');
			var data = {
				'action' : 'vtbots_tank_set_team',
				'team' : team
			};
			$.ajax({
				url: ajaxurl,
				data: data,
				method: 'POST',
			}).done(function() {
				location.href = location.href.split("?")[0].split("#")[0];;
			});
		});
	} );
</script>
