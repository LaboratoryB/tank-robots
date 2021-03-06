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
    if ( typeof tankConfig.command_endpoint == 'undefined' ) {
      throw new Error('missing command_endpoint in config');
    }
    if ( typeof tankConfig.api_key == 'api_key' ) {
      throw new Error('missing api_key');
    }
    this.setConfig('general',tankConfig.general);
    this.setConfig('command_endpoint',tankConfig.command_endpoint);
    this.setConfig('api_key',tankConfig.api_key);
    console.log(module.data);
  }
}

module.exports = Config;
