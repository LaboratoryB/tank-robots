const pigpio = require('pigpio');
const Gpio = pigpio.Gpio;

let ioInitialized = false;
const maxSpeed = 255;

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

function Motors() {
	this.motor1 = new Motor(12, 5);
	this.motor2 = new Motor(13, 6);
}
Motors.prototype.setSpeeds = function(m1Speed, m2Speed) {
	this.motor1.setSpeed(m1Speed);
	this.motor2.setSpeed(m2Speed);
}

module.exports = {
	motors: Motors(),
	maxSpeed: maxSpeed
}
