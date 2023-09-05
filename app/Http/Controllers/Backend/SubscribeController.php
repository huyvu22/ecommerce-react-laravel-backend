<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\NewsletterSubscriberDataTable;
use App\Helper\MailHepler;
use App\Http\Controllers\Controller;
use App\Models\NewsLetterSubscribes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    public function index( NewsletterSubscriberDataTable $dataTable)
    {
        return $dataTable->render('admin.subscriber.index');
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $emails = NewsLetterSubscribes::where('is_verified',1)->pluck('email')->toArray();

        //mail config
        MailHepler::setMailConfig();

        Mail::to($emails)->send(new \App\Mail\Newsletter($request->title, $request->content));
        toastr()->success('Mail sent successfully!');
        return redirect()->back();

    }

    public function destroy(string $id)
    {
        NewsLetterSubscribes::find($id)->delete();
        toastr()->success('Subscriber deleted successfully');
        return redirect()->back();
    }
}
