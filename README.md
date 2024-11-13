# Talayen


## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation Using Docker](#installation-using-docker)
  - [1. Clone the Repository](#1-clone-the-repository)
  - [2. Configure the `.env` File](#2-configure-the-env-file)
  - [3. Build and Run Containers](#3-build-and-run-containers)
  - [4. Run Migrations and Seeders](#4-run-migrations-and-seeders)
- [Common Issues](#common-issues)

## Prerequisites

To run the application using Docker, ensure you have the following software installed on your machine:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Installation Using Docker

### 1. Clone the Repository

First, clone the project repository from GitHub:

```bash
git clone https://github.com/your-username/your-repository.git
cd your-repository
```

### 2. Configure the `.env` File

```bash
cp .env.example .env
```

### 3. Build and Run Containers

```bash
docker compose up -d --build
```

### 4. Run Migrations and Seeders

```bash
docker compose exec app bash
php artisan migrate --seed
```

### Common Issues

you can use laravel sail 

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
