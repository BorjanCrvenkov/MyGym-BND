<?php

namespace Tests\Feature\BaseTests;

use App\Models\User;
use App\Traits\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseFilterTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->administrator()->create();
    }

    /**
     * @param array $filters
     * @param array $expectedIds
     * @return void
     */
    protected function testBulkFilters(array $filters = [], array $expectedIds = []): void
    {
        $path = $this->resolveFilterUrl($filters);

        $response = $this->be($this->user)->get($path);

        $data = $response->json()['data'];

        $actualIds = array_column($data, 'id');

        sort($actualIds);

        $this->assertSame($expectedIds, $actualIds);
    }

    /**
     * @param array $filters
     * @return string
     */
    public function resolveFilterUrl(array $filters): string
    {
        $path = $this->endpoint;

        if (empty($filters)) {
            return $path;
        }

        $path .= '?';

        foreach ($filters as $key => $value) {
            if(is_bool($value)){
                $value = $value ? 'true' : 'false';
            }

            $path .= "filter[{$key}]={$value}&";
        }

        return rtrim($path, "&");
    }
}
