<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use DB;

class BaseModel extends Model
{
    public $primaryKey = 'id';
    public $timestamps = false;

    public function column_defaults()
    {
        $defaults = DB::select("SELECT name, max_length, is_nullable, object_definition(default_object_id) AS [default]
                            FROM sys.columns WHERE   object_id = object_id('".$this->getTable()."')");

        foreach ($defaults as $key => $val)
        {
            $defaults[$val->name] = $val;
            unset($defaults[$key]);
            unset($defaults[$val->name]->name);

            if(stristr($val->default,'getdate'))
                $val->default = Carbon::now('Europe/Istanbul')->toDateTimeString();
            else if(stristr($val->default,'suser_sname'))
                $val->default = head(DB::select("select suser_sname()")[0]);
            else
            {
                $val->default = str_replace(["\n", "\r", "(", ")", "'"],'', $val->default);
                if (stristr($val->default, ' as '))
                {
                    $exploded = explode(' ', $val->default);
                    $val->default = end($exploded);
                }
            }
        }

        //dd($defaults1);

        return $defaults;
    }
}
