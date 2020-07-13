<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Helper_Report extends Mage_Core_Helper_Abstract
{
    public static $folder = "apdh";
    public static $file = "data.json";

    public function saveReport($email)
    {
        $date = Mage::getSingleton('core/date');
        $array = array(
            "y" => $date->gmtDate('Y'),
            "m" => $date->gmtDate('F'),
            "d" => end(explode("@", $email)),
        );
        $this->save($array);
    }

    public function save($data)
    {
        $path = $this->createDirectory();
        $file = $path . DS . self::$file;
        $db = $this->readFile($file);

        if (!isset($db[$data['y']]['attacks'][$data['d']])) {
            $db[$data['y']]['attacks'][$data['d']] = $this->getStructure();
        }

        $db[$data['y']]['attacks'][$data['d']][$data['m']] += 1;
        $db[$data['y']]['percent'][$data['d']] += 1;
        $db[$data['y']]['percent']['total'] += 1;

        $db = json_encode($db);

        try{
            $io = New Varien_Io_File();
            $io->write($file, $db);
            $io->close();
        } catch (Exception $e){
            Mage::log("Exception:: ".$e->getMessage(), Zend_Log::ALERT, 'apdh_blacklist.log', true);
        }
    }

    public function createDirectory()
    {
        $directory = Mage::getBaseDir('var') . DS . self::$folder;

        try {
            $io = new Varien_Io_File();
            $io->checkAndCreateFolder($directory);
            $io->close();
        } catch (Exception $e) {
            Mage::log("Exception:: ".$e->getMessage(), Zend_Log::ALERT, 'apdh_blacklist.log', true);
        }

        return $directory;
    }

    public function readFile($file, $json = false)
    {
        $io = new Varien_Io_File();
        $path = $io->dirname($file);
        $io->open(array('path' => $path));
        $currentData = $io->read($file);
        $io->close();

        if ($json == false) {
            $array = json_decode($currentData, true);
            ksort($array);

            return $array;
        }

        return $currentData;
    }

    public function getStructure()
    {
        $structure = array(
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0
        );

        return $structure;
    }

    public function getAttacks($data, $date)
    {
        $array = $data[$date]['attacks'];
        $attacks = array();

        if (!empty($array)) {
            $labels = array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'October',
                'November',
                'December'
            );
            $datasets = array();

            foreach ($array as $domain => $values) {
                $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $args = array(
                    "label" => $domain,
                    "backgroundColor" => "$color",
                    "borderColor" => "$color",
                    "data" => "",
                    "fill" => false,
                );

                foreach ($values as $month => $qty) {
                    $args['data'][] = $qty;
                }

                $datasets[] = $args;
            }

            $attacks["labels"] = $labels;
            $attacks["datasets"] = $datasets;
        }

        return $attacks;
    }

    public function getPercent($data, $date)
    {
        $array = $data[$date]['percent'];
        $total = $array['total'];
        unset($array['total']);

        $args = array();

        if (!empty($array)) {
            $args['datasets']['label'] = "Percent";

            foreach ($array as $domain => $qty) {
                $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $args['datasets']['data'][] = round(($qty * 100) / $total);
                $args['datasets']['backgroundColor'][] = $color;
                $args['labels'][] = $domain;
            }
        }

        return $args;
    }

    public function getYears($data)
    {
        return array_keys($data);
    }
}