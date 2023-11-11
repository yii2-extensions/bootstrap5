<?php

declare(strict_types=1);

namespace yiiunit\extensions\bootstrap5\providers;

final class Data
{
    public static function invalidItems(): array
    {
        return [
            [['content']], // only content without label key
            [[[]]], // only content array without label
            [[['content' => 'test']]], // only content array without label
        ];
    }

    public static function staticControl(): array
    {
        return [
            [
                'foo',
                [],
                '<input type="text" class="form-control-plaintext" value="foo" readonly>'
            ],
            [
                '<html>',
                [],
                '<input type="text" class="form-control-plaintext" value="&lt;html&gt;" readonly>'
            ]
        ];
    }
}
