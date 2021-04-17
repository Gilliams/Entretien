<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Article;
use App\Tests\Traits\AssertHasErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{

    use AssertHasErrors;

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        //$routerService = self::$container->get('router');
        //$myCustomService = self::$container->get(CustomService::class);
    }

    public function getEntity(): Article
    {
        return (new Article())
        ->setTitle('Title')
        ->setContent('Content')
        ->setAuthor(new User())
        ->setCreatedAt(new \DateTime())
        ->setSlug('title-slug');
    }




    public function testValidArticleEntity(){
        $this->assertHasErrors($this->getEntity(), 0);
    }
    public function testInvalidBlankTitle(){
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }
    public function testInvalidBlankContent(){
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }
    public function testInvalidBlankAuthor(){
        $this->assertHasErrors($this->getEntity()->setAuthor(null), 1);
    }

}
