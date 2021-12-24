<?php

namespace App\Phone\Http\Controllers;

use App\Phone\Jobs\SyncContactsJob;
use Illuminate\Http\Request;

class ContactsController extends Controller
{

    public function syncContacts(Request $request)
    {
        $data = collect($request->all())->values();

        dispatch(new SyncContactsJob($data))->onQueue('default');

        return [
            'message' => 'Contacts synced'
        ];
    }

    public function getContacts()
    {
        return \App\Contact::with('addresses', 'emails', 'numbers', 'websites')->get();
    }
}
