<?php

namespace PantheonSalesEngineering\GoComposer;

class ArchitectureMap
{
    /**
     * @var array
     */
    private static $map = array(
        'AMD64' => 'amd64',
        'x86_64' => 'amd64',
        'i586' => '386',
    );

    /**
     * @param string $phpArchitecture
     * @return string
     */
    public static function getGoArchitecture(string $phpArchitecture): string
    {
        $lowercaseArchitecture = strtolower($phpArchitecture);
        return static::$map[$lowercaseArchitecture] ?? $phpArchitecture;
    }
}