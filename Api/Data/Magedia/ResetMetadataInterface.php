<?php

declare(strict_types=1);

namespace Magedia\Demo\Api\Data\Magedia;

interface ResetMetadataInterface
{
    public const DEMO_RESET_CONFIG_TABLE = 'magedia_reset_config';

    public const CUSTOM_TABLE_LIKE = '%magedia%';

    public const UNAVAILABLE_MODULES = [
        'Demo',
        'Core',
        'DemoNavigation'
    ];
}
