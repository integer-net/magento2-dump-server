<?php

declare(strict_types=1);

namespace IntegerNet\DumpServer\Plugin;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;

use function sprintf;

class SetServerAddress
{
    final public const  DEFAULT_ADDRESS  = '127.0.0.1:9912';

    private const DEV_DUMP_SERVER_CONFIG = 'dev_dump_server';

    public function __construct(
        private readonly State $state,
        private readonly DeploymentConfig $deploymentConfig
    ) {
    }

    /**@throws FileSystemException
     * @throws RuntimeException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(FrontControllerInterface $frontController, RequestInterface $request): void
    {
        if ($this->state->getMode() !== State::MODE_DEVELOPER) {
            return;
        }

        $config = $this->getDumpServerConfig();
        if (!$config['enabled']) {
            return;
        }

        $this->setDumpServerAddress($config);
    }

    /**
     * @param array{enabled: bool, address: string} $config
     */
    private function setDumpServerAddress(array $config): void
    {
        $_SERVER["VAR_DUMPER_FORMAT"] = sprintf('tcp://%s', $config['address']);
    }

    /**
     * @return array{enabled: bool, address: string}
     *
     * @throws FileSystemException
     * @throws RuntimeException
     */
    private function getDumpServerConfig(): array
    {
        /** @var null|string|array{enabled?: bool, address?: string} $value */
        $value = $this->deploymentConfig->get(self::DEV_DUMP_SERVER_CONFIG);

        if (is_array($value)) {
            return [
                'enabled' => $value['enabled'] ?? true,
                'address' => $value['address'] ?? self::DEFAULT_ADDRESS,
            ];
        }

        return [
            'enabled' => true,
            'address' => is_string($value) && $value !== ''
                ? $value
                : self::DEFAULT_ADDRESS,
        ];
    }
}
