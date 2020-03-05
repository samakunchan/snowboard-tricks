<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\Video;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    const FIXTURES_IMAGE_DIR = __DIR__ . '/../../src/DataFixtures/files/';

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $tricks = $this->getDataForTricks();
        for ($i = 0; $i < count($tricks); $i++) {
            $trick = new Trick();
            $trick->setTitle($tricks[$i]['title']);
            $trick->setDescription($tricks[$i]['description']);
            for ($j = 0; $j < $tricks[$i]['images']; $j++) {
                $this->buildImages($trick, $tricks, $i, $j);
            }
            foreach ($tricks[$i]['videos'] as $key => $trick_video) {
                $this->buildVideos($trick, $tricks, $trick_video, $key, $i);
            }
            if ($i <= 2) {
                $trick->setTrickGroup($this->getReference(TrickGroupFixtures::GROUP_ROTATION));
            } elseif ($i >2 && $i <= 4) {
                $trick->setTrickGroup($this->getReference(TrickGroupFixtures::GROUP_GRAB));
            } else {
                $trick->setTrickGroup($this->getReference(TrickGroupFixtures::GROUP_FLIP));
            }
            $trick->setCreatedBy($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            $trick->setCreatedAt(new DateTime('now'));
            $manager->persist($trick);
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [TrickGroupFixtures::class,UserFixtures::class];
    }

    public function getDataForTricks()
    {
        return [
            [
                'title' => 'mute',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 2,
                'videos' => []
            ],
            [
                'title' => 'stalefish',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 3,
                'videos' => ['f9FjhCt_w2U']
            ],
            [
                'title' => 'indy',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 2,
                'videos' => ['iKkhKekZNQ8', '6yA3XqjTh_w'],
            ],
            [
                'title' => 'sad',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 2,
                'videos' => ['KEdFwJ4SWq4']
            ],
            [
                'title' => 'japan',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 2,
                'videos' => ['CzDjM7h_Fwo']
            ],
            [
                'title' => 'frontflip',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 3,
                'videos' => ['gMfmjr-kuOg']
            ],
            [
                'title' => 'backflip',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi dolore eum facilis, ipsa iure laboriosam laborum laudantium libero molestias nisi odio repellendus reprehenderit tempore unde vel. Earum magnam mollitia officia.',
                'images' => 2,
                'videos' => ['W853WVF5AqI']
            ],
        ];
    }

    public function buildImages($trick, $tricks, $iindex, $jindex)
    {
        $image = new Image();
        $image->setAlt(substr(self::FIXTURES_IMAGE_DIR.$tricks[$iindex]['title'].($jindex +1), 0, -3));
        $image->setExt('jpg');

        $file = new File(self::FIXTURES_IMAGE_DIR.$tricks[$iindex]['title'].($jindex +1).'.'.$image->getExt());

        $originalFilename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $image->setCompleteUrl($fileName);
        $image->setCreatedAt(new DateTime('now'));
        $file->move(__DIR__ . '/../../public/uploads', $fileName);
        $image->setTrick($trick);
        $trick->addImage($image);
    }

    public function buildVideos($trick, $tricks, $trick_video, $key, $iindex)
    {
        $video = new Video();
        $video->setTitle($tricks[$iindex]['title']. ' video '. $key);
        $video->setSrc($trick_video);
        $video->setCreatedAt(new DateTime('now'));
        $video->setTrick($trick);
        $trick->addVideo($video);
    }
}
