<?php
namespace App\Services;

class StrategyReportDataService
{
    public static function getStrategyReportData():array
    {
        return [
          'cover' => [
              'name' => '',
              'date' => ''
          ],
          'about_us' => [
              'group_assets' => '',
              'group_employees' => '',
              'corporate_clients' => '',
              'group_offices' => '',
              'office_map' => ''
          ],
          'meet_the_team' => [
              'adviser_picture' => '',
              'adviser_name' => '',
              'adviser_title' => '',
              'adviser_bio' => ''
          ]
        ];
    }
}
