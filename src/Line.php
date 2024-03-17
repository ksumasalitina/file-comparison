<?php

namespace App;

use App\Data\ChangeTypes;

class Line
{
    public function __construct(
        public int $row,
        public string $text,
        public ChangeTypes $changeType,
    ) {}
}