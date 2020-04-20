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
                $this->getBody($mbox, $msgno);
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

    // protected $extensionList = array('mp3', 'pdf', 'png');
    protected $saveDir = './';

    private function getBody($mbox, $msgno)
    {
        dump('getBody($mbox, $msgno): ' . print_r($mbox, true), print_r($msgno, true));

        $charset = null;
        $encoding = null;
        $attached_data = null;
        $parameters = null;

        $info = imap_fetchstructure($mbox, $msgno);
        dump('$info');
        if (!empty($info->parts)) {
            dump('!empty($info->parts)');
            $parts_cnt = count($info->parts);
            for ($p = 0; $p < $parts_cnt; $p++) {
                dump('$info->parts[' . $p . ']: ' . print_r($info->parts[$p], true));

                if ($info->parts[$p]->type == 0) {
                    if (empty($charset)) {
                        $charset = $info->parts[$p]->parameters[0]->value;
                    }
                    if (empty($encoding)) {
                        $encoding = $info->parts[$p]->encoding;
                    }
                } elseif (!empty($info->parts[$p]->parts) && $info->parts[$p]->parts[$p]->type == 0) {
                    $parameters = $info->parts[$p]->parameters[0]->value;
                    if (empty($charset)) {
                        $charset = $info->parts[$p]->parts[$p]->parameters[0]->value;
                    }
                    if (empty($encoding)) {
                        $encoding = $info->parts[$p]->parts[$p]->encoding;
                    }
                } elseif ($info->parts[$p]->type == 3 || $info->parts[$p]->type == 4 || $info->parts[$p]->type == 5) {
                    $files = imap_mime_header_decode($info->parts[$p]->parameters[0]->value);
                    if (!empty($files) && is_array($files)) {
                        $attached_data[$p]['file_name'] = null;
                        foreach ($files as $key => $file) {
                            if ($file->charset != 'default') {
                                $attached_data[$p]['file_name'] .= mb_convert_encoding($file->text, 'UTF-8', $file->charset);
                            } else {
                                $attached_data[$p]['file_name'] .= $file->text;
                            }
                        }
                    }
                    $attached_data[$p]['content_type'] = $info->parts[$p]->subtype;
                }
            }
        } else {
            dump('empty($info->parts)');
            $charset = $info->parameters[0]->value;
            $encoding = $info->encoding;
        }
        if (empty($charset)) {
            dump('empty($charset)');
        }

        $body = imap_fetchbody($mbox, $msgno, 1, FT_INTERNAL);
        $body = trim($body);
        dump('trim($body)');

        if (!empty($body)) {
            dump('!empty($body)');

            switch ($encoding) {
                case 0:
                    $mail[$msgno]['body'] = mb_convert_encoding($body, "UTF-8", $charset);
                    break;
                case 1:
                    $encode_body = imap_8bit($body);
                    $encode_body = imap_qprint($encode_body);
                    $mail[$msgno]['body'] = mb_convert_encoding($encode_body, "UTF-8", $charset);
                    break;
                case 3:
                    $encode_body = imap_base64($body);
                    $mail[$msgno]['body'] = mb_convert_encoding($encode_body, "UTF-8", $charset);
                    break;
                case 4:
                    $encode_body = imap_qprint($body);
                    $mail[$msgno]['body'] = mb_convert_encoding($encode_body, 'UTF-8', $charset);
                    break;
                case 2:
                case 5:
                default:
                    dump('$encoding: ' . $encoding);
                    break;
            }
        } else {
            dump('empty($body)');
        }

        if (!empty($attached_data)) {
            dump('!empty($attached_data)');
            foreach ($attached_data as $key => $value) {
                $attached = imap_fetchbody($mbox, $msgno, $key + 1, FT_INTERNAL);
                if (empty($attached)) break;

                list($name, $ex) = explode('.', $value['file_name']);
                $mail[$msgno]['attached_file'][$key]['file_name'] = $name . '_' . time() . '_' . $key . '.' . $ex;
                $mail[$msgno]['attached_file'][$key]['content_body'] = imap_base64($attached);
                $mail[$msgno]['attached_file'][$key]['content_type'] = strtolower($value['content_type']);

                dump('$mail: ' . print_r($mail[$msgno]['attached_file'][$key]['content_type'], true));

                $body = $mail[$msgno]['attached_file'][$key]['content_body'];
                $saveFilename = $mail[$msgno]['attached_file'][$key]['file_name'];
                $savePath = $this->saveDir . $saveFilename;
                if ($fp = fopen($savePath, "w")) {
                    $length = strlen($body);
                    fwrite($fp, $body, $length);
                    fclose($fp);
                    dump('Saved: ' . $saveFilename);
                }
            }
        }
    }
}
