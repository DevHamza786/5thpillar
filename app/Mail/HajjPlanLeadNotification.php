<?php

namespace App\Mail;

use App\Models\HajjPlanLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HajjPlanLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $results;

    /**
     * Create a new message instance.
     */
    public function __construct(HajjPlanLead $lead, array $results)
    {
        $this->lead = $lead;
        $this->results = $results;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = ($this->lead->plan_type ?? 'hajj') === 'umrah'
            ? 'New Umrah Plan Lead: '.$this->lead->name
            : 'New Hajj Plan Lead: '.$this->lead->name;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hajj-plan-lead',
        );
    }
}
