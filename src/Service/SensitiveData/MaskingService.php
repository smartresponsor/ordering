<?php

declare(strict_types=1);

namespace App\Service\SensitiveData;

final readonly class MaskingService
{
    /** @var string[] */
    private array $maskKeys;

    public function __construct(array $maskKeys = [])
    {
        $this->maskKeys = array_map('strtolower', $maskKeys);
    }

    /** @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function maskArray(array $data): array
    {
        $masked = [];
        foreach ($data as $key => $value) {
            $lookup = strtolower((string) $key);
            if (in_array($lookup, $this->maskKeys, true)) {
                $masked[$key] = is_string($value) ? $this->maskString($value) : '***';
                continue;
            }

            $masked[$key] = is_array($value) ? $this->maskArray($value) : $value;
        }

        return $masked;
    }

    private function maskString(string $value): string
    {
        if ('' === $value) {
            return '';
        }
        if (strlen($value) <= 4) {
            return str_repeat('*', strlen($value));
        }

        return substr($value, 0, 2).str_repeat('*', max(0, strlen($value) - 4)).substr($value, -2);
    }
}
