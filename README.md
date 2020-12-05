
## Installation
```
docker run --rm -v $(pwd):/app -w /app  docker_php composer install
```

## Test
```
docker run --rm -v $(pwd):/app -w /app  docker_php php bin/phpunit
```

## Execution
```
docker run --rm -v $(pwd):/app -w /app --interactive --tty docker_php php bin/console app:machine-vending-start
```

## Usage

