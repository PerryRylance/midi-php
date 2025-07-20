<?php

namespace PerryRylance\Midi;

use PerryRylance\Midi\Collections\TrackCollection;
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

    public function writeBytes(WriteStream $stream): void
    {
        $validator = new FileValidator($this);

        $stream->writeUint(File::HEADER_CHUNK_ID);
        $stream->writeUint(0x6); // NB: Size of the following header

        $validator->validateTrackCount();

        $stream->writeShort($this->type->value);
        $stream->writeShort($this->tracks->count());

        $this->resolution->writeBytes($stream);

        for($i = 0; $i < $this->tracks->count(); $i++)
        {
            $track = $this->tracks[$i];

            $validator->validateTrack($track, $i);

            $track->writeBytes($stream);
        }
    }
}
