<?php

return [
    'base_uri' => 'https://api.zoom.us/v2/',
    'account_id' => 'account_id',
    'client_id' => 'client_id',
    'client_secret' => 'client_secret',


    'template_id' => null, // No template used; leave as null if not applicable
    'pre_schedule' => false, // Not a pre-scheduled meeting
    'schedule_for' => null, // No alternative scheduling user; set to null if unused
    "settings" => [
        'host_video' => true, // Enable host video on join
        'participant_video' => true, // Enable participant video on join
        'cn_meeting' => false, // false = Disables the Chinese (cn) region for the meeting, using global servers (default: false); true = Enables the China region for hosting the meeting
        'in_meeting' => false, // false = Disables in-meeting controls for participants, such as screen sharing or muting (default: false); true = Enables in-meeting controls for participants
        'join_before_host' => true, // Allow participants to join before the host
        'mute_upon_entry' => true, // Mute participants on entry
        'watermark' => false, // Disables the watermark on the meeting video feed (default: false)
        'use_pmi' => false,   // Disables the use of Personal Meeting ID (PMI) for the meeting (default: false)
        'waiting_room' => true, // Enable waiting room for participants
        'audio' => 'voip', // Allow both telephony and VoIP audio
        'auto_recording' => 'none', // Automatically record to the cloud
        'approval_type' => 1, // Manual approval for participants
        'registration_type' => 1, // 1 = No registration required, 2 = Registration required, 3 = Approval required after registration
    ],
];
