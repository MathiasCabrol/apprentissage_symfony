<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtensions extends AbstractExtension 
{
    public function getFilters()
    {
        return [
            new TwigFilter('easyStringCut', [$this, 'easyStringCut']),
        ];
    }

    public function easyStringCut(string $string): string 
    {
        $newString = substr($string, 3);
        return $newString;
    }
}