<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum OrganizationBusiness: string
{
    case ADVERTISING = 'advertising';
    case BUSINESS_CONSULTANTS = 'business_consultants';
    case CYBERSECURITY_AUDIT = 'cybersecurity_audit';
    case E_COMMERCE = 'e_commerce';
    case EVENT_MANAGEMENT = 'event_management';
    case EDTECH_EDUCATION = 'edtech_education';
    case FINTECH_BANK_BNPL = 'fintech_bank_bnpl';
    case FMCG_RETAIL = 'fmcg_retail';
    case LOGISTICS_E_TRANSPORT = 'logistics_e_transport';
    case HR = 'hr';
    case HEALTHTECH_HEALTHCARE = 'healthtech_healthcare';
    case LAW_FIRM_TAX_FIRM = 'law_firm_tax_firm';
    case INSURTECH_INSURANCE = 'insurtech_insurance';
    case ISPS_NETWORKS_TELCO = 'isps_networks_telco';
    case MANUFACTURERS_EXPORTERS = 'manufacturers_exporters';
    case MOTORS_PARTS = 'motors_parts';
    case MEDIA_PUBLICATION_TV_CHANNEL = 'media_publication_tv_channel';
    case NGO_NPO = 'ngo_npo';
    case PHARMA = 'pharma';
    case PROPERTYTECH_REAL_ESTATE_DEVELOPERS = 'propertytech_real_estate_developers';
    case SOFTWARE_HOUSE_DIGITAL_AGENCY = 'software_house_digital_agency';
    case TECHNOLOGY_CLOUD = 'technology_cloud';
    case UTILITY_APP_BUSINESS = 'utility_app_business';

    public function label(): string
    {
        return match ($this) {
            self::ADVERTISING => 'Advertising / Marketing',
            self::BUSINESS_CONSULTANTS => 'Business Consultants',
            self::CYBERSECURITY_AUDIT => 'CyberSecurity / Audit',
            self::E_COMMERCE => 'E-Commerce',
            self::EVENT_MANAGEMENT => 'Event Management',
            self::EDTECH_EDUCATION => 'EdTech / Education',
            self::FINTECH_BANK_BNPL => 'Fintech / Bank / BNPL',
            self::FMCG_RETAIL => 'FMCG / Retail',
            self::LOGISTICS_E_TRANSPORT => 'Logistics / e-Transport',
            self::HR => 'HR',
            self::HEALTHTECH_HEALTHCARE => 'HealthTech / HealthCare',
            self::LAW_FIRM_TAX_FIRM => 'Law Firm / Tax Firm',
            self::INSURTECH_INSURANCE => 'InsurTech / Insurance',
            self::ISPS_NETWORKS_TELCO => 'ISPs / Networks / Telco',
            self::MANUFACTURERS_EXPORTERS => 'Manufacturers / Exporters',
            self::MOTORS_PARTS => 'Motors / Parts',
            self::MEDIA_PUBLICATION_TV_CHANNEL => 'Media Publication / TV Channel',
            self::NGO_NPO => 'NGO / NPO',
            self::PHARMA => 'Pharma',
            self::PROPERTYTECH_REAL_ESTATE_DEVELOPERS => 'PropertyTech / Real Estate / Developers',
            self::SOFTWARE_HOUSE_DIGITAL_AGENCY => 'Software House / Digital Agency',
            self::TECHNOLOGY_CLOUD => 'Technology / Cloud',
            self::UTILITY_APP_BUSINESS => 'Utility App / Business',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->label()] = $case->label();
        }
        return $options;
    }
}