<?php

namespace App;

use App\Data\ChangeTypes;

class FilesDifference
{
    public array $differences = [];
    
    public function compare(string $firstFile, string $secondFile, bool $strict = false): array
    {
        $lines1 = explode("\n", $firstFile);
        $lines2 = explode("\n", $secondFile);

        if(!$strict) {
            $lines1 = array_map(fn($item) => trim($item), $lines1);
            $lines2 = array_map(fn($item) => trim($item), $lines2);
        }

        $equalLines = LongestCommonSequence::find($lines1, $lines2);
        $usedLines1 = [];
        $usedLines2 = [];

        foreach ($lines1 as $key1 => $line1) {
            if (!in_array($line1, $equalLines)) {
                $found = false;
                foreach ($lines2 as $key2 => $line2) {
                    if (!in_array($key2, $usedLines2)) {
                        if (!in_array($line2, $equalLines)) {
                            if (!in_array($key1, $usedLines1)) {
                                $this->differences[] = new Line($key1 + 1,$line1 . '|' . $line2, ChangeTypes::MODIFIED);
                                $usedLines2[] = $key2;
                                $usedLines1[] = $key1;
                            } else {
                                $this->differences[] = new Line($key1 + 1, $line2, ChangeTypes::NEW);
                                $usedLines2[] = $key2;
                            }
                        } else {
                            $found = true;
                            break;
                        }
                    }
                }

                if ($found && !in_array($key1, $usedLines1)) {
                    $this->differences[] = new Line($key1 + 1,$line1, ChangeTypes::DELETED);
                    $usedLines1[] = $key1;
                }
            } else {
                $this->differences[] = new Line($key1 + 1, $line1, ChangeTypes::SAME);
                $usedLines2[] = array_search($line1, $lines2);
                $usedLines1[] = $key1;
            }
        }

        foreach (array_diff(array_keys($lines1), $usedLines1) as $key1) {
            $this->differences[] = new Line($key1 + 1,$lines1[$key1], ChangeTypes::DELETED);
        }

        foreach (array_diff(array_keys($lines2), $usedLines2) as $key2) {
            $this->differences[] = new Line($key2 + 1,$lines2[$key2], ChangeTypes::NEW);
        }

        return $this->differences;
    }
    
    public function getDifference(): string
    {
        $text = '';

        /** @var Line $line */
        foreach ($this->differences as $line) {
            $text .= $line->row . ' ' . $line->changeType->getSymbol() . ' ' . $line->text . "\n";
        }

        return $text;
    }
}