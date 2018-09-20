"use strict";

class Infrared extends EventEmitter {
  constructor(lircdPath) {
    this.lircdPath = lircdPath;
    this.connectLircd();
  }
  sendFireCommand() {
    return this.sendCommand(this.getFireButton());
  }
  sendCommand(command) {
    // command should be a lirc key, like KEY_POWER or KEY_UP
    lirc.sendOnce(this.getRemote(), command).catch(error => {
      if (error) console.log(error);
    });
  }
  receiveCommand(remote, button, repeat) {
    if (remote == getRemote() && button == getFireButton()) {
      console.log('you sunk my battleship! fire button was pressed!');
      this.emit('hit');
    }
    console.log('button ' + button + ' on remote ' + remote + ' was pressed! (repeat ' + repeat + ')');
  }
  getRemote() {
    return 'lab-b-robot-tank';
  }
  getFireButton() {
    return 'KEY_POWER';
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
