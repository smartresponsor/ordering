<?php

declare(strict_types=1);

namespace App\Infrastructure\Monitoring;

final class MetricsCollector
{
    private object|null $registry = null;

    public function __construct(object|null $registry = null)
    {
        if (null !== $registry) {
            $this->registry = $registry;

            return;
        }

        if (class_exists(\Prometheus\CollectorRegistry::class) && class_exists(\Prometheus\Storage\InMemory::class)) {
            /** @var object $collectorRegistry */
            $collectorRegistry = new \Prometheus\CollectorRegistry(new \Prometheus\Storage\InMemory());
            $this->registry = $collectorRegistry;
        }
    }

    /** @param array<string, string> $labels */
    public function inc(string $name, array $labels = []): void
    {
        if (null === $this->registry || !is_callable([$this->registry, 'getOrRegisterCounter'])) {
            return;
        }

        $counter = $this->registry->getOrRegisterCounter('order', $name, '', array_keys($labels));
        if (is_object($counter) && is_callable([$counter, 'inc'])) {
            $counter->inc(array_values($labels));
        }
    }

    /** @param array<string, string> $labels */
    public function observe(string $name, float $seconds, array $labels = []): void
    {
        if (null === $this->registry || !is_callable([$this->registry, 'getOrRegisterHistogram'])) {
            return;
        }

        $histogram = $this->registry->getOrRegisterHistogram('order', $name, '', array_keys($labels));
        if (is_object($histogram) && is_callable([$histogram, 'observe'])) {
            $histogram->observe($seconds, array_values($labels));
        }
    }

    public function render(): string
    {
        if (null === $this->registry || !class_exists(\Prometheus\RenderTextFormat::class) || !is_callable([$this->registry, 'getMetricFamilySamples'])) {
            return '';
        }

        /** @var object $renderer */
        $renderer = new \Prometheus\RenderTextFormat();
        if (!is_callable([$renderer, 'render'])) {
            return '';
        }

        return $renderer->render($this->registry->getMetricFamilySamples());
    }
}
