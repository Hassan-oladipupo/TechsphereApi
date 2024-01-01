<?php

namespace Proxies\__CG__\App\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Comment extends \App\Entity\Comment implements \Doctrine\ORM\Proxy\InternalProxy
{
     use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as __load;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'author' => [parent::class, 'author', null],
        "\0".parent::class."\0".'blog' => [parent::class, 'blog', null],
        "\0".parent::class."\0".'createdate' => [parent::class, 'createdate', null],
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'text' => [parent::class, 'text', null],
        'author' => [parent::class, 'author', null],
        'blog' => [parent::class, 'blog', null],
        'createdate' => [parent::class, 'createdate', null],
        'id' => [parent::class, 'id', null],
        'text' => [parent::class, 'text', null],
    ];

    public function __isInitialized(): bool
    {
        return isset($this->lazyObjectState) && $this->isLazyObjectInitialized();
    }

    public function __serialize(): array
    {
        $properties = (array) $this;
        unset($properties["\0" . self::class . "\0lazyObjectState"]);

        return $properties;
    }
}
