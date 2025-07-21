# midi-php
A package for reading and writing MIDI files and streams in PHP.

## Testing
> TODO: Docs and script for run tests on all versions of PHP

- `docker compose up`
- `docker exec -t php84 composer test` (replacing `php84` with whichever version you wish to target)

## TODO
- PHPStan
- Formatter
- Make robust against misusing trait (eg private vars cause __)
- MIDI visualizer (eg hex viewer with bits highlighted) would be amazing
