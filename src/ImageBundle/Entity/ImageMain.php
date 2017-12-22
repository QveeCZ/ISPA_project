<?php

namespace ImageBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use EshopPackage\CatalogBundle\Entity\ProductVariant;
use EshopPackage\CoreBundle\Classes\eImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Orm\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class ImageMain
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Unmapped property to handle file uploads
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {

        $uploadDir =  \ImageBundle\UPLOAD_DIR;

        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        $filename = uniqid() . "_" . $this->getFile()->getClientOriginalName();

        $this->setImage($this->checkImage($filename));

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $uploadDir,
            $filename
        );

        if($this->filename){
            unlink($uploadDir . $this->filename);
        }

        // set the path property to the filename where you've saved the file
        $this->filename = $filename;

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    public function remove(){

        $uploadDir =  \ImageBundle\UPLOAD_DIR;
        if($this->filename){
            unlink($uploadDir . $this->filename);
        }
    }
    /**
     * Lifecycle callback to upload the file to the server
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }


    /**
     * @var \DateTime $updated
     *
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var String $filename
     *
     *
     * @ORM\Column(name="filename", type="string", nullable=true)
     */
    protected $filename;

    /**
     * @var bool $image
     *
     *
     * @ORM\Column(name="image", type="boolean", nullable=false)
     */
    protected $image;


    /**
     * @var \DateTime $protocolDate
     *
     *
     * @ORM\Column(name="protocol_date", type="date", nullable=false)
     */
    protected $protocolDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return String
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param String $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        return $this->image;
    }

    /**
     * @param bool $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \DateTime
     */
    public function getProtocolDate()
    {
        return $this->protocolDate;
    }

    /**
     * @param \DateTime $protocolDate
     */
    public function setProtocolDate($protocolDate)
    {
        $this->protocolDate = $protocolDate;
    }



    /**
     * @param string $filename
     * @return bool
     */
    private function checkImage($filename){
        $allowed =  array('gif','png' ,'jpg');
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array($ext,$allowed) ) {
            return false;
        }

        return true;
    }


}