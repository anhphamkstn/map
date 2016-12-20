<?php namespace App\Services;

use App\CustomGeocoding;
use App\Opportune;

class FactoryService
{

    public static function getUserService()
    {
        return new UserService();
    }

    public static function getLookupCodeService()
    {
        return new LookupCodeService();
    }

    public static function getTaskService()
    {
        return new TaskService();
    }

    public static function getLeadService()
    {
        return new LeadService();
    }

    public static function getUploadService()
    {
        return new UploadService();
    }

    public static function getOrganizationService()
    {
        return new OrganizationService();
    }
    
    public static function getRemarkService()
    {
        return new RemarkService();
    }

    public static function getContactService()
    {
        return new ContactService();
    }

    public static function getTrackingLocationService()
    {
        return new TrackingLocationService();
    }
    
    public static function getAttendanceService()
    {
        return new AttendanceService();
    }
    public static function getProductService()
    {
        return new ProductService();
    }
    public static function getProductImageService()
    {
        return new ProductImageService();
    }
    public static function getStockDetailService()
    {
        return new StockDetailService();
    }
    public static function getStockHeaderService()
    {
        return new StockHeaderService();
    }
    public static function getOpportuneService()
    {
        return new OpportuneService();
    }
    public static function getOrderService()
    {
        return new OrderService();
    }
    public static function getOrderProductService()
    {
        return new OrderProductService();
    }

    public static  function  getTeamSaleService()
    {
        return new SaleTeamService();
    }

    public  static  function  getTalent()
    {
        return new TalentService();
    }

    public static function getCustomGeocodingService()
    {
        return new CustomGeocodingService();
    }
    
    public static function getCutTrackingLocationService()
    {
        return new CutTrackingLocationService();
    }
    public static function getCustomerService()
    {
        return new CustomerService();
    }
}
