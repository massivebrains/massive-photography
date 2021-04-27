<?php

function _d($dateString = '', $time = true)
{
    if (!$dateString || $dateString == '') {
        return '00-00-0000';
    }

    if ($time == false) {
        return date('M d, Y', strtotime($dateString));
    }

    return date('M d, Y g:i A', strtotime($dateString));
}

function _t($dateString = '')
{
    return date('g:i A', strtotime($dateString));
}

function _reference()
{
    $reference = 'O-' . time() * rand(0, 99);

    if (strlen($reference) < 8) {
        return _reference();
    }

    return $reference;
}

function _email($to = '', $subject = '', $body = '')
{
    try {
        $api_key = env('MAILGUN_API_KEY');

        $payload = [

            'from' => 'Massive Photography <no-reply@massive-photography.com>',
            'to' => $to,
            'bcc' => 'vadeshayo@gmail.com',
            'subject' => $subject,
            'html' => $body,
        ];

        $result = Mailgun\Mailgun::create($api_key)
            ->messages()
            ->send(env('MAILGUN_DOMAIN'), $payload);
    } catch (Exception $e) {
    }
}

function _to_phone($string = '')
{
    return '234' . substr($string, -10);
}


function _cloudinary($file = '', $folder = 'massive-photography', $is_base_64 = false)
{
    try {
        $public_id = str_random(10);

        if ($is_base_64 == true) {
            $response = \Cloudinary\Uploader::upload("data:image/png;base64,$file", [

                'public_id' => $public_id,
                'folder' => $folder,
            ]);
        } else {
            $response = \Cloudinary\Uploader::upload($file, [

                'public_id' => $public_id,
                'folder' => $folder,
            ]);
        }

        return (object) ['status' => true, 'link' => $response['secure_url']];
    } catch (Exception $e) {
        return (object) ['status' => false, 'link' => null, 'error' => $e->getMessage()];
    }
}

function _log($log = '', \App\Models\User $performedOn = null)
{
    $log = $log . ' :: ' . request()->ip();

    if (\Auth::check()) {
        $user = \Auth::user();

        if ($performedOn != null) {
            return activity()->performedOn($performedOn)->causedBy($user)->log((string) $log);
        }

        return activity()->causedBy($user)->log((string) $log);
    }

    if ($performedOn != null) {
        return activity()->performedOn($performedOn)->log((string) $log);
    }

    return activity()->log((string) $log);
}
