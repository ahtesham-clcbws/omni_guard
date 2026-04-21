<?php

namespace OmniGuard\Data;

use OmniGuard\Data\Concerns\BaseData;
use OmniGuard\Data\Concerns\ValidateableData;
use OmniGuard\Data\Contracts\BaseData as BaseDataContract;
use OmniGuard\Data\Contracts\ValidateableData as ValidateableDataContract;

class Dto implements ValidateableDataContract, BaseDataContract
{
    use ValidateableData;
    use BaseData;
}
