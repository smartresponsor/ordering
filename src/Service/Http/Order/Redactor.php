<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

final class Redactor
{
    /** @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function redactArray(array $data): array
    {
        $masked = [];
        foreach ($data as $key => $value) {
            if ($this->isSensitive((string) $key)) {
                $masked[$key] = '***';
            } elseif (is_array($value)) {
                $masked[$key] = $this->redactArray($value);
            } else {
                $masked[$key] = $value;
            }
        }

        return $masked;
    }

    private function isSensitive(string $key): bool
    {
        $lookup = strtolower($key);
        foreach (['token', 'secret', 'password', 'card', 'iban', 'pan'] as $needle) {
            if (str_contains($lookup, $needle)) {
                return true;
            }
        }

        return false;
    }
}
