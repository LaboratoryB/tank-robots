<?php if(!defined('ABSPATH')) { die(); }
?>
<style>
.vt-bots-tank-controller {
background-color: #666;
font-family: arial, helvetica, sans-serif;
padding: 15px;
min-height: 100vh;
line-height: 1.45;
}
.vt-bots-tank-controller, .vt-bots-tank-controller *, .vt-bots-tank-controller *:before, .vt-bots-tank-controller *:after {
box-sizing: border-box;
}

.vt-bots-tank-controller .team {
	padding: 5px;
	margin: -15px;
	margin-bottom: 0;
	text-align: center;
	text-transform: uppercase;
	font-weight: bold;
	color: #fff;
}

.vt-bots-tank-controller .team a {
	font-size: 0.7em;
	display: block;
	color: rgba(255,255,255,0.5);
	padding: 5px 5px 0;
}

.vt-bots-tank-controller .score-left,
.vt-bots-tank-controller .score-right {
	display: block;
	position: absolute;
	top: 32px;
	width: 120px;
	height: 50px;
	background-color: #444;
	color: #fff;
	font-weight: bold;
	font-size: 15px;
	text-transform: uppercase;
	text-align: center;
	border-radius: 0 0 10px 10px;
}
@media screen and (max-width: 782px) {
	.vt-bots-tank-controller .score-left,
	.vt-bots-tank-controller .score-right {
		top: 46px;
	}
}

.vt-bots-tank-controller .score-left {
	left: 15px;
}
.vt-bots-tank-controller .score-right {
	right: 15px;
}

.vt-bots-tank-controller .score-left .tick,
.vt-bots-tank-controller .score-right .tick {
	display: inline-block;
	width: 10px;
	height: 20px;
	background-color: #fff;
	margin: 2px;
}
.vt-bots-tank-controller .score-left .tick.hit,
.vt-bots-tank-controller .score-right .tick.hit {
	background-color: #444;
}

@media screen and (max-width: 430px) {
	.vt-bots-tank-controller .score-left,
	.vt-bots-tank-controller .score-right {
		width: 80px;
		font-size: 10px;
	}
	.vt-bots-tank-controller .score-left .tick,
	.vt-bots-tank-controller .score-right .tick {
		width: 8px;
		height: 16px;
		margin: 1px;
	}
}

.vt-bots-tank-controller .stream-embed {
	position: relative;
	max-width: 1000px;
	margin: 0 auto;
}
@media screen and (max-width: 600px) {
	.vt-bots-tank-controller .stream-embed {
		margin: 0 -15px;
	}
}

.vt-bots-tank-controller .stream-embed > .inner {
	position: relative;
	padding-bottom: 56.25%;
}

.vt-bots-tank-controller .stream-embed > .inner > * {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
}

.vt-bots-tank-controller .message {
	color: #fff;
	margin: 30px auto;
	max-width: 550px;
	text-align: center;
	font-weight: bold;
	font-size: 1.2em;
	background-color: #F00;
	min-height: 25px;
}
.vt-bots-tank-controller .message:empty {
	background-color: transparent;
}

.vt-bots-tank-controller .controller {
display: flex;
width: 100%;
border-top: 1px solid #ccc;
padding: 30px;
padding: 15px;
}
.vt-bots-tank-controller .controller button {
color: #bbb;
text-shadow: -1px -1px 0px #999, 2px 2px 0px #fff;
box-shadow: 2px 2px 0px #222, -1px -1px 0px #999;
}
.vt-bots-tank-controller .controller button:active {
box-shadow: none;
}
.vt-bots-tank-controller .controller .dpad {
position: relative;
height: 200px;
width: 200px;
font-size: 14px;
}
.vt-bots-tank-controller .controller .dpad * {
position: absolute;
}
.vt-bots-tank-controller .controller .dpad .forwards, .vt-bots-tank-controller .controller .dpad .backwards {
left: 50%;
transform: translateX(-50%);
height: 75px;
width: 50px;
}
.vt-bots-tank-controller .controller .dpad .left, .vt-bots-tank-controller .controller .dpad .right {
top: 50%;
transform: translateY(-50%);
width: 75px;
height: 50px;
}
.vt-bots-tank-controller .controller .dpad .forwards {
top: 0;
}
.vt-bots-tank-controller .controller .dpad .right {
right: 0;
}
.vt-bots-tank-controller .controller .dpad .backwards {
bottom: 0;
}
.vt-bots-tank-controller .controller .dpad .left {
left: 0;
}
.vt-bots-tank-controller .controller .dpad .forwards:after, .vt-bots-tank-controller .controller .dpad .right:after, .vt-bots-tank-controller .controller .dpad .backwards:after, .vt-bots-tank-controller .controller .dpad .left:after {
display: block;
content: ' ';
font-size: 2em;
font-family: fontawesome;
}
.vt-bots-tank-controller .controller .dpad .forwards:after {
content: '\f077';
}
.vt-bots-tank-controller .controller .dpad .right:after {
content: '\f054';
}
.vt-bots-tank-controller .controller .dpad .backwards:after {
content: '\f078';
}
.vt-bots-tank-controller .controller .dpad .left:after {
content: '\f053';
}
@media (max-width: 720px) {
	.vt-bots-tank-controller .controller .dpad {
		width: 150px;
		height: 150px;
	}
	.vt-bots-tank-controller .controller .dpad .forwards, .vt-bots-tank-controller .controller .dpad .backwards {
		height: 50px;
		width: 42px;
	}
	.vt-bots-tank-controller .controller .dpad .left, .vt-bots-tank-controller .controller .dpad .right {
		width: 50px;
		height: 42px;
	}
}
@media (max-width: 500px) {
	.vt-bots-tank-controller .controller .dpad {
		width: 100px;
		height: 100px;
	}
	.vt-bots-tank-controller .controller .dpad .forwards, .vt-bots-tank-controller .controller .dpad .backwards {
		height: 35px;
		width: 30px;
	}
	.vt-bots-tank-controller .controller .dpad .left, .vt-bots-tank-controller .controller .dpad .right {
		width: 35px;
		height: 30px;
	}
	.vt-bots-tank-controller .controller .dpad .forwards:after, .vt-bots-tank-controller .controller .dpad .right:after, .vt-bots-tank-controller .controller .dpad .backwards:after, .vt-bots-tank-controller .controller .dpad .left:after {
		font-size: 1.3em;
	}
}
.vt-bots-tank-controller .controller .resolution {
text-align: center;
flex: 1 1 auto;
padding-top: 45px;
font-size: 30px;
font-weight: bold;
}
.vt-bots-tank-controller .controller .resolution .resolution-label {
font-size: 20px;
text-transform: uppercase;
color: #444;
margin-bottom: 5px;
}
.vt-bots-tank-controller .controller .resolution button {
font-size: 40px;
color: #bbb;
font-weight: bold;
}
.vt-bots-tank-controller .controller .resolution .resolution-value {
color: #fff;
}
@media (max-width: 720px) {
	.vt-bots-tank-controller .controller .resolution {
		font-size: 20px;
		padding-top: 32px;
	}
	.vt-bots-tank-controller .controller .resolution .resolution-label {
		font-size: 16px;
	}
	.vt-bots-tank-controller .controller .resolution button {
		font-size: 30px;
	}
}
@media (max-width: 500px) {
	.vt-bots-tank-controller .controller .resolution {
		font-size: 16px;
		padding-top: 13px;
	}
	.vt-bots-tank-controller .controller .resolution .resolution-label {
		font-size: 13px;
	}
	.vt-bots-tank-controller .controller .resolution button {
		font-size: 22px;
	}
}
.vt-bots-tank-controller .controller .command {
	text-align: right;
	padding: 50px 0;
	width: 200px;
}
.vt-bots-tank-controller .controller .command .fire {
	width: 100px;
	height: 100px;
	font-size: 2em;
	font-weight: bold;
	text-transform: uppercase;
}
.vt-bots-tank-controller .controller .command .fire:before {
	content: '\f05b';
	font-family: fontawesome;
	font-weight: normal;
	display: block;
	font-size: 1.5em;
}
@media (max-width: 720px) {
	.vt-bots-tank-controller .controller .command {
		padding: 42px 0;
		width: 150px;
	}
	.vt-bots-tank-controller .controller .command .fire {
		width: 66px;
		height: 66px;
		font-size: 1.5em;
	}
}
@media (max-width: 500px) {
	.vt-bots-tank-controller .controller .command {
		padding: 25px 0;
		width: 100px;
	}
	.vt-bots-tank-controller .controller .command .fire {
		width: 50px;
		height: 50px;
		font-size: 1em;
	}
}

@media (max-width: 400px) {
	.vt-bots-tank-controller .controller .command {
		width: 50px;
	}
}

</style>
<div class="vt-bots-tank-controller">
	<div class="team" style="background-color: <?php echo esc_attr( $team_data['color'] ); ?>;">
		<?php _e($team_data['name']);?>
		<a href="?teamchange=1">change teams?</a>
	</div>
	<div class="score-left">
		Left Tank:
		<div class="value"><span class="tick"></span><span class="tick"></span><span class="tick"></span><span class="tick"></span></div>
	</div>
	<div class="score-right">
		Right Tank:
		<div class="value"><span class="tick"></span><span class="tick"></span><span class="tick"></span><span class="tick"></span></div>
	</div>

	<?php if( !$this->viewer_is_local_ip() ) : ?>
	<div class="stream-embed">
		<div class="inner"><?php echo $team_data['embed']?></div>
	</div>
	<?php endif; ?>

	<div class="message"></div>
	<div class="controller">
		<div class="dpad">
			<button class="forwards" name="command" value="forwards"></button>
			<button class="right" name="command" value="right"></button>
			<button class="backwards" name="command" value="backwards"></button>
			<button class="left" name="command" value="left"></button>
		</div>
		<div class="resolution">
			<!--
			<div class="resolution-label">Speed</div>
			<button name="resolution" value="-">&minus;</button>
			<button name="resolution" value="|"><i class="fa fa-ban"></i></button>
			<button name="resolution" value="+">&plus;</button>
			-->
		</div>
		<div class="command">
			<button class="fire" name="fire" value="fire">Fire</button>
		</div>
	</div>
</div>
<script>
jQuery(function($) {
	var ajaxurl = "<?php echo $this->ajax_url; ?>";
	var sending_command = false;
	$('button').click(function(e) {
		e.preventDefault();
		if(sending_command) {
			return;
		}
		sending_command = true;
		var command = $(this).val();
		console.log(command);
		var data = {
			'action' : 'vtbots_tank_send_command',
			'command' : command
		};
		$.ajax({
			url: ajaxurl,
			data: data,
			method: 'POST',
		}).always(function() {
			sending_command = false;
		});
	});
	var seen_events = [];
	var max_health = <?php echo esc_attr( $team_data['health'] ); ?>;
	var poll_for_score = function() {
		let data = {
			'action': 'vtbots_tank_get_score'
		};
		$.ajax({
			url: ajaxurl,
			data: data,
			method: 'POST',
		}).done(function( data ) {
			console.log(data);
			var event = data.event;
			var $message = $('.vt-bots-tank-controller .message');
			if( event && event.event_id ) {
				if( !seen_events.includes( event.event_id ) ) {
					seen_events.push( event.event_id );
					$message.html( event.message );
				} else {
					$message.html("");
				}
			} else {
				$message.html("");
			}
			if( data.scores ) {
				$('.score-left .value').html('');
				$('.score-right .value').html('');
				for( var i = 0; i < max_health; i++ ) {
					if( i < max_health - data.scores.left ) {
						$('.score-left .value').append('<div class="tick"></div>');
					} else {
						$('.score-left .value').append('<div class="tick hit"></div>');
					}
					if( i < max_health - data.scores.right ) {
						$('.score-right .value').append('<div class="tick"></div>');
					} else {
						$('.score-right .value').append('<div class="tick hit"></div>');
					}
				}
			}
		}).always(function() {
			setTimeout(function() {
				poll_for_score();
			}, 3000);
		});
	}
	poll_for_score();
});
</script>
