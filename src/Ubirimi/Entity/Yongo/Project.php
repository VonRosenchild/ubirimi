<?php

namespace Ubirimi\Entity\Yongo;

class Project
{
    private $id;
    private $leadId;
    private $clientId;
    private $issueTypeSchemeId;
    private $issueTypeScreenSchemeId;
    private $issueTypeFieldConfigurationId;
    private $workflowSchemeId;
    private $permissionSchemeId;
    private $notificationSchemeId;
    private $issueSecuritySchemeId;
    private $projectCategoryId;
    private $name;
    private $code;
    private $description;
    private $helpDeskEnabledFlag;
    private $issueNumber;
    private $dateCreated;
    private $dateUpdated;

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $issueNumber
     */
    public function setIssueNumber($issueNumber)
    {
        $this->issueNumber = $issueNumber;
    }

    /**
     * @return mixed
     */
    public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    /**
     * @param mixed $issueSecuritySchemeId
     */
    public function setIssueSecuritySchemeId($issueSecuritySchemeId)
    {
        $this->issueSecuritySchemeId = $issueSecuritySchemeId;
    }

    /**
     * @return mixed
     */
    public function getIssueSecuritySchemeId()
    {
        return $this->issueSecuritySchemeId;
    }

    /**
     * @param mixed $issueTypeFieldConfigurationId
     */
    public function setIssueTypeFieldConfigurationId($issueTypeFieldConfigurationId)
    {
        $this->issueTypeFieldConfigurationId = $issueTypeFieldConfigurationId;
    }

    /**
     * @return mixed
     */
    public function getIssueTypeFieldConfigurationId()
    {
        return $this->issueTypeFieldConfigurationId;
    }

    /**
     * @param mixed $issueTypeSchemeId
     */
    public function setIssueTypeSchemeId($issueTypeSchemeId)
    {
        $this->issueTypeSchemeId = $issueTypeSchemeId;
    }

    /**
     * @return mixed
     */
    public function getIssueTypeSchemeId()
    {
        return $this->issueTypeSchemeId;
    }

    /**
     * @param mixed $issueTypeScreenSchemeId
     */
    public function setIssueTypeScreenSchemeId($issueTypeScreenSchemeId)
    {
        $this->issueTypeScreenSchemeId = $issueTypeScreenSchemeId;
    }

    /**
     * @return mixed
     */
    public function getIssueTypeScreenSchemeId()
    {
        return $this->issueTypeScreenSchemeId;
    }

    /**
     * @param mixed $leadId
     */
    public function setLeadId($leadId)
    {
        $this->leadId = $leadId;
    }

    /**
     * @return mixed
     */
    public function getLeadId()
    {
        return $this->leadId;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $notificationSchemeId
     */
    public function setNotificationSchemeId($notificationSchemeId)
    {
        $this->notificationSchemeId = $notificationSchemeId;
    }

    /**
     * @return mixed
     */
    public function getNotificationSchemeId()
    {
        return $this->notificationSchemeId;
    }

    /**
     * @param mixed $permissionSchemeId
     */
    public function setPermissionSchemeId($permissionSchemeId)
    {
        $this->permissionSchemeId = $permissionSchemeId;
    }

    /**
     * @return mixed
     */
    public function getPermissionSchemeId()
    {
        return $this->permissionSchemeId;
    }

    /**
     * @param mixed $projectCategoryId
     */
    public function setProjectCategoryId($projectCategoryId)
    {
        $this->projectCategoryId = $projectCategoryId;
    }

    /**
     * @return mixed
     */
    public function getProjectCategoryId()
    {
        return $this->projectCategoryId;
    }

    /**
     * @param mixed $helpDeskEnabledFlag
     */
    public function setHelpDeskDeskEnabledFlag($helpDeskEnabledFlag)
    {
        $this->helpDeskEnabledFlag = $helpDeskEnabledFlag;
    }

    /**
     * @return mixed
     */
    public function getHelpDeskEnabledFlag()
    {
        return $this->helpDeskEnabledFlag;
    }

    /**
     * @param mixed $workflowSchemeId
     */
    public function setWorkflowSchemeId($workflowSchemeId)
    {
        $this->workflowSchemeId = $workflowSchemeId;
    }

    /**
     * @return mixed
     */
    public function getWorkflowSchemeId()
    {
        return $this->workflowSchemeId;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}