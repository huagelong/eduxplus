<?php

namespace Eduxplus\CoreBundle\Lib\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Eduxplus\CoreBundle\Lib\Service\ViolationService;

class RequestService
{
    /**
     * SerializerInterface
     *
     * @var SerializerInterface
     */
    private  $serializer;
    /**
     * 
     *
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * Undocumented variable
     *
     * @var ViolationService
     */
    private $violator;

    /**
     * Undocumented variable
     *
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
       SerializerInterface $serializer,
       ValidatorInterface $validator,
       ViolationService $violator,
        RequestStack $requestStack
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->violator = $violator;
        $this->requestStack = $requestStack;
    }

    public function validate(string $model): object
    {
        $data = $this->requestStack->getCurrentRequest()->getContent();
        if (!$data) {
            throw new ValidatorException('Empty body.');
        }

        try {
            $object = $this->serializer->deserialize($data, $model, 'json');
        } catch (\Exception $e) {
            throw new ValidatorException('Invalid body.');
        }

        $errors = $this->validator->validate($object);

        if ($errors->count()) {
            throw new ValidatorException($this->violator->buildError($errors));
        }
        return $object;
    }
}
