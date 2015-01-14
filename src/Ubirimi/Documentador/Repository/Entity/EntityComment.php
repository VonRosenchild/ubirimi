<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Documentador\Repository\Entity;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;

class EntityComment {

    public function getById($commentId) {
        $query = "SELECT * " .
            "FROM documentator_entity_comment " .
            "where documentator_entity_comment.id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $commentId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public function updateCommentsParrent($oldParentId, $newParentId) {
        $query = "update documentator_entity_comment set parent_comment_id = ? where parent_comment_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("ii", $newParentId, $oldParentId);
            $stmt->execute();
        }
    }

    public function deleteById($commentId) {
        $comment = EntityComment::getById($commentId);
        $parentId = $comment['parent_comment_id'];

        EntityComment::updateCommentsParrent($commentId, $parentId);

        $query = "delete from documentator_entity_comment where id = ? limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $commentId);
            $stmt->execute();
        }
    }

    public function addComment($pageId, $userId, $content, $date, $parentCommentId = null) {
        $query = "INSERT INTO documentator_entity_comment(documentator_entity_id, user_id, parent_comment_id, content, date_created) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiiss", $pageId, $userId, $parentCommentId, $content, $date);
            $stmt->execute();

            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public function getComments($pageId, $resultType = null) {
        $query = "SELECT documentator_entity_comment.id, documentator_entity_comment.user_id, documentator_entity_comment.content, documentator_entity_comment.documentator_entity_id, " .
            "documentator_entity_comment.date_created, general_user.first_name, general_user.last_name, " .
            "documentator_entity_comment.parent_comment_id, 0 as printed " .
            "FROM documentator_entity_comment " .
            "left join general_user on general_user.id = documentator_entity_comment.user_id " .
            "where documentator_entity_comment.documentator_entity_id = ? " .
            "order by documentator_entity_comment.date_created asc";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $pageId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                if ($resultType == 'array') {
                    $resultArray = array();
                    while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                        $resultArray[] = $data;
                    }
                    return $resultArray;
                }
            } else
                return null;
        }
    }

    public function getCommentsLayoutHTML($comments, &$htmlLayout, $commentParentId, $identationIndex) {

        for ($i = 0; $i < count($comments); $i++) {

            if ($comments[$i]['parent_comment_id'] == $commentParentId && $comments[$i]['printed'] == 0) {
                if ($comments[$i]['parent_comment_id'] == null)
                    $identationIndex = 0;

                $htmlLayout .= '<table class="table table-hover table-condensed">';
                    $htmlLayout .= '<tr>';
                        for ($j = 0; $j < $identationIndex; $j++) {
                            $htmlLayout .= '<td width="30"></td>';
                        }

                        $htmlLayout .= '<td width="25px" style="vertical-align: top">';
                            $htmlLayout .= '<img src="' . UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(UbirimiContainer::get()['session']->get('user'), 'small') . '" style="vertical-align: top" />';
                        $htmlLayout .= '</td>';
                        $htmlLayout .= '<td>';
                            $htmlLayout .= LinkHelper::getUserProfileLink($comments[$i]['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $comments[$i]['first_name'], $comments[$i]['last_name']);
                            $htmlLayout .= '<div>' . str_replace("\n", "<br />", $comments[$i]['content']) . '</div>';

                            $htmlLayout .= '<div style="margin-top: 4px">';
                                $htmlLayout .= '<a href="#" id="entity_reply_comment_' . $comments[$i]['id'] . '">Reply</a>';
                                $htmlLayout .= '<span> | </span>';
                                $htmlLayout .= '<a href="#" id="entity_delete_comment_' . $comments[$i]['id'] . '">Delete</a>';
                            $htmlLayout .= '</div>';
                            $htmlLayout .= '<div id="innerCommentSection_' . $comments[$i]['id'] . '" style="display: none;">';
                                $htmlLayout .= '<textarea class="inputTextAreaLarge" id="inner_doc_view_page_add_comment_content_' . $comments[$i]['id'] . '" style="width: 100%"></textarea>';
                                $htmlLayout .= '<div style="height: 2px"></div>';
                                    $htmlLayout .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
                                        $htmlLayout .= '<tr>';
                                            $htmlLayout .= '<td>';
                                                $htmlLayout .= '<div>';
                                                    $htmlLayout .= '<input type="button" name="add_comment" id="inner_btn_doc_view_page_add_comment_' . $comments[$i]['id'] . '" value="Add Comment" class="btn ubirimi-btn"/>';
                                                $htmlLayout .= '</div>';
                                            $htmlLayout .= '</td>';
                                        $htmlLayout .= '</tr>';
                                    $htmlLayout .= '</table>';
                            $htmlLayout .= '</div>';
                        $htmlLayout .= '</td>';
                    $htmlLayout .= '</tr>';
                $htmlLayout .= '</table>';
                $comments[$i]['printed'] = 1;

                $identationIndex++;
                EntityComment::getCommentsLayoutHTML($comments, $htmlLayout, $comments[$i]['id'], $identationIndex);
                $identationIndex--;
            }
        }

        return $htmlLayout;
    }

    public function deleteCommentsByEntityId($pageId) {
        $query = "delete from documentator_entity_comment where documentator_entity_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $pageId);
            $stmt->execute();
        }
    }
}