<?php

namespace PuraUserModel\Entity;

class PuraUserEntity {
    private $id;
    private $edu_id;
    private $user_id;
    private $barcode;
    private $library_code;
    private $remarks;
    private $library_system_number;
    private $has_access;
    private $access_created;
    private $date_expiration;
    private $blocked;
    private $blocked_created;
    private $last_account_extension_request;
    private $created;
    private $username;
    private $firstname;
    private $lastname;
    private $email;
    private $language;

    /**
     * IsMemberEducationInstitution
     *
     * @var boolean is_member_education_institution true if the user is a member of an education
     *                                              institution (needed by nanoo.tv)
     */
    private $is_member_education_institution;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEduId()
    {
        return $this->edu_id;
    }

    /**
     * @param mixed $edu_id
     */
    public function setEduId($edu_id): void
    {
        $this->edu_id = $edu_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param mixed $barcode
     */
    public function setBarcode($barcode): void
    {
        $this->barcode = $barcode;
    }

    /**
     * @return mixed
     */
    public function getLibraryCode()
    {
        return $this->library_code;
    }

    /**
     * @param mixed $library_code
     */
    public function setLibraryCode($library_code): void
    {
        $this->library_code = $library_code;
    }

    /**
     * @return mixed
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @param mixed $remarks
     */
    public function setRemarks($remarks): void
    {
        $this->remarks = $remarks;
    }

    /**
     * @return mixed
     */
    public function getLibrarySystemNumber()
    {
        return $this->library_system_number;
    }

    /**
     * @param mixed $library_system_number
     */
    public function setLibrarySystemNumber($library_system_number): void
    {
        $this->library_system_number = $library_system_number;
    }

    /**
     * @return mixed
     */
    public function getHasAccess()
    {
        return $this->has_access;
    }

    /**
     * @param mixed $has_access
     */
    public function setHasAccess($has_access): void
    {
        $this->has_access = $has_access;
    }

    /**
     * @return mixed
     */
    public function getAccessCreated()
    {
        return $this->access_created;
    }

    /**
     * @param mixed $access_created
     */
    public function setAccessCreated($access_created): void
    {
        $this->access_created = $access_created;
    }

    /**
     * @return mixed
     */
    public function getDateExpiration()
    {
        return $this->date_expiration;
    }

    /**
     * @param mixed $date_expiration
     */
    public function setDateExpiration($date_expiration): void
    {
        $this->date_expiration = $date_expiration;
    }

    /**
     * @return mixed
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * @param mixed $blocked
     */
    public function setBlocked($blocked): void
    {
        $this->blocked = $blocked;
    }

    /**
     * @return mixed
     */
    public function getBlockedCreated()
    {
        return $this->blocked_created;
    }

    /**
     * @param mixed $blocked_created
     */
    public function setBlockedCreated($blocked_created): void
    {
        $this->blocked_created = $blocked_created;
    }

    /**
     * @return mixed
     */
    public function getLastAccountExtensionRequest()
    {
        return $this->last_account_extension_request;
    }

    /**
     * @param mixed $last_account_extension_request
     */
    public function setLastAccountExtensionRequest($last_account_extension_request): void
    {
        $this->last_account_extension_request = $last_account_extension_request;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language): void
    {
        $this->language = $language;
    }

    /**
     * @return bool
     */
    public function getIsMemberEducationInstitution(): bool
    {
        return $this->is_member_education_institution;
    }

    /**
     * @param bool $is_member_education_institution
     *
     * @return bool
     */
    public function setIsMemberEducationInstitution($is_member_education_institution)
    {
        $this->is_member_education_institution = $is_member_education_institution;
    }
}
