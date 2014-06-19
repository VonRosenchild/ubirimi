<?php

namespace Ubirimi\Repository\Email;
use Swift_Message;
use Ubirimi\Container\UbirimiContainer;

class EmailQueue {

    public static function send($smtpSettings, $emailData) {
        $mailer = Email::getMailer($smtpSettings);

        $message = Swift_Message::newInstance($emailData['subject'])
            ->setFrom(array($emailData['from_address']))
            ->setTo(array($emailData['to_address']))
            ->setBody($emailData['content'], 'text/html');

        @$mailer->send($message);
    }

    public static function add($clientId, $fromAddress, $toAddress, $replyToAddress, $subject, $content, $date) {
        $query = "INSERT INTO general_mail_queue(client_id, from_address, to_address, reply_to_address, subject, content, date_created)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("issssss", $clientId, $fromAddress, $toAddress, $replyToAddress, $subject, $content, $date);
            $stmt->execute();
        }
    }

    public static function getBatch() {
        $query = 'SELECT * ' .
            'FROM general_mail_queue ' .
            "order by id asc " .
            "limit 20";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result;
            } else {
                return null;
            }
        }
    }

    public static function deleteById($emailId) {
        $query = "delete from general_mail_queue where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $emailId);
            $stmt->execute();
        }
    }

    public static function getByClientId($clientId) {
        $query = 'SELECT * ' .
            'FROM general_mail_queue ' .
            "where client_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result;
            } else {
                return null;
            }
        }
    }

    public static function getAll() {
        $query = 'SELECT general_mail_queue.from_address, general_mail_queue.to_address, general_mail_queue.subject, general_mail_queue.content, general_mail_queue.date_created, ' .
                 'client.company_domain ' .
            'FROM general_mail_queue ' .
            'left join client on client.id = general_mail_queue.client_id';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                return $result;
            } else {
                return null;
            }
        }
    }
}