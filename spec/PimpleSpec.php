<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReflectionFunction;

class PimpleSpec extends ObjectBehavior
{
    function it_is_initializable_and_implements_array_access()
    {
        $this->shouldImplement('ArrayAccess');
    }

    function it_stores_strings()
    {
        $this['param'] = 'value';

        $this['param']->shouldReturn('value');
    }

    function it_stores_executable_closures()
    {
        $this['service'] = function() { return new \SplObjectStorage(); };

        $this->offsetGet('service')->shouldHaveType('SplObjectStorage');
    }

    function it_nests_an_offsetGet_method_to_avoid_collisions_with_PHPSpec()
    {
        $this['service'] = function() { return new \SplObjectStorage(); };

        $this->offsetGet('service')->shouldBeAnInstanceOf('SplObjectStorage');
        $this->offsetGet('service')->shouldBeLike(new \SplObjectStorage());
    }

    function it_executes_with_container_argument_when_value_is_a_factory()
    {
        $this['container'] = function($container) { return $container; };

        $this->offsetGet('container')->shouldReturn($this);
    }

    public function it_responds_to_isset()
    {
        $this['param'] = 'value';
        $this['service'] = function () { return new \SplObjectStorage(); };
        $this['null'] = null;

        $this->shouldHaveKey('param');
        $this->shouldHaveKey('service');
        $this->shouldHaveKey('null');
        $this->shouldNotHaveKey('non_existent');
    }

    public function getMatchers()
    {
        return [
            'beAClosure' => function($subject, $closure) {
                $rf = new ReflectionFunction($closure);
                $result = call_user_func($closure) === call_user_func($subject);
                return $rf->isClosure() && $result;
            },
            'returnWhenInvokedWith' => function($subject, array $resultAndArgument) {
                return $resultAndArgument[0] === call_user_func($subject, $resultAndArgument[1]);
            }
        ];
    }
}
