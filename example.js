const motors = require("./index.js").motors;
const maxSpeed = require("./index.js").maxSpeed;

console.log('motors',motors);

let motor2 = motors.motor2;
console.log('motor2',motor2);

motor2.setSpeed(480);
//motors.motor1.setSpeed(480);
