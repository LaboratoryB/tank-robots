const Gpio = require('pigpio').Gpio;
const testPin = new Gpio(21, {mode:Gpio.OUTPUT});
let dutyCycle = 0;
setInterval(() => {
  testPin.pwmWrite(dutyCycle);
 
  dutyCycle += 5;
  if (dutyCycle > 255) {
    dutyCycle = 0;
  }
}, 20);
