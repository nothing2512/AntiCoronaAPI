<?php

namespace Corona\models;


use Corona\config\Constants;

class CaseModel implements Constants {

    public function getIndonesianCase() {

        $_data = file_get_contents(self::CASE_INDONESIA);
        $data = json_decode($_data)[0];

        return [
            "location" => $data->name,
            "cases" => $data->positif,
            "recovered" => $data->sembuh,
            "death" => $data->meninggal,
            "flag"  => self::INDONESIAN_FLAG
        ];
    }

    public function getProvinceCase() {

        $result = [];

        $_data = file_get_contents(self::CASE_PROVINCE);
        $data = json_decode($_data);

        foreach ($data as $value) {
            $attr = $value->attributes;
            array_push($result, [
                "location" => $attr->Provinsi,
                "cases"  => $attr->Kasus_Posi,
                "recovered" => $attr->Kasus_Semb,
                "death" => $attr->Kasus_Meni
            ]);
        }

        return $result;
    }

    public function getCountriesCase() {

        $result = [];

        $_data = file_get_contents(self::CASE_COUNTRIES);
        $data = json_decode($_data);

        foreach ($data as $item) {
            array_push($result, [
                "location" => $item->country,
                "cases"  => $item->cases,
                "recovered" => $item->recovered,
                "death" => $item->deaths,
                "flag"  => $item->countryInfo->flag
            ]);
        }

        return $result;
    }

    public function getGlobalCase() {

        $_data = file_get_contents(self::CASE_GLOBAL);
        $data = json_decode($_data);

        return [
            "cases" => $data->cases,
            "death" => $data->deaths,
            "recovered" => $data->recovered
        ];
    }
}