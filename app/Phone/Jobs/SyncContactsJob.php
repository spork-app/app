<?php


namespace App\Phone\Jobs;


use App\Features\Contacts\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncContactsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
            $contact = Contact::where('uid', $this->data['id'] ?? null)->first();

            if(!empty($contact)) {
                return;
            }

            if (isset($this->data['id'])) {
                $this->data['uid'] = $this->data['id'];
            }

            /** @var Contact $contact */
            $contact = Contact::create($this->data);

            foreach($this->data['addresses'] as $address) {
                $contact->addresses()->create($address);
            }
            foreach($this->data['emails'] as $email) {
                $contact->emails()->create($email);
            }
            foreach($this->data['numbers'] as $number) {
                $contact->numbers()->create($number);
            }
            foreach($this->data['websites'] as $website) {
                $contact->websites()->create([
                    'site' => $website
                ]);

        }
    }
}
