<?php if(!defined('ABSPATH')) { die(); }
?>
<style>
body {
	background-color: #666;
	color: #fff;
}
.tank-battle-login-prompt {
	margin: 15px auto;
	max-width: 400px;
	padding: 15px;
}
.tank-battle-login-prompt h1 {
	color: inherit;
	text-align: center;
}
.tank-battle-login-prompt .button_wrap {
	text-align: center;
}
.tank-battle-login-prompt .btn {
	display: inline-block;
	padding: 50px 20px;
	width: 120px;
	background-color: #ccc;
	color: #444;
	font-weight: bold;
	text-align: center;
}
</style>
<div class="tank-battle-login-prompt">
	<h1>Login or Register to Continue</h1>
	<p>
		To select your team and start playing tank battle, please login or create an account below. This account is completely free.
	</p>
	<div class="button_wrap">
		<a href="<?php echo wp_login_url( get_permalink() ); ?>" class="btn">Login</a>
		<span class="or">or</span>
		<a href="<?php echo wp_registration_url(); ?>" class="btn">Register</a>
	</div>
</div>
