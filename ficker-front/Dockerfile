FROM node:16-alpine

WORKDIR /app

RUN apk add --no-cache curl

COPY package.json yarn.lock /app/

RUN mkdir src && yarn install

COPY . .

ARG env

RUN yarn build

CMD yarn start