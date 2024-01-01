<?php

namespace Proxies\__CG__\App\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class BlogPost extends \App\Entity\BlogPost implements \Doctrine\ORM\Proxy\InternalProxy
{
     use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as __load;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'BlogText' => [parent::class, 'BlogText', null],
        "\0".parent::class."\0".'BlogTitle' => [parent::class, 'BlogTitle', null],
        "\0".parent::class."\0".'author' => [parent::class, 'author', null],
        "\0".parent::class."\0".'comments' => [parent::class, 'comments', null],
        "\0".parent::class."\0".'createdate' => [parent::class, 'createdate', null],
        "\0".parent::class."\0".'extraPrivacy' => [parent::class, 'extraPrivacy', null],
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'likedBy' => [parent::class, 'likedBy', null],
        'BlogText' => [parent::class, 'BlogText', null],
        'BlogTitle' => [parent::class, 'BlogTitle', null],
        'author' => [parent::class, 'author', null],
        'comments' => [parent::class, 'comments', null],
        'createdate' => [parent::class, 'createdate', null],
        'extraPrivacy' => [parent::class, 'extraPrivacy', null],
        'id' => [parent::class, 'id', null],
        'likedBy' => [parent::class, 'likedBy', null],
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
