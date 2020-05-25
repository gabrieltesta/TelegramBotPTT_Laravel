<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class BotController extends Controller
{
    private $chat_id;
    private $img_url;
    private $token;
    private $bot;

    function __construct()
    {
        $this->token    = config('telegram.token');
        $this->chat_id  = config('telegram.chat_id');
        $this->img_url  = config('telegram.img_url');

        if(isset($this->token, $this->chat_id, $this->img_url))
            $this->bot = new Api($this->token);

        $this->validateBot();
    }

    public function __invoke($id)
    {
        $this->startBot();
    }

    private function startBot()
    {
        try
        {
            $this->bot->sendPhoto([
                'chat_id'   => $this->chat_id,
                'photo'     => InputFile::create($this->img_url, 'PTT_'.date('Y-m-d_H:i:s')),
                'caption'   => date('d/m/Y H:i')
            ]);
        }
        catch(\Exception $e)
        {
            \Log::error('Erro ao enviar a mensagem');
            die();
        }

        \Log::info('Mensagem enviada com sucesso.');
        die();
    }

    private function validateBot()
    {
        if(!$this->bot)
        {
            \Log::error('Erro ao criar instância do Bot.');
            die();
        }
    }
}
