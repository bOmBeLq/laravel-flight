## CAE Laravel Assignment
This repository is solution to one of recruitment assignments.
### Task details

[Task description](doc/assignement.md)  
[Roster file](doc/roster.md)


### Setup
- have docker and docker compose installed
- run `bin/setup.sh`
- to execute in container use `bin/docker.sh` or regular `docker` commands

### Testing
`bin/docker.sh vendor/bin/phpunit`

### Accessing endpoints
-   `bin/docker.sh ./artisan serve --port 80 --host 0.0.0.0`
- For list of endpoints visit http://127.0.0.1:5543/api/documentation

### Notes
- I didn't define swagger response formats
- I don't like the `period` filter but whole idea of "nextWeek" is odd. I wouldn't implement it in my API. Just use date from/to filters
- Tests for invalid file are missing
