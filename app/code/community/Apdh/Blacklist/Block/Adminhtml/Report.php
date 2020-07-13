<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Block_Adminhtml_Report extends Mage_Core_Block_Template
{
    public static $file = "data.json";

    public function getStatistics($date)
    {
        $helper = Mage::helper('blacklist/report');
        $path = $helper->createDirectory();
        $file = $path . DS . self::$file;
        $data = $helper->readFile($file);

        $statistics['years'] = $helper->getYears($data);
        $statistics['selected'] = $date;

        foreach ($statistics['years'] as $value) {
            $statistics['attacks'][$value] = $helper->getAttacks($data, $value);
            $statistics['percent'][$value] = $helper->getPercent($data, $value);
        }

        return $statistics;
    }

    public function getAllStatistics()
    {
        $date = Mage::getSingleton('core/date')->gmtDate('Y');

        $statistics = $this->getStatistics($date);

        return $statistics;
    }
}
