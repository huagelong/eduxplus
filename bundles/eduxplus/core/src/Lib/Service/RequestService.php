<?php

namespace Eduxplus\CoreBundle\Util;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Eduxplus\CoreBundle\Util\ViolationService;

class RequestService
{
    public function __construct(
       private SerializerInterface $serializer,
       private ValidatorInterface $validator,
       private ViolationService $violator,
        private RequestStack $requestStack
    ) {}

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
