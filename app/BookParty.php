<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class BookParty extends Model
{
    use SoftDeletes;
    protected $table = 'book_party';

    protected $fillable = [
        'title', 'start_time', 'place', 'speaker', 'max_user', 'summary', 'checkin_code', 'status'
    ];

    protected $hidden = [
//        'checkin_code'
    ];

    public function getStatusAttribute($value) {
        if (Carbon::parse($this->attributes['start_time'])->isPast()) {
            return 1;
        }

        return $value;
    }

    static public function getBookPartyWhenLogin($id, $uid, $withTrashed=false) {
        if (!$id) {
            return null;
        }
        if (!$withTrashed) {
            $bookParty = BookParty::where('id', '=', $id)->where('status', '=', '0')->first();
        } else {
            $bookParty = BookParty::withTrashed()->where('id', '=', $id)->where('status', '=', '0')->first();
        }
        if (!$bookParty) {
            return null;
        }
        if ($uid) {
            if (BookPartySignup::where('uid', $uid)->where('book_party_id', $id)->first()) {
                $bookParty->isSignup = true;
            }
            if (BookPartyCheckin::where('uid', $uid)->where('book_party_id', $id)->first()) {
                $bookParty->isCheckin = true;
            }
        }
        $bookParty->isSignup = !!$bookParty->isSignup;
        $bookParty->isCheckin = !!$bookParty->isCheckin;
        return $bookParty;
    }
}
