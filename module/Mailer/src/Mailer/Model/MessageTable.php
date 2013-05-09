<?php
namespace Mailer\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

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
        //$resultSet = $this->tableGateway->select($select);
        $resultSet = $this->tableGateway->select(function (Select $select) {
             //$select->where->like('last_name', $letter.'%');
             $select->order('sent DESC');
        });
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
            'message_subject' => $message->message_subject,
            'message_content' => $message->message_content,
            'member_info' => $message->member_info,
            'tax_receipt' => $message->tax_receipt,
            'tax_year' => $message->tax_year,
             /* can be null */
            'send_to' => $message->send_to,
            'location' => $message->location,
        );

        $id = (int)$message->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        } else {
            if ($this->getMessage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            } else {
                throw new \Exception('Message id does not exist');
            }
        }
    }

    public function deleteMessage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}