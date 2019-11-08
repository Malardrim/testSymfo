<?php


namespace App\Services;


use phpDocumentor\Reflection\Types\Object_;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiForm
{
    private $validator;
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateAndCreate($data, $entityClassName){

        $objectNormalizer = new ObjectNormalizer();
        $normalizers = [$objectNormalizer];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        dump($data);die;
        $result = $serializer->deserialize($data, "Array", 'json');
        die();
        $errors = $this->validator->validate($result);

        if(count($errors) > 0){
            throw new CustomApiException(Response::HTTP_BAD_REQUEST, (string) $errors);
        }

        return $result;

    }
}