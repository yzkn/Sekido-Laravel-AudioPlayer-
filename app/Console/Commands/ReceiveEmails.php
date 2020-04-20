<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReceiveEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive  e-mails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('handle');

        dump('handle');

        $imap_server = config('imap.host');
        $imap_port = config('imap.port');
        $imap_user = config('imap.username');
        $imap_pass = config('imap.password');
        // dump('$imap_server: ' . $imap_server);
        // dump('$imap_port: ' . $imap_port);
        // dump('$imap_user: ' . $imap_user);
        // dump('$imap_pass: ' . $imap_pass);

        $this->imap_receive_emails($imap_server, $imap_port, $imap_user, $imap_pass);
    }

    private function imap_receive_emails($imap_server, $imap_port, $imap_user, $imap_pass)
    {
        $limit = 1;

        if (($mbox = imap_open('{' . $imap_server . ':' . $imap_port . '}INBOX', $imap_user, $imap_pass)) == false) {
            dump('Failed to connect to ' . $imap_server);
        } else {
            dump('connected to ' . $imap_server);
            $headers = imap_headers($mbox);
            dump('$headers ' . print_r($headers, true));
            $info = imap_check($mbox);
            dump('$info ' . print_r($info, true));
            for ($i = 0; $i < $limit; $i++) {
                $msgno = $info->Nmsgs - $i;
                if ($msgno <= 0) break;

                $header = imap_header($mbox, $msgno);
                $subject = $this->getSubject($header);
                dump($msgno . ':' . $subject);
                dump($this->getBody($mbox, $msgno));
            }
            imap_close($mbox);
            dump('imap_close');
        }
    }

    private function getSubject($header)
    {
        if (!isset($header->subject)) {
            return '';
        }
        $mhead = imap_mime_header_decode($header->subject);
        $subject = '';
        foreach ($mhead as $key => $value) {
            if ($value->charset == 'default') {
                $subject .= $value->text;
            } else {
                $subject .= mb_convert_encoding($value->text, 'UTF-8', $value->charset);
            }
        }
        return $subject;
    }

    private function getBody($mbox, $msgno)
    {
        $body = imap_body($mbox, $msgno);
        $body = mb_convert_encoding($body, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS');
        return $body;
    }
}
