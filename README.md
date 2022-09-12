# Velv Challenge

## Requirements

- Docker + Docker Compose
- Node.JS + NPM

## Installation

1. Clone this repository and navigate into it
2. Run the container environment installation: `docker-compose up -d`
3. Execute the backend dependencies installation: `docker exec velv-backend-php composer install`
4. Navigate into the frontend directory: `cd src/frontend/`
5. Execute the frontend dependencies installation: `npm install`
6. Run the frontend: `npm run dev`

## Usage

The backend API will be available on http://localhost:8000
The frontend will be available on http://localhost:5173

## Tests

There are unit tests and application tests for the backend. Run them using the docker environment:

```docker exec velv-backend-php php bin/phpunit```
