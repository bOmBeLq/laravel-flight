<?php

namespace App\Service\RosterImport\Parser;

use App\Models\Enum\ActivityType;

class ParseResultRow
{
    public function __construct(
        private readonly string $activity,
        private readonly ActivityType $type,
        private readonly ?\DateTimeImmutable $checkInTime,
        private readonly ?\DateTimeImmutable $checkOutTime,
        private readonly string $locationFrom,
        private readonly string $locationTo,
        private readonly \DateTimeImmutable $timeFrom,
        private readonly \DateTimeImmutable $timeTo,
    ) {
    }

    public function getActivity(): string
    {
        return $this->activity;
    }

    public function getType(): ActivityType
    {
        return $this->type;
    }

    public function getCheckInTime(): ?\DateTimeImmutable
    {
        return $this->checkInTime;
    }

    public function getCheckOutTime(): ?\DateTimeImmutable
    {
        return $this->checkOutTime;
    }

    public function getLocationFrom(): string
    {
        return $this->locationFrom;
    }

    public function getLocationTo(): string
    {
        return $this->locationTo;
    }

    public function getTimeFrom(): \DateTimeImmutable
    {
        return $this->timeFrom;
    }

    public function getTimeTo(): \DateTimeImmutable
    {
        return $this->timeTo;
    }
}
