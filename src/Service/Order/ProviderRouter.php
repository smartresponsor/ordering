<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\ServiceInterface\Order\ProviderRouterInterface;

final class ProviderRouter implements ProviderRouterInterface
{
    /** @var array<string, ProviderAdapterInterface> */
    private array $adapter;

    /** @var array<string, HealthProbe> */
    private array $probe;

    /** @var array<string, float> */
    private array $canaryPercent;

    /**
     * @param array<string, ProviderAdapterInterface> $adapter
     * @param array<string, HealthProbe>              $probe
     * @param array<string, float>                    $canaryPercent
     */
    public function __construct(
        array $adapter,
        array $probe,
        array $canaryPercent,
        private ProviderPolicy $policy,
        private CanarySwitch $canarySwitch,
        private QuotaPolicy $quotaPolicy,
        private CostPolicy $costPolicy,
    ) {
        $this->adapter = $adapter;
        $this->probe = $probe;
        $this->canaryPercent = $canaryPercent;
    }

    public function select(RouteContext $context): RouteDecision
    {
        $bestProvider = null;
        $bestScore = INF;

        foreach ($this->adapter as $name => $adapter) {
            if (!isset($this->probe[$name])) {
                continue;
            }

            $probe = $this->probe[$name];

            if (!$this->quotaPolicy->allow($probe)) {
                continue;
            }

            if ($context->canary()) {
                $percent = $this->canaryPercent[$name] ?? 0.0;
                if ($percent <= 0.0) {
                    continue;
                }
                if (!$this->canarySwitch->allow($context->intentId(), $percent)) {
                    continue;
                }
            }

            $latNorm = min(1.0, max(0.0, $probe->p95Ms() / max(1, $this->policy->thresholdP95())));
            $errNorm = min(1.0, max(0.0, $probe->errorRate() / max(0.001, $this->policy->thresholdError())));
            $costNorm = $this->costPolicy->normalize($probe->costPerTxn());

            $score = $latNorm * $this->policy->weightLatency()
                + $errNorm * $this->policy->weightError()
                + $costNorm * $this->policy->weightCost();

            if ($score < $bestScore) {
                $bestScore = $score;
                $bestProvider = $name;
            }
        }

        if (null === $bestProvider) {
            $keys = array_keys($this->adapter);
            $bestProvider = $keys[0] ?? 'default';
            $bestScore = 1.0;
        }

        return new RouteDecision($bestProvider, (float) $bestScore);
    }
}
