<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Model;

use Magento\Store\Model\ScopeInterface;

class Config
{
    public const XML_PATH_DV_CAMPUS_PERSONAL_DISCOUNT_GENERAL_ENABLED
        = 'dv_campus_personal_discount/general/enabled';

    public const XML_PATH_DV_CAMPUS_PERSONAL_DISCOUNT_GENERAL_ALLOW_FOR_GUESTS
        = 'dv_campus_personal_discount/general/allow_for_guests';

    public const XML_PATH_DV_CAMPUS_PERSONAL_DISCOUNT_GENERAL_SALES_EMAIL_IDENTITY
        = 'dv_campus_personal_discount/general/sender_email_identity';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get whether the module is enabled or not
     *
     * @return bool
     */
    public function enabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_DV_CAMPUS_PERSONAL_DISCOUNT_GENERAL_ENABLED,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get if guest customers can submit requests
     *
     * @return bool
     */
    public function allowForGuests(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_DV_CAMPUS_PERSONAL_DISCOUNT_GENERAL_ALLOW_FOR_GUESTS,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
