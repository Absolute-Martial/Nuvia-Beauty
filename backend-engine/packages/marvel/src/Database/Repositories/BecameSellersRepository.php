<?php


namespace Marvel\Database\Repositories;

use Marvel\Database\Models\BecameSeller;

class BecameSellersRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return BecameSeller::class;
    }

}
