<?php

namespace PantheonSalesEngineering\GoComposer;

use RuntimeException;

class Config
{
    /**
     * Version of Go.
     * @var string
     */
    private $goVersion;

    /**
     * Template string for downloading Go versions.
     * @var string
     */
    private $goDownloadUrl;

    /**
     * URL to resolve go versions.
     * @var string
     */
    private $goVersionUrl = "https://go.dev/VERSION";

    /**
     * Config constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $conf
     * @return Config
     */
    public static function fromArray(array $conf): Config
    {
        $self = new self();

        $self->goVersion = $conf['go-version'] ?? null;
        $self->goDownloadUrl = $conf['go-download-url'] ?? null;

        if ($self->getGoVersion() === null) {
            throw new RuntimeException('You must specify a go-version');
        }

        return $self;
    }

    /**
     * @return string
     */
    public function getGoVersion(): string
    {
        if (is_string($this->goVersion)) {
            return $this->goVersion;
        }

        return $this->getGoLatest();
    }

    /**
     * @return string|null
     */
    public function getGoDownloadUrl(): ?string
    {
        return $this->goDownloadUrl;
    }

    public function getGoLatest(): string
    {
        $api_url = $this->goVersionUrl . '?m=text';
        $this->goVersion = ltrim(file_get_contents($api_url), "go");
        return $this->goVersion;
    }
}