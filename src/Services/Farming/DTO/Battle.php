<?php

namespace Nassau\CartoonBattle\Services\Farming\DTO;

final class Battle
{
    private $success;
    private $message;
    private $id;

    public function __construct($data)
    {
        $this->success = isset($data['result']) && true === (bool)$data['result'];
        $this->message = isset($data['result_message']) ? $data['result_message'] : '';
        $this->id = isset($data['battle_data']['battle_id']) ? $data['battle_data']['battle_id'] : null;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return is_array($this->message) ? implode(" ", $this->message) : $this->message;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }


}