<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class TrickGroupFixtures extends Fixture
{
    public const GROUP_ROTATION = 'Rotation';
    public const GROUP_GRAB = 'Grab';
    public const GROUP_FLIP = 'Flip';
    public function load(ObjectManager $manager)
    {
        $groups = [
            ['title' => 'Grab', 'icon' => 'https://image.flaticon.com/icons/svg/625/625271.svg'],
            ['title' => 'Rotation', 'icon' => 'https://image.flaticon.com/icons/svg/2503/2503652.svg'],
            ['title' => 'Flip', 'icon' => 'https://image.flaticon.com/icons/svg/37/37542.svg'],
            ['title' => 'Rotation désaxée', 'icon' => 'https://img.icons8.com/ios/50/000000/snowboarding.png'],
            ['title' => 'Slide', 'icon' => 'https://image.flaticon.com/icons/svg/2513/2513267.svg'],
            ['title' => 'One foot', 'icon' => 'https://image.flaticon.com/icons/svg/2513/2513264.svg'],
            ['title' => 'Old school', 'icon' => 'https://img.icons8.com/ios-glyphs/30/000000/luge.png'],
        ];
        for ($i = 0; $i < count($groups); $i++) {
            $trickGroup = new TrickGroup();
            $trickGroup->setTitle($groups[$i]['title']);
            $trickGroup->setIcone($groups[$i]['icon']);

            $manager->persist($trickGroup);
            if ($groups[$i]['title'] === 'Rotation') {
                $this->addReference(self::GROUP_ROTATION, $trickGroup);
            }
            if ($groups[$i]['title'] === 'Grab') {
                $this->addReference(self::GROUP_GRAB, $trickGroup);
            }
            if ($groups[$i]['title'] === 'Flip') {
                $this->addReference(self::GROUP_FLIP, $trickGroup);
            }
        }
        $manager->flush();
    }
}
