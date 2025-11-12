<?
include_once('../include/common.inc.php');

$columns_id = $column_id.",20";
//获取页面导航栏信息，在../inc/header.inc.php中使用
$arr_nav_list = $obj_portal_data->get_nav_list();

//获取首页主体部分各栏目的基本信息，可根据column_id一次获取多个栏目的新写
$arr_columns_info = $obj_portal_data->get_columns_info($columns_id);
$arr_content_info = $obj_portal_data -> get_contents_info($content_id);

$content_files = $arr_content_info[$content_id]['files'];

$my_list = '';
$cu_player_list = '';
$explanation_list = '';
$s_num = 0;
if(!empty($content_files))
{
    foreach($content_files as $file)
    {
        $my_list .= '<li><a onclick="changeStream('.$s_num.')">'.$file['file_name'].'<a></li>';
        $cu_player_list .= $file['file_url_down'].'|';
        $explanation_list .= htmlspecialchars($file['description']).'`';
    }
}
$cu_player_list = trim($cu_player_list,'|');
$explanation_list = trim($explanation_list,'`');

$page_title = $arr_content_info[$content_id]['subject'];
include_once('./header.inc.php');
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/portal/group/js/swfobject.js"></script>
          <!---- 三级页 文章 ---->
          <div class="content_text content_mv">
<!--              <div class="content_title"><strong><a href="index.php?portal_id=<?=$portal_id?>"><?=_("首页")?></a> / <a href="column.php?portal_id=<?=$portal_id?>&column_id=<?=$column_id?>"><?=$arr_columns_info[$column_id]['column_name']?></a></strong></div>-->
                  <h2><?=$arr_content_info[$content_id]['subject']?></h2>
                  <h3><span><?=_("发布时间：")?><?=date('Y-m-d',$arr_content_info[$content_id]['time'])?></span>&nbsp;&nbsp;<!--<span>共有xx人浏览</span>&nbsp;&nbsp;<span>发布人：xx</span>--></h3>
                    <div id="myplayer">
                      <div id="CuPlayer" > <strong>提示：您的Flash Player版本过低，请升级</a>！</strong> </div>
                      <div class="list">
                        <ul class="mylist">
                            <?=$my_list?>
                        </ul>
                      </div>
                      
                    <script type=text/javascript>
                    <!--
                    var CuPlayerList = "<?=$cu_player_list?>";
                    var ExplanationList = "<?=$explanation_list?>";
                    var sp =CuPlayerList.split("|")  
                    var el = ExplanationList.split("`");
                    var num = sp.length;
                    var video_index = 0;
                    function getNext(pars)
                    {	
                      if(video_index < num-1)
                        {
                            video_index++;
                            so.addVariable("CuPlayerFile",sp[video_index]);
                            so.write("CuPlayer");
                            $("#explanation").html(el[video_index]);
                        }
                        else
                        {
                        video_index = 0;
                        so.addVariable("CuPlayerFile",sp[video_index]);
                        so.write("CuPlayer");
                        $("#explanation").html(el[video_index]);	
                        }
                    }
                    function changeStream(CuPlayerFile){
                    so.addVariable("CuPlayerFile",sp[CuPlayerFile]);
                    so.write("CuPlayer");
                    $("#explanation").html(el[CuPlayerFile]);	
                    }
                    
                    CuPlayerFile =sp[video_index];
                    var so = new SWFObject("/static/portal/group/flash/player.swf","CuPlayer","600","400","9","#000000");
                    so.addParam("allowfullscreen","true");
                    so.addParam("allowscriptaccess","always");
                    so.addParam("wmode","opaque");
                    so.addParam("quality","high");
                    so.addParam("salign","lt");
                    so.addVariable("CuPlayerFile",CuPlayerFile);
                    so.addVariable("CuPlayerImage","Images/flashChangfa2.jpg");
                    so.addVariable("CuPlayerLogo","");
                    so.addVariable("CuPlayerShowImage","true");
                    so.addVariable("CuPlayerWidth","600");
                    so.addVariable("CuPlayerHeight","400");
                    so.addVariable("CuPlayerAutoPlay","true");
                    so.addVariable("CuPlayerAutoRepeat","false");
                    so.addVariable("CuPlayerShowControl","true");
                    so.addVariable("CuPlayerAutoHideControl","false");
                    so.addVariable("CuPlayerAutoHideTime","6");
                    so.addVariable("CuPlayerVolume","80");
                    so.addVariable("CuPlayerGetNext","true");
                    so.write("CuPlayer");	
                    
                    //-->
                    </script>
                    </div>
                  <p id="explanation"></p>
                  <?
                  if(!empty($content_files))
                  {
                      //echo '<h5><strong>'._("附件:").'</strong>';
                      foreach($content_files as $file)
                      {
                          if($file['download']=='0')
                          {
                              continue;
                          }
                          $s_num ++;
                          $files_html .= '<span><a href="'.$file['file_url_down'].'">'.$file['file_name'].'</a></span>';
                      }
                      //echo '</h5>';
                  }
                  if($s_num > 0)
                  {
                      echo '<h5><strong>'._("附件:").'</strong>'.$files_html.'</h5>';
                  }
                  ?>
          </div>
        </div>
<?
include_once('./footer.inc.php');
?>


