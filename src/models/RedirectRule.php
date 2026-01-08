<?php

namespace Redirect\models; 
use Microservices\models\BaseModel;
class RedirectRule extends BaseModel
{ 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'integer' => ['_id', 'created_by'],
        'unixtime' => ['created_time', 'updated_time'] 
    ];
    protected $idAutoIncrement = 0;
    #####
    protected $table = 'redirect_rules';
    #####
    protected $primaryKey = '_id';
    #### GTRI MAC DINH
    protected $dataDefault = [
        'create' => ['status' => "active"]
    ];
}