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
     *
     * @var string $email
     * @Assert\NotBlank(message = "Zadejte prosím svůj email")
     */
    protected $email;


    /**
     *
     * @var string $birthDate
     * @Assert\NotBlank(message = "Zadejte datum svého narození")
     */
    protected $birthDate;

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
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
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