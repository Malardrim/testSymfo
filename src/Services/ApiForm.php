<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiForm
{
    private $validator;
    private $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function validateAndCreate($data, $entityClassName)
    {
        $result = $this->serializer->deserialize($data, $entityClassName, 'json');
        $errors = $this->validator->validate($result);

        if (count($errors) > 0) {
            throw new CustomApiException(Response::HTTP_BAD_REQUEST, (string)$errors);
        }
        return $result;
    }
}