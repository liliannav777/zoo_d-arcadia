# syntax = docker/dockerfile:1

# Adjust NODE_VERSION as desired
ARG NODE_VERSION=20.11.1
FROM node:${NODE_VERSION}-slim as base

LABEL fly_launch_runtime="NodeJS"

# NodeJS app lives here
WORKDIR /app

# Set production environment
ENV NODE_ENV=production
ARG YARN_VERSION=1.22.22

# Supprimer les fichiers existants de yarn
RUN rm -f /usr/local/bin/yarn /usr/local/bin/yarnpkg

# Installer Yarn
RUN npm install -g yarn@$YARN_VERSION

# Throw-away build stage to reduce size of final image
FROM base as build

# Install packages needed to build node modules
RUN apt-get update -qq && \
    apt-get install -y python-is-python3 pkg-config build-essential 

# Install node modules
COPY --link package.json yarn.lock .
RUN yarn install --production=false

# Copy application code
COPY --link . .

# Build application
RUN yarn run build

# Remove development dependencies
RUN yarn install --production=true


# Final stage for app image
FROM base

# Copy built application
COPY --from=build /app /app

# Start the server by default, this can be overwritten at runtime
CMD [ "yarn", "run", "start" ]
