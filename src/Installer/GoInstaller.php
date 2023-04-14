<?php

namespace PantheonSalesEngineering\GoComposer\Installer;

use Composer\IO\IOInterface;
use Composer\Util\RemoteFilesystem;
use PantheonSalesEngineering\GoComposer\Installer;
use PantheonSalesEngineering\GoComposer\GoContext;

class GoInstaller extends Installer
{

    /**
     * NodeDownloader constructor.
     * @param IOInterface $io
     * @param RemoteFilesystem $remoteFs
     * @param GoContext $context
     * @param string|null $downloadUriTemplate
     */
    public function __construct(
        IOInterface      $io,
        RemoteFilesystem $remoteFs,
        GoContext        $context,
        string           $downloadUriTemplate = null
    ) {

        // Declare download template.
        $downloadUriTemplate = (!empty($downloadUriTemplate)) ? $downloadUriTemplate : 'https://go.dev/dl/go${version}.${osType}-${architecture}.${format}';

        // Setup paths for executables.
        $executableList = [
            'go' => [
                'nix' => 'bin/go',
                'link' => 'go',
                'win' => 'go.exe',
            ]
        ];

        // Declare command to check if installed.
        $installedCommand = ["go", "version"];

        // Initialize object.
        parent::__construct($io, $remoteFs, $context, $downloadUriTemplate, $installedCommand, $executableList);
    }
}