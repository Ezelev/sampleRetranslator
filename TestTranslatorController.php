<?php

namespace App\Http\Controllers;

use App\Classes\Models\Queue;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class TestTranslatorController extends Controller
{
    public function logSend($error)
    {
        try {
            $client = new Client();
            $request = $client->request('POST', 'https://qwe.asd.zxc/api/log/add',
                ['headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'], 'verify' => false,
                    'json' => ['alias' => 'retranslator', 'name' => 'testLog', 'payload' => ['TextError' => $error]]]);
            return $request;
        } catch (RequestException $err) {
            return $err;
        }

    }

    public function translate (Request $request)
    {
        try
        {
            $address = $request->query();
            $data =['query' => ['format' => 'json', 'geocode' => $address['adress']]];
            $client = new Client();
            $response = $client->request('GET', 'https://geocode-maps.yandex.ru/1.x/', $data);
            $body = $response->getBody()->getContents();
            return $body;
        }
        catch (RequestException $err)
        {
            $body = $err->getMessage();
            $this->logSend($body);
            return $body;
        }
    }
}
