<?php namespace GoCardless\Pro\Tests\Models;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_get_all_links()
    {
        $entity = BasicEntity::fromArray(
            [
                'links' => [
                    'customer_id' => 'CU12345',
                    'mandate_id'  => 'MD12345'
                ]
            ]
        );

        $this->assertCount(2, $entity->getLinks());
    }

    /** @test */
    function it_can_get_a_single_link()
    {
        $entity = BasicEntity::fromArray(['links' => ['customer_id' => 'CU12345']]);

        $this->assertEquals('CU12345', $entity->getLink('customer_id'));
    }
}
