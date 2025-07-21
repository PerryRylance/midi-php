<?php

namespace PerryRylance\Midi\Events\Factories;

use PerryRylance\Midi\Events\Control\AftertouchEvent;
use PerryRylance\Midi\Events\Control\ChannelAftertouchEvent;
use PerryRylance\Midi\Events\Control\ControlEvent;
use PerryRylance\Midi\Events\Control\ControlEventType;
use PerryRylance\Midi\Events\Control\ControllerEvent;
use PerryRylance\Midi\Events\Control\NoteOffEvent;
use PerryRylance\Midi\Events\Control\NoteOnEvent;
use PerryRylance\Midi\Events\Control\PitchWheelEvent;
use PerryRylance\Midi\Events\Control\ProgramChangeEvent;
use PerryRylance\Midi\Exceptions\ParseException;
use PerryRylance\Midi\Streams\ReadStream;
use PerryRylance\Midi\Streams\StatusBytes;

class ControlEventFactory
{
	public static function fromStream(ReadStream $stream, int $leading, int $delta, StatusBytes $status): ControlEvent
	{
		$running = ($leading & 0xF0) < 0x80;

		if ($running) {
			$type = $status[0];
			$channel = $status[1];

			$stream->seekRelative(-1);
		} else {
			$status[0] = $type = $leading & 0xF0;
			$status[1] = $channel = $leading & 0x0F;
		}

		switch (ControlEventType::tryFrom($type)) {
			case ControlEventType::NOTE_ON:
				$result = new NoteOnEvent($delta, $channel);
				break;

			case ControlEventType::NOTE_OFF:
				$result = new NoteOffEvent($delta, $channel);
				break;

			case ControlEventType::AFTERTOUCH:
				$result = new AftertouchEvent($delta, $channel);
				break;

			case ControlEventType::CONTROLLER:
				$result = new ControllerEvent($delta, $channel);
				break;

			case ControlEventType::PITCH_WHEEL:
				$result = new PitchWheelEvent($delta, $channel);
				break;

			case ControlEventType::PROGRAM_CHANGE:
				$result = new ProgramChangeEvent($delta, $channel);
				break;

			case ControlEventType::CHANNEL_AFTERTOUCH:
				$result = new ChannelAftertouchEvent($delta, $channel);
				break;

			default:
				throw new ParseException("Invalid control event type 0x" . dechex($type));
		}

		$result->readBytes($stream);

		return $result;
	}
}
