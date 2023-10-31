<?php

namespace jdavidbakr\MailTracker\Model;

use Illuminate\Database\Eloquent\Model;
use jdavidbakr\MailTracker\Concerns\IsSentEmailUrlClickedModel;
use jdavidbakr\MailTracker\Contracts\SentEmailUrlClickedModel;


class SentEmailUrlClicked extends Model implements SentEmailUrlClickedModel
{
    use IsSentEmailUrlClickedModel;

    protected $table = 'sent_emails_url_clicked';

    protected $fillable = [
        'sent_email_id',
        'url',
        'hash',
        'clicks',
    ];

    protected $keyType = 'int';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->keyType = (config('mail-tracker.use_uuids')) ? 'string' : 'int';
    }

    public function getConnectionName()
    {
        $connName = config('mail-tracker.connection');
        return $connName ?: config('database.default');
    }

    public function email()
    {
        return $this->belongsTo(SentEmail::class, 'sent_email_id');
    }

}
