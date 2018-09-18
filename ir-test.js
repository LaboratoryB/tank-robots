try {
  const lirc = require('lirc-client')({
    path: '/var/run/lirc/lircd'
  });
  lirc.on('connect', () => {
      lirc.send('VERSION').then(res => {
          console.log('LIRC Version', res);
      });


      lirc.sendOnce('tank-robot', 'fire').catch(error => {
          if (error) console.log(error);
      });
  });

  lirc.on('receive', function (remote, button, repeat) {
      console.log('button ' + button + ' on remote ' + remote + ' was pressed!');
  });

} catch (e) {
  console.err('Error connecting to lircd: ',e);
}
