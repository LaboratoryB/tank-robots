const tc_lib     = require("./tank-control.js");
const request    = require("request");
const os         = require("os");
const hostname   = os.hostname();

const Config     = require('./config.js');
let config       = new Config('./private/config.json');
let tank         = new tc_lib.Tank();
let requestDelay = 500;

let register_hit = function() {
  let endpoint   = config.getConfig('command_endpoint');
  let apikey     = config.getConfig('api_key');
  let uri = endpoint + '?action=vtbots_tank_register_hit&hostname=' + hostname + "&apikey=" + apikey;
  request(uri, { json: true }, (err, res, body) => {});
}
tank.set_hit_callback( register_hit );

let request_command = function() {
  let endpoint = config.getConfig('command_endpoint');
  let apikey   = config.getConfig('api_key');
  let uri = endpoint + '?action=vtbots_tank_get_command&hostname=' + hostname + "&apikey=" + apikey;
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
    tank.parseCommand( command, resolution, () => { 
      setTimeout( ()=>{ request_command(); }, requestDelay );
    } );
  });
}
request_command();
