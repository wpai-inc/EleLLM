<?php

test('the prompts work', function () {
    // @todo: Implement this
    // $blade = <<<EOT
    // foo {{ $var }}
    // EOT;

    // $compiled = $llm->prompt()->render($blade, ['var' => 'bar']);

    $result = <<<'EOT'
    foo bar
    biz baz

    EOT;
    $compiled = $this->llm->prompt()->file('test', ['var' => 'bar']);
    expect($compiled)->toBe($result);
});
