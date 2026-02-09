<?php

namespace App\Repositories\ReportCardTemplate;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ReportCardTemplateInterface
{
    public function all(array $columns = ['*'], array $relations = [], array $where = []): Collection;
    
    public function create(array $payload): ?Model;
    
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;
    
    public function update(int $modelId, array $payload): ?Model;
    
    public function deleteById(int $modelId): bool;
    
    public function builder();
}
