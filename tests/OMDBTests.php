<?php

use aharen\OMDbAPI;
use PHPUnit\Framework\TestCase;

/**
 * Unit testing for OMDB.
 */
class OMDBTests extends TestCase
{
    /**
     * Testing the search
     */
    public function testSearch()
    {
        $omdb = new aharen\OMDbAPI($_ENV['OMDB_KEY']);
        $result = $omdb->search('matrix');

        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertEquals(200, $result->code);
        $this->assertNotEmpty($result->data);
        $this->assertObjectHasAttribute('Search', $result->data);
        $search = $result->data->Search;
        $this->assertNotEmpty($search);
        $found = false;
        foreach ($search as $entry) {
            $this->assertObjectHasAttribute('Title', $entry);
            if ($entry->Title == 'The Matrix') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    /**
     * Testing fetch (IMDB id and title)
     */
    public function testFetch()
    {
        $omdb = new aharen\OMDbAPI($_ENV['OMDB_KEY']);

        $result = $omdb->fetch('i', 'tt0338013');
        $this->assertEquals(200, $result->code);
        $this->assertEquals('Eternal Sunshine of the Spotless Mind', $result->data->Title);
        $result = $omdb->fetch('t', 'Eternal Sunshine of the Spotless Mind');
        $this->assertEquals(200, $result->code);
        $this->assertEquals('Eternal Sunshine of the Spotless Mind', $result->data->Title);
    }

    /**
     * Testing associative mode
     */
    public function testSearchAssoc()
    {
        $omdb = new aharen\OMDbAPI($_ENV['OMDB_KEY'], false, true);
        $result = $omdb->search('matrix');

        $this->assertTrue(is_array($result));
        $this->assertEquals(200, $result['code']);
        $this->assertNotEmpty($result['data']);
        $this->assertArrayHasKey('Search', $result['data']);
        $search = $result['data']['Search'];
        $this->assertNotEmpty($search);
        $found = false;
        foreach ($search as $entry) {
            $this->assertArrayHasKey('Title', $entry);
            if ($entry['Title'] == 'The Matrix') {
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    /**
     * Testing parameters search
     */
    public function testParameters()
    {
        $omdb = new aharen\OMDbAPI($_ENV['OMDB_KEY']);
        $result = $omdb->fetch('i', 'tt0773262', ['Season' => 1]);

        $this->assertEquals(200, $result->code);
        $this->assertEquals(12, count($result->data->Episodes));
    }
}
