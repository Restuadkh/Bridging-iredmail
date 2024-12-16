<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vmail extends Model
{
    use HasFactory;
    protected $table = 'mailbox';

    protected $fillable = [
        'username',
        'password',
        'name',
        'language',
        'mailboxformat',
        'mailboxfolder',
        'storagebasedirectory',
        'storagenode',
        'maildir',
        'quota',
        'domain',
        'transport',
        'department',
        'rank',
        'employeeid',
        'isadmin',
        'isglobaladmin',
        'enablesmtp',
        'enablesmtpsecured',
        'enablepop3',
        'enablepop3secured',
        'enablepop3tls',
        'enableimap',
        'enableimapsecured',
        'enableimaptls',
        'enabledeliver',
        'enablelda',
        'enablemanagesieve',
        'enablemanagesievesecured',
        'enablesieve',
        'enablesievesecured',
        'enablesievetls',
        'enableinternal',
        'enabledoveadm',
        'enablelib-storage',
        'enablequota-status',
        'enableindexer-worker',
        'enablelmtp',
        'enabledsync',
        'enablesogo',
        'enablesogowebmail',
        'enablesogocalendar',
        'enablesogoactivesync',
        'allow_nets',
        'disclaimer',
        'settings',
        'passwordlastchange',
        'created',
        'modified',
        'expired',
        'active',
    ];

    public $timestamps = false;
}
