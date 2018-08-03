const base     = require("./index.js");
const motors   = new base.motors();
const maxSpeed = base.maxSpeed;

console.log('motors',motors);

let motor1 = motors.motor1;
let motor2 = motors.motor2;
console.log('motor1',motor1);
console.log('motor2',motor2);

motor1.setSpeed(128);
motor2.setSpeed(128);
