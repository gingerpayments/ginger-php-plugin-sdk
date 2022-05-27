<?php

namespace GingerPluginSdk\Entities;

use GingerPluginSdk\Bases\BaseField;
use GingerPluginSdk\Helpers\MultiFieldsEntityTrait;
use GingerPluginSdk\Helpers\SingleFieldTrait;
use GingerPluginSdk\Interfaces\MultiFieldsEntityInterface;
use JetBrains\PhpStorm\Pure;

final class Client implements MultiFieldsEntityInterface
{
    use MultiFieldsEntityTrait;
    use SingleFieldTrait;

    protected string $propertyName = 'client';
    private BaseField $userAgent;
    private BaseField $platformName;
    private BaseField $platformVersion;
    private BaseField $pluginName;
    private BaseField $pluginVersion;

    /**
     * @param string $userAgent - HTTP user agent
     * @param string $platformName - Name of the software used to connect to the API, e.g. Magento Community Edition
     * @param string $platformVersion - Version of the software used to connect to the API, e.g. 1.9.2.2
     * @param string $pluginName - Name of the plugin used to connect to the API, e.g. ginger-magento
     * @param string $pluginVersion - Version of the plugin used to connect to the API, e.g. 1.0.0
     */
    public function __construct(
        string $userAgent,
        string $platformName,
        string $platformVersion,
        string $pluginName,
        string $pluginVersion
    )
    {
        $this->userAgent = $this->createSimpleField(
            propertyName: 'user_agent',
            value: $userAgent
        );
        $this->platformName = $this->createSimpleField(
            propertyName: 'platform_name',
            value: $platformName
        );
        $this->platformVersion = $this->createSimpleField(
            propertyName: 'platform_version',
            value: $platformVersion
        );
        $this->pluginName = $this->createSimpleField(
            propertyName: 'plugin_name',
            value: $pluginName
        );
        $this->pluginVersion = $this->createSimpleField(
            propertyName: 'plugin_version',
            value: $pluginVersion
        );
    }

    #[Pure] public function getUserAgent(): string
    {
        return $this->userAgent->get();
    }

    #[Pure] public function getPlatformName(): string
    {
        return $this->pluginName->get();
    }

    #[Pure] public function getPlatformVersion(): string
    {
        return $this->platformVersion->get();
    }

    #[Pure] public function getPluginName(): string
    {
        return $this->pluginName->get();
    }

    #[Pure] public function getPluginVersion(): string
    {
        return $this->pluginVersion->get();
    }
}