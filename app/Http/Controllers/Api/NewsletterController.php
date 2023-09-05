<?php

namespace App\Http\Controllers\Api;

use App\Helper\MailHepler;
use App\Http\Controllers\Controller;
use App\Mail\SubscriptionVerification;
use App\Models\NewsLetterSubscribes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    use \App\Traits\HttpResponses;
    public function newsLetter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
           return $this->error($validator->errors()->first('email'),'Validation failed', 422);
        }

        $existSubscriber = NewsLetterSubscribes::where('email', $request->email)->first();

        if(!empty($existSubscriber)){
            if($existSubscriber->is_verified == 0) {

                //send link verification again
                $existSubscriber->verified_token = Str::random(25);
                $existSubscriber->save();

                //mail config
                MailHepler::setMailConfig();

                //send mail
                Mail::to($existSubscriber->email)->send(new SubscriptionVerification($existSubscriber));

                return $this->success($existSubscriber,'Check your email to activate newsletter');

            }else if($existSubscriber->is_verified == 1){
                return $this->error('','Your already subscribed',400);
            }
        }else{
            $subscriber = new NewsLetterSubscribes();
            $subscriber->email = $request->email;
            $subscriber->verified_token = Str::random(25);
            $subscriber->is_verified = 0;
            $subscriber->save();

            //mail config
            MailHepler::setMailConfig();

            //send mail
            Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));

            return $this->success($existSubscriber,'Check your email to activate newsletter');

        }

    }

    public function newsLetterEmailVerify($token)
    {
        $verify =  NewsLetterSubscribes::where('verified_token', $token)->first();

        if($verify){
            $verify->verified_token = 'verified';
            $verify->is_verified = 1;
            $verify->save();

        }else {
             abort(404);
        }
        toastr('Subscription successfully');
        return redirect()->to('http://localhost:3000/');
    }
}
