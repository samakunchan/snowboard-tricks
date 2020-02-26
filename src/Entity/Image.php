<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $ext;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $completeUrl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images")
     */
    private $trick;

    private $file;

    private $tempFileName;


    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param mixed $file
     * @return Image
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        if ($this->ext === null){
            $this->tempFileName = $this->completeUrl; // ici
            $this->ext = null;
            $this->alt = null;
        }
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        if ($this->file === null){
            return;
        }
        if ($this->completeUrl !== null) {
            $this->tempFileName = $this->completeUrl;
        }
        $originalFilename = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$this->file->guessExtension();
        $this->alt = $safeFilename.'-'.uniqid();
        $this->ext = $this->file->guessExtension();
        $this->completeUrl = $fileName;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(null === $this->file){
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (isset($this->tempFileName)) {
            $oldFile = $this->getUploadRootDir().'/'.$this->tempFileName;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->completeUrl   // Le nom du fichier à créer, ici « id.ext »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFileName = $this->getUploadRootDir().'/'.$this->completeUrl;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFileName)) {
            // On supprime le fichier
            unlink($this->tempFileName);
        }
    }

    /**
     * @return string
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'uploads';
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../public/'.$this->getUploadDir();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getExt(): ?string
    {
        return $this->ext;
    }

    public function setExt(string $ext): self
    {
        $this->ext = $ext;

        return $this;
    }

    public function getCompleteUrl(): ?string
    {
        return $this->completeUrl;
    }

    public function setCompleteUrl(string $completeUrl): self
    {
        $this->completeUrl = $completeUrl;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
