#!/bin/bash
set -e

# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Detect php services dynamically from docker-compose
services=$(docker compose config --services | grep '^php')

if [ -z "$services" ]; then
    echo "❌ No PHP services found in docker-compose.yml"
    exit 1
fi

# Run tests for each PHP service in the foreground
for service in $services; do
    echo "🧪 Running tests in $service..."
    docker compose up --build "$service"
done
