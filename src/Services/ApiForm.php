<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiForm
{
    /**
     * @var ValidatorInterface $validator Auto wired service by symfony
     */
    private $validator;

    /**
     * @var SerializerInterface $serializer Auto wired service by symfony
     */
    private $serializer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ApiForm constructor.
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, TranslatorInterface $translator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    /**
     * Deserializes then validates à entity
     *
     * @param $data string Data to deserialize
     * @param $entityClassName string Name of the entity to process
     * @param string $format Format of the data (json recommended)
     * @return array|object Result of the process if the Entity is valid
     * @throws \ReflectionException
     */
    public function validateAndCreate($data, $entityClassName, $format = 'json')
    {
        $result = $this->serializer->deserialize($data, $entityClassName, $format);
        $errors = $this->validator->validate($result);
        if (count($errors) > 0) {
            $new_errors = [];
            $class = strtolower((new \ReflectionClass($entityClassName))->getShortName());
            foreach ($errors as $error){
                $name = $class . '_' . $error->getPropertyPath();
                $message = $this->translator->trans($error->getMessage(), $error->getParameters(), 'errors');
                $new_errors[$name] = $message;
            }
            return $new_errors;
        }
        return $result;
    }
}