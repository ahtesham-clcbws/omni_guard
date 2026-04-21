<?php

namespace OmniGuard\Data\Support\Validation;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationRuleParser;
use OmniGuard\Data\Attributes\Validation\Accepted;
use OmniGuard\Data\Attributes\Validation\AcceptedIf;
use OmniGuard\Data\Attributes\Validation\ActiveUrl;
use OmniGuard\Data\Attributes\Validation\After;
use OmniGuard\Data\Attributes\Validation\AfterOrEqual;
use OmniGuard\Data\Attributes\Validation\Alpha;
use OmniGuard\Data\Attributes\Validation\AlphaDash;
use OmniGuard\Data\Attributes\Validation\AlphaNumeric;
use OmniGuard\Data\Attributes\Validation\ArrayType;
use OmniGuard\Data\Attributes\Validation\Bail;
use OmniGuard\Data\Attributes\Validation\Before;
use OmniGuard\Data\Attributes\Validation\BeforeOrEqual;
use OmniGuard\Data\Attributes\Validation\Between;
use OmniGuard\Data\Attributes\Validation\BooleanType;
use OmniGuard\Data\Attributes\Validation\Confirmed;
use OmniGuard\Data\Attributes\Validation\CurrentPassword;
use OmniGuard\Data\Attributes\Validation\Date;
use OmniGuard\Data\Attributes\Validation\DateEquals;
use OmniGuard\Data\Attributes\Validation\DateFormat;
use OmniGuard\Data\Attributes\Validation\Declined;
use OmniGuard\Data\Attributes\Validation\DeclinedIf;
use OmniGuard\Data\Attributes\Validation\Different;
use OmniGuard\Data\Attributes\Validation\Digits;
use OmniGuard\Data\Attributes\Validation\DigitsBetween;
use OmniGuard\Data\Attributes\Validation\Dimensions;
use OmniGuard\Data\Attributes\Validation\Distinct;
use OmniGuard\Data\Attributes\Validation\DoesntEndWith;
use OmniGuard\Data\Attributes\Validation\DoesntStartWith;
use OmniGuard\Data\Attributes\Validation\Email;
use OmniGuard\Data\Attributes\Validation\EndsWith;
use OmniGuard\Data\Attributes\Validation\Enum;
use OmniGuard\Data\Attributes\Validation\ExcludeIf;
use OmniGuard\Data\Attributes\Validation\ExcludeUnless;
use OmniGuard\Data\Attributes\Validation\ExcludeWith;
use OmniGuard\Data\Attributes\Validation\ExcludeWithout;
use OmniGuard\Data\Attributes\Validation\Exists;
use OmniGuard\Data\Attributes\Validation\File;
use OmniGuard\Data\Attributes\Validation\Filled;
use OmniGuard\Data\Attributes\Validation\GreaterThan;
use OmniGuard\Data\Attributes\Validation\GreaterThanOrEqualTo;
use OmniGuard\Data\Attributes\Validation\Image;
use OmniGuard\Data\Attributes\Validation\In;
use OmniGuard\Data\Attributes\Validation\InArray;
use OmniGuard\Data\Attributes\Validation\IntegerType;
use OmniGuard\Data\Attributes\Validation\IP;
use OmniGuard\Data\Attributes\Validation\IPv4;
use OmniGuard\Data\Attributes\Validation\IPv6;
use OmniGuard\Data\Attributes\Validation\Json;
use OmniGuard\Data\Attributes\Validation\LessThan;
use OmniGuard\Data\Attributes\Validation\LessThanOrEqualTo;
use OmniGuard\Data\Attributes\Validation\ListType;
use OmniGuard\Data\Attributes\Validation\Lowercase;
use OmniGuard\Data\Attributes\Validation\MacAddress;
use OmniGuard\Data\Attributes\Validation\Max;
use OmniGuard\Data\Attributes\Validation\MaxDigits;
use OmniGuard\Data\Attributes\Validation\Mimes;
use OmniGuard\Data\Attributes\Validation\MimeTypes;
use OmniGuard\Data\Attributes\Validation\Min;
use OmniGuard\Data\Attributes\Validation\MinDigits;
use OmniGuard\Data\Attributes\Validation\MultipleOf;
use OmniGuard\Data\Attributes\Validation\NotIn;
use OmniGuard\Data\Attributes\Validation\NotRegex;
use OmniGuard\Data\Attributes\Validation\Nullable;
use OmniGuard\Data\Attributes\Validation\Numeric;
use OmniGuard\Data\Attributes\Validation\Password;
use OmniGuard\Data\Attributes\Validation\Present;
use OmniGuard\Data\Attributes\Validation\Prohibited;
use OmniGuard\Data\Attributes\Validation\ProhibitedIf;
use OmniGuard\Data\Attributes\Validation\ProhibitedUnless;
use OmniGuard\Data\Attributes\Validation\Prohibits;
use OmniGuard\Data\Attributes\Validation\Regex;
use OmniGuard\Data\Attributes\Validation\Required;
use OmniGuard\Data\Attributes\Validation\RequiredArrayKeys;
use OmniGuard\Data\Attributes\Validation\RequiredIf;
use OmniGuard\Data\Attributes\Validation\RequiredUnless;
use OmniGuard\Data\Attributes\Validation\RequiredWith;
use OmniGuard\Data\Attributes\Validation\RequiredWithAll;
use OmniGuard\Data\Attributes\Validation\RequiredWithout;
use OmniGuard\Data\Attributes\Validation\RequiredWithoutAll;
use OmniGuard\Data\Attributes\Validation\Same;
use OmniGuard\Data\Attributes\Validation\Size;
use OmniGuard\Data\Attributes\Validation\Sometimes;
use OmniGuard\Data\Attributes\Validation\StartsWith;
use OmniGuard\Data\Attributes\Validation\StringType;
use OmniGuard\Data\Attributes\Validation\Timezone;
use OmniGuard\Data\Attributes\Validation\Ulid;
use OmniGuard\Data\Attributes\Validation\Unique;
use OmniGuard\Data\Attributes\Validation\Uppercase;
use OmniGuard\Data\Attributes\Validation\Url;
use OmniGuard\Data\Attributes\Validation\Uuid;
use OmniGuard\Data\Exceptions\CouldNotCreateValidationRule;

class ValidationRuleFactory
{
    public function create(string $rule): ValidationRule
    {
        [$keyword, $parameters] = ValidationRuleParser::parse($rule);

        /** @var \OmniGuard\Data\Attributes\Validation\StringValidationAttribute|null $ruleClass */
        $ruleClass = $this->mapping()[Str::snake($keyword)] ?? null;

        if ($ruleClass === null) {
            throw CouldNotCreateValidationRule::create($rule);
        }

        return $ruleClass::create(...$parameters);
    }

    protected function mapping(): array
    {
        return [
            Accepted::keyword() => Accepted::class,
            AcceptedIf::keyword() => AcceptedIf::class,
            ActiveUrl::keyword() => ActiveUrl::class,
            After::keyword() => After::class,
            AfterOrEqual::keyword() => AfterOrEqual::class,
            Alpha::keyword() => Alpha::class,
            AlphaDash::keyword() => AlphaDash::class,
            AlphaNumeric::keyword() => AlphaNumeric::class,
            ArrayType::keyword() => ArrayType::class,
            Bail::keyword() => Bail::class,
            Before::keyword() => Before::class,
            BeforeOrEqual::keyword() => BeforeOrEqual::class,
            Between::keyword() => Between::class,
            BooleanType::keyword() => BooleanType::class,
            Confirmed::keyword() => Confirmed::class,
            CurrentPassword::keyword() => CurrentPassword::class,
            Date::keyword() => Date::class,
            DateEquals::keyword() => DateEquals::class,
            DateFormat::keyword() => DateFormat::class,
            Declined::keyword() => Declined::class,
            DeclinedIf::keyword() => DeclinedIf::class,
            Different::keyword() => Different::class,
            Digits::keyword() => Digits::class,
            DigitsBetween::keyword() => DigitsBetween::class,
            Dimensions::keyword() => Dimensions::class,
            Distinct::keyword() => Distinct::class,
            Email::keyword() => Email::class,
            DoesntEndWith::keyword() => DoesntEndWith::class,
            DoesntStartWith::keyword() => DoesntStartWith::class,
            EndsWith::keyword() => EndsWith::class,
            Enum::keyword() => Enum::class,
            ExcludeIf::keyword() => ExcludeIf::class,
            ExcludeUnless::keyword() => ExcludeUnless::class,
            ExcludeWith::keyword() => ExcludeWith::class,
            ExcludeWithout::keyword() => ExcludeWithout::class,
            Exists::keyword() => Exists::class,
            File::keyword() => File::class,
            Filled::keyword() => Filled::class,
            GreaterThan::keyword() => GreaterThan::class,
            GreaterThanOrEqualTo::keyword() => GreaterThanOrEqualTo::class,
            Image::keyword() => Image::class,
            In::keyword() => In::class,
            InArray::keyword() => InArray::class,
            IntegerType::keyword() => IntegerType::class,
            IP::keyword() => IP::class,
            IPv4::keyword() => IPv4::class,
            IPv6::keyword() => IPv6::class,
            Json::keyword() => Json::class,
            LessThan::keyword() => LessThan::class,
            LessThanOrEqualTo::keyword() => LessThanOrEqualTo::class,
            ListType::keyword() => ListType::class,
            Lowercase::keyword() => Lowercase::class,
            MacAddress::keyword() => MacAddress::class,
            Max::keyword() => Max::class,
            MaxDigits::keyword() => MaxDigits::class,
            Mimes::keyword() => Mimes::class,
            MimeTypes::keyword() => MimeTypes::class,
            Min::keyword() => Min::class,
            MinDigits::keyword() => MinDigits::class,
            MultipleOf::keyword() => MultipleOf::class,
            NotIn::keyword() => NotIn::class,
            NotRegex::keyword() => NotRegex::class,
            Nullable::keyword() => Nullable::class,
            Numeric::keyword() => Numeric::class,
            Password::keyword() => Password::class,
            Present::keyword() => Present::class,
            Prohibited::keyword() => Prohibited::class,
            ProhibitedIf::keyword() => ProhibitedIf::class,
            ProhibitedUnless::keyword() => ProhibitedUnless::class,
            Prohibits::keyword() => Prohibits::class,
            Regex::keyword() => Regex::class,
            Required::keyword() => Required::class,
            RequiredArrayKeys::keyword() => RequiredArrayKeys::class,
            RequiredIf::keyword() => RequiredIf::class,
            RequiredUnless::keyword() => RequiredUnless::class,
            RequiredWith::keyword() => RequiredWith::class,
            RequiredWithAll::keyword() => RequiredWithAll::class,
            RequiredWithout::keyword() => RequiredWithout::class,
            RequiredWithoutAll::keyword() => RequiredWithoutAll::class,
            Same::keyword() => Same::class,
            Size::keyword() => Size::class,
            Sometimes::keyword() => Sometimes::class,
            StartsWith::keyword() => StartsWith::class,
            StringType::keyword() => StringType::class,
            Timezone::keyword() => Timezone::class,
            Unique::keyword() => Unique::class,
            Uppercase::keyword() => Uppercase::class,
            Url::keyword() => Url::class,
            Ulid::keyword() => Ulid::class,
            Uuid::keyword() => Uuid::class,
        ];
    }
}
