<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class ComposerConstraintTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const NAME = 'name';

    /**
     * @var string
     */
    public const VERSION = 'version';

    /**
     * @var string
     */
    public const IS_DEV = 'isDev';

    /**
     * @var string
     */
    public const EXPECTED_VERSION = 'expectedVersion';

    /**
     * @var string
     */
    public const MODULE_INFO = 'moduleInfo';

    /**
     * @var string
     */
    public const MESSAGES = 'messages';

    /**
     * @var string
     */
    public const DEFINED_CONSTRAINTS = 'definedConstraints';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $version;

    /**
     * @var bool|null
     */
    protected $isDev;

    /**
     * @var string|null
     */
    protected $expectedVersion;

    /**
     * @var \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer|null
     */
    protected $moduleInfo;

    /**
     * @var \ArrayObject<\Generated\Shared\Transfer\ConstraintMessageTransfer>
     */
    protected $messages;

    /**
     * @var \ArrayObject<\Generated\Shared\Transfer\ComposerConstraintTransfer>
     */
    protected $definedConstraints;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'name' => 'name',
        'Name' => 'name',
        'version' => 'version',
        'Version' => 'version',
        'is_dev' => 'isDev',
        'isDev' => 'isDev',
        'IsDev' => 'isDev',
        'expected_version' => 'expectedVersion',
        'expectedVersion' => 'expectedVersion',
        'ExpectedVersion' => 'expectedVersion',
        'module_info' => 'moduleInfo',
        'moduleInfo' => 'moduleInfo',
        'ModuleInfo' => 'moduleInfo',
        'messages' => 'messages',
        'Messages' => 'messages',
        'defined_constraints' => 'definedConstraints',
        'definedConstraints' => 'definedConstraints',
        'DefinedConstraints' => 'definedConstraints',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::NAME => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'name',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::VERSION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'version',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::IS_DEV => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'is_dev',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::EXPECTED_VERSION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'expected_version',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::MODULE_INFO => [
            'type' => 'Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer',
            'type_shim' => null,
            'name_underscore' => 'module_info',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::MESSAGES => [
            'type' => 'Generated\Shared\Transfer\ConstraintMessageTransfer',
            'type_shim' => null,
            'name_underscore' => 'messages',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::DEFINED_CONSTRAINTS => [
            'type' => 'Generated\Shared\Transfer\ComposerConstraintTransfer',
            'type_shim' => null,
            'name_underscore' => 'defined_constraints',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
            'is_primitive_array' => false,
        ],
    ];

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->modifiedProperties[self::NAME] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $name
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setNameOrFail($name)
    {
        if ($name === null) {
            $this->throwNullValueException(static::NAME);
        }

        return $this->setName($name);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getNameOrFail()
    {
        if ($this->name === null) {
            $this->throwNullValueException(static::NAME);
        }

        return $this->name;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->modifiedProperties[self::VERSION] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $version
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setVersionOrFail($version)
    {
        if ($version === null) {
            $this->throwNullValueException(static::VERSION);
        }

        return $this->setVersion($version);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getVersionOrFail()
    {
        if ($this->version === null) {
            $this->throwNullValueException(static::VERSION);
        }

        return $this->version;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireVersion()
    {
        $this->assertPropertyIsSet(self::VERSION);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isDev
     *
     * @return $this
     */
    public function setIsDev($isDev)
    {
        $this->isDev = $isDev;
        $this->modifiedProperties[self::IS_DEV] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return bool|null
     */
    public function getIsDev()
    {
        return $this->isDev;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isDev
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setIsDevOrFail($isDev)
    {
        if ($isDev === null) {
            $this->throwNullValueException(static::IS_DEV);
        }

        return $this->setIsDev($isDev);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getIsDevOrFail()
    {
        if ($this->isDev === null) {
            $this->throwNullValueException(static::IS_DEV);
        }

        return $this->isDev;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireIsDev()
    {
        $this->assertPropertyIsSet(self::IS_DEV);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $expectedVersion
     *
     * @return $this
     */
    public function setExpectedVersion($expectedVersion)
    {
        $this->expectedVersion = $expectedVersion;
        $this->modifiedProperties[self::EXPECTED_VERSION] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getExpectedVersion()
    {
        return $this->expectedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $expectedVersion
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setExpectedVersionOrFail($expectedVersion)
    {
        if ($expectedVersion === null) {
            $this->throwNullValueException(static::EXPECTED_VERSION);
        }

        return $this->setExpectedVersion($expectedVersion);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getExpectedVersionOrFail()
    {
        if ($this->expectedVersion === null) {
            $this->throwNullValueException(static::EXPECTED_VERSION);
        }

        return $this->expectedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireExpectedVersion()
    {
        $this->assertPropertyIsSet(self::EXPECTED_VERSION);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer|null $moduleInfo
     *
     * @return $this
     */
    public function setModuleInfo(ComposerConstraintModuleInfoTransfer $moduleInfo = null)
    {
        $this->moduleInfo = $moduleInfo;
        $this->modifiedProperties[self::MODULE_INFO] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer|null
     */
    public function getModuleInfo()
    {
        return $this->moduleInfo;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $moduleInfo
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setModuleInfoOrFail(ComposerConstraintModuleInfoTransfer $moduleInfo)
    {
        return $this->setModuleInfo($moduleInfo);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    public function getModuleInfoOrFail()
    {
        if ($this->moduleInfo === null) {
            $this->throwNullValueException(static::MODULE_INFO);
        }

        return $this->moduleInfo;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireModuleInfo()
    {
        $this->assertPropertyIsSet(self::MODULE_INFO);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \ArrayObject<\Generated\Shared\Transfer\ConstraintMessageTransfer> $messages
     *
     * @return $this
     */
    public function setMessages(ArrayObject $messages)
    {
        $this->messages = $messages;
        $this->modifiedProperties[self::MESSAGES] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\ConstraintMessageTransfer>
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \Generated\Shared\Transfer\ConstraintMessageTransfer $message
     *
     * @return $this
     */
    public function addMessage(ConstraintMessageTransfer $message)
    {
        $this->messages[] = $message;
        $this->modifiedProperties[self::MESSAGES] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireMessages()
    {
        $this->assertCollectionPropertyIsSet(self::MESSAGES);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \ArrayObject<\Generated\Shared\Transfer\ComposerConstraintTransfer> $definedConstraints
     *
     * @return $this
     */
    public function setDefinedConstraints(ArrayObject $definedConstraints)
    {
        $this->definedConstraints = new ArrayObject();

        foreach ($definedConstraints as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::DEFINED_CONSTRAINTS]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addDefinedConstraint(...$args);
        }

        $this->modifiedProperties[self::DEFINED_CONSTRAINTS] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\ComposerConstraintTransfer>
     */
    public function getDefinedConstraints(): ArrayObject
    {
        return $this->definedConstraints;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer $definedConstraint
     *
     * @return $this
     */
    public function addDefinedConstraint(ComposerConstraintTransfer $definedConstraint)
    {
        $this->definedConstraints[] = $definedConstraint;
        $this->modifiedProperties[self::DEFINED_CONSTRAINTS] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDefinedConstraints()
    {
        $this->assertCollectionPropertyIsSet(self::DEFINED_CONSTRAINTS);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'name':
                case 'version':
                case 'isDev':
                case 'expectedVersion':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'moduleInfo':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'messages':
                case 'definedConstraints':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property, static::class));
                    }
            }
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);

                continue;
            }
            switch ($property) {
                case 'name':
                case 'version':
                case 'isDev':
                case 'expectedVersion':
                    $values[$arrayKey] = $value;

                    break;
                case 'moduleInfo':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;

                    break;
                case 'messages':
                case 'definedConstraints':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);

                continue;
            }
            switch ($property) {
                case 'name':
                case 'version':
                case 'isDev':
                case 'expectedVersion':
                    $values[$arrayKey] = $value;

                    break;
                case 'moduleInfo':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;

                    break;
                case 'messages':
                case 'definedConstraints':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return void
     */
    protected function initCollectionProperties(): void
    {
        $this->messages = $this->messages ?: new ArrayObject();
        $this->definedConstraints = $this->definedConstraints ?: new ArrayObject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'isDev' => $this->isDev,
            'expectedVersion' => $this->expectedVersion,
            'moduleInfo' => $this->moduleInfo,
            'messages' => $this->messages,
            'definedConstraints' => $this->definedConstraints,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'is_dev' => $this->isDev,
            'expected_version' => $this->expectedVersion,
            'module_info' => $this->moduleInfo,
            'messages' => $this->messages,
            'defined_constraints' => $this->definedConstraints,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, false) : $this->name,
            'version' => $this->version instanceof AbstractTransfer ? $this->version->toArray(true, false) : $this->version,
            'is_dev' => $this->isDev instanceof AbstractTransfer ? $this->isDev->toArray(true, false) : $this->isDev,
            'expected_version' => $this->expectedVersion instanceof AbstractTransfer ? $this->expectedVersion->toArray(true, false) : $this->expectedVersion,
            'module_info' => $this->moduleInfo instanceof AbstractTransfer ? $this->moduleInfo->toArray(true, false) : $this->moduleInfo,
            'messages' => $this->messages instanceof AbstractTransfer ? $this->messages->toArray(true, false) : $this->addValuesToCollection($this->messages, true, false),
            'defined_constraints' => $this->definedConstraints instanceof AbstractTransfer ? $this->definedConstraints->toArray(true, false) : $this->addValuesToCollection($this->definedConstraints, true, false),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, true) : $this->name,
            'version' => $this->version instanceof AbstractTransfer ? $this->version->toArray(true, true) : $this->version,
            'isDev' => $this->isDev instanceof AbstractTransfer ? $this->isDev->toArray(true, true) : $this->isDev,
            'expectedVersion' => $this->expectedVersion instanceof AbstractTransfer ? $this->expectedVersion->toArray(true, true) : $this->expectedVersion,
            'moduleInfo' => $this->moduleInfo instanceof AbstractTransfer ? $this->moduleInfo->toArray(true, true) : $this->moduleInfo,
            'messages' => $this->messages instanceof AbstractTransfer ? $this->messages->toArray(true, true) : $this->addValuesToCollection($this->messages, true, true),
            'definedConstraints' => $this->definedConstraints instanceof AbstractTransfer ? $this->definedConstraints->toArray(true, true) : $this->addValuesToCollection($this->definedConstraints, true, true),
        ];
    }
}
