<?php

namespace spec;

use PhpSpec\ObjectBehavior;
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

    function it_returns_different_instances_from_factories()
    {
        $this['service'] = function() { return new \SplObjectStorage(); };

        $this->offsetGet('service')->shouldBeLike(new \SplObjectStorage());
        $this->offsetGet('service')->shouldNotBe(new \SplObjectStorage());
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

    function it_takes_key_value_pairs_via_constructor()
    {
        $this->beConstructedWith(['param' => 'value']);
        $this->offsetGet('param')->shouldReturn('value');
    }

    function it_tests_key_is_present()
    {
        $exception = new \InvalidArgumentException('Identifier "foo" is not defined.');
        $this->shouldThrow($exception)->duringOffsetGet('foo');
    }

    function it_holds_and_honors_null_values()
    {
        $this['foo'] = null;
        $this['foo']->shouldReturn(null);
    }

    function it_supports_unset()
    {
        $this['param'] = 'value';
        $this['service'] = function() { new \SplObjectStorage(); };

        unset($this['param'], $this['service']);
        $this->shouldNotHaveKey('param');
        $this->shouldNotHaveKey('service');
    }

    function it_shares_a_persistent_common_instance()
    {
        $this['shared_service'] = $this->share(function() { return new \SplObjectStorage(); });
        $service = $this->offsetGet('shared_service');
        $this->offsetGet('shared_service')->shouldNotBe(null);
        $this->offsetGet('shared_service')->shouldBe($service);
    }

    function it_can_fence_callback_from_being_factored()
    {
        $callback = function() { return 'foo'; };

        $this['protected'] = $this->protect($callback);
        $this->offsetGet('protected')->shouldBe($callback);
    }

    function it_fences_function_names_treating_them_as_parameters()
    {
        $this['global_function'] = 'strlen';
        $this->offsetGet('global_function')->shouldBe('strlen');
    }

    function it_gives_back_raw_value()
    {
        $this['service'] = $definition = function() { return 'foo'; };
        $this->raw('service')->shouldBe($definition);
    }

    function it_gives_back_raw_nulls()
    {
        $this['foo'] = null;
        $this->raw('foo')->shouldBe(null);
    }

    function it_tests_key_present_on_raw_call()
    {
        $exception = new \InvalidArgumentException('Identifier "foo" is not defined.');
        $this->shouldThrow($exception)->duringRaw('foo');
    }

    function it_can_extend_values()
    {
        $this['shared_service'] = $this->share(function() { return new \SplObjectStorage(); });
        $value = 123;
        $assignmentDecorator = function($sharedService) use ($value) {
            $sharedService->value = $value;

            return $sharedService;
        };
        $this->extend('shared_service', $assignmentDecorator);

        $this->offsetGet('shared_service')->shouldHaveType(get_class($this->offsetGet('shared_service')));
        $this->offsetGet('shared_service')->value->shouldBe($value);
        $this->offsetGet('shared_service')->value->shouldBe($this->offsetGet('shared_service')->value);
        $this->offsetGet('shared_service')->shouldBe($this->offsetGet('shared_service'));
    }

    function it_can_list_all_keys_present()
    {
        $this['foo'] = 5;
        $this['bar'] = 7;
        $this->keys()->shouldReturn(['foo', 'bar']);
    }

    function it_checks_for_valid_key_when_extending()
    {
        $exception = new \InvalidArgumentException('Identifier "foo" is not defined.');
        $this->shouldThrow($exception)->duringExtend('foo', function() {});
    }

    function it_invokes_factory_when_value_is_an_invokable_object()
    {
        $this['invokable'] = new Invokable();
        $this->offsetGet('invokable')->shouldReturn('I was invoked');
    }

    function it_treats_non_invokable_object_as_a_parameter()
    {
        $objectParameter = new \StdClass();
        $this['non_invokable'] = $objectParameter;
        $this->offsetGet('non_invokable')->shouldReturn($objectParameter);
    }
}

class Invokable
{
    function __invoke()
    {
        return 'I was invoked';
    }
}