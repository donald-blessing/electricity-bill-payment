<?php

declare (strict_types=1);

namespace App\Actions;

use App\Traits\PhoneNumberFormatter;
use Exception;
use Illuminate\Support\Facades\Log;

class SendSmsAction
{
    use PhoneNumberFormatter;

    /**
     * @throws Exception
     */
    public static function execute(string $phoneNumber, string $sms): bool
    {
        Log::info('Sending SMS to '.$phoneNumber.' successfully');
//        try {
//            $url = config('services.termii.api_url').'/api/sms/send';
//
//            $formattedPhoneNumber = (new self)->getFormattedPhoneNumber($phoneNumber);
//
//            $data = [
//                'api_key' => config('services.termii.app_key'),
//                // 'from' => config('services.termii.sender_id'),
//                'from'    => 'N-Alert',
//                'to'      => $formattedPhoneNumber,
//                'sms'     => $sms,
//                'type'    => 'plain',
//                'channel' => 'dnd',
//            ];
//
//            $response = HTTP::post($url, $data);
//
//            $responseData = json_decode($response->body(), true);
//
//            if (!array_key_exists('code', $responseData)) {
//                throw new Exception('Something went wrong. Response was incomplete. Code is missing.');
//            }
//
//            if ($responseData['code'] !== 'ok') {
//                throw new Exception($responseData['message']);
//            }
//        } catch (Throwable $th) {
//            report($th);
//
//            throw new Exception($th->getMessage());
//        }

        return true;
    }
}
