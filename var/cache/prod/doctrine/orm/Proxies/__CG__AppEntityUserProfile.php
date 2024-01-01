<?php

namespace Proxies\__CG__\App\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class UserProfile extends \App\Entity\UserProfile implements \Doctrine\ORM\Proxy\InternalProxy
{
     use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as __load;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'Bio' => [parent::class, 'Bio', null],
        "\0".parent::class."\0".'Image' => [parent::class, 'Image', null],
        "\0".parent::class."\0".'company' => [parent::class, 'company', null],
        "\0".parent::class."\0".'dateOfBirth' => [parent::class, 'dateOfBirth', null],
        "\0".parent::class."\0".'id' => [parent::class, 'id', null],
        "\0".parent::class."\0".'location' => [parent::class, 'location', null],
        "\0".parent::class."\0".'name' => [parent::class, 'name', null],
        "\0".parent::class."\0".'twitterUsername' => [parent::class, 'twitterUsername', null],
        "\0".parent::class."\0".'user' => [parent::class, 'user', null],
        "\0".parent::class."\0".'websiteUrl' => [parent::class, 'websiteUrl', null],
        'Bio' => [parent::class, 'Bio', null],
        'Image' => [parent::class, 'Image', null],
        'company' => [parent::class, 'company', null],
        'dateOfBirth' => [parent::class, 'dateOfBirth', null],
        'id' => [parent::class, 'id', null],
        'location' => [parent::class, 'location', null],
        'name' => [parent::class, 'name', null],
        'twitterUsername' => [parent::class, 'twitterUsername', null],
        'user' => [parent::class, 'user', null],
        'websiteUrl' => [parent::class, 'websiteUrl', null],
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
