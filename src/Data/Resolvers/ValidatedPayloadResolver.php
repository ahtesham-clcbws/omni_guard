<?php

namespace OmniGuard\Data\Resolvers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use OmniGuard\Data\Contracts\BaseData;
use OmniGuard\Data\Contracts\ValidateableData;

class ValidatedPayloadResolver
{
    /** @param class-string<ValidateableData&BaseData> $dataClass */
    public function execute(
        string $dataClass,
        Validator $validator
    ): array {
        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            if (method_exists($dataClass, 'redirect')) {
                $exception->redirectTo(app()->call([$dataClass, 'redirect']));
            }

            if (method_exists($dataClass, 'redirectRoute')) {
                $exception->redirectTo(route(app()->call([$dataClass, 'redirectRoute'])));
            }

            if (method_exists($dataClass, 'errorBag')) {
                $exception->errorBag(app()->call([$dataClass, 'errorBag']));
            }

            throw $exception;
        }

        return $validator->validated();
    }
}
