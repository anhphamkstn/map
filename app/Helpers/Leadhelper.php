<?php
namespace App\Helpers;

use App\Lead;

class LeadHelper
{
    public static function getContactFromLead(Lead $lead)
    {
        return [
            'contact_source' => '5',
            'status' => '5',
            'reference_source_by' => '20',
            'prefix_name' => $lead->name_prefix_name,
            'first_name' => $lead->name_first_name,
            'last_name' => $lead->name_last_name,
            'position' => $lead->position,
            'department' => $lead->department,
            'office_phone' => $lead->office_phone,
            'mobile_phone' => $lead->mobile_phone,
            'home_phone' => $lead->home_phone,
            'other_phone' => $lead->other_phone,
            'fax_number' => $lead->fax_number,
            'email' => $lead->email,
            'other_email' => $lead->other_email,

            'do_not_call' => $lead->do_not_call,
            'sms_opt_in' => $lead->sms_opt_in,
            'email_opt_out' => $lead->email_opt_out,
            'invoice_email' => $lead->invoice_email,

            'address' => $lead->addresses()->select()->get()
        ];
    }

    public static function getOrganizationFromLead(Lead $lead)
    {
        return [
            'org_source' => '5',
            'status' => '5',
            'reference_source_by' => '25',

            'name' => $lead->account_name,
            'office_phone' => $lead->office_phone,
            'fax_number' => $lead->fax_number,
            'email' => $lead->email,
            'other_email' => $lead->other_email,

            'do_not_call' => $lead->do_not_call,
            'email_opt_out' => $lead->email_opt_out,
            'invoice_email' => $lead->invoice_email,

            'billing_address' => $lead->addresses()->select()->get()[0],
            'shipping_address' => $lead->addresses()->select()->get()[0],
        ];
    }
}