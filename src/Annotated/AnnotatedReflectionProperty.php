<?php

namespace AEngine\Orchid\Annotation\Annotated;

use AEngine\Orchid\Annotation\AnnotationReader;
use AEngine\Orchid\Annotation\Interfaces\AnnotatedInterface;
use ReflectionException;
use ReflectionProperty;

class AnnotatedReflectionProperty extends ReflectionProperty implements AnnotatedInterface
{
    protected $annotations;

    /**
     * Return element type
     *
     * @return string
     */
    public function getAnnotatedElementType()
    {
        return 'PROPERTY';
    }

    /**
     * Return all annotations
     *
     * @return array
     * @throws ReflectionException
     */
    public function getAnnotations()
    {
        if ($this->annotations == null) {
            $this->annotations = AnnotationReader::parse($this);
        }

        return $this->annotations;
    }

    /**
     * Return annotation by strict name or array of annotations
     *
     * @param string $name
     * @param bool   $strict
     *
     * @return array
     * @throws ReflectionException
     */
    public function getAnnotation($name, $strict = true)
    {
        $annotations = $this->getAnnotations();

        if ($strict) {
            if (isset($annotations[$name])) {
                return $annotations[$name];
            }

            return null;
        }

        foreach ($annotations as $annotation => $obj) {
            if (strpos($annotation, $name) === false) {
                unset($annotations[$annotation]);
            }
        }

        return $annotations;
    }

    /**
     * Check has annotations
     *
     * @param string $annotationClass
     *
     * @return bool
     * @throws ReflectionException
     */
    public function hasAnnotation($annotationClass)
    {
        return !!$this->getAnnotation($annotationClass);
    }
}
