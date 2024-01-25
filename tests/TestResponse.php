<?php

namespace Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse as BaseTestResponse;

class TestResponse extends BaseTestResponse
{
    /**
     * {@inheritDoc}
     */
    public function assertJsonValidationErrors($errors, $responseKey = 'errors')
    {
        $errors = Arr::wrap($errors);

        PHPUnit::assertNotEmpty($errors, 'No validation errors were provided.');

        $jsonErrors = Arr::get($this->json(), $responseKey) ?? [];

        $errorMessage = $jsonErrors
            ? 'Response has the following JSON validation errors:'.
            PHP_EOL.PHP_EOL.json_encode($jsonErrors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL
            : 'Response does not have JSON validation errors.';

        foreach ($errors as $key => $value) {
            $this->assertJsonValidationErrorFor($key, $responseKey);

            foreach (Arr::wrap($value) as $expectedMessage) {
                $errorMissing = true;

                foreach (Arr::wrap($jsonErrors[$key]) as $jsonErrorMessage) {
                    if (Str::contains($jsonErrorMessage, $expectedMessage)) {
                        $errorMissing = false;

                        break;
                    }
                }
            }

            if ($errorMissing) {
                PHPUnit::fail(
                    "Failed to find a validation error in the response for key and message: '$key' => '$expectedMessage'".PHP_EOL.PHP_EOL.$errorMessage
                );
            }
        }

        return $this;
    }
}
