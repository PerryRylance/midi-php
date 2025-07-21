# midi-php
A package for reading and writing MIDI files and streams in PHP.

## Usage
### Reading MIDI data
- Get your data into a binary string
- Instantiate a `new ReadStream` with your binary data
- Instantiate a `new File`
- Call `readBytes` passing in your `ReadStream`

### Creating MIDI data
- Instantiate a `new File`
- Instantiate a `new Track` and push it to your files `tracks`
- Instantiate any subclasses of `Event` you need, eg `NoteOnEvent`
- Push your events to the tracks `events`
- Don't forget `EndOfTrackEvent`!

### Writing MIDI data
- You'll need a `File` either read in or created from scratch as described above
- Instantiate a `new WriteStream`
- Call `writeBytes` on your `File` passing in your `WriteStream`
- Use `toBinary` on your `WriteStream` to get the binary data

## Testing
> TODO: Docs and script for run tests on all versions of PHP

- `docker compose up`
- `docker exec -t php84 composer test` (replacing `php84` with whichever version you wish to target)

## Credits
- With thanks to [Recording Blogs](https://www.recordingblogs.com/wiki/musical-instrument-digital-interface-midi), [Teragon Audio](http://midi.teragonaudio.com/tech/midispec/run.htm) and [Mido](https://mido.readthedocs.io/en/latest/meta_message_types.html) for insight into the MIDI spec.
- With thanks to [jazz-soft](https://github.com/jazz-soft/test-midi-files) for the test files.
- With thanks to Jeff Boudier for [MIDIopsy](https://github.com/jeffbourdier/MIDIopsy/releases/tag/v1.2)
