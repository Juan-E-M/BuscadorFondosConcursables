<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestableFund extends Model
{
    use HasFactory;
    protected $table = "contestable_funds";
    protected $fillable = [
        'institution',
        'name',
        'summary',
        'start_date',
        'end_date',
        'status',
        'budget',
        'link',
        'others',
        'region',
        'file_path',
    ];

    public function country()
    { return $this->belongsTo(Country::class);}
    public function trl()
    { return $this->belongsTo(Trl::class); }
    public function crl()
    { return $this->belongsTo(Crl::class); }
    public function ocde()
    { return $this->belongsToMany(Ocde::class, 'contestable_fund_ocde', 'contestable_fund_id', 'ocde_id'); }
    public function ods()
    { return $this->belongsToMany(Ods::class, 'contestable_fund_ods', 'contestable_fund_id', 'ods_id');}

}
