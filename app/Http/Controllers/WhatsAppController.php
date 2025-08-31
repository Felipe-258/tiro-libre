<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class WhatsAppController extends Controller
{
    public function sendWhatsAppMessage($number, $message)
    {
        $command = "node resources/js/whatsapp/sendMessage.js {$number} \"{$message}\"";
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
