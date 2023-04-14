<?php

namespace PantheonSalesEngineering\GoComposer;


use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\RemoteFilesystem;
use Exception;
use PantheonSalesEngineering\GoComposer\Exception\VersionVerificationException;
use PantheonSalesEngineering\GoComposer\Installer\GoInstaller;

class GoComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var Config
     */
    private $config;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;

        $extraConfig = $this->composer->getPackage()->getExtra();

        if (isset($extraConfig['pantheon-se']['go-composer'])) {
            $this->config = Config::fromArray($extraConfig['pantheon-se']['go-composer']);
        } else {
            $this->config = new Config();
        }
    }

    /**
     * @return \array[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_UPDATE_CMD => [
                ['onPostUpdate', 1]
            ],
            ScriptEvents::POST_INSTALL_CMD => [
                ['onPostUpdate', 1]
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function onPostUpdate(Event $event)
    {
        $fs = new RemoteFilesystem($this->io, $this->composer->getConfig());
        $context = new GoContext(
            $this->composer->getConfig()->get('vendor-dir'),
            $this->composer->getConfig()->get('bin-dir')
        );

        $goInstaller = new GoInstaller(
            $this->io,
            $fs,
            $context,
            $this->config->getGoDownloadUrl()
        );

        $installedGoVersion = $goInstaller->isInstalled();
        $goVersion = 'go' . $this->config->getGoVersion();

        if (
            $installedGoVersion === false ||
            strpos($installedGoVersion, $goVersion) === false
        ) {
            $this->io->write(sprintf(
                'Installing Go (%s)',
                $this->config->getGoVersion()
            ));

            $goInstaller->install($this->config->getGoVersion());

            $installedGoVersion = $goInstaller->isInstalled();
            if (strpos($installedGoVersion, $goVersion) === false) {
                $this->io->write(array_merge(['Bin files:'], glob($context->getBinDir() . '/*.*')), true, IOInterface::VERBOSE);
                throw new VersionVerificationException('Go', $goVersion, $installedGoVersion);
            } else {
                $this->io->overwrite(sprintf(
                    'Go installed (%s)',
                    $this->config->getGoVersion()
                ));
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
