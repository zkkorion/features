<?
/**
 * $var принтуемый массив если приходит массив из двух переменных (arResult и arParams (именно в таком порядке)) выводит оба с небольшим оформлением
 * $mode по умолчанию тру = испольщуется print_r. false = используется var_dump
 * $show режим показа на странице по умолчанию true = показывать. false = скроет результат работы со страницы но оставинт просмотр в инструментах разработчика во вкладке Элементы (Elements)
 */
function ShowRes($var, $mode = true, $show = true){
    echo $retVal = ($show) ? '<pre>' : '<pre style="display:none;">';
    if ($mode) 
    {
        if (count($var) == 2)
        {
           echo '***********  ARRESULT **************';?><br><?
           echo (print_r($var[0]));
           echo '************ ARPARAMS ***************';?><br><?
           echo (print_r($var[1]));
        }else 
        {
            echo (print_r($var));
        }
    }else 
    {
        if (count($var) == 2)
        {
           echo '***********  ARRESULT **************';?><br><?
           echo (var_dump($var[0]));
           echo '************ ARPARAMS ***************';?><br><?
           echo (var_dump($var[1]));
        }else 
        {
            echo (var_dump($var));
        }
    }
    echo '</pre>';
}
?>