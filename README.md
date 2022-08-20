# Banking Kata - PHP Symfony

## Credit and Acknowledgement

This project is based on the work of [Valentina Cupać ](https://github.com/valentinacupac): [banking-kata-java](https://github.com/valentinacupac/banking-kata-java)

I just wanted to make the same example in a php symfony project.

## Overview

This project illustrates TDD & Clean Architecture implementation in Symfony, showing the Use Case Driven Development
Approach.

We implement a Banking system with the following use cases:

- Open account
- Withdraw funds
- Deposit funds
- View account

## Running the project

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Run tests using `docker compose php vendor/bin/simple-phpunit`
5. Run `docker compose down --remove-orphans` to stop the Docker containers.
