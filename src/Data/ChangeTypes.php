<?php

namespace App\Data;

enum ChangeTypes
{
    case NEW;
    case DELETED;
    case MODIFIED;
    case SAME;

    public function getSymbol(): string
    {
        return match ($this) {
            self::NEW => '+',
            self::DELETED => '-',
            self::MODIFIED => '*',
            self::SAME => ' ',
        };
    }
}
