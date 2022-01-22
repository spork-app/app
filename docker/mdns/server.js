const express = require('express');
const app = express();
const Bonjour = require('bonjour-service');
const bonjour = new Bonjour.default()

app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET,OPTIONS');
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res.header('Allow-Credentials', 'true');
    next();
})

let servicesToTrack = {}

const services = ['ftp','ssh','http','https','tcp','minecraft','A','SRV','NS',]
    .map(type => bonjour.find({ type }, (service) => servicesToTrack = { ...servicesToTrack, [service.type + '://' +service.host + ':' + service.port]: service }))

app.get('/', (req, res) => res.json(services.flatMap(service => service.services).map(service => {
    delete service.rawTxt;
    return service;
})))

app.listen(3000, () => {
    console.log('mdns listening!')
})

