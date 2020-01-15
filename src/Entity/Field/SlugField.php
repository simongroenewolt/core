<?php

declare(strict_types=1);

namespace Bolt\Entity\Field;

use Bolt\Common\Str;
use Bolt\Entity\Field;
use Bolt\Entity\FieldInterface;
use Doctrine\ORM\Mapping as ORM;
use Tightenco\Collect\Support\Collection;

/**
 * @ORM\Entity
 */
class SlugField extends Field implements FieldInterface
{
    public function getType(): string
    {
        return 'slug';
    }

    public function setValue($value): parent
    {
        if (is_array($value)) {
            $value = reset($value);
        }
        $value = Str::slug($value);
        parent::setValue([$value]);

        return $this;
    }

    public function getSlugUseFields(): array
    {
        return Collection::wrap($this->getDefinition()->get('uses'))->toArray();
    }
}
