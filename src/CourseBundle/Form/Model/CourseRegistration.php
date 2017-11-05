<?php
namespace CourseBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CourseRegistration
{

    /**
     *
     * @var string $name
     * @Assert\NotBlank(message = "Zadejte prosím své jméno")
     */
    protected $name;


    /**
     *
     * @var string $name
     * @Assert\NotBlank(message = "Zadejte prosím své příjmení")
     */
    protected $surname;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }




}