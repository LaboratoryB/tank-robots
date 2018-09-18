"use strict";

class Config {
  constructor(configFile) {
    this.configFile = configFile;
    this.loadConfig();
  }

  fsReadConfig(filename) {
    try {
      var fs         = require('fs');
      var data       = fs.readFileSync(filename);
      var json       = JSON.parse(data);
      return json;
    } catch (e) {
      console.log('Error trying to load config file ' + filename + ': ' + e);
      return null;
    }
  }

  setConfig(name, data) {
    if ( typeof module.data == 'undefined' ) {
      module.data = [];
    }
    module.data[name] = data;
    return true;
  }

  getConfig(name) {
    if ( typeof module.data == 'undefined' ) {
      module.data = [];
    }
    if ( typeof module.data[name] != 'undefined' ) {
      return module.data[name];
    }
    return null;
  }

  loadConfig() {
    var tankConfig = this.fsReadConfig( this.configFile );
    //console.log(tankConfig);
    if ( typeof tankConfig.general == 'undefined' ) {
      throw new Error('missing general config json');
    }
    this.setConfig('general',tankConfig.general);
    console.log(module.data);
  }
}

module.exports = Config;
