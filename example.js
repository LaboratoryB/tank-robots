const motors = require("./index.js").motors;
const maxSpeed = require("./index.js").maxSpeed;

console.log('motors',motors);

motors.motor2.setSpeed(480);
motors.motor1.setSpeed(480);
