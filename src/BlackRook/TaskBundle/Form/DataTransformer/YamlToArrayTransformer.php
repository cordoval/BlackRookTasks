<?php

namespace BlackRook\TaskBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Yaml\Yaml;


class YamlToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array into yaml
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
        if (null === $array || "" === $array) {
            return "";
        }

        if (!is_array($array)) {
            throw new UnexpectedTypeException($array, 'array');
        }

        $yaml = Yaml::dump($array);

        return $yaml;
    }

    /**
     * Transforms a yaml string into an array.
     *
     * @param  mixed $value  An array
     * @return mixed $value  An array
     * @throws UnexpectedTypeException if the given value is not an array
     */
    public function reverseTransform($value)
    {
        if(empty($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $array = Yaml::parse($value);

        if ($array === null) {
            throw new UnexpectedTypeException($value, 'valid YAML string');
        }

        return $array;
    }
}
