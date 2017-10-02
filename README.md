hab-backend-challenge
=====================

Habitissimo Backend Challenge, by @jcortesa
Mainly, all the challenge code is in the HabApiBundle

# Installing / Getting started

First, clone this repository:

```bash
$ git clone https://github.com/jcortesa/hab-backend-challenge.git
```

Then, build and get the project running:

```bash
$ docker-compose up --build
```

Populate database with categories data

```bash
$ docker exec -ti [docker-instance-id] sh
# php bin/console hab-api:create-categories
```

If something strange happens, delete the docker-compose installation of the project:

```bash
$ docker-compose rm -v
$ docker-compose up --build
```

Access to backend via

```
http://localhost:8000
```

## Licensing

The code in this project is licensed under MIT license.