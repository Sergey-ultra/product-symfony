<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseApiRequest
{
    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();
        $this->validate();
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        $messages = [
            'message' => 'validation_failed',
            'errors' => []
        ];

        /** @var ConstraintViolation $message */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
            $response->send();
        }
    }

    protected function getRequest(): Request
    {
       return Request::createFromGlobals();
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->toArray() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
