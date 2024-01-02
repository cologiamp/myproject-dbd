<?php

namespace App\Models;

use App\Models\BaseModels\Model;

class Note extends Model
{
    //todo:?/ Do comments need to be able to retrive all of the things that they were commented on?
    //If so, because they can be applied to *any* model, this becomes non-trivial and needs to be done in a N+1 query way.

}
