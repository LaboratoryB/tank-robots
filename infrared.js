"use strict";

const EventEmitter = require('events');

class Infrared extends EventEmitter {
  constructor(lircdPath) {
    super();
    this.connected = false;
    this.lircdPath = lircdPath;
    this.remote = 'lab-b-tank-robot';
    this.fireButton = 'KEY_POWER';
    this.connectLircd();
  }
  sendFireCommand() {
    return this.sendCommand(this.fireButton);
  }
  sendCommand(command) {
    // command should be a lirc key, like KEY_POWER or KEY_UP
    lirc.sendOnce(this.remote, command).catch(error => {
      if (error) console.log(error);
    });
  }
  receiveCommand(remote, button, repeat) {
    console.log('button ' + button + ' on remote ' + remote + ' was pressed! (repeat ' + repeat + ')');
    if (remote !== this.remote) {
      console.log('ignoring this command, doesn\'t match the correct remote');
      return;
    }
    if (button !== this.fireButton) {
      console.log('not the fire button.');
    } else {
      console.log('you sunk my battleship! fire button was pressed!');
      this.emit('hit');
    }
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
