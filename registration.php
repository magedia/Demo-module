<?php

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

/**
 * @author    Magedia Team
 * @copyright Copyright (c) 2021 Magedia (https://www.magedia.com)
 * @package   Magedia_Core
 * @version   1.0.0
 */

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Magedia_Demo',
    __DIR__
);
