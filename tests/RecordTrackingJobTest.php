<?php

namespace rommea\MailTracker\Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use rommea\MailTracker\Model\SentEmail;
use rommea\MailTracker\RecordBounceJob;
use rommea\MailTracker\RecordDeliveryJob;
use rommea\MailTracker\RecordTrackingJob;
use rommea\MailTracker\RecordComplaintJob;
use rommea\MailTracker\RecordLinkClickJob;
use rommea\MailTracker\Events\ViewEmailEvent;
use rommea\MailTracker\Events\LinkClickedEvent;

class RecordTrackingJobTest extends SetUpTest
{
    /**
     * @test
     */
    public function it_records_views()
    {
        Event::fake();
        $track = \rommea\MailTracker\Model\SentEmail::create([
                'hash' => Str::random(32),
            ]);
        $job = new RecordTrackingJob($track, '127.0.0.1');

        $job->handle();

        Event::assertDispatched(ViewEmailEvent::class, function ($e) use ($track) {
            return $track->id == $e->sent_email->id &&
                $e->ip_address == '127.0.0.1';
        });
        $this->assertDatabaseHas('sent_emails', [
                'id' => $track->id,
                'opens' => 1,
            ]);
    }
}
