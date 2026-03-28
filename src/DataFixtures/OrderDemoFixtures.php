<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Service\Demo\OrderDemoDataService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class OrderDemoFixtures extends Fixture
{
    public function __construct(private readonly OrderDemoDataService $demoDataService)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->demoDataService->purge();
        $this->demoDataService->load();
    }
}
