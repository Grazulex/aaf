<?php

namespace App\Services\Messages;

use App\Models\Battle;
use App\Models\Message;
use App\Models\Party;

class CreateMessageService
{
    public function create(int $type = 1, Party $from, Party $to = null, int $value = null)
    {

        switch ($type) {
            case 1:
                $text = 'is now friend with';
                break;
            case 2:
                $text = 'is not more friend with';
                break;
            case 3:
                $text = 'try to add to much friends';
                break;
            case 4:
                $text = 'give a potion to';
                break;
            case 5:
                $text = 'use a potion and won %value% healt';
                break;
            case 6:
                $text = 'have used a potion and share with friend and won each %value% healt';
                break;
            case 7:
                $text = 'is wackup now';
                break;
            case 8:
                $text = 'have made %value% step(s)';
                break;
            case 9:
                $text = 'enter now in sleep mode';
                break;
            case 10:
                $text = 'have change %value% healt to add armor';
                break;
            case 11:
                $text = 'dont have enought healt to add armor (%value%)';
                break;
            case 12:
                $text = 'have attack and win %value% healt from';
                break;
            case 13:
                $text = 'have attack and share with friend and won each %value% healt';
                break;
            case 14:
                $text = 'is attacked and lost %value% healt for';
                break;
            case 15:
                $text = 'is attacked and lost %value% armor';
                break;
            case 16:
                $text = 'is dead';
                break;
            case 17:
                $text = 'have reset all his thefts';
                break;
            case 18:
                $text = 'have reset all his friends';
                break;
            case 19:
                $text = 'try to theft';
                break;
            case 20:
                $text = 'have theft %value% potion from';
                break;
            case 21:
                $text = 'is wackup and won %value% healt';
                break;
            case 22:
                $text = 'try but can not theft ';
                break;
            case 23:
                $text = 'try but can not give potion to';
                break;
            default:
                $text = 'make a step';
                break;
        }


        if ($value) {

            $text = str_replace('%value%', $value, $text);
        }

        Message::create([
            'room_id' => 1,
            'from_id' => $from->id,
            'to_id' => (isset($to) ? $to->id : null),
            'message' => $text
        ]);
    }
}
