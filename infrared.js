"use strict";

class Infrared {
  constructor(lircdPath) {
    this.lircdPath = lircdPath;
    connectLircd();
  }
  sendFireCommand() {
    return this.sendCommand('KEY_POWER');
  }
  sendCommand(command) {
    // command should be a lirc key, like KEY_POWER or KEY_UP
    lirc.sendOnce('lab-b-robot-tank', command).catch(error => {
      if (error) console.log(error);
    });
  }
  receiveCommand(remote, button, repeat) {
    console.log('button ' + button + ' on remote ' + remote + ' was pressed! (repeat: ' + repeat + ')');
  }
  connectLircd() {
    if (typeof this.connected != 'undefined' && this.connected) {
      return true; // already connected
    }
    try {
      // initialize connection to lircd at specified path
      const lirc = require('lirc-client')({
        path: this.lircdPath
      });
      // export for use elsewhere, after connecting
      this.lirc = lirc;
      lirc.on('connect', () => {
        this.connected = true;
        lirc.send('VERSION').then(res => {
            console.log('LIRC Version', res);
        });
      });
      lirc.on('receive', this.receiveCommand);
    } catch (e) {
      console.err('Error connecting to lircd: ',e);
    }
  }
}

module.exports = Infrared;
