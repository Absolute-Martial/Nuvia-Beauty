FROM node:24.15.0-alpine

ENV NEXT_TELEMETRY_DISABLED=1
ENV APPLICATION_MODE=production
ENV NEXT_PUBLIC_REST_API_ENDPOINT=http://localhost
ENV NEXT_PUBLIC_SHOP_URL=http://localhost:3003
ENV NEXT_PUBLIC_AUTH_TOKEN_KEY=AUTH_CRED
ENV NEXT_PUBLIC_DEFAULT_LANGUAGE=en
ENV NEXT_PUBLIC_ENABLE_MULTI_LANG=false
ENV NEXT_PUBLIC_AVAILABLE_LANGUAGES=en
ENV REVALIDATE_DURATION=120

RUN apk add --no-cache yarn

WORKDIR /app

COPY package.json .yarnrc ./
COPY admin-panel/package.json ./admin-panel/package.json
COPY storefront/package.json ./storefront/package.json
COPY vendor-portal/package.json ./vendor-portal/package.json

RUN yarn install --network-timeout 600000

COPY . .

WORKDIR /app/storefront

RUN yarn build

EXPOSE 3003

CMD ["yarn", "start"]
