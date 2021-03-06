<?php


namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Adventure;
use AppBundle\Entity\Monster;
use Doctrine\Common\Collections\ArrayCollection;

class AdventureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Adventure
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new Adventure();

        $monsters = new \ReflectionProperty($this->subject, 'monsters');
        $monsters->setAccessible(true);
        $monsters->setValue($this->subject, new ArrayCollection([
            $this->makeCommonMonster(),
            $this->makeBossMonster(),
            $this->makeCommonMonster(),
            $this->makeBossMonster(),
        ]));
    }

    public function testGetMonsters()
    {
        $this->assertCount(4, $this->subject->getMonsters());
    }

    public function testGetCommonMonsters()
    {
        $commonMonsters = $this->subject->getCommonMonsters();
        $this->assertCount(2, $commonMonsters);
        foreach ($commonMonsters as $commonMonster) {
            $this->assertFalse($commonMonster->getIsUnique());
        }
    }

    public function testGetBossMonsters()
    {
        $bossMonsters = $this->subject->getBossMonsters();
        $this->assertCount(2, $bossMonsters);
        foreach ($bossMonsters as $bossMonster) {
            $this->assertTrue($bossMonster->getIsUnique());
        }
    }

    public function testSetMonsters()
    {
        $this->subject->setMonsters(new ArrayCollection([$this->makeBossMonster()]));

        $this->assertCount(1, $this->subject->getMonsters());
        $this->assertCount(0, $this->subject->getCommonMonsters());
        $this->assertCount(1, $this->subject->getBossMonsters());
    }

    public function testSetBossMonsters()
    {
        $this->subject->setBossMonsters(new ArrayCollection([$this->makeBossMonster()]));

        $this->assertCount(3, $this->subject->getMonsters());
        $this->assertCount(2, $this->subject->getCommonMonsters());
        $this->assertCount(1, $this->subject->getBossMonsters());
    }

    public function testSetCommonMonsters()
    {
        $this->subject->setCommonMonsters(new ArrayCollection([$this->makeCommonMonster()]));

        $this->assertCount(3, $this->subject->getMonsters());
        $this->assertCount(1, $this->subject->getCommonMonsters());
        $this->assertCount(2, $this->subject->getBossMonsters());
    }

    public function testAddMonster()
    {
        $this->subject->addMonster($this->makeCommonMonster());

        $this->assertCount(5, $this->subject->getMonsters());
        $this->assertCount(3, $this->subject->getCommonMonsters());
        $this->assertCount(2, $this->subject->getBossMonsters());
    }

    private function makeCommonMonster(): Monster
    {
        $monster = new Monster();
        $monster->setIsUnique(false);

        return $monster;
    }

    private function makeBossMonster(): Monster
    {
        $monster = new Monster();
        $monster->setIsUnique(true);

        return $monster;
    }
}
