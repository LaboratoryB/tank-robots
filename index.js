const wpi = require("wiring-pi")

const maxSpeed = 480
const ioInitialized = false

function ioInit() {
	if(ioInitialized) return
	
	wpi.wiringPiSetupGpio()
	wpi.pinMode(12, wpi.GPIO.PWM_OUTPUT)
	wpi.pinMode(13, wpi.GPIO.PWM_OUTPUT)

	wpi.pwmSetMode(wpi.GPIO.PWM_MODE_MS)
	wpi.pwmSetRange(maxSpeed)
	wpi.pwmSetClock(2)

	wpi.pinMode(5, wpi.GPIO.OUTPUT)
	wpi.pinMode(6, wpi.GPIO.OUTPUT)

	ioInitialized = true
}

function Motor(pwmPin, dirPin) {
	this.pwmPin = pwmPin
	this.dirPin = dirPin
}

Motor.prototype.setSpeed(speed) {
	var dirValue
	if(speed < 0) {
		speed = -speed
		dirValue = 1
	} else {
		dirValue = 0
	}
	ioInit()
	wpi.digitalWrite(this.dirPin, dirValue)
	wpi.pwmWrite(this.pwmPin, speed)
}

function Motors() {
	this.motor1 = new Motor(12, 5)
	this.motor2 = new Motor(13, 6)
}
Motors.prototype.setSpeeds(m1Speed, m2Speed) {
	this.motor1.setSpeed(m1Speed)
	this.motor2.setSpeed(m2Speed)
}

module.exports = {
	motors: Motors(),
	maxSpeed: maxSpeed
}
