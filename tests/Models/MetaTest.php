<?php namespace GoCardless\Pro\Tests\Models;

use GoCardless\Pro\Models\Meta;

class MetaTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_be_created_without_meta()
    {
        $meta = new Meta;

        $this->assertAttributeEquals([], 'attributes', $meta);
    }

    /** @test */
    function it_allows_meta_being_added()
    {
        $meta = new Meta;

        $meta->add('foo', 'Bar');

        $this->assertAttributeEquals(['foo' => 'Bar'], 'attributes', $meta);
    }

    /** @test */
    function it_can_be_created_with_meta()
    {
        $meta = new Meta(['foo' => 'bar']);

        $this->assertAttributeEquals(['foo' => 'bar'], 'attributes', $meta);
    }

    /** @test */
    function it_throws_an_invalid_argument_expection_if_there_is_too_many_attributes()
    {
        $this->setExpectedException('InvalidArgumentException');

        $meta = new Meta;

        $meta->add('foo', 'foo')
            ->add('bar', 'foo')
            ->add('baz', 'foo')
            ->add('bing', 'foo');
    }

    /** @test */
    function it_throws_an_exception_if_the_key_length_is_too_long()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Meta(['this_key_is_too_long_for_gocardless_xyxyxyxyxyxyxyx' => 'foo']);
    }

    /** @test */
    function it_throws_an_exception_if_the_value_is_too_long()
    {
        $this->setExpectedException('InvalidArgumentException');

        new Meta(['a' => 'Pellentesque augue ex, semper sed libero id, finibus dignissim ante. Aliquam erat volutpat. Quisque purus urna, facilisis non lacus ut, congue dictum lectus. Suspendisse sagittis vestibulum risus, ut cursus lectus sagittis sit amet.']);
    }

    /** @test */
    function it_allows_meta_to_be_removed()
    {
        $meta = new Meta(['foo' => 'bar']);

        $meta->remove('foo');

        $this->assertAttributeEquals([], 'attributes', $meta);
    }

    /** @test */
    function it_provides_a_fluent_interface()
    {
        $meta = new Meta;

        $this->assertSame($meta, $meta->add('foo', 'bar'));
        $this->assertSame($meta, $meta->remove('foo'));
    }

    /** @test */
    function it_fails_silently_if_the_key_doesnt_exist_when_removing_item()
    {
        $meta = new Meta;

        $meta->remove('foo');
        $meta->remove('bar');

        $this->assertAttributeEquals([], 'attributes', $meta);
    }

    /** @test */
    function it_returns_a_meta_item_of_a_given_key()
    {
        $meta = new Meta(['foo' => 'bar']);

        $this->assertEquals('bar', $meta->get('foo'));
    }

    /** @test */
    function it_returns_null_if_the_key_doesnt_exist()
    {
        $this->assertNull((new Meta)->get('foo'));
    }

    /** @test */
    function it_returns_if_a_meta_item_exists()
    {
        $meta = new Meta(['foo' => 'bar']);

        $this->assertTrue($meta->exists('foo'));
        $this->assertFalse($meta->exists('bar'));
    }

    /** @test */
    function it_returns_all_meta()
    {
        $details = ['foo' => 'bar', 'baz' => 'bing'];

        $meta = new Meta($details);

        $this->assertEquals($details, $meta->all());
    }
}