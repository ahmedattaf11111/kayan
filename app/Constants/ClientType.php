<?php

namespace App\Constants;

class ClientType
{
    const PHARMACIST = "PHARMACIST";
    const COSMETICS_STORE = "COSMETICS_STORE";
    const DRUG_STORE = "DRUG_STORE";
    const ALL = self::PHARMACIST . "," . self::COSMETICS_STORE . "," . self::DRUG_STORE;
}
