<?php
/**
 * @author    Magedia Team
 * @copyright Copyright (c) 2021 Magedia (https://www.magedia.com)
 * @package   Magedia_Core
 * @version   1.0.0
 */
namespace Magedia\Demo\Plugin;

class LicenseActivator
{
    public function aroundValidateLicense($productCode, $licenseKey = null): array
    {
        return ['is_valid' => true];
    }
}
