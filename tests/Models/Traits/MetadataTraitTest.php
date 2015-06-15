<?php
namespace GoCardless\Tests\Models\Traits;

class MetadataTraitTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function SetUp()
    {
        $this->object = $this->getObjectForTrait('GoCardless\Pro\Models\Traits\Metadata');
    }

    /** @test */
    function it_can_be_used_by_an_object()
    {
        $this->assertContains('GoCardless\Pro\Models\Traits\Metadata', class_uses($this->object));
    }

    /** @test */
    function it_can_return_all_meta()
    {
        $this->assertEquals([], $this->object->getMetadata());
    }

    /** @test */
    function it_allows_meta_being_added()
    {
        $this->object->addMetadata('foo', 'Bar');

        $this->assertEquals(['foo' => 'Bar'], $this->object->getMetadata());
    }

    /** @test */
    function it_can_have_a_whole_metadata_array_set()
    {
        $this->object->setMetadata(['foo' => 'bar', 'bar' => 'baz']);

        $this->assertEquals(['foo' => 'bar', 'bar' => 'baz'], $this->object->getMetadata());
    }

    /** @test */
    function it_throws_an_invalid_argument_expection_if_there_is_too_many_attributes()
    {
        $this->setExpectedException('OutOfBoundsException');

        $this->object
            ->addMetadata('foo', 'foo')
            ->addMetadata('bar', 'foo')
            ->addMetadata('baz', 'foo')
            ->addMetadata('bing', 'foo');
    }

    /** @test */
    function it_throws_an_exception_if_the_key_length_is_too_long()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->object->setMetadata(['this_key_is_too_long_for_gocardless_xyxyxyxyxyxyxyx' => 'foo']);
    }

    /** @test */
    function it_throws_an_exception_if_the_value_is_too_long()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->object->setMetadata(['a' => 'Pellentesque augue ex, semper sed libero id, finibus dignissim ante. Aliquam erat volutpat. Quisque purus urna, facilisis non lacus ut, congue dictum lectus. Suspendisse sagittis vestibulum risus, ut cursus lectus sagittis sit amet.']);
    }

    /** @test */
    function it_allows_meta_to_be_removed()
    {
        $this->object->setMetadata(['foo' => 'bar']);

        $this->object->removeMetadata('foo');

        $this->assertEquals([], $this->object->getMetadata());
    }

    /** @test */
    function it_provides_a_fluent_interface()
    {
        $this->assertSame($this->object, $this->object->addMetadata('foo', 'bar'));
        $this->assertSame($this->object, $this->object->removeMetadata('foo'));
    }

    /** @test */
    function it_fails_silently_if_the_key_doesnt_exist_when_removing_item()
    {
        $this->object->removeMetadata('foo');
        $this->object->removeMetadata('bar');

        $this->assertEquals([], $this->object->getMetadata());
    }

    /** @test */
    function it_returns_a_meta_item_of_a_given_key()
    {
        $this->object->setMetadata(['foo' => 'bar']);

        $this->assertEquals('bar', $this->object->getMetadata('foo'));
    }

    /** @test */
    function it_returns_null_if_the_key_doesnt_exist()
    {
        $this->assertNull($this->object->getMetadata('foo'));
    }

    /** @test */
    function it_returns_if_a_meta_item_exists()
    {
        $this->object->setMetadata(['foo' => 'bar']);

        $this->assertTrue($this->object->metadataExists('foo'));
        $this->assertFalse($this->object->metadataExists('bar'));
    }

    /** @test */
    function it_populates_using_fromarray()
    {
        $mandate = \GoCardless\Pro\Models\Mandate::fromArray([
            'metadata' => ['foo' => 'bar']
        ]);

        $this->assertEquals(['foo' => 'bar'], $mandate->getMetadata());
    }
}
