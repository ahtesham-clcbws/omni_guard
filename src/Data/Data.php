<?php

namespace OmniGuard\Data;

use Illuminate\Contracts\Support\Responsable;
use OmniGuard\Data\Concerns\AppendableData;
use OmniGuard\Data\Concerns\BaseData;
use OmniGuard\Data\Concerns\ContextableData;
use OmniGuard\Data\Concerns\EmptyData;
use OmniGuard\Data\Concerns\IncludeableData;
use OmniGuard\Data\Concerns\ResponsableData;
use OmniGuard\Data\Concerns\TransformableData;
use OmniGuard\Data\Concerns\ValidateableData;
use OmniGuard\Data\Concerns\WrappableData;
use OmniGuard\Data\Contracts\AppendableData as AppendableDataContract;
use OmniGuard\Data\Contracts\BaseData as BaseDataContract;
use OmniGuard\Data\Contracts\ContextableData as ContextableDataContract;
use OmniGuard\Data\Contracts\EmptyData as EmptyDataContract;
use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Contracts\ResponsableData as ResponsableDataContract;
use OmniGuard\Data\Contracts\TransformableData as TransformableDataContract;
use OmniGuard\Data\Contracts\ValidateableData as ValidateableDataContract;
use OmniGuard\Data\Contracts\WrappableData as WrappableDataContract;

abstract class Data implements Responsable, AppendableDataContract, BaseDataContract, TransformableDataContract, ContextableDataContract, IncludeableDataContract, ResponsableDataContract, ValidateableDataContract, WrappableDataContract, EmptyDataContract
{
    use ResponsableData;
    use IncludeableData;
    use AppendableData;
    use ValidateableData;
    use WrappableData;
    use TransformableData;
    use BaseData;
    use EmptyData;
    use ContextableData;
}
