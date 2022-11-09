<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use DB;

class Coa extends Model
{
    protected $table = 'ak_coa';
    public $timestamps = false;

    protected $primaryKey = 'coa_id';

    public $coaAsetTanah     = "1100001/UM";
    public $coaAsetBangunan  = "1100002/UM";

    protected $fillable = [
		'group_coa_id','header_coa_id','group_detail','activity_code','code','desc','khaidah_type'
    ];

    public function groupcoa()
    {
        return $this->belongsTo(GroupCoa::class, 'group_coa_id');
    }

    /**
     * var arrayParam multi dimesional array example array('laravelRaw' => true/false, key => ,value
     */
    public static function getListCoa($arrayParam = array(), $q = '')
    {
        $data = DB::table('ak_coa AS a')->selectRaw(
                "b.desc desc_group_coa, a.*, c.desc header_desc, a.coa_id as id, CONCAT(a.code,' - ', a.desc) AS text,
                case 
                    WHEN a.group_detail =1 THEN a.coa_id * 10000
                    ELSE (a.header_coa_id * 10000) + a.coa_id
                END AS parentId")
                ->join('ak_group_coa AS b', 'b.group_coa_id', '=', DB::Raw("a.group_coa_id", DB::raw("b.is_deleted = 0")))
                ->leftJoin('ak_coa AS c', 'c.coa_id', '=', DB::Raw("a.header_coa_id", DB::raw("c.is_deleted = 0")))
                ->whereRaw('a.is_deleted = 0');
        
        if(strlen(trim($q)) > 0){
            $data->whereRaw("(upper(a.code) like upper('%".$q."%') or upper(a.desc) like upper('%".$q."%') or upper(b.code) like '%".$q."%' or upper(b.desc) like upper('%".$q."%') )");
        }

        if(count($arrayParam) > 0){
            foreach($arrayParam as $rows){
                if($rows['laravelRaw'])
                    $data->whereRaw($rows['content']);
                else
                $data->where($rows['key'],$rows['value']);
            }
        }

        $data->orderByRaw("b.group_coa_id, parentId, a.coa_id");
        // echo $data->toSql();
        return $data;       
    }

     
}