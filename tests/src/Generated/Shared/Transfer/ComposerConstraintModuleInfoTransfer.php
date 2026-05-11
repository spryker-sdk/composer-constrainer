<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class ComposerConstraintModuleInfoTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const IS_CUSTOMIZED = 'isCustomized';

    /**
     * @var string
     */
    public const IS_CONFIGURED = 'isConfigured';

    /**
     * @var string
     */
    public const IS_DEV = 'isDev';

    /**
     * @var string
     */
    public const CUSTOMIZED_LINE_COUNT = 'customizedLineCount';

    /**
     * @var string
     */
    public const CONSTRAINT_REASONS = 'constraintReasons';

    /**
     * @var string
     */
    public const EXPECTED_CONSTRAINT_LOCK = 'expectedConstraintLock';

    /**
     * @var string
     */
    public const DEFINED_CONSTRAINT_LOCK = 'definedConstraintLock';

    /**
     * @var string
     */
    public const DEFINED_VERSION = 'definedVersion';

    /**
     * @var string
     */
    public const LOCKED_VERSION = 'lockedVersion';

    /**
     * @var string
     */
    public const EXPECTED_VERSION = 'expectedVersion';

    /**
     * @var bool|null
     */
    protected $isCustomized;

    /**
     * @var bool|null
     */
    protected $isConfigured;

    /**
     * @var bool|null
     */
    protected $isDev;

    /**
     * @var int|null
     */
    protected $customizedLineCount;

    /**
     * @var string[]
     */
    protected $constraintReasons = [];

    /**
     * @var string|null
     */
    protected $expectedConstraintLock;

    /**
     * @var string|null
     */
    protected $definedConstraintLock;

    /**
     * @var string|null
     */
    protected $definedVersion;

    /**
     * @var string|null
     */
    protected $lockedVersion;

    /**
     * @var string|null
     */
    protected $expectedVersion;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'is_customized' => 'isCustomized',
        'isCustomized' => 'isCustomized',
        'IsCustomized' => 'isCustomized',
        'is_configured' => 'isConfigured',
        'isConfigured' => 'isConfigured',
        'IsConfigured' => 'isConfigured',
        'is_dev' => 'isDev',
        'isDev' => 'isDev',
        'IsDev' => 'isDev',
        'customized_line_count' => 'customizedLineCount',
        'customizedLineCount' => 'customizedLineCount',
        'CustomizedLineCount' => 'customizedLineCount',
        'constraint_reasons' => 'constraintReasons',
        'constraintReasons' => 'constraintReasons',
        'ConstraintReasons' => 'constraintReasons',
        'expected_constraint_lock' => 'expectedConstraintLock',
        'expectedConstraintLock' => 'expectedConstraintLock',
        'ExpectedConstraintLock' => 'expectedConstraintLock',
        'defined_constraint_lock' => 'definedConstraintLock',
        'definedConstraintLock' => 'definedConstraintLock',
        'DefinedConstraintLock' => 'definedConstraintLock',
        'defined_version' => 'definedVersion',
        'definedVersion' => 'definedVersion',
        'DefinedVersion' => 'definedVersion',
        'locked_version' => 'lockedVersion',
        'lockedVersion' => 'lockedVersion',
        'LockedVersion' => 'lockedVersion',
        'expected_version' => 'expectedVersion',
        'expectedVersion' => 'expectedVersion',
        'ExpectedVersion' => 'expectedVersion',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::IS_CUSTOMIZED => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'is_customized',
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
        self::IS_CONFIGURED => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'is_configured',
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
        self::CUSTOMIZED_LINE_COUNT => [
            'type' => 'int',
            'type_shim' => null,
            'name_underscore' => 'customized_line_count',
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
        self::CONSTRAINT_REASONS => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'constraint_reasons',
            'is_collection' => false,
            'is_transfer' => false,
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
        self::EXPECTED_CONSTRAINT_LOCK => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'expected_constraint_lock',
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
        self::DEFINED_CONSTRAINT_LOCK => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'defined_constraint_lock',
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
        self::DEFINED_VERSION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'defined_version',
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
        self::LOCKED_VERSION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'locked_version',
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
    ];

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isCustomized
     *
     * @return $this
     */
    public function setIsCustomized($isCustomized)
    {
        $this->isCustomized = $isCustomized;
        $this->modifiedProperties[self::IS_CUSTOMIZED] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return bool|null
     */
    public function getIsCustomized()
    {
        return $this->isCustomized;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isCustomized
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setIsCustomizedOrFail($isCustomized)
    {
        if ($isCustomized === null) {
            $this->throwNullValueException(static::IS_CUSTOMIZED);
        }

        return $this->setIsCustomized($isCustomized);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getIsCustomizedOrFail()
    {
        if ($this->isCustomized === null) {
            $this->throwNullValueException(static::IS_CUSTOMIZED);
        }

        return $this->isCustomized;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireIsCustomized()
    {
        $this->assertPropertyIsSet(self::IS_CUSTOMIZED);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isConfigured
     *
     * @return $this
     */
    public function setIsConfigured($isConfigured)
    {
        $this->isConfigured = $isConfigured;
        $this->modifiedProperties[self::IS_CONFIGURED] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return bool|null
     */
    public function getIsConfigured()
    {
        return $this->isConfigured;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param bool|null $isConfigured
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setIsConfiguredOrFail($isConfigured)
    {
        if ($isConfigured === null) {
            $this->throwNullValueException(static::IS_CONFIGURED);
        }

        return $this->setIsConfigured($isConfigured);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getIsConfiguredOrFail()
    {
        if ($this->isConfigured === null) {
            $this->throwNullValueException(static::IS_CONFIGURED);
        }

        return $this->isConfigured;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireIsConfigured()
    {
        $this->assertPropertyIsSet(self::IS_CONFIGURED);

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
     * @param int|null $customizedLineCount
     *
     * @return $this
     */
    public function setCustomizedLineCount($customizedLineCount)
    {
        $this->customizedLineCount = $customizedLineCount;
        $this->modifiedProperties[self::CUSTOMIZED_LINE_COUNT] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return int|null
     */
    public function getCustomizedLineCount()
    {
        return $this->customizedLineCount;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param int|null $customizedLineCount
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setCustomizedLineCountOrFail($customizedLineCount)
    {
        if ($customizedLineCount === null) {
            $this->throwNullValueException(static::CUSTOMIZED_LINE_COUNT);
        }

        return $this->setCustomizedLineCount($customizedLineCount);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return int
     */
    public function getCustomizedLineCountOrFail()
    {
        if ($this->customizedLineCount === null) {
            $this->throwNullValueException(static::CUSTOMIZED_LINE_COUNT);
        }

        return $this->customizedLineCount;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCustomizedLineCount()
    {
        $this->assertPropertyIsSet(self::CUSTOMIZED_LINE_COUNT);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string[]|null $constraintReasons
     *
     * @return $this
     */
    public function setConstraintReasons(array $constraintReasons = null)
    {
        if ($constraintReasons === null) {
            $constraintReasons = [];
        }

        $this->constraintReasons = [];

        foreach ($constraintReasons as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::CONSTRAINT_REASONS]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addConstraintReason(...$args);
        }

        $this->modifiedProperties[self::CONSTRAINT_REASONS] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string[]
     */
    public function getConstraintReasons(): array
    {
        return $this->constraintReasons;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string $constraintReason
     *
     * @return $this
     */
    public function addConstraintReason(string $constraintReason)
    {
        $this->constraintReasons[] = $constraintReason;
        $this->modifiedProperties[self::CONSTRAINT_REASONS] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireConstraintReasons()
    {
        $this->assertPropertyIsSet(self::CONSTRAINT_REASONS);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $expectedConstraintLock
     *
     * @return $this
     */
    public function setExpectedConstraintLock($expectedConstraintLock)
    {
        $this->expectedConstraintLock = $expectedConstraintLock;
        $this->modifiedProperties[self::EXPECTED_CONSTRAINT_LOCK] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getExpectedConstraintLock()
    {
        return $this->expectedConstraintLock;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $expectedConstraintLock
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setExpectedConstraintLockOrFail($expectedConstraintLock)
    {
        if ($expectedConstraintLock === null) {
            $this->throwNullValueException(static::EXPECTED_CONSTRAINT_LOCK);
        }

        return $this->setExpectedConstraintLock($expectedConstraintLock);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getExpectedConstraintLockOrFail()
    {
        if ($this->expectedConstraintLock === null) {
            $this->throwNullValueException(static::EXPECTED_CONSTRAINT_LOCK);
        }

        return $this->expectedConstraintLock;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireExpectedConstraintLock()
    {
        $this->assertPropertyIsSet(self::EXPECTED_CONSTRAINT_LOCK);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $definedConstraintLock
     *
     * @return $this
     */
    public function setDefinedConstraintLock($definedConstraintLock)
    {
        $this->definedConstraintLock = $definedConstraintLock;
        $this->modifiedProperties[self::DEFINED_CONSTRAINT_LOCK] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getDefinedConstraintLock()
    {
        return $this->definedConstraintLock;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $definedConstraintLock
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setDefinedConstraintLockOrFail($definedConstraintLock)
    {
        if ($definedConstraintLock === null) {
            $this->throwNullValueException(static::DEFINED_CONSTRAINT_LOCK);
        }

        return $this->setDefinedConstraintLock($definedConstraintLock);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getDefinedConstraintLockOrFail()
    {
        if ($this->definedConstraintLock === null) {
            $this->throwNullValueException(static::DEFINED_CONSTRAINT_LOCK);
        }

        return $this->definedConstraintLock;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDefinedConstraintLock()
    {
        $this->assertPropertyIsSet(self::DEFINED_CONSTRAINT_LOCK);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $definedVersion
     *
     * @return $this
     */
    public function setDefinedVersion($definedVersion)
    {
        $this->definedVersion = $definedVersion;
        $this->modifiedProperties[self::DEFINED_VERSION] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getDefinedVersion()
    {
        return $this->definedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $definedVersion
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setDefinedVersionOrFail($definedVersion)
    {
        if ($definedVersion === null) {
            $this->throwNullValueException(static::DEFINED_VERSION);
        }

        return $this->setDefinedVersion($definedVersion);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getDefinedVersionOrFail()
    {
        if ($this->definedVersion === null) {
            $this->throwNullValueException(static::DEFINED_VERSION);
        }

        return $this->definedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDefinedVersion()
    {
        $this->assertPropertyIsSet(self::DEFINED_VERSION);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $lockedVersion
     *
     * @return $this
     */
    public function setLockedVersion($lockedVersion)
    {
        $this->lockedVersion = $lockedVersion;
        $this->modifiedProperties[self::LOCKED_VERSION] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getLockedVersion()
    {
        return $this->lockedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $lockedVersion
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setLockedVersionOrFail($lockedVersion)
    {
        if ($lockedVersion === null) {
            $this->throwNullValueException(static::LOCKED_VERSION);
        }

        return $this->setLockedVersion($lockedVersion);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getLockedVersionOrFail()
    {
        if ($this->lockedVersion === null) {
            $this->throwNullValueException(static::LOCKED_VERSION);
        }

        return $this->lockedVersion;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireLockedVersion()
    {
        $this->assertPropertyIsSet(self::LOCKED_VERSION);

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
                case 'isCustomized':
                case 'isConfigured':
                case 'isDev':
                case 'customizedLineCount':
                case 'constraintReasons':
                case 'expectedConstraintLock':
                case 'definedConstraintLock':
                case 'definedVersion':
                case 'lockedVersion':
                case 'expectedVersion':
                    $this->$normalizedPropertyName = $value;
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
                case 'isCustomized':
                case 'isConfigured':
                case 'isDev':
                case 'customizedLineCount':
                case 'constraintReasons':
                case 'expectedConstraintLock':
                case 'definedConstraintLock':
                case 'definedVersion':
                case 'lockedVersion':
                case 'expectedVersion':
                    $values[$arrayKey] = $value;

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
                case 'isCustomized':
                case 'isConfigured':
                case 'isDev':
                case 'customizedLineCount':
                case 'constraintReasons':
                case 'expectedConstraintLock':
                case 'definedConstraintLock':
                case 'definedVersion':
                case 'lockedVersion':
                case 'expectedVersion':
                    $values[$arrayKey] = $value;

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
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'isCustomized' => $this->isCustomized,
            'isConfigured' => $this->isConfigured,
            'isDev' => $this->isDev,
            'customizedLineCount' => $this->customizedLineCount,
            'constraintReasons' => $this->constraintReasons,
            'expectedConstraintLock' => $this->expectedConstraintLock,
            'definedConstraintLock' => $this->definedConstraintLock,
            'definedVersion' => $this->definedVersion,
            'lockedVersion' => $this->lockedVersion,
            'expectedVersion' => $this->expectedVersion,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'is_customized' => $this->isCustomized,
            'is_configured' => $this->isConfigured,
            'is_dev' => $this->isDev,
            'customized_line_count' => $this->customizedLineCount,
            'constraint_reasons' => $this->constraintReasons,
            'expected_constraint_lock' => $this->expectedConstraintLock,
            'defined_constraint_lock' => $this->definedConstraintLock,
            'defined_version' => $this->definedVersion,
            'locked_version' => $this->lockedVersion,
            'expected_version' => $this->expectedVersion,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'is_customized' => $this->isCustomized instanceof AbstractTransfer ? $this->isCustomized->toArray(true, false) : $this->isCustomized,
            'is_configured' => $this->isConfigured instanceof AbstractTransfer ? $this->isConfigured->toArray(true, false) : $this->isConfigured,
            'is_dev' => $this->isDev instanceof AbstractTransfer ? $this->isDev->toArray(true, false) : $this->isDev,
            'customized_line_count' => $this->customizedLineCount instanceof AbstractTransfer ? $this->customizedLineCount->toArray(true, false) : $this->customizedLineCount,
            'constraint_reasons' => $this->constraintReasons instanceof AbstractTransfer ? $this->constraintReasons->toArray(true, false) : $this->constraintReasons,
            'expected_constraint_lock' => $this->expectedConstraintLock instanceof AbstractTransfer ? $this->expectedConstraintLock->toArray(true, false) : $this->expectedConstraintLock,
            'defined_constraint_lock' => $this->definedConstraintLock instanceof AbstractTransfer ? $this->definedConstraintLock->toArray(true, false) : $this->definedConstraintLock,
            'defined_version' => $this->definedVersion instanceof AbstractTransfer ? $this->definedVersion->toArray(true, false) : $this->definedVersion,
            'locked_version' => $this->lockedVersion instanceof AbstractTransfer ? $this->lockedVersion->toArray(true, false) : $this->lockedVersion,
            'expected_version' => $this->expectedVersion instanceof AbstractTransfer ? $this->expectedVersion->toArray(true, false) : $this->expectedVersion,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'isCustomized' => $this->isCustomized instanceof AbstractTransfer ? $this->isCustomized->toArray(true, true) : $this->isCustomized,
            'isConfigured' => $this->isConfigured instanceof AbstractTransfer ? $this->isConfigured->toArray(true, true) : $this->isConfigured,
            'isDev' => $this->isDev instanceof AbstractTransfer ? $this->isDev->toArray(true, true) : $this->isDev,
            'customizedLineCount' => $this->customizedLineCount instanceof AbstractTransfer ? $this->customizedLineCount->toArray(true, true) : $this->customizedLineCount,
            'constraintReasons' => $this->constraintReasons instanceof AbstractTransfer ? $this->constraintReasons->toArray(true, true) : $this->constraintReasons,
            'expectedConstraintLock' => $this->expectedConstraintLock instanceof AbstractTransfer ? $this->expectedConstraintLock->toArray(true, true) : $this->expectedConstraintLock,
            'definedConstraintLock' => $this->definedConstraintLock instanceof AbstractTransfer ? $this->definedConstraintLock->toArray(true, true) : $this->definedConstraintLock,
            'definedVersion' => $this->definedVersion instanceof AbstractTransfer ? $this->definedVersion->toArray(true, true) : $this->definedVersion,
            'lockedVersion' => $this->lockedVersion instanceof AbstractTransfer ? $this->lockedVersion->toArray(true, true) : $this->lockedVersion,
            'expectedVersion' => $this->expectedVersion instanceof AbstractTransfer ? $this->expectedVersion->toArray(true, true) : $this->expectedVersion,
        ];
    }
}
