<?php

namespace Eduxplus\CoreBundle\Lib\Service;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationService
{

    public function buildError(ConstraintViolationListInterface $violations) : string
    {
        $obj = $violations[count($violations)-1];
        return $obj->getMessage();
//        return $obj->getMessage()."=>".$this->makeCameCase($obj->getPropertyPath());
    }

    public function build(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $errors[
            $this->makeCameCase($violation->getPropertyPath())
            ] = $violation->getMessage();
        }

        return $this->buildMessages($errors);
    }

    public function makeCameCase(string $text): string
    {
        if (!trim($text)) {
            return $text;
        }
        $separator="_";
        $text = $separator. str_replace($separator, " ", strtolower($text));
        return ltrim(str_replace(" ", "", ucwords($text)), $separator);
    }

    private function buildMessages(array $errors): array
    {
        $result = [];

        foreach ($errors as $path => $message) {
            $temp = &$result;

            foreach (explode('.', $path) as $key) {
                preg_match('/(.*)(\[.*?\])/', $key, $matches);
                if ($matches) {
                    $index = str_replace(['[', ']'], '', $matches[2]);
                    $temp = &$temp[$matches[1]][$index];
                } else {
                    $temp = &$temp[$key];
                }
            }

            $temp = $message;
        }

        return $result;
    }

}
