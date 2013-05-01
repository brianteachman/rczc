<?php
namespace Mailer\Model;

use Zend\Db\TableGateway\TableGateway;

class MessageTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        // returns Zend\Db\ResultSet\ResultSet
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getMessage($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveMessage(Message $message)
    {
        $data = array(
            'id' => $message->id,
            'message_subject' => $message->message_subject,
            'message_content' => $message->message_content,
            'member_info' => $message->member_info,
            'tax_receipt' => $message->tax_receipt,
            'tax_year' => $message->tax_year,
            'membership_type' => $message->membership_type,
            'send_to' => $message->send_to,
            'location' => $message->location,
        );

        $id = (int)$message->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMessage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteMessage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}