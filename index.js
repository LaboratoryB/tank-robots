const tc_lib   = require("./tank-control.js");
const request  = require("request");
const os       = require("os");
const hostname = os.hostname();
const endpoint = 'https://vtbots.com/wp-content/plugins/vtbots-tank-controller/fast-ajax.php';

const Config   = require('./config.js');
let config     = new Config('./private/config.json');
let tank       = new tc_lib.Tank();

let request_command = function() {
  let uri = endpoint + '?action=vtbots_tank_get_command&hostname=' + hostname;
  request(uri, { json: true }, (err, res, body) => {
    let command = 'wait';
    let resolution = 1;
    if (err) {
      console.log("Error getting command: ", err);
    } else {
      command = body.command;
      resolution = body.resolution;
      console.log( "Response from server: got command ", command, " and resolution ", resolution );
    }
    tank.parseCommand( command, resolution, () => { request_command(); } );
  });
}
request_command();

// TODO: add support for registering an enemy fire callback (and sending to server)
//       add support for registering a tank-disabled callback (and sending to server)
//       maybe add the ability for tanks to be partially disabled?
