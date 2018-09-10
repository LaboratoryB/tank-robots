const pigpio = require('pigpio');
const Gpio = pigpio.Gpio;

let ioInitialized = false;
const maxSpeed = 255; // this is the range the PWM library supports it may damage motors over time
const tankSpeed = 100; // this is the PWM setting we'll be using live, to protect the motors
const commandDuration = 1000; // how long will each command run, in milliseconds

function ioInit() {
	if(ioInitialized) return;
	pigpio.configureClock(1, pigpio.CLOCK_PCM);
	ioInitialized = true;
}

function Motor(pwmPin, dirPin) {
	ioInit();
	this.motor = new Gpio(pwmPin, {mode: Gpio.OUTPUT});
	this.direction = new Gpio(dirPin, {mode: Gpio.OUTPUT});
}

Motor.prototype.setSpeed = function(speed) {
	var dirValue;
	if(speed < 0) {
		speed = -speed;
		dirValue = 1;
	} else {
		dirValue = 0;
	}
	if( speed > maxSpeed ) {
		speed = 255;
	}
	this.direction.digitalWrite(dirValue);
	this.motor.pwmWrite(speed);
}

function Treads() {
	this.motor1 = new Motor(12, 5);
	this.motor2 = new Motor(13, 6);
}
Treads.prototype.setSpeeds = function(m1Speed, m2Speed) {
	this.motor1.setSpeed(m1Speed);
	this.motor2.setSpeed(m2Speed);
}

function Turret() {
	// TODO: this is a stub for eventual firing control
}

Turret.prototype.fire = function() {
	// TODO: another stub for firing control
	// Note: unlike tread controls, fire is an action, not a state, and only occurs once when called
}

// TODO: fire JavaScript event when hit registered
// TODO: fire JavaScript event when destroyed

function Tank() {
	this.treads = new Treads();
	this.turret = new Turret();
}

Tank.prototype.stop = function() {
	this.treads.setSpeeds( 0, 0 );
}

Tank.prototype.forwards = function() {
	this.treads.setSpeeds( tankSpeed, tankSpeed );
}

Tank.prototype.backwards = function() {
	this.treads.setSpeeds( -1*tankSpeed, -1*tankSpeed );
}

Tank.prototype.left = function() {
	this.treads.setSpeeds( -1*tankSpeed, tankSpeed );
}

Tank.prototype.right = function() {
	this.treads.setSpeeds( tankSpeed, -1*tankSpeed );
}

Tank.prototype.fire = function() {
	this.turret.fire(); // Note: unlike tread controls, fire is an action, not a state, and only occurs once
}

Tank.prototype.parseCommand = function( command, intensity=1, callback=() => {} ) {
	// Note: accepted commands are: stop,forwards,backwards,left,right,fire
	// intensity allows us to optionally control duration, for finer aiming (range: 0-1)
	let d = intensity * commandDuration;
	switch(command) {
		case "stop": // stop is rarely needed, since all commands terminate with a stop
			this.stop();
			break;
		case "forwards":
			this.forwards();
			break;
		case "backwards":
			this.backwards();
			break;
		case "left":
			this.left();
			break;
		case "right":
			this.right();
			break;
		case "fire":
			this.fire();
			break;
	}
	setTimeout( () => {
		this.stop();
		callback();
	}, d );
}


module.exports = {
	Tank: Tank,
	maxSpeed: maxSpeed,
	tankSpeed: tankSpeed,
	commandDuration: commandDuration
}
