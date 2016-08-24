<?php namespace Waavi\ResponseCache\Test\Cache;

use Waavi\ResponseCache\Cache\TaggedRepository;
use Waavi\ResponseCache\Test\TestCase;

class TaggedRepositoryTest extends TestCase
{
    public function setUp()
    {
        // During the parent's setup, both a 'es' 'Spanish' and 'en' 'English' languages are inserted into the database.
        parent::setUp();
        $this->repo = new TaggedRepository(\Cache::getStore(), 'translation');
    }

    /**
     * @test
     */
    public function test_has_with_no_entry()
    {
        $this->assertFalse($this->repo->has('key'));
    }

    /**
     * @test
     */
    public function test_has_returns_true_if_entry()
    {
        $this->repo->put('key', 'value', 60);
        $this->assertTrue($this->repo->has('key'));
    }

    /**
     * @test
     */
    public function test_get_returns_null_if_empty()
    {
        $this->assertNull($this->repo->get('key'));
    }

    /**
     * @test
     */
    public function test_get_return_content_if_hit()
    {
        $this->repo->put('key', 'value', 60);
        $this->assertEquals('value', $this->repo->get('key'));
    }

    /**
     * @test
     */
    public function test_clear_all_removes_all_in_tag()
    {
        \Cache::put('original', 'original', 60);
        $this->repo->put('key', 'value', 60);
        $this->repo->put('key2', 'value', 60);
        $this->repo->clear();
        $this->assertNull($this->repo->get('key'));
        $this->assertNull($this->repo->get('key2'));
        $this->assertEquals('original', \Cache::get('original'));
    }
}
