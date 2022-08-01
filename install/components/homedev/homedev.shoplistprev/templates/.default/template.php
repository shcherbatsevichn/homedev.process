<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Homedev\Process\ShopListController;

\Bitrix\Main\UI\Extension::load("ui.buttons"); 
\Bitrix\Main\UI\Extension::load("ui.dialogs.messagebox");

?>
<div class="homedev-container">
    <h1>Работники магазинов</h1>


<?php 








$grid_options = new Bitrix\Main\Grid\Options('shop_list');
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$nav_params = $grid_options->GetNavParams();

$nav = new Bitrix\Main\UI\PageNavigation('shop_list');
$nav->allowAllRecords(true)
    ->setPageSize($nav_params['nPageSize'])
    ->initFromUri();

$APPLICATION->IncludeComponent(
    'bitrix:main.ui.filter',
    '',
    [ 
        'FILTER_ID' => 'shop_list', 
        'GRID_ID' => 'shop_list', 
        'FILTER' => [ 
            ['id' => 'ID', 'name' => '№', 'sort' => 'ID'],
            ['id' => 'SHOPS_NAME', 'name' => 'Название магазина'],
            ['id' => 'SHOPS_USER_ID', 'name' => 'Продавец'], 
        ], 
        'ENABLE_LIVE_SEARCH' => true, 
        'ENABLE_LABEL' => true
    ]
);

$filterOption = new Bitrix\Main\UI\Filter\Options('shop_list');
$filterData = $filterOption->getFilter([]);
$filter = [];
foreach ($filterData as $k => $v) {
    if(isset($filterData['SHOPS_NAME'])){
        $filter['SHOPS_NAME'] = "%".$filterData['SHOPS_NAME']."%";
    }
    if(isset($filterData['SHOPS_USER_ID'])){
        $filter['SHOPS_USER_ID'] = "%".$filterData['SHOPS_USER_ID']."%";
    }  
}

if (CModule::IncludeModule("homedev.process"))
{
    $list = ShopListController::getShopUserList($filter);
}

foreach($list as $row){
    if($row['SHOPS_USER_ID'] != '' && $row['SHOPS_USER_ID'] != '-'){
        $userId = $row['SHOPS_USER_ID'];
        $user = CUser::GetByID(
            $userId
        );
        $result = $user->Fetch();
        $row['SHOPS_USER_ID'] = "<a class='feed-workday-user-name' href='/company/personal/user/".$userId."/' bx-tooltip-user-id='".$userId."'>".$result['NAME']." ".$result['LAST_NAME']."</a>";
    }
    $newList[] = $row;
}

$arResult['ROWS'] = $newList;



$allrows = $arResult['ROWS'];
$list = array();
foreach($allrows as $row){
    $list[] = array(
        'data' =>$row,
    );
}



$APPLICATION->IncludeComponent(
    'bitrix:main.ui.grid',
    '',
    [
    'GRID_ID' => 'shop_list', 
    'COLUMNS' => [
        ['id' => 'ID', 'name' => '№', 'sort' => 'ID', 'default' => true], 
        ['id' => 'SHOPS_NAME', 'name' => 'Название магазина', 'sort' => 'SHOPS_NAME', 'default' => true], 
        ['id' => 'SHOPS_USER_ID', 'name' => 'Текущий Работник', 'sort' => 'SHOPS_USER_ID', 'default' => true], 
    ], 
    'ROWS' => $list,
    'SHOW_ROW_CHECKBOXES' => false, 
    'NAV_OBJECT' => $nav, 
    'AJAX_MODE' => 'Y', 
    'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''), 
    'PAGE_SIZES' => [ 
        ['NAME' => "5", 'VALUE' => '5'], 
        ['NAME' => '10', 'VALUE' => '10'], 
        ['NAME' => '20', 'VALUE' => '20'], 
        ['NAME' => '50', 'VALUE' => '50'], 
        ['NAME' => '100', 'VALUE' => '100'] 
    ], 
    'AJAX_OPTION_JUMP'          => 'N', 
    'SHOW_CHECK_ALL_CHECKBOXES' => true, 
    'SHOW_ROW_ACTIONS_MENU'     => true, 
    'SHOW_GRID_SETTINGS_MENU'   => true, 
    'SHOW_NAVIGATION_PANEL'     => true, 
    'SHOW_PAGINATION'           => true, 
    'SHOW_SELECTED_COUNTER'     => true, 
    'SHOW_TOTAL_COUNTER'        => true, 
    'SHOW_PAGESIZE'             => true, 
    'SHOW_ACTION_PANEL'         => true, 
    'ACTION_PANEL'              => [ 
        'GROUPS' => [ 
            'TYPE' => [ 
                'ITEMS' => [ 
                    [ 
                        'ID'    => 'set-type', 
                        'TYPE'  => 'DROPDOWN', 
                        'ITEMS' => [ 
                            ['VALUE' => '', 'NAME' => '- Выбрать -'], 
                            ['VALUE' => 'plus', 'NAME' => 'Поступление'], 
                            ['VALUE' => 'minus', 'NAME' => 'Списание'] 
                        ] 
                    ], 
                ], 
            ] 
        ], 
    ], 
    'ALLOW_COLUMNS_SORT'        => true, 
    'ALLOW_COLUMNS_RESIZE'      => true, 
    'ALLOW_HORIZONTAL_SCROLL'   => true, 
    'ALLOW_SORT'                => true, 
    'ALLOW_PIN_HEADER'          => true, 
    'AJAX_OPTION_HISTORY'       => 'N' 
    ]
);?>

<div class="homedev-row" style="margin-left:15px">
    <a class="ui-btn ui-btn-secondary homedev-btn" href="/process/index.php">назад</a>
</div>
</div>