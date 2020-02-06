<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Classes.php";

class Message
{
    public $id, $from_name, $to_name, $date, $message, $mark_read, $answer_from, $sent_to_everyone, $attachment;


    public function __construct($id = 0)
    {
        $this->sent_to_everyone = 0;
        if ($id) {
            $sql = new SQLConnection();
            $temp = $sql->getArray("SELECT * FROM poker_messages WHERE id=$id");
            if (count($temp)) {
                $temp = $temp[0];
                $this->id = $temp['id'];
                $this->from_name = $temp['from_name'];
                $this->to_name = $temp['to_name'];
                $this->date = $temp['date'];
                $this->message = $temp['message'];
                $this->mark_read = $temp['mark_read'];
                $this->answer_from = $temp['answer_from'];
                $this->sent_to_everyone = $temp['sent_to_everyone'];
                $this->attachment = $temp['attachment'];
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public static function getOutbox($userName = '')
    {
        $sql = new SQLConnection();
        $temp = $sql->getArray("SELECT id FROM poker_messages WHERE from_name " . ($userName == '' ? ' IS NULL' : "='$userName'") . " AND sent_to_everyone=0 ORDER BY date DESC");
        $ret = array();
        foreach ($temp as $msg) {
            $ret[] = new Message($msg[0]);
        }
        return $ret;
    }

    public static function getInbox($userName = '', $unread = false)
    {
        $sql = new SQLConnection();
        $temp = $sql->getArray("SELECT id FROM poker_messages WHERE to_name " . ($userName == '' ? ' IS NULL' : "='$userName'") . ($unread ? ' AND mark_read=0' : '') . " ORDER BY date DESC");
        $ret = array();
        foreach ($temp as $msg) {
            $ret[] = new Message($msg[0]);
        }
        return $ret;
    }

    public function sendToEveryone()
    {
        $count = 0;
        $sql = new SQLConnection();
        $temp = $sql->getArray("SELECT name FROM poker_users WHERE name IS NOT NULL AND name <> ''");
        foreach ($temp as $key => $value) {
            $msg = new Message();
            $msg->to_name = $value['name'];
            $msg->message = $this->message;
            $msg->sent_to_everyone = 1;
            $msg->attachment = $this->attachment;
            $msg->submitChanges();
            $count++;
        }
        return $count;
    }

    public function submitChanges()
    {
        $sql = new SQLConnection();
        if (!$this->id) {
            $sql->query("INSERT INTO poker_messages (from_name, to_name, message, answer_from, attachment, sent_to_everyone )
          VALUES ("
                . ($this->from_name ? "'{$this->from_name}'" : 'NULL') . ",
          " . ($this->to_name ? "'{$this->to_name}'" : 'NULL') . ",
          '" . $sql->escape($this->message) . "',
          " . ($this->answer_from ? $this->answer_from : 'NULL') . ",
          '" . $this->attachment . "',
          " . ($this->sent_to_everyone) . ")");
        } else {
            $sql->query("UPDATE poker_messages SET message='" . $sql->escape($this->message) . "', mark_read={$this->mark_read} WHERE id={$this->id}");
        }
        if (mysqli_error($sql->DBSource)) {
            Logger::addReport("Message::submitChanges", Logger::STATUS_ERROR, mysqli_error
            ($sql->DBSource));
            die('Cannot save message');
        }
    }
}