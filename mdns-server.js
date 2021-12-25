const express = require('express');
const app = express();
const Bonjour = require('bonjour-service');

const bonjour = new Bonjour.default()

let services = {};
const searching = bonjour.find({ type: 'http' }, function (service) {
    const key = service.type + '://' +service.host + ':' + service.port;

    console.log(service)
    services = {
        ...services,
        [key]: service
    };
})
  

app.get('/', function (req, res) {
  searching.update();
  res.json(services);
})

app.listen(3000, () => {
    console.log('mdns listening on port 3000!')
})
