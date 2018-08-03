const base     = require("./index.js");
const motors   = new base.motors();
const maxSpeed = base.maxSpeed;

console.log('motors',motors);

let motor1 = motors.motor1;
let motor2 = motors.motor2;
console.log('motor1',motor1);
console.log('motor2',motor2);

let maxSafeSpeed = 175;
let currentSpeed = 0;
console.log('wait for first setSpeed command...');
setInterval(() => {
  if ( currentSpeed == maxSafeSpeed ) {
    currentSpeed = 0;
  } else {
    currentSpeed = maxSafeSpeed;
  }
  console.log('setting new currentSpeed: ', currentSpeed);
  motor1.setSpeed(currentSpeed);
  motor2.setSpeed(currentSpeed);
}, 2000);
