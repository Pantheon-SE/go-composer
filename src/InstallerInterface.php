<?php

namespace PantheonSalesEngineering\GoComposer;


interface InstallerInterface
{

    /**
     * @param string $version
     * @return bool
     */
    function install(string $version): bool;

    /**
     * @return string|false
     */
    function isInstalled();

}