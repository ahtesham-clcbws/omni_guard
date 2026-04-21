<?php

namespace OmniGuard\Scanner\TokenParsers;

use OmniGuard\Scanner\Collections\TokenCollection;
use OmniGuard\Scanner\Collections\UsageCollection;
use OmniGuard\Scanner\Data\DiscoveredAttribute;
use OmniGuard\Scanner\Data\DiscoveredClass;
use OmniGuard\Scanner\Data\DiscoveredEnum;
use OmniGuard\Scanner\Data\DiscoveredInterface;
use OmniGuard\Scanner\Data\DiscoveredTrait;
use OmniGuard\Scanner\Enums\DiscoveredStructureType;

class DiscoveredDataTokenParser
{
    public function __construct(
        protected ReferenceTokenParser $referenceTokenResolver = new ReferenceTokenParser(),
        protected ReferenceListTokenParser $classListResolver = new ReferenceListTokenParser(),
        protected DiscoveredEnumTokenParser $discoveredEnumResolver = new DiscoveredEnumTokenParser(),
        protected DiscoveredClassTokenParser $discoveredClassResolver = new DiscoveredClassTokenParser(),
        protected StructureHeadTokenParser $structureHeadResolver = new StructureHeadTokenParser(),
    ) {
    }

    /**
     * @param DiscoveredAttribute[] $attributes
     */
    public function execute(
        int $index,
        TokenCollection $tokens,
        string $namespace,
        UsageCollection $usages,
        array $attributes,
        DiscoveredStructureType $type,
        string $file,
    ): DiscoveredInterface|DiscoveredClass|DiscoveredTrait|DiscoveredEnum {
        return match ($type) {
            DiscoveredStructureType::ClassDefinition => $this->discoveredClassResolver->execute(
                $index,
                $tokens,
                $namespace,
                $usages,
                $attributes,
                $file
            ),
            DiscoveredStructureType::Interface => $this->resolveInterface(
                $index,
                $tokens,
                $namespace,
                $usages,
                $attributes,
                $file
            ),
            DiscoveredStructureType::Trait => $this->resolveTrait(
                $index,
                $tokens,
                $namespace,
                $usages,
                $attributes,
                $file
            ),
            DiscoveredStructureType::Enum => $this->discoveredEnumResolver->execute(
                $index,
                $tokens,
                $namespace,
                $usages,
                $attributes,
                $file
            )
        };
    }

    /**
     * @param DiscoveredAttribute[] $attributes
     */
    protected function resolveInterface(
        int $index,
        TokenCollection $tokens,
        string $namespace,
        UsageCollection $usages,
        array $attributes,
        string $file
    ): DiscoveredInterface {
        $head = $this->structureHeadResolver->execute($index, $tokens, $namespace, $usages);

        return new DiscoveredInterface(
            $tokens->get($index)->text,
            $file,
            $namespace,
            $head->extends,
            $attributes,
        );
    }

    /**
     * @param DiscoveredAttribute[] $attributes
     */
    protected function resolveTrait(
        int $index,
        TokenCollection $tokens,
        string $namespace,
        UsageCollection $usages,
        array $attributes,
        string $file
    ): DiscoveredTrait {
        return new DiscoveredTrait(
            $tokens->get($index)->text,
            $file,
            $namespace,
        );
    }
}
