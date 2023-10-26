<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\About;
use App\Models\EmailConfig;
use App\Models\FAQ;
use App\Models\GeneralSetting;
use App\Models\PrivacyPolicy;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    use \App\Traits\HttpResponses;
    public function about()
    {
        $about = About::first();
        return $this->success($about);
    }

    public function termsAndCondition()
    {
        $termsAndCondition = TermsAndCondition::first();
        return $this->success($termsAndCondition);
    }

    public function privacyPolicy()
    {
        $policy = PrivacyPolicy::first();
        return $this->success($policy);
    }

    public function contact()
    {
        $contact = GeneralSetting::first();
        return  $this->success($contact);
    }

    public function postContactForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required|email',
            'subject' => 'required|max:200',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(),'Validation failed', 422);
        }

        $setting = EmailConfig::first();
        \Mail::to($setting->email)->send(new Contact($request->subject, $request->message, $request->email));

        return $this->success('', 'Submit contact form successfully');
    }

    public function faqs()
    {
        $questionAndAnswer = FAQ::where('status',1)->get();
        return $this->success($questionAndAnswer);
    }
}
