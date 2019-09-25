<?
namespace A1expert
{
    use Bitrix\Main\Context, Bitrix\Main\Type\DateTime, Bitrix\Main\Loader, Bitrix\Iblock;
        
    class Fixer
    {
        public $beacon;
        public function __construct()
        {
            $this->$beacon = true;
        }
        /**
         * Очищает массив от пустых значений и делает trim. Все происходит рекурсивно, без ограничения по вложенности.
         * @var array $array - массив для очистки, передается по ссылке
         */
        public function CleanArray(&$array)
        {
            
            foreach ($array as $k=>$v)
            {
                if(is_array($v))
                    $this->CleanArray($array[$k]);
                else
                    $array[$k] = trim($array[$k]);
                if(empty($v) || empty($array[$k]))
                    unset($array[$k]);
            }
        }
        public function GetRealPage()
        {
            return $_SERVER["REAL_FILE_PATH"];
        }
        /**
         * 
         */
        public function SetProps(&$array)
        {
            foreach ($array as $key => $value)
                $array[$key] = "PROPERTY_" . $value;
        }
        public function GetProps(&$arResult)
        {
            $arProps = $arResult["props"];
            foreach ($arProps as $k => $v)
            {
                switch ($v["PROPERTY_TYPE"])
                {
                    case "F":
                        if(is_array($v["VALUE"]))
                        {
                            foreach ($v["VALUE"] as $i=>$id) {
                                $arResult["PROPERTY_". $v["CODE"] . "_VALUE"][$i] = \CFile::GetPath($id);
                            }
                        }
                        else
                            $arResult["PROPERTY_". $v["CODE"] . "_VALUE"] = \CFile::GetFileArray($arResult["PROPERTY_". $v["CODE"] . "_VALUE"]);
                        break;
                    default:
                        break;
                }
            }
        }
        public function SetOrder($array1=array(), $array2=array())
        {
            if(!is_array($array1))
                $array1 = array();
            if(!is_array($array2))
                $array2 = array();
            $retunArr = array_merge($array1=array(), $array2=array());
            return $retunArr;
        }
        public function SetFilter($array1=array(), $array2=array())
        {
            if(!is_array($array1))
                $array1 = array();
            if(!is_array($array2))
                $array2 = array();
            $tmpArr = array("ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
            $retunArr = array_merge($tmpArr, $array1, $array2);
            return $retunArr;
        }
        public function SetSelect($array1=array(), $array2=array())
        {
            if(!is_array($array1))
                $array1 = array();
            if(!is_array($array2))
                $array2 = array();
            $tmpArr = array("ID", "IBLOCK_ID");
            $retunArr = array_merge($tmpArr, $array1, $array2);
            return $retunArr;
        }
        /**
         * Возвращает массив, где первый элемент это ID инфоблока, а второй коды свойств этого же инфоблока. При этом происходит проверка на заданность ID инфоблока, т.к. если в свойстве "привязка к элементу" в настройках не задать ИБ, то приходит нулевое значение в независимости от того что установлено в элементе, короче это костыль. 
         * @var $emergencyIblockId - это массив с элементами, которые возвращает компонент из свойства "привязка к элементам", там есть ID инфоблока.
         */
        public function GetTypeE($iblockId, $emergencyIblockId)
        {
            Loader::includeModule("iblock");
            $propsIblockId = $iblockId;
            if($propsIblockId == 0)
            {
                if(empty($emergencyIblockId))
                    throw new Exception("Не зада ID инфоблока для свойства \"Привязка к элементу\"");                
                $cur = current($emergencyIblockId);
                $propsIblockId = $cur["IBLOCK_ID"];
            }
            $properties = \CIBlockProperty::GetList(Array("sort"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$propsIblockId));
            while ($prop_fields = $properties->GetNext())
            {
                $props[] = "PROPERTY_" . $prop_fields["CODE"];
            }
            return array("propsIblockId"=>$propsIblockId, "props"=>$props);
        }
        public function GetFSize($fsize)
        {
            $unit = "КБ";
            if (strlen($fsize) > 6) 
            {
                $fsize = $fsize / 1024000;
                $unit = "МБ";
            }else {
                $fsize = $fsize / 1024;
            }
            $returnFsize = sprintf("%.2f", $fsize) . $unit;
            return $returnFsize;
        }
        public function GetPictures(&$arResult, $arSelect)
        {
            if(in_array("PREVIEW_PICTURE", $arSelect) || in_array("DETAIL_PICTURE", $arSelect))
            {
                if(!empty($arResult["PREVIEW_PICTURE"]))
                    $arResult["PREVIEW_PICTURE"] = \CFile::GetFileArray($arResult["PREVIEW_PICTURE"]);
                if(!empty($arResult["DETAIL_PICTURE"]))
                    $arResult["DETAIL_PICTURE"] = \CFile::GetFileArray($arResult["DETAIL_PICTURE"]);
            }
        }
        public function MakeGallery($imageIds, $bigSize=array("width"=>800, "height"=>600), $smallSize=array("width"=>112, "height"=>62))
        {
            foreach ($imageIds as $i=>$img)
            {
                $gallery[$i]["BIG_IMG"] = ImageHandler::Resize($img, $bigSize, true);
                $gallery[$i]["SMALL_IMG"] = ImageHandler::Resize($img, $smallSize);
            }
            return $gallery;
        }
        /**
         * @var array $arOrder
         * @var array $arFilter
         * @var bool $arGroupBy
         * @var bool $arNavStartParams
         * @var array $arSelect
         */
        public function GetElements($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelect = array(), $props = true)
        {
            Loader::includeModule("iblock");
            if(empty($arOrder))
                $arOrder = array("SORT"=>"ASC");
            
            $rsElement = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect);
            while($obElement = $rsElement->GetNextElement())
            {
                $arResult = $obElement->fields;
                if($props)
                    $arResult["props"] = $obElement->GetProperties();
                $arResult["arSelect"] = $arSelect;
                if($props)
                    $this->GetProps($arResult);
                $this->GetPictures($arResult, $arSelect);
                $returnResult[] = $arResult;
            }
            return $returnResult;
        }
        
        public function GetSections($arOrder = array(), $arFilter = array(), $bIncCnt = false, $arSelect = array(), $NavStartParams = false)
        {
            Loader::includeModule("iblock");
            if(empty($arOrder))
                $arOrder = array("SORT"=>"ASC");
            $rsSections = \CIBlockSection::GetList($arOrder, $arFilter, $bIncCnt, $arSelect, $NavStartParams);
            while ($arSect = $rsSections->GetNext())
            {
                $returnResult[] = $arSect;
            }
            return $returnResult;
        }

        public function GetMenu($iblockId, $withSections = true)
        {
            $arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "NAME");
            $arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => $iblockId);
            $elements = $this->GetElements(array(), $arFilter, false, false, $arSelect);
            $secs = $this->GetSections(array(), $arFilter, true, array("ID", "IBLOCK_ID", "SECTION_PAGE_URL", "NAME", "DEPTH_LEVEL"), false);

            foreach ($secs as $sec)
            {
                if($withSections)
                {
                    $isParent = ($sec["ELEMENT_CNT"] > 0) ? true : false;
                    $arResult[] = array(
                        $sec["NAME"],
                        $sec["SECTION_PAGE_URL"],
                        "",
                        array(
                            "FROM_IBLOCK" => true,
                            "IS_PARENT" => $isParent,
                            "DEPTH_LEVEL" => $sec["DEPTH_LEVEL"],
                        ),
                    );
                }
                foreach ($elements as $key=>$element)
                {
                    if ($element["IBLOCK_SECTION_ID"] == $sec["ID"])
                    {
                        $depthLevel = ($withSections) ? intval($sec["DEPTH_LEVEL"]) + 1 : 1;
                        $arResult[] = array(
                            $element["NAME"],
                            $element["DETAIL_PAGE_URL"],
                            "",
                            array(
                                "FROM_IBLOCK" => true,
                                "IS_PARENT" => false,
                                "DEPTH_LEVEL" => $depthLevel,
                            ),
                        );
                        unset($elements[$key]);
                    }
                }
            }
            foreach ($elements as $element)
            {
                if($element["IBLOCK_SECTION_ID"] == NULL)
                {
                    $arResult[] = array(
                        $element["NAME"],
                        $element["DETAIL_PAGE_URL"],
                        "",
                        array(
                            "FROM_IBLOCK" => true,
                            "IS_PARENT" => false,
                            "DEPTH_LEVEL" => 1,
                        ),
                    );
                }
            }
            return $arResult;
        }
    }
}