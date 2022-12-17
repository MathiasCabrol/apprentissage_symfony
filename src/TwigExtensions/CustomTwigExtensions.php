<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CustomTwigExtensions extends AbstractExtension 
{
    public function getFilters()
    {
        return [
            new TwigFilter('easyStringCut', [$this, 'easyStringCut']),
            new TwigFilter('ceil', [$this, 'ceil'])
        ];
    }

    public function easyStringCut(string $string): string 
    {
        $newString = substr($string, 3);
        return $newString;
    }

    public function ceil(float $number): int
    {
        return ceil($number);
    }
}