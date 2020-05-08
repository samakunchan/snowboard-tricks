<?php
namespace App\Service;

use App\Entity\Image;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
        }

        return $fileName;
    }

    public function multiUpload($files) // reçoit a un tableau de fichier
    {
        $allUrl = [];
        foreach($files as $file)
        {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            $image = new Image();
            $image->setAlt($safeFilename.'-'.uniqid());
            $image->setExt($file->guessExtension());
            $image->setCompleteUrl($fileName);
            try {
                $image->setCreatedAt(new DateTime('now'));
            } catch (Exception $e) {
            }
            try {
                $file->move($this->getTargetDirectory(), $fileName);
                $allUrl[] = $image;
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        }

        return $allUrl;
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function deleteImageDir(Image $image)
    {
        $imageUrl = $this->getTargetDirectory().'/'.$image->getCompleteUrl();
        if (file_exists($imageUrl)) {
            unlink($imageUrl);
        }
    }
}
