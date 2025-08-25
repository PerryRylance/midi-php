<?php

namespace PerryRylance\Midi;

use Linna\TypedArrayObject\ArrayOfClasses;
use PerryRylance\Midi\Collections\EventCollection;
use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Event;
use PerryRylance\Midi\Events\Factories\EventFactory;
use PerryRylance\Midi\Events\Meta\EndOfTrackEvent;
use PerryRylance\Midi\Exceptions\MissingEndOfTrackException;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Exceptions\UnsupportedTrackException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;
use PerryRylance\Midi\Streams\WriteStream;
use PerryRylance\Midi\Validators\TrackValidator;

class Track
{
	const HEADER_CHUNK_ID = 0x4D54726B;

	public EventCollection $events;

	public function __construct()
	{
		$this->events = new EventCollection();
	}

	public function readBytes(ReadStream $stream): void
	{
		if ($stream->readUint() !== Track::HEADER_CHUNK_ID) {
			throw new UnsupportedTrackException($stream, 'Expected MTrk, only MIDI trakcs are supported presently');
		}

		$chunkSize = $stream->readUint();
		$status = new StatusBytes();

		$bytes = $cursor = 0;
		$eot = false;

		/** @var Event $event */
		$event = null;

		while ($bytes < $chunkSize) {
			if ($eot) {
				throw new ParseException($stream, 'Unexpected end of track event');
			}

			$cursor = $stream->getPosition();

			$delta = $stream->readVlv();

			$event = EventFactory::fromStream($stream, $status, $delta);

			if (!($event instanceof ControlEvent)) {
				$status[0] = $status[1] = 0;
			} // NB: Not a control event, reset status bytes

			if ($event instanceof EndOfTrackEvent) {
				$eot = true;
			}

			$this->events->append($event);

			$bytes += $stream->getPosition() - $cursor;
		}

		if (!$eot) {
			throw new ParseException($stream, 'Expected end of track event');
		}

		if ($bytes < $chunkSize) {
			throw new ParseException($stream, 'Expected bytes read to be equal to specified chunk size');
		}
	}

	public function writeBytes(WriteStream $stream, ?int $options = 0): void
	{
		$validator = new TrackValidator($this);

		$stream->writeUint(Track::HEADER_CHUNK_ID);

		$wroteEndOfTrack = false;
		$chunkSizePosition = $stream->getPosition();

		$stream->writeUint(0); // NB: Temporarily write zero for chunk size, we'll alter this later

		$status = new StatusBytes();

		for ($i = 0; $i < $this->events->count(); $i++) {
			$event = $this->events[$i];

			if($event instanceof EndOfTrackEvent &&
				$options & TrackSerializationOptions::AUTOMATIC_END_OF_TRACK && 
				$i < $this->events->count() - 1)
				continue; // TODO: Carry delta when replacing this? It'll just eat the delta right now

			try{
				$validator->validateEvent($event, $i);
			}catch(MissingEndOfTrackException $e) {
				if(!($options & TrackSerializationOptions::AUTOMATIC_END_OF_TRACK))
					throw $e;
			}

			// NB: Delta written here. Delta is a track concept and not related to pure events. We do this here so that events can be streamed in real time.
			$stream->writeVlv($event->delta);

			$event->writeBytes($stream, $status);

			if (!($event instanceof ControlEvent)) {
				$status[0] = $status[1] = 0;
			} // NB: Reset status bytes

			if($event instanceof EndOfTrackEvent)
				$wroteEndOfTrack = true;
		}

		if($options & TrackSerializationOptions::AUTOMATIC_END_OF_TRACK && !$wroteEndOfTrack)
		{
			$stream->writeVlv(0);
			(new EndOfTrackEvent())->writeBytes($stream, $status);
		}

		$trackEndPosition = $stream->getPosition();
		$chunkSize = $trackEndPosition - $chunkSizePosition - 4; // NB: Delta bytes minus the 4 bytes for the chunk uint itself

		$validator->assertValidSize($chunkSize);

		$stream->seekTo($chunkSizePosition);
		$stream->writeUint($chunkSize);

		$stream->seekTo($trackEndPosition);
	}
}
