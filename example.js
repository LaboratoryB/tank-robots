const tc_lib   = require("./index.js");
const tank     = new tc_lib.Tank();

let i = 0;
// Begin test of all functions
// Note: stop will force an early termination of commands, if necessary. in most cases it can be replaced by a "skip turn"
//       fire is not yet implemented
let commands = ['forwards', 'stop', 'backwards', 'left', 'right', 'fire'];
setInterval(() => {
  tank.parseCommand( commands[c] );
  i = ( i + 1 ) % commands.length;
}, 3000);

// TODO: add support for registering an enemy fire callback
//       add support for registering a tank-disabled callback
//       maybe add the ability for tanks to be partially
