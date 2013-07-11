<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PimpleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pimple');
        $this->shouldImplement('ArrayAccess');
    }

    function it_should_store_strings()
    {
        $this['param'] = 'value';
        $this['param']->shouldReturn('value');
    }
}
