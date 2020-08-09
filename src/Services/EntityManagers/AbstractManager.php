<?php


namespace App\Services\EntityManagers;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $objectManager;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $objectManager, ValidatorInterface $validator)
    {
        $this->objectManager = $objectManager;
        $this->validator = $validator;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getObjectManager(): EntityManagerInterface
    {
        return $this->objectManager;
    }

}