<?php

// =========================
// ERROR REPORTING (DEV)
// =========================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =========================
// BASE PATH (CORRETO)
// main.php está na raiz: gerador/
// =========================
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

// =========================
// CONFIG & CORE
// =========================
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/variables.php';

require_once BASE_PATH . '/functions/db.php';
require_once BASE_PATH . '/functions/bot.php';
require_once BASE_PATH . '/functions/functions.php';

// =========================
// TIMEZONE
// =========================
date_default_timezone_set(
    !empty($config['timeZone']) ? $config['timeZone'] : 'UTC'
);

// =========================
// SAFE VARIABLES (ANTI NOTICE)
// =========================
$message    = isset($message) ? (string) $message : '';
$data       = isset($data) ? (string) $data : '';
$username   = !empty($username) ? $username : 'User';
$userId     = isset($userId) ? (int) $userId : 0;
$chat_id    = isset($chat_id) ? (int) $chat_id : 0;
$message_id = isset($message_id) ? (int) $message_id : 0;

$callbackchatid     = $callbackchatid     ?? 0;
$callbackmessageid = $callbackmessageid ?? 0;

$messagesec = '';

// =========================
// MODULES (CORE)
// =========================
require_once BASE_PATH . '/modules/admin.php';
require_once BASE_PATH . '/modules/skcheck.php';
require_once BASE_PATH . '/modules/binlookup.php';
require_once BASE_PATH . '/modules/iban.php';
require_once BASE_PATH . '/modules/stats.php';
require_once BASE_PATH . '/modules/me.php';
require_once BASE_PATH . '/modules/apikey.php';

// =========================
// CHECKER MODULES
// =========================
require_once BASE_PATH . '/modules/checker/ss.php';
require_once BASE_PATH . '/modules/checker/schk.php';
require_once BASE_PATH . '/modules/checker/sm.php';

// =========================
// /START
// =========================
if (strpos($message, '/start') === 0) {

    if (!isBanned($userId) && !isMuted($userId)) {

        addUser($userId);

        if ($userId === (int) $config['adminID']) {
            $messagesec = "<b>Type /admin to view admin commands</b>";
        }

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => "<b>Hello @$username 👋

Type /cmds to see all available commands.</b>

$messagesec",
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '💠 Created By 💠', 'url' => 'https://t.me/iamNVN']
                    ],
                    [
                        ['text' => '💎 Source Code 💎', 'url' => 'https://github.com/iam-NVN/SDMN_CheckerBot']
                    ]
                ]
            ])
        ]);
    }
}

// =========================
// /CMDS
// =========================
if (
    strpos($message, '/cmds') === 0 ||
    strpos($message, '!cmds') === 0
) {

    if (!isBanned($userId) && !isMuted($userId)) {

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => '<b>Which commands would you like to check?</b>',
            'parse_mode' => 'html',
            'reply_to_message_id' => $message_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => '💳 CC Checker Gates', 'callback_data' => 'checkergates']
                    ],
                    [
                        ['text' => '🛠 Other Commands', 'callback_data' => 'othercmds']
                    ]
                ]
            ])
        ]);
    }
}

// =========================
// CALLBACK: BACK
// =========================
if ($data === 'back') {

    bot('editMessageText', [
        'chat_id' => $callbackchatid,
        'message_id' => $callbackmessageid,
        'text' => '<b>Which commands would you like to check?</b>',
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '💳 CC Checker Gates', 'callback_data' => 'checkergates']
                ],
                [
                    ['text' => '🛠 Other Commands', 'callback_data' => 'othercmds']
                ]
            ]
        ])
    ]);
}

// =========================
// CALLBACK: CHECKER GATES
// =========================
if ($data === 'checkergates') {

    bot('editMessageText', [
        'chat_id' => $callbackchatid,
        'message_id' => $callbackmessageid,
        'text' => "<b>━━ CC Checker Gates ━━</b>

<b>/ss | !ss</b> – Stripe Auth  
<b>/sm | !sm</b> – Stripe Merchant  
<b>/schk | !schk</b> – User Stripe Merchant (SK Required)

<b>/apikey sk_live_xxx</b> – Add SK  
<b>/myapikey | !myapikey</b> – View your SK

<b>ϟ Join <a href='https://t.me/pyLeads'>pyLeads</a></b>",
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '⬅ Return', 'callback_data' => 'back']
                ]
            ]
        ])
    ]);
}

// =========================
// CALLBACK: OTHER CMDS
// =========================
if ($data === 'othercmds') {

    bot('editMessageText', [
        'chat_id' => $callbackchatid,
        'message_id' => $callbackmessageid,
        'text' => "<b>━━ Other Commands ━━</b>

<b>/me | !me</b> – Your info  
<b>/stats | !stats</b> – Checker stats  
<b>/key | !key</b> – SK checker  
<b>/bin | !bin</b> – BIN lookup  
<b>/iban | !iban</b> – IBAN checker  

<b>ϟ Join <a href='https://t.me/pyLeads'>pyLeads</a></b>",
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '⬅ Return', 'callback_data' => 'back']
                ]
            ]
        ])
    ]);
}
