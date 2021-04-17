<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Tests\Traits\AssertHasErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserTest extends KernelTestCase
{

    use AssertHasErrors;

    public function getEntity(): User
    {
        return (new User())
        ->setEmail('florian@hotmail.fr')
        ->setPassword('secret')
        ->setUsername('florian')
        ->addArticle(new Article)
        ->addComment(new Comment);
    }

    public function testValidUserEntity(){
        $this->assertHasErrors($this->getEntity(), 0);
    }
    public function testInvalidBlankEmail(){
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidEmail(){
        $this->assertHasErrors($this->getEntity()->setEmail('0000aaaa'), 1);
    }
    public function testInvalidBlankUsername(){
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testValidUserArticle(){
        $this->assertHasErrors($this->getEntity()->addArticle(new Article), 0);
    }
    public function testValidUserComment(){
        $this->assertHasErrors($this->getEntity()->addComment(new Comment), 0);
    }

}
