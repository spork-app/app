FROM node:16

WORKDIR /app

COPY mdns-server.js /app/server.js
COPY package.json /app/package.json

RUN npm install

EXPOSE 5353
EXPOSE 80

CMD ["node", "server.js"]
