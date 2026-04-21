<?php

namespace OmniGuard\Data\Support\Partials\Segments;

class AllPartialSegment extends PartialSegment
{
    public function __toString(): string
    {
        return '*';
    }
}
