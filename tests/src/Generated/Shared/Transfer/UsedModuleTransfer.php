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
class UsedModuleTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const MODULE = 'module';

    /**
     * @var string
     */
    public const PACKAGE_NAME = 'packageName';

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
    public const CUSTOMIZED_LINE_COUNT = 'customizedLineCount';

    /**
     * @var string
     */
    public const CONSTRAINT_REASONS = 'constraintReasons';

    /**
     * @var string|null
     */
    protected $organization;

    /**
     * @var string|null
     */
    protected $module;

    /**
     * @var string|null
     */
    protected $packageName;

    /**
     * @var bool|null
     */
    protected $isCustomized;

    /**
     * @var bool|null
     */
    protected $isConfigured;

    /**
     * @var int|null
     */
    protected $customizedLineCount;

    /**
     * @var string[]
     */
    protected $constraintReasons = [];

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'organization' => 'organization',
        'Organization' => 'organization',
        'module' => 'module',
        'Module' => 'module',
        'package_name' => 'packageName',
        'packageName' => 'packageName',
        'PackageName' => 'packageName',
        'is_customized' => 'isCustomized',
        'isCustomized' => 'isCustomized',
        'IsCustomized' => 'isCustomized',
        'is_configured' => 'isConfigured',
        'isConfigured' => 'isConfigured',
        'IsConfigured' => 'isConfigured',
        'customized_line_count' => 'customizedLineCount',
        'customizedLineCount' => 'customizedLineCount',
        'CustomizedLineCount' => 'customizedLineCount',
        'constraint_reasons' => 'constraintReasons',
        'constraintReasons' => 'constraintReasons',
        'ConstraintReasons' => 'constraintReasons',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::ORGANIZATION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'organization',
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
        self::MODULE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'module',
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
        self::PACKAGE_NAME => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'package_name',
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
    ];

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $organization
     *
     * @return $this
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        $this->modifiedProperties[self::ORGANIZATION] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $organization
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setOrganizationOrFail($organization)
    {
        if ($organization === null) {
            $this->throwNullValueException(static::ORGANIZATION);
        }

        return $this->setOrganization($organization);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getOrganizationOrFail()
    {
        if ($this->organization === null) {
            $this->throwNullValueException(static::ORGANIZATION);
        }

        return $this->organization;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireOrganization()
    {
        $this->assertPropertyIsSet(self::ORGANIZATION);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $module
     *
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        $this->modifiedProperties[self::MODULE] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $module
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setModuleOrFail($module)
    {
        if ($module === null) {
            $this->throwNullValueException(static::MODULE);
        }

        return $this->setModule($module);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getModuleOrFail()
    {
        if ($this->module === null) {
            $this->throwNullValueException(static::MODULE);
        }

        return $this->module;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireModule()
    {
        $this->assertPropertyIsSet(self::MODULE);

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $packageName
     *
     * @return $this
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
        $this->modifiedProperties[self::PACKAGE_NAME] = true;

        return $this;
    }

    /**
     * @module ComposerConstrainer
     *
     * @return string|null
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @module ComposerConstrainer
     *
     * @param string|null $packageName
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setPackageNameOrFail($packageName)
    {
        if ($packageName === null) {
            $this->throwNullValueException(static::PACKAGE_NAME);
        }

        return $this->setPackageName($packageName);
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getPackageNameOrFail()
    {
        if ($this->packageName === null) {
            $this->throwNullValueException(static::PACKAGE_NAME);
        }

        return $this->packageName;
    }

    /**
     * @module ComposerConstrainer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePackageName()
    {
        $this->assertPropertyIsSet(self::PACKAGE_NAME);

        return $this;
    }

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
                case 'organization':
                case 'module':
                case 'packageName':
                case 'isCustomized':
                case 'isConfigured':
                case 'customizedLineCount':
                case 'constraintReasons':
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
                case 'organization':
                case 'module':
                case 'packageName':
                case 'isCustomized':
                case 'isConfigured':
                case 'customizedLineCount':
                case 'constraintReasons':
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
                case 'organization':
                case 'module':
                case 'packageName':
                case 'isCustomized':
                case 'isConfigured':
                case 'customizedLineCount':
                case 'constraintReasons':
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
            'organization' => $this->organization,
            'module' => $this->module,
            'packageName' => $this->packageName,
            'isCustomized' => $this->isCustomized,
            'isConfigured' => $this->isConfigured,
            'customizedLineCount' => $this->customizedLineCount,
            'constraintReasons' => $this->constraintReasons,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'organization' => $this->organization,
            'module' => $this->module,
            'package_name' => $this->packageName,
            'is_customized' => $this->isCustomized,
            'is_configured' => $this->isConfigured,
            'customized_line_count' => $this->customizedLineCount,
            'constraint_reasons' => $this->constraintReasons,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'organization' => $this->organization instanceof AbstractTransfer ? $this->organization->toArray(true, false) : $this->organization,
            'module' => $this->module instanceof AbstractTransfer ? $this->module->toArray(true, false) : $this->module,
            'package_name' => $this->packageName instanceof AbstractTransfer ? $this->packageName->toArray(true, false) : $this->packageName,
            'is_customized' => $this->isCustomized instanceof AbstractTransfer ? $this->isCustomized->toArray(true, false) : $this->isCustomized,
            'is_configured' => $this->isConfigured instanceof AbstractTransfer ? $this->isConfigured->toArray(true, false) : $this->isConfigured,
            'customized_line_count' => $this->customizedLineCount instanceof AbstractTransfer ? $this->customizedLineCount->toArray(true, false) : $this->customizedLineCount,
            'constraint_reasons' => $this->constraintReasons instanceof AbstractTransfer ? $this->constraintReasons->toArray(true, false) : $this->constraintReasons,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'organization' => $this->organization instanceof AbstractTransfer ? $this->organization->toArray(true, true) : $this->organization,
            'module' => $this->module instanceof AbstractTransfer ? $this->module->toArray(true, true) : $this->module,
            'packageName' => $this->packageName instanceof AbstractTransfer ? $this->packageName->toArray(true, true) : $this->packageName,
            'isCustomized' => $this->isCustomized instanceof AbstractTransfer ? $this->isCustomized->toArray(true, true) : $this->isCustomized,
            'isConfigured' => $this->isConfigured instanceof AbstractTransfer ? $this->isConfigured->toArray(true, true) : $this->isConfigured,
            'customizedLineCount' => $this->customizedLineCount instanceof AbstractTransfer ? $this->customizedLineCount->toArray(true, true) : $this->customizedLineCount,
            'constraintReasons' => $this->constraintReasons instanceof AbstractTransfer ? $this->constraintReasons->toArray(true, true) : $this->constraintReasons,
        ];
    }
}
