<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

   
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animal", inversedBy="images")
     */
    private $animal;

    /**
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;
    
    /**
     * @ORM\Column(name="main", type="boolean")
     */
    private $main;

    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;
    
    /**
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/gif" })
     * @Assert\File(mimeTypesMessage = "Merci d'envoyer des photos aux formats jpeg, png, ou gif")
     */
    private $file;
    
    private $tempFilename;
    
    public function __construct() 
    {
        $this->main = false;
        $this->date = new \Datetime();
    }
    
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        
        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->extension) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->extension;

            // On réinitialise les valeurs des attributs extension et alt
            $this->extension = null;
            $this->alt = null;
        }

    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getExtension() 
    {
        return $this->extension;
    }
    
    public function getAlt() 
    {
        return $this->alt;  
    }

    /**
     * Set animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return Image
     */
    public function setAnimal(\AppBundle\Entity\Animal $animal = null)
    {
        $this->animal = $animal;

        return $this;
    }

    /**
     * Get animal
     *
     * @return \AppBundle\Entity\Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « extension »
        $this->extension = $this->file->guessExtension();

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        $this->resizeImage($this->file);

    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getExtension();
    }
    
    public function resizeImage($originalImage)
    {
          // Get current dimensions
        $ImageDetails = getimagesize($originalImage);
        $height_orig = $ImageDetails[1];
        $width_orig = $ImageDetails[0];
        $ratio = $ImageDetails[0]/$ImageDetails[1];
        $fileExtention = $this->extension;
        $jpegQuality = 75;
        $width = 800;
        $newPath = $this->getUploadRootDir();

        //Resize dimensions are bigger than original image, stop processing
        if ($width > $width_orig){
            // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->id.'.'.$this->extension   // Le nom du fichier à créer, ici « id.extension »
        ); 
            return false;
        }

        $height = $width / $ratio;
        $height = round($height);

        $gd_image_dest = imagecreatetruecolor($width, $height);
        $gd_image_src = null;
        switch( $fileExtention ){
            case 'png' :
                $gd_image_src = imagecreatefrompng($originalImage);
                imagealphablending( $gd_image_dest, false );
                imagesavealpha( $gd_image_dest, true );
                break;
            case 'jpeg': case 'jpg': $gd_image_src = imagecreatefromjpeg($originalImage); 
                break;
            case 'gif' : $gd_image_src = imagecreatefromgif($originalImage); 
                break;
            default: break;
        }

        imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        $filesystem = new Filesystem();
        $filesystem->mkdir($newPath, 0744);
        $newFileName = $newPath . "/" . $this->id . "." . $fileExtention;

        switch( $fileExtention ){
            case 'png' : imagepng($gd_image_dest, $newFileName); break;
            case 'jpeg' : case 'jpg' : imagejpeg($gd_image_dest, $newFileName, $jpegQuality); break;
            case 'gif' : imagegif($gd_image_dest, $newFileName); break;
            default: break;
        }

        
    }
    

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Image
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Image
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

}
