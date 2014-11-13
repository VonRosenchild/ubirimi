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

namespace Ubirimi;

class LinkHelper {

    public static function getYongoIssueListPageLink($linkText = null, $parameters, $justLinkFlag = null, $class = null) {

        // prepare the data
        foreach ($parameters as $key => $value) {
            if (is_array($value))
                $parameters[$key] = implode('|', $value);
            else if ($value == null) {
                unset($parameters[$key]);
            }
        }

        if (!$class)
            $class = 'linkNoUnderline';
        $link = '<a class="' . $class . '" href="';

        $link2 = $parameters['link_to_page'] . '?page=' . $parameters['page'];
        if (isset($parameters['sort'])) $link2 .= '&sort=' . $parameters['sort'];
        if (isset($parameters['sort_order'])) $link2 .= '&order=' . $parameters['sort_order'];
        if (isset($parameters['filter'])) $link2 .= '&filter=' . $parameters['filter'];
        if (isset($parameters['filter_type'])) $link2 .= '&filter_type=' . $parameters['filter_type'];
        if (isset($parameters['project'])) $link2 .= '&project=' . $parameters['project'];
        if (isset($parameters['component'])) $link2 .= '&component=' . $parameters['component'];
        if (isset($parameters['fix_version'])) $link2 .= '&fix_version=' . $parameters['fix_version'];
        if (isset($parameters['type'])) $link2 .= '&type=' . $parameters['type'];
        if (isset($parameters['priority'])) $link2 .= '&priority=' . $parameters['priority'];
        if (isset($parameters['assignee'])) $link2 .= '&assignee=' . $parameters['assignee'];
        if (isset($parameters['reporter'])) $link2 .= '&reporter=' . $parameters['reporter'];
        if (isset($parameters['status'])) $link2 .= '&status=' . $parameters['status'];
        if (isset($parameters['resolution'])) $link2 .= '&resolution=' . $parameters['resolution'];
        if (isset($parameters['filter'])) $link2 .= '&filter=' . $parameters['filter'];
        if (isset($parameters['date_created_after'])) $link2 .= '&date_created_after=' . $parameters['date_created_after'];
        if (isset($parameters['date_created_before'])) $link2 .= '&date_created_before=' . $parameters['date_created_before'];
        if (isset($parameters['date_due_after'])) $link2 .= '&date_due_after=' . $parameters['date_due_after'];
        if (isset($parameters['date_due_before'])) $link2 .= '&date_due_before=' . $parameters['date_due_before'];

        $link3 = '">' . $linkText . '</a>';
        if (!$justLinkFlag)
            return $link . $link2 . $link3;
        else
            return $link2;
    }


    public static function getUserProfileLink($userId, $productId, $firstName, $lastName, $CSSClass = null) {

        $prefix = '';
        switch ($productId) {
            case SystemProduct::SYS_PRODUCT_YONGO:
                $prefix = '/yongo/user/profile/';
                break;
            case SystemProduct::SYS_PRODUCT_DOCUMENTADOR:
                $prefix = '/documentador/user/profile/';
                break;
            case SystemProduct::SYS_PRODUCT_HELP_DESK:
                $prefix = '/helpdesk/customer-portal/profile/';
                break;
        }
        $cssClassHTML = '';
        if ($CSSClass) {
            $cssClassHTML = 'class="' . $CSSClass . '"';
        }
        $link = '<a ' . $cssClassHTML . ' href="' . $prefix . $userId . '">' . $firstName . ' ' . $lastName . '</a>';

        return $link;
    }

    public static function getYongoProjectLink($projectId, $text) {
        $link = '<a style="text-decoration: none" href="/yongo/project/issues/' . $projectId . '">' . $text . '</a>';

        return $link;
    }

    public static function getYongoProjectComponentLink($projectComponentId, $link_text) {
        $link = '<a href="/yongo/project/component/' . $projectComponentId . '">' . $link_text . '</a>';

        return $link;
    }

    public static function getYongoProjectVersionLink($project_version_id, $textLink) {
        $link = '<a href="/yongo/project/version/' . $project_version_id . '">' . $textLink . '</a>';

        return $link;
    }

    public static function getYongoIssueViewLink($issueId, $issueNr, $projectCode, $justLinkFlag = null) {
        if ($justLinkFlag) {
            return '/yongo/issue/' . $issueId;
        } else {
            $link = '<a href="/yongo/issue/' . $issueId . '">' . $projectCode . '-' . $issueNr . '</a>';
        }

        return $link;
    }

    public static function getYongoIssueViewLinkJustHref($issueId) {
        $link = '/yongo/issue/' . $issueId;

        return $link;
    }

    public static function getDocumentadorPageLink($pageId, $pageTitle, $class = null) {
        $linkClassHTML = '';
        if ($class)
            $linkClassHTML = 'class="' . $class . '"';
        $link = '<a ' . $linkClassHTML . ' href="/documentador/page/view/' . $pageId . '">' . $pageTitle . '</a>';

        return $link;
    }
}