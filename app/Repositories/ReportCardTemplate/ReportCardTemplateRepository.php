<?php

namespace App\Repositories\ReportCardTemplate;

use App\Models\ReportCardTemplate;
use App\Repositories\Saas\SaaSRepository;

class ReportCardTemplateRepository extends SaaSRepository implements ReportCardTemplateInterface
{
    public function __construct(ReportCardTemplate $model)
    {
        parent::__construct($model, 'report_card');
    }
}

