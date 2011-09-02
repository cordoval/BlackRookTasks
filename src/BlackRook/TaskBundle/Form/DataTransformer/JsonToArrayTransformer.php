<?php

namespace BlackRook\TaskBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class JsonToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array into json
     *
     * @param  mixed $array  An array
     *
     * @return mixed         An array
     *
     * @throws UnexpectedTypeException if the given value is not an array
     * @throws TransformationFailedException if the choices can not be retrieved
     */
    public function transform($array)
    {
        if (null === $array) {
            return "";
        }

        if (!is_array($array)) {
            throw new UnexpectedTypeException($array, 'array');
        }

        $json = json_encode($array);

        return $json;
    }

    /**
     * Transforms a json string into an array.
     *
     * @param  mixed $value  An array
     * @return mixed $value  An array
     * @throws UnexpectedTypeException if the given value is not an array
     */
    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if(empty($value)) {
            return;
        }

        $array = json_decode($value, TRUE);

        if ($array === null) {
            throw new UnexpectedTypeException($value, 'valid JSON string');
        }

        return $array;
    }
}
