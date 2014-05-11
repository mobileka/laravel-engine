<?php

namespace spec\Mobileka\L3\Engine\Laravel\Helpers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrSpec extends ObjectBehavior
{
	public function it_is_initializable()
	{
		$this->shouldHaveType('Mobileka\L3\Engine\Laravel\Helpers\Arr');
	}

	public function it_should_tell_whether_two_arrays_have_intersections()
	{
		$this::haveIntersections(['test', 'find_me'], ['something', 'find_me'])->shouldReturn(true);
		$this::haveIntersections(['test', 'find_me'], ['something', 'no'])->shouldReturn(false);
		$this::haveIntersections([['alloha', 'hello'], 'bye'], ['foo', ['alloha', 'hello']])->shouldReturn(true);
		$this::haveIntersections([['alloha', 'hello'], 'bye'], ['hello'])->shouldReturn(false);
	}

	public function it_should_implode_an_array_recursively()
	{
		$this::implodeRecursively(['Hello', ['world']], ', ')->shouldReturn('Hello, world');
		$this::implodeRecursively(['Hello', ['world', ['!']]], ', ')->shouldReturn('Hello, world, !');
		$this::implodeRecursively(['Hello', 'world'], ', ')->shouldReturn('Hello, world');
		$this::implodeRecursively(['Hello'], ', ')->shouldReturn('Hello');
	}

	public function it_shpuld_return_the_first_truthy_element_of_an_array()
	{
		$this::find([false, null, '', 0, 'YEAH!'])->shouldReturn('YEAH!');
		$this::find([false, null, '', 0])->shouldReturn(null);
		$this::find([false, null, 'foo', 0], 'bar')->shouldNotReturn('bar');
		$this::find([false, null, '', 0], 'foo')->shouldReturn('foo');
	}
}
