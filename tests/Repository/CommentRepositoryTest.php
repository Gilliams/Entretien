<?php

namespace App\Tests\Repository;

use App\Repository\CommentRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CommentRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        self::bootKernel();
        $comments = $this->loadFixtureFiles([
            __DIR__ . '\CommentRepositoryTestFixtures.yaml'
        ]);
        $comments = self::$container->get(CommentRepository::class)->count([]);
        $this->assertEquals(10, $comments);
    }
}