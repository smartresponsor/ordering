<?php

declare(strict_types=1);

namespace App\Service\Config;

use App\Administering\Service\Config\ConfigApplyService;
use App\Administering\Service\Config\ConfigFileWriterService;
use App\Administering\ServiceInterface\Config\AdministrationConfigToolServiceInterface;
use App\Administering\Value\Config\AdministrationConfigToolDescriptor;
use App\Form\Config\OrderingRateLimitsConfigData;
use App\Form\Config\OrderingRateLimitsConfigFormType;
use Symfony\Component\Yaml\Yaml;

final readonly class OrderingRateLimitsConfigService implements AdministrationConfigToolServiceInterface
{
    public function __construct(
        private string $projectDir,
        private ConfigApplyService $applyService,
        private ConfigFileWriterService $fileWriter,
    ) {
    }

    public function descriptor(): AdministrationConfigToolDescriptor
    {
        return new AdministrationConfigToolDescriptor(
            applicationCode: 'Ordering',
            toolCode: 'ordering.rate_limits',
            label: 'Ordering Rate Limits',
            description: 'Safe runtime limits for ordering API read/write traffic.',
            formClass: OrderingRateLimitsConfigFormType::class,
            serviceClass: self::class,
            requiredPermission: 'administration.config.update',
            editableFields: [
                'apiWriteLimit',
                'apiWriteIntervalMinutes',
                'apiReadLimit',
                'apiReadIntervalMinutes',
            ],
            sensitiveFields: [],
            readableFiles: [
                'config/component/runtime.yaml',
                'config/packages/rate_limiter.php',
            ],
            writableFiles: ['config/component/runtime.yaml'],
            metadata: [
                'section' => 'Configuration',
                'kind' => 'rate_limits',
            ],
            secretNames: [],
            applyStrategy: 'component_runtime_yaml',
        );
    }

    public function loadData(): object
    {
        $data = new OrderingRateLimitsConfigData();
        $manifest = $this->runtimeManifest();
        $data->apiWriteLimit = (string) ($manifest['ordering_rate_limit_api_write_limit'] ?? 60);
        $data->apiWriteIntervalMinutes = (string) ($manifest['ordering_rate_limit_api_write_interval_minutes'] ?? 1);
        $data->apiReadLimit = (string) ($manifest['ordering_rate_limit_api_read_limit'] ?? 600);
        $data->apiReadIntervalMinutes = (string) ($manifest['ordering_rate_limit_api_read_interval_minutes'] ?? 1);

        return $data;
    }

    public function save(object $data, array $context = []): array
    {
        $payload = $this->assertData($data);

        return $this->applyService->save(
            $this->descriptor(),
            (string) ($context['actor'] ?? 'system'),
            $this->stateRows($payload, 'pending'),
            [
                'ordering_rate_limit_api_write_limit' => $payload->apiWriteLimit,
                'ordering_rate_limit_api_write_interval_minutes' => $payload->apiWriteIntervalMinutes,
                'ordering_rate_limit_api_read_limit' => $payload->apiReadLimit,
                'ordering_rate_limit_api_read_interval_minutes' => $payload->apiReadIntervalMinutes,
            ],
            [],
        );
    }

    public function apply(object $data, array $context = []): array
    {
        $payload = $this->assertData($data);
        $patch = $this->runtimePatch($payload);
        $write = $this->fileWriter->write(
            $this->projectDir.'/../Ordering',
            'config/component/runtime.yaml',
            $patch,
            $this->descriptor()->writableFiles,
        );

        $status = 'applied' === $write['status'] ? 'applied' : 'failed';

        return $this->applyService->apply(
            $this->descriptor(),
            (string) ($context['actor'] ?? 'system'),
            $this->stateRows($payload, $status),
            $patch,
            [],
            [[
                'path' => $write['path'],
                'backup_path' => $write['backup_path'],
                'status' => $write['status'],
                'message' => $write['message'],
            ]],
            [],
            'applied' === $write['status'] ? null : $write['message'],
            $status,
        );
    }

    private function assertData(object $data): OrderingRateLimitsConfigData
    {
        if (!$data instanceof OrderingRateLimitsConfigData) {
            throw new \InvalidArgumentException('Ordering rate limits config expects OrderingRateLimitsConfigData.');
        }

        return $data;
    }

    /** @return array<string, mixed> */
    private function runtimeManifest(): array
    {
        $path = $this->projectDir.'/../Ordering/config/component/runtime.yaml';
        $parsed = is_file($path) ? Yaml::parseFile($path) : [];

        return is_array($parsed) ? $parsed : [];
    }

    /**
     * @return array<string, mixed>
     */
    private function runtimePatch(OrderingRateLimitsConfigData $data): array
    {
        $apiWriteLimit = (int) $data->apiWriteLimit;
        $apiWriteIntervalMinutes = (int) $data->apiWriteIntervalMinutes;
        $apiReadLimit = (int) $data->apiReadLimit;
        $apiReadIntervalMinutes = (int) $data->apiReadIntervalMinutes;

        if ($apiWriteLimit < 1 || $apiWriteIntervalMinutes < 1 || $apiReadLimit < 1 || $apiReadIntervalMinutes < 1) {
            throw new \InvalidArgumentException('Rate limit values must be positive integers.');
        }

        return [
            'ordering_rate_limit_api_write_limit' => $apiWriteLimit,
            'ordering_rate_limit_api_write_interval_minutes' => $apiWriteIntervalMinutes,
            'ordering_rate_limit_api_read_limit' => $apiReadLimit,
            'ordering_rate_limit_api_read_interval_minutes' => $apiReadIntervalMinutes,
        ];
    }

    /**
     * @return array<string, array{fieldType:string, secret:bool, current:?string, pending:?string, masked:?string, status:string}>
     */
    private function stateRows(OrderingRateLimitsConfigData $data, string $status): array
    {
        return [
            'ordering_rate_limit_api_write_limit' => ['fieldType' => 'number', 'secret' => false, 'current' => $data->apiWriteLimit, 'pending' => $data->apiWriteLimit, 'masked' => null, 'status' => $status],
            'ordering_rate_limit_api_write_interval_minutes' => ['fieldType' => 'number', 'secret' => false, 'current' => $data->apiWriteIntervalMinutes, 'pending' => $data->apiWriteIntervalMinutes, 'masked' => null, 'status' => $status],
            'ordering_rate_limit_api_read_limit' => ['fieldType' => 'number', 'secret' => false, 'current' => $data->apiReadLimit, 'pending' => $data->apiReadLimit, 'masked' => null, 'status' => $status],
            'ordering_rate_limit_api_read_interval_minutes' => ['fieldType' => 'number', 'secret' => false, 'current' => $data->apiReadIntervalMinutes, 'pending' => $data->apiReadIntervalMinutes, 'masked' => null, 'status' => $status],
        ];
    }
}
