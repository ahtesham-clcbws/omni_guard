<?php

namespace OmniGuard\Data\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OmniGuard\Data\Support\DataContainer;
use OmniGuard\Data\Support\Partials\PartialType;
use OmniGuard\Data\Support\Transformation\TransformationContextFactory;
use OmniGuard\Data\Support\Wrapping\WrapExecutionType;

trait ResponsableData
{
    public function toResponse($request)
    {
        $contextFactory = TransformationContextFactory::create()
            ->withWrapExecutionType(WrapExecutionType::Enabled);

        $includePartials = DataContainer::get()->requestQueryStringPartialsResolver()->execute(
            $this,
            $request,
            PartialType::Include
        );

        if ($includePartials) {
            $contextFactory->mergeIncludePartials($includePartials);
        }

        $excludePartials = DataContainer::get()->requestQueryStringPartialsResolver()->execute(
            $this,
            $request,
            PartialType::Exclude
        );

        if ($excludePartials) {
            $contextFactory->mergeExcludePartials($excludePartials);
        }

        $onlyPartials = DataContainer::get()->requestQueryStringPartialsResolver()->execute(
            $this,
            $request,
            PartialType::Only
        );

        if ($onlyPartials) {
            $contextFactory->mergeOnlyPartials($onlyPartials);
        }

        $exceptPartials = DataContainer::get()->requestQueryStringPartialsResolver()->execute(
            $this,
            $request,
            PartialType::Except
        );

        if ($exceptPartials) {
            $contextFactory->mergeExceptPartials($exceptPartials);
        }

        return new JsonResponse(
            data: $this->transform($contextFactory),
            status: $this->calculateResponseStatus($request),
        );
    }

    protected function calculateResponseStatus(Request $request): int
    {
        return $request->isMethod(Request::METHOD_POST) ? Response::HTTP_CREATED : Response::HTTP_OK;
    }

    public static function allowedRequestIncludes(): ?array
    {
        return [];
    }

    public static function allowedRequestExcludes(): ?array
    {
        return [];
    }

    public static function allowedRequestOnly(): ?array
    {
        return [];
    }

    public static function allowedRequestExcept(): ?array
    {
        return [];
    }
}
