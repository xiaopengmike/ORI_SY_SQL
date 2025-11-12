<!DOCTYPE html>
<html>
<head>
    <title><?=$page_title?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=$page_charset?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?   
    $path = pathinfo($_SERVER['SCRIPT_NAME']);
    $basename = strtolower($path['basename']);
    ?>
    <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/enterprise/style/css/css.css">
    <?
    if($basename=='column.php')
    {
    ?>
        <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/enterprise/style/css/list.css">
    <?
    }
    elseif($basename!='index.php')
    {
    ?>
    <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/portal/enterprise/style/css/content.css">
    <?
    }
    ?>
    <script type="text/javascript" src="/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
</head>
<body>
<div id="enterprise">
<div class="main">
      <!----- 头部 ----->
      <div id="header">
            <div class="logo">
                <a href="<?=$arr_portal_info['logo_link']?>" target="_blank">
                    <?if($arr_portal_info['logo_img'] != '') {?><span class="img"><img src="<?=$arr_portal_info['logo_img']?>" /></span><?}?>
                    <span class="text"><?=$arr_portal_info['logo_text']?></span>
                </a>
             </div>
             <div class="nav">
                <ul>
                    <?
                    $css = 'current';
                    if(!empty($column_id))
                    {
                        $css = '';
                    }
                    ?>
                    <li class="<?=$css?>"><span><a href="index.php?portal_id=<?=$portal_id?>"><?=_("首 页")?></a></span></li>
                    <?
                    foreach($arr_nav_list as $nav_id => $nav)
                    {
                        if($nav['column_type'] != '1' && $nav['column_link'] == '')
                        {
                            $nav['column_link'] = 'column.php?portal_id='.$portal_id.'&column_id='.$nav_id;
                        }
                        $css = $column_id == $nav_id ? 'current' : '';
                        echo '<li class="'.$css.'"><span><a target="'.get_link_target_desc($nav['link_target']).'" href="'.$nav['column_link'].'">'.htmlspecialchars($nav['column_name']).'</a></span></li>';
                    }
                    ?>
                </ul>
            </div>
      </div>
    <!-------内容区域 content ------->
    <div id="content">
  