<?php

namespace PerryRylance\Midi;

use PerryRylance\Midi\Collections\TrackCollection;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Validators\FileValidator;

class File
{
	const HEADER_CHUNK_ID = 0x4D546864;

	public TrackCollection $tracks;
	public FileType $type;
	public Resolution $resolution;

	public function __construct()
	{
		$this->tracks = new TrackCollection();
		$this->type = FileType::TYPE_1;
		$this->resolution = new Resolution();
	}

	private function readHeader(ReadStream $stream): int
	{
		$signature = $stream->readUint();

		if ($signature !== File::HEADER_CHUNK_ID) {
			throw new ParseException($stream, "Expected MThd");
		}

		$size = $stream->readUint();

		if ($size !== 6) {
			throw new ParseException($stream, 'Expected header size to be 6');
		}

		$this->type = FileType::tryFrom($stream->readShort());

		if ($this->type === null) {
			throw new ParseException($stream, 'Invalid file type');
		}

		$numTracks = $stream->readShort();

		$this->resolution->readBytes($stream);

		return $numTracks;
	}

	public function readBytes(ReadStream $stream): void
	{
		$numTracks = $this->readHeader($stream);

		$this->tracks = new TrackCollection();

		for ($i = 0; $i < $numTracks; $i++) {
			$track = new Track();
			$track->readBytes($stream);

			$this->tracks->append($track);
		}

		if ($stream->getPosition() < $stream->getLength()) {
			throw new ParseException($stream, 'Unexpected data after parsing file');
		}
	}

	public function writeBytes(WriteStream $stream, int $trackSerializationOptions = TrackSerializationOptions::AUTOMATIC_END_OF_TRACK): void
	{
		$validator = new FileValidator($this);

		$stream->writeUint(File::HEADER_CHUNK_ID);
		$stream->writeUint(0x6); // NB: Size of the following header

		$validator->validateTrackCount();

		$stream->writeShort($this->type->value);
		$stream->writeShort($this->tracks->count());

		$this->resolution->writeBytes($stream);

		for ($i = 0; $i < $this->tracks->count(); $i++) {
			/** @var Track $track */
			$track = $this->tracks[$i];

			$validator->validateTrack($track, $i);

			$track->writeBytes($stream, $trackSerializationOptions);
		}
	}
}
