<?php

namespace spec\Scripture;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Scripture\Memory');
    }

    function it_should_allow_to_probe_for_relevance()
    {
       $this->isTimeToRemember()->shouldReturn(true);
    }

    //function
}
