<?php

namespace App\Repositories\AdmitCardTemplate;

use App\Models\AdmitCardTemplate;
use App\Repositories\Saas\SaaSRepository;

class AdmitCardTemplateRepository extends SaaSRepository implements AdmitCardTemplateInterface {
    public function __construct(AdmitCardTemplate $model) {
        parent::__construct($model , 'admit_card');
    }
}
