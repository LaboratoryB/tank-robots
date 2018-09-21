<?php if(!defined('ABSPATH')) { die(); }
?>
<style>

body {
	background-color: #000;
}
.vt-bots-tank-scoreboard {
	position: relative;
	background-color: #000;
	font-family: arial, helvetica, sans-serif;
	padding: 15px;
	width: 500px;
	height: 700px;
	line-height: 1.45;
}
.vt-bots-tank-scoreboard, .vt-bots-tank-scoreboard *, .vt-bots-tank-scoreboard *:before, .vt-bots-tank-scoreboard *:after {
	box-sizing: border-box;
}

.vt-bots-tank-scoreboard .score-left,
.vt-bots-tank-scoreboard .score-right {
	display: block;
	position: relative;
	color: #fff;
	font-weight: bold;
	font-size: 30px;
	text-transform: uppercase;
	text-align: left;
	margin: 15px 0;
	padding-left: 15px;
	border-left: 30px solid;
}
.vt-bots-tank-scoreboard .score-left {
	border-left-color: #f00;
}
.vt-bots-tank-scoreboard .score-right {
	border-left-color: #00f;
}

.vt-bots-tank-scoreboard .score-left .tick,
.vt-bots-tank-scoreboard .score-right .tick {
	display: inline-block;
	width: 60px;
	height: 90px;
	background-color: #fff;
	margin: 2px;
}
.vt-bots-tank-scoreboard .score-left .tick.hit,
.vt-bots-tank-scoreboard .score-right .tick.hit {
	background-color: #444;
}

@media screen and (max-width: 430px) {
	.vt-bots-tank-scoreboard .score-left,
	.vt-bots-tank-scoreboard .score-right {
		width: 80px;
		font-size: 10px;
	}
	.vt-bots-tank-scoreboard .score-left .tick,
	.vt-bots-tank-scoreboard .score-right .tick {
		width: 8px;
		height: 16px;
		margin: 1px;
	}
}

.vt-bots-tank-scoreboard .message {
	color: #fff;
	margin: 30px auto;
	max-width: 550px;
	text-align: center;
	font-weight: bold;
	font-size: 1.2em;
	background-color: #F00;
	min-height: 25px;
}
.vt-bots-tank-scoreboard .message:empty {
	background-color: transparent;
}

</style>
<div class="vt-bots-tank-scoreboard">
	<div class="score-left">
		Left Tank:
		<div class="value"><span class="tick"></span><span class="tick"></span><span class="tick"></span><span class="tick"></span></div>
	</div>
	<div class="score-right">
		Right Tank:
		<div class="value"><span class="tick"></span><span class="tick"></span><span class="tick"></span><span class="tick"></span></div>
	</div>
	<div class="message"></div>
</div>
<script>
jQuery(function($) {
	var ajaxurl = "<?php echo $this->ajax_url; ?>";
	var seen_events = [];
	var max_health = 4;
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
			var $message = $('.vt-bots-tank-scoreboard .message');
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
