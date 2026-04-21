<?php

namespace OmniGuard\Data;

use OmniGuard\Data\Concerns\AppendableData;
use OmniGuard\Data\Concerns\BaseData;
use OmniGuard\Data\Concerns\ContextableData;
use OmniGuard\Data\Concerns\EmptyData;
use OmniGuard\Data\Concerns\IncludeableData;
use OmniGuard\Data\Concerns\ResponsableData;
use OmniGuard\Data\Concerns\TransformableData;
use OmniGuard\Data\Concerns\WrappableData;
use OmniGuard\Data\Contracts\AppendableData as AppendableDataContract;
use OmniGuard\Data\Contracts\BaseData as BaseDataContract;
use OmniGuard\Data\Contracts\ContextableData as ContextableDataContract;
use OmniGuard\Data\Contracts\EmptyData as EmptyDataContract;
use OmniGuard\Data\Contracts\IncludeableData as IncludeableDataContract;
use OmniGuard\Data\Contracts\ResponsableData as ResponsableDataContract;
use OmniGuard\Data\Contracts\TransformableData as TransformableDataContract;
use OmniGuard\Data\Contracts\WrappableData as WrappableDataContract;
use OmniGuard\Data\DataPipes\CastPropertiesDataPipe;
use OmniGuard\Data\DataPipes\DefaultValuesDataPipe;
use OmniGuard\Data\DataPipes\FillRouteParameterPropertiesDataPipe;
use OmniGuard\Data\DataPipes\InjectPropertyValuesPipe;
use OmniGuard\Data\DataPipes\MapPropertiesDataPipe;

class Resource implements BaseDataContract, AppendableDataContract, IncludeableDataContract, TransformableDataContract, ResponsableDataContract, WrappableDataContract, EmptyDataContract, ContextableDataContract
{
    use BaseData;
    use AppendableData;
    use IncludeableData;
    use ResponsableData;
    use TransformableData;
    use WrappableData;
    use EmptyData;
    use ContextableData;

    public static function pipeline(): DataPipeline
    {
        return DataPipeline::create()
            ->into(static::class)
            ->through(MapPropertiesDataPipe::class)
            ->through(FillRouteParameterPropertiesDataPipe::class)
            ->through(InjectPropertyValuesPipe::class)
            ->through(DefaultValuesDataPipe::class)
            ->through(CastPropertiesDataPipe::class);
    }
}
