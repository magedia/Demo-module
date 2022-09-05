<?php

declare(strict_types=1);

namespace Magedia\Demo\Model\Reset;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ResourceConnection;

class AbstractTableGetter
{
    /**
     * @var ResourceConnection
     */
    public ResourceConnection $resourceConnection;

    /**
     * @var DeploymentConfig
     */
    public DeploymentConfig $deploymentConfig;

    /**
     * @param ResourceConnection $resourceConnection
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        DeploymentConfig   $deploymentConfig
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->deploymentConfig = $deploymentConfig;
    }

}
