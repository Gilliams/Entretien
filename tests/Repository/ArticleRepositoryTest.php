<?php

namespace App\Tests\Repository;

use App\Repository\ArticleRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class ArticleRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        self::bootKernel();
        $articles = $this->loadFixtureFiles([
            __DIR__ . '\ArticleRepositoryTestFixtures.yaml'
        ]);
        $articles = self::$container->get(ArticleRepository::class)->count([]);
        $this->assertEquals(10, $articles);
    }
}