<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Zoom {
    protected string $accessToken;
    protected $client;
    protected $account_id;
    protected $client_id;
    protected $client_secret;

    protected $setting;

    public function __construct() {
        if (auth()->check()) {
            $user = auth()->user();
            $this->client_id = method_exists($user, 'clientID') ? $user->clientID() : config('zoom.client_id');
            $this->client_secret = method_exists($user, 'clientSecret') ? $user->clientSecret() : config('zoom.client_secret');
            $this->account_id = method_exists($user, 'accountID') ? $user->accountID() : config('zoom.account_id');
        } else {
            $this->client_id = config('zoom.client_id');
            $this->client_secret = config('zoom.client_secret');
            $this->account_id = config('zoom.account_id');
        }

        try {
            $this->accessToken = $this->getAccessToken();
        } catch (\Exception $e) {
            $this->accessToken = $this->client_secret;
            info($e->getMessage());
        }
        $this->client = new Client([
            'base_uri' => config('zoom.base_uri'),
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type'  => 'application/json',
            ],
        ]);
        $this->setting = cache()->get('setting');
    }

    protected function getAccessToken() {

        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                'Host'          => 'zoom.us',
            ],
        ]);

        $response = $client->request('POST', "https://zoom.us/oauth/token", [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $this->account_id,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['access_token'];
    }

    /**
     * Convert a given date and time to Zoom-compatible ISO 8601 format.
     *
     * @param string $dateTime The date and time to format (e.g., '2025-01-01 10:00:00').
     * @return string Formatted date in 'Y-m-d\TH:i:s' format or an empty string on error.
     */
    public function toZoomTimeFormat(string $dateTime) {
        try {
            $setting = cache()->get('setting');
            $timezone = $setting?->timezone ?? config('app.timezone');
            $date = new \DateTime($dateTime, new \DateTimeZone($timezone));
            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            info('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());

            return '';
        }
    }
    /**
     * Format the data for a Zoom meeting creation.
     *
     * @param string $topic The topic or name of the meeting.
     * @param int $duration The duration of the meeting in minutes.
     * @param string $start_time The start time of the meeting in ISO 8601 format (e.g., '2025-01-01T10:00:00Z').
     *
     * @return array Formatted data for meeting creation.
     */
    public function dataFormat(string $topic, int $duration, string $start_time): array {
        return [
            "agenda"       => $this->setting?->app_name ?? config('app.name'),
            "topic"        => $topic,
            "type"         => 2,
            "duration"     => $duration,
            "timezone"     => $this->setting?->timezone ?? config('app.timezone'),
            "password"     => Str::random(8),
            "start_time"   => $this->toZoomTimeFormat($start_time), //'2024-12-31T15:00:00', // Start time in ISO 8601 format
            "template_id" => config('zoom.template_id'),
            "pre_schedule" => config('zoom.pre_schedule'),
            "schedule_for" => config('zoom.schedule_for'),
            "settings"     => [
                'host_video'        => config('zoom.settings.host_video'),
                'participant_video' => config('zoom.settings.participant_video'),
                'cn_meeting'        => config('zoom.settings.cn_meeting'),
                'in_meeting'        => config('zoom.settings.in_meeting'),
                'join_before_host'  => config('zoom.settings.join_before_host'),
                'mute_upon_entry'   => config('zoom.settings.mute_upon_entry'),
                'watermark'         => config('zoom.settings.watermark'),
                'use_pmi'           => config('zoom.settings.use_pmi'),
                'waiting_room'      => config('zoom.settings.waiting_room'),
                'audio'             => config('zoom.settings.audio'),
                'auto_recording'    => config('zoom.settings.auto_recording'),
                'approval_type'     => config('zoom.settings.approval_type'),
                'registration_type' => config('zoom.settings.registration_type'),
            ],
        ];

    }

    // create meeting
    public function createMeeting(array $data) {
        try {
            $response = $this->client->request('POST', 'users/me/meetings', [
                'json' => $data,
            ]);
            $res = json_decode($response->getBody(), true);
            return [
                'status' => true,
                'data'   => $res,
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    // update meeting
    public function updateMeeting(string $meetingId, array $data) {
        try {
            $response = $this->client->request('PATCH', 'meetings/' . $meetingId, [
                'json' => $data,
            ]);
            $res = json_decode($response->getBody(), true);
            return [
                'status' => true,
                'data'   => $res,
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    // delete meeting
    public function deleteMeeting(string $meetingId) {
        try {
            $response = $this->client->request('DELETE', 'meetings/' . $meetingId);
            if ($response->getStatusCode() === 204) {
                return [
                    'status'  => true,
                    'message' => 'Meeting Deleted Successfully',
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => 'Something went wrong',
                ];
            }
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => $th->getMessage(),
            ];
        }

    }

    // get meeting
    public function getMeeting(string $meetingId) {
        try {
            $response = $this->client->request('GET', 'meetings/' . $meetingId);
            $data = json_decode($response->getBody(), true);
            return [
                'status' => true,
                'data'   => $data,
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
